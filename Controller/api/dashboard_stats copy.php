<?php
if (!defined('ALLOW_ACCESS')) {
    http_response_code(403);
    die(json_encode(["status" => "error", "message" => "Access Denied"]));
}

ini_set('display_errors', 0);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/auth_middleware.php';
require_once __DIR__ . '/../../Model/db_connect.php';

// Xác định tài khoản đang đăng nhập
$activeUser = isset($auth_user) ? $auth_user : (isset($currentUser) ? $currentUser : []);
$userId = isset($activeUser['user_id']) ? intval($activeUser['user_id']) : (isset($activeUser['id']) ? intval($activeUser['id']) : 0);

if ($userId <= 0) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Phiên đăng nhập không hợp lệ hoặc đã hết hạn."]);
    exit();
}

try {
    // 1. Kiểm tra đơn vị của tài khoản: phân biệt P5 với các Khoa khác (Yêu cầu 2)
    $stmtUser = $pdo->prepare("
        SELECT u.user_id, u.unit_id, o.unit_code, o.unit_name
        FROM tbl_user u
        LEFT JOIN tbl_org_unit o ON u.unit_id = o.unit_id
        WHERE u.user_id = ?
    ");
    $stmtUser->execute([$userId]);
    $uInfo = $stmtUser->fetch(PDO::FETCH_ASSOC);

    $unitCode = strtoupper(trim($uInfo['unit_code'] ?? ''));
    $userUnitId = intval($uInfo['unit_id'] ?? 0);
    $isAdmin = ($userId === 1 || $userId === 7);

    // Nếu là Admin hoặc đơn vị P5: xem toàn bộ không giới hạn. Nếu khác P5: lọc theo đơn vị
    $isP5OrAdmin = ($isAdmin || $unitCode === 'P5' || strpos($unitCode, 'P05') !== false || strpos($unitCode, 'QLNCKH') !== false);

    // 2. Nhận các tham số lọc thời gian (Năm / Quý / Khoảng ngày - Yêu cầu 3)
    $filterType = $_GET['filter_type'] ?? 'year';
    $year = isset($_GET['year']) && !empty($_GET['year']) ? intval($_GET['year']) : intval(date('Y'));
    $quarter = isset($_GET['quarter']) ? intval($_GET['quarter']) : 0;
    $startDate = $_GET['start_date'] ?? '';
    $endDate = $_GET['end_date'] ?? '';

    // Xây dựng điều kiện WHERE
    $whereConditions = ["p.is_deleted = 0"];
    $params = [];

    // Lọc theo Khoa nếu không thuộc P5
    if (!$isP5OrAdmin) {
        if ($userUnitId > 0) {
            $whereConditions[] = "(
                p.project_user_action_id IN (SELECT user_id FROM tbl_user WHERE unit_id = ?)
                OR p.project_id IN (SELECT project_id FROM tbl_project_member WHERE user_id IN (SELECT user_id FROM tbl_user WHERE unit_id = ?))
            )";
            $params[] = $userUnitId;
            $params[] = $userUnitId;
        } else {
            $whereConditions[] = "p.project_user_action_id = ?";
            $params[] = $userId;
        }
    }

    // Lọc thời gian căn cứ vào ngày phê duyệt hoặc ngày tạo đề tài
    $dateCol = "COALESCE(p.approved_date, p.created_at)";

    if ($filterType === 'year' && $year > 0) {
        $whereConditions[] = "YEAR($dateCol) = ?";
        $params[] = $year;
    } elseif ($filterType === 'quarter') {
        if ($year > 0) {
            $whereConditions[] = "YEAR($dateCol) = ?";
            $params[] = $year;
        }
        if ($quarter >= 1 && $quarter <= 4) {
            $whereConditions[] = "QUARTER($dateCol) = ?";
            $params[] = $quarter;
        }
    } elseif ($filterType === 'custom') {
        if (!empty($startDate)) {
            $whereConditions[] = "DATE($dateCol) >= ?";
            $params[] = $startDate;
        }
        if (!empty($endDate)) {
            $whereConditions[] = "DATE($dateCol) <= ?";
            $params[] = $endDate;
        }
    }

    $whereSQL = implode(" AND ", $whereConditions);

    // 3. Thống kê tổng quan chung (giữ nguyên logic COALESCE settlement_budget, estimated_budget)
    $sqlTotal = "SELECT 
                    COUNT(*) AS total_projects,
                    SUM(CASE WHEN status = 'running' THEN 1 ELSE 0 END) AS running_projects,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) AS completed_projects,
                    COALESCE(SUM(COALESCE(settlement_budget, estimated_budget)), 0) AS total_budget_all
                 FROM tbl_project p 
                 WHERE $whereSQL";
    $stmtTotal = $pdo->prepare($sqlTotal);
    $stmtTotal->execute($params);
    $overview = $stmtTotal->fetch(PDO::FETCH_ASSOC);

    // 4. Thống kê theo Loại đề tài (sử dụng tbl_sub_system có trong hệ thống)
    $sqlTypes = "SELECT 
                    s.sub_system_id AS project_type_id,
                    s.system_name AS type_name,
                    COUNT(p.project_id) AS total_count,
                    SUM(CASE WHEN p.status = 'pending' THEN 1 ELSE 0 END) AS pending_count,
                    SUM(CASE WHEN p.status = 'running' THEN 1 ELSE 0 END) AS running_count,
                    SUM(CASE WHEN p.status = 'awaiting_payment' THEN 1 ELSE 0 END) AS awaiting_payment_count,
                    SUM(CASE WHEN p.status = 'completed' THEN 1 ELSE 0 END) AS completed_count,
                    COALESCE(SUM(COALESCE(p.settlement_budget, p.estimated_budget)), 0) AS total_approved_budget,
                    COALESCE((
                        SELECT SUM(pay.paid_amount) 
                        FROM tbl_project_payment pay 
                        JOIN tbl_project pr ON pay.project_id = pr.project_id 
                        WHERE pr.sub_system_id = s.sub_system_id AND pr.is_deleted = 0
                    ), 0) AS total_paid_amount
                 FROM tbl_sub_system s
                 LEFT JOIN tbl_project p ON s.sub_system_id = p.sub_system_id AND $whereSQL
                 GROUP BY s.sub_system_id, s.system_name
                 ORDER BY total_count DESC";

    $stmtTypes = $pdo->prepare($sqlTypes);
    $stmtTypes->execute($params);
    $typeStats = $stmtTypes->fetchAll(PDO::FETCH_ASSOC);

    // 5. Lấy danh sách chi tiết các đề tài để xuất file Excel (Yêu cầu 4)
    $sqlProjects = "SELECT 
                        p.project_id,
                        p.project_code,
                        p.project_name,
                        p.status,
                        s.system_name AS type_name,
                        COALESCE(p.settlement_budget, p.estimated_budget, 0) AS approved_budget,
                        COALESCE((SELECT SUM(paid_amount) FROM tbl_project_payment WHERE project_id = p.project_id), 0) AS total_paid,
                        COALESCE(m.full_name, 'Chưa gán') AS leader_name,
                        COALESCE(m.platoon, o.unit_name, '—') AS leader_unit
                    FROM tbl_project p
                    LEFT JOIN tbl_sub_system s ON p.sub_system_id = s.sub_system_id
                    LEFT JOIN tbl_project_member m ON p.project_id = m.project_id AND m.is_leader = 1
                    LEFT JOIN tbl_user u ON p.project_user_action_id = u.user_id
                    LEFT JOIN tbl_org_unit o ON u.unit_id = o.unit_id
                    WHERE $whereSQL
                    ORDER BY p.project_id DESC";
    $stmtProjects = $pdo->prepare($sqlProjects);
    $stmtProjects->execute($params);
    $projectList = $stmtProjects->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "scope" => $isP5OrAdmin ? "all" : "unit",
        "unit_name" => $uInfo['unit_name'] ?? 'Toàn trường',
        "data" => [
            "overview" => [
                "total_projects"     => intval($overview['total_projects'] ?? 0),
                "running_projects"   => intval($overview['running_projects'] ?? 0),
                "completed_projects" => intval($overview['completed_projects'] ?? 0),
                "total_budget"       => floatval($overview['total_budget_all'] ?? 0)
            ],
            "type_stats" => $typeStats,
            "project_list" => $projectList
        ]
    ], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
exit();
