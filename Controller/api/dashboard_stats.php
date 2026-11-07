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

try {
    // -------------------------------------------------------------
    // 1. PHÂN QUYỀN P5 vs CÁC KHOA KHÁC
    // -------------------------------------------------------------
    $unitCode = '';
    $userUnitId = 0;
    if ($userId > 0) {
        $stmtU = $pdo->prepare("
            SELECT u.unit_id, o.unit_code, o.unit_name 
            FROM tbl_user u 
            LEFT JOIN tbl_org_unit o ON u.unit_id = o.unit_id 
            WHERE u.user_id = ?
        ");
        $stmtU->execute([$userId]);
        $uRow = $stmtU->fetch(PDO::FETCH_ASSOC);
        $unitCode = strtoupper(trim($uRow['unit_code'] ?? ''));
        $userUnitId = intval($uRow['unit_id'] ?? 0);
    }

    $isAdmin = ($userId === 1 || $userId === 7);
    $isP5OrAdmin = ($isAdmin || $unitCode === 'P5' || strpos($unitCode, 'P05') !== false || strpos($unitCode, 'QLNCKH') !== false);

    // Bộ lọc theo đơn vị
    $whereUnit = "p.is_deleted = 0";
    $unitParams = [];
    if (!$isP5OrAdmin && $userId > 0) {
        if ($userUnitId > 0) {
            $whereUnit .= " AND (
                p.project_user_action_id IN (SELECT user_id FROM tbl_user WHERE unit_id = ?)
                OR p.project_id IN (SELECT project_id FROM tbl_project_member WHERE user_id IN (SELECT user_id FROM tbl_user WHERE unit_id = ?))
            )";
            $unitParams[] = $userUnitId;
            $unitParams[] = $userUnitId;
        } else {
            $whereUnit .= " AND p.project_user_action_id = ?";
            $unitParams[] = $userId;
        }
    }

    // -------------------------------------------------------------
    // 2. GIỮ NGUYÊN CẤU TRÚC GỐC 1: THỐNG KÊ TỔNG QUAN CHUNG (OVERVIEW)
    // -------------------------------------------------------------
    $sqlTotal = "SELECT 
                    COUNT(*) AS total_projects,
                    SUM(CASE WHEN p.status = 'running' THEN 1 ELSE 0 END) AS running_projects,
                    SUM(CASE WHEN p.status = 'completed' THEN 1 ELSE 0 END) AS completed_projects,
                    COALESCE(SUM(COALESCE(p.settlement_budget, p.estimated_budget)), 0) AS total_budget_all
                 FROM tbl_project p WHERE $whereUnit";
    $stmtTotal = $pdo->prepare($sqlTotal);
    $stmtTotal->execute($unitParams);
    $overview = $stmtTotal->fetch(PDO::FETCH_ASSOC);

    // -------------------------------------------------------------
    // 3. GIỮ NGUYÊN CẤU TRÚC GỐC 2: THỐNG KÊ THEO LOẠI ĐỀ TÀI (TYPE_STATS)
    // -------------------------------------------------------------
    $hasSubSys = $pdo->query("SHOW COLUMNS FROM tbl_project LIKE 'sub_system_id'")->rowCount() > 0;

    if ($hasSubSys) {
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
                     LEFT JOIN tbl_project p ON s.sub_system_id = p.sub_system_id AND $whereUnit
                     GROUP BY s.sub_system_id, s.system_name";
    } else {
        $sqlTypes = "SELECT 
                        t.project_type_id,
                        t.type_name,
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
                            WHERE pr.project_type_id = t.project_type_id AND pr.is_deleted = 0
                        ), 0) AS total_paid_amount
                     FROM tbl_project_type t
                     LEFT JOIN tbl_project p ON t.project_type_id = p.project_type_id AND $whereUnit
                     GROUP BY t.project_type_id, t.type_name";
    }
    $stmtTypes = $pdo->prepare($sqlTypes);
    $stmtTypes->execute($unitParams);
    $typeStats = $stmtTypes->fetchAll(PDO::FETCH_ASSOC);

    // -------------------------------------------------------------
    // 4. DANH MỤC ĐỘNG TỪ tbl_sub_system (TỰ GÁN KÝ HIỆU A, B, C...)
    // -------------------------------------------------------------
    $stmtSub = $pdo->query("SELECT sub_system_id, system_code, system_name FROM tbl_sub_system ORDER BY sub_system_id ASC");
    $dbSubSystems = $stmtSub->fetchAll(PDO::FETCH_ASSOC);

    $alphabet = range('a', 'z');
    $dynamicSubMap = [];
    $subSystemDefinitions = [];

    foreach ($dbSubSystems as $index => $subItem) {
        $keyChar = $alphabet[$index] ?? ('col_' . $subItem['sub_system_id']);
        $dynamicSubMap[$subItem['sub_system_id']] = $keyChar;
        $subSystemDefinitions[] = [
            'key'            => $keyChar,
            'sub_system_id'  => intval($subItem['sub_system_id']),
            'system_code'    => $subItem['system_code'],
            'system_name'    => $subItem['system_name'],
            'short_label'    => $keyChar . '. ' . $subItem['system_name']
        ];
    }

    // -------------------------------------------------------------
    // 5. MA TRẬN SO SÁNH THỜI GIAN ĐỘNG (NĂM / QUÝ / THÁNG / KHOẢNG NĂM)
    // -------------------------------------------------------------
    $gran = $_GET['gran'] ?? 'nam'; // 'nam', 'khoang_nam', 'trong_nam', 'quy'
    $basis = $_GET['basis'] ?? 'sub';
    $filterYear = isset($_GET['year']) ? intval($_GET['year']) : intval(date('Y'));
    $fromYear = isset($_GET['from_year']) ? intval($_GET['from_year']) : 0;
    $toYear = isset($_GET['to_year']) ? intval($_GET['to_year']) : 0;

    $dateCol = ($basis === 'start') ? "COALESCE(p.approved_date, p.created_at)" : "COALESCE(p.created_at, NOW())";

    $extraWhere = $whereUnit;
    $extraParams = $unitParams;

    if ($gran === 'quy') {
        $timeExpr = "CONCAT('Quý ', QUARTER($dateCol), '/', YEAR($dateCol))";
        $extraWhere .= " AND YEAR($dateCol) = ?";
        $extraParams[] = $filterYear;
    } elseif ($gran === 'khoang_nam') {
        $timeExpr = "YEAR($dateCol)";
        if ($fromYear > 0 && $toYear > 0) {
            $extraWhere .= " AND YEAR($dateCol) BETWEEN ? AND ?";
            $extraParams[] = min($fromYear, $toYear);
            $extraParams[] = max($fromYear, $toYear);
        }
    } elseif ($gran === 'trong_nam') {
        $timeExpr = "CONCAT('Tháng ', MONTH($dateCol))";
        $extraWhere .= " AND YEAR($dateCol) = ?";
        $extraParams[] = $filterYear;
    } else {
        // Mặc định: 'nam'
        $timeExpr = "YEAR($dateCol)";
    }

    // LẤY TẤT CẢ CÁC NĂM THỰC TẾ TRONG CSDL ĐỂ TỰ ĐỘNG SINH OPTION VÀ MA TRẬN
    $stmtYears = $pdo->query("SELECT DISTINCT YEAR($dateCol) AS y FROM tbl_project p WHERE p.is_deleted = 0 AND $dateCol IS NOT NULL ORDER BY y ASC");
    $dbYears = $stmtYears->fetchAll(PDO::FETCH_COLUMN);

    $currentYear = intval(date('Y'));
    if (empty($dbYears)) {
        $dbYears = [$currentYear - 2, $currentYear - 1, $currentYear];
    }
    if (!in_array($currentYear, $dbYears)) {
        $dbYears[] = $currentYear;
    }
    sort($dbYears);

    // Xây dựng khung ma trận mặc định sẵn các cột
    $matrix = [];
    if ($gran === 'nam') {
        foreach ($dbYears as $y) {
            $lbl = 'Năm ' . $y;
            $matrix[$lbl] = ['period' => $lbl, 'total' => 0, 'budget' => 0];
            foreach ($subSystemDefinitions as $def) {
                $matrix[$lbl][$def['key']] = 0;
            }
        }
    } elseif ($gran === 'khoang_nam' && $fromYear > 0 && $toYear > 0) {
        $minY = min($fromYear, $toYear);
        $maxY = max($fromYear, $toYear);
        for ($y = $minY; $y <= $maxY; $y++) {
            $lbl = 'Năm ' . $y;
            $matrix[$lbl] = ['period' => $lbl, 'total' => 0, 'budget' => 0];
            foreach ($subSystemDefinitions as $def) {
                $matrix[$lbl][$def['key']] = 0;
            }
        }
    } elseif ($gran === 'trong_nam') {
        for ($m = 1; $m <= 12; $m++) {
            $lbl = 'Tháng ' . $m;
            $matrix[$lbl] = ['period' => $lbl, 'total' => 0, 'budget' => 0];
            foreach ($subSystemDefinitions as $def) {
                $matrix[$lbl][$def['key']] = 0;
            }
        }
    } elseif ($gran === 'quy') {
        for ($q = 1; $q <= 4; $q++) {
            $lbl = 'Quý ' . $q . '/' . $filterYear;
            $matrix[$lbl] = ['period' => $lbl, 'total' => 0, 'budget' => 0];
            foreach ($subSystemDefinitions as $def) {
                $matrix[$lbl][$def['key']] = 0;
            }
        }
    }

    // Truy vấn tổng hợp theo thời gian và sub_system_id
    $sqlTimeline = "SELECT 
                        $timeExpr AS period_label,
                        YEAR($dateCol) AS yr,
                        p.sub_system_id,
                        COUNT(p.project_id) AS total_count,
                        COALESCE(SUM(COALESCE(p.settlement_budget, p.estimated_budget, 0)), 0) AS total_budget
                    FROM tbl_project p
                    WHERE $extraWhere
                    GROUP BY $timeExpr, YEAR($dateCol), p.sub_system_id
                    ORDER BY yr ASC, period_label ASC";
    $stmtTime = $pdo->prepare($sqlTimeline);
    $stmtTime->execute($extraParams);
    $timelineRaw = $stmtTime->fetchAll(PDO::FETCH_ASSOC);

    // Đổ dữ liệu thực tế vào khung ma trận
    foreach ($timelineRaw as $r) {
        $label = in_array($gran, ['nam', 'khoang_nam']) ? ('Năm ' . $r['yr']) : $r['period_label'];
        if (!isset($matrix[$label])) {
            $baseRow = ['period' => $label, 'total' => 0, 'budget' => 0];
            foreach ($subSystemDefinitions as $def) {
                $baseRow[$def['key']] = 0;
            }
            $matrix[$label] = $baseRow;
        }

        $sid = intval($r['sub_system_id']);
        $colKey = $dynamicSubMap[$sid] ?? null;
        if ($colKey && isset($matrix[$label][$colKey])) {
            $cnt = intval($r['total_count']);
            $matrix[$label][$colKey] += $cnt;
            $matrix[$label]['total'] += $cnt;
            $matrix[$label]['budget'] += floatval($r['total_budget']);
        }
    }

    // -------------------------------------------------------------
    // 6. DANH SÁCH CHI TIẾT ĐỀ TÀI PHỤC VỤ XUẤT FILE EXCEL
    // -------------------------------------------------------------
    $sqlProjects = "SELECT 
                        p.project_id, p.project_code, p.project_name, p.status,
                        COALESCE(p.settlement_budget, p.estimated_budget, 0) AS approved_budget,
                        COALESCE((SELECT SUM(paid_amount) FROM tbl_project_payment WHERE project_id = p.project_id), 0) AS total_paid,
                        COALESCE(m.full_name, 'Chưa gán') AS leader_name,
                        COALESCE(m.platoon, o.unit_name, '—') AS leader_unit
                    FROM tbl_project p
                    LEFT JOIN tbl_project_member m ON p.project_id = m.project_id AND m.is_leader = 1
                    LEFT JOIN tbl_user u ON p.project_user_action_id = u.user_id
                    LEFT JOIN tbl_org_unit o ON u.unit_id = o.unit_id
                    WHERE $whereUnit
                    ORDER BY p.project_id DESC";
    $stmtProjects = $pdo->prepare($sqlProjects);
    $stmtProjects->execute($unitParams);
    $projectList = $stmtProjects->fetchAll(PDO::FETCH_ASSOC);

    // -------------------------------------------------------------
    // TRẢ VỀ PHẢN HỒI JSON CHUẨN HÓA
    // -------------------------------------------------------------
    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "scope" => $isP5OrAdmin ? "all" : "unit",
        "unit_name" => $uRow['unit_name'] ?? 'Toàn Học viện',
        "data" => [
            "overview"           => [
                "total_projects"     => intval($overview['total_projects'] ?? 0),
                "running_projects"   => intval($overview['running_projects'] ?? 0),
                "completed_projects" => intval($overview['completed_projects'] ?? 0),
                "total_budget"       => floatval($overview['total_budget_all'] ?? 0)
            ],
            "type_stats"         => $typeStats,
            "sub_systems"        => $subSystemDefinitions,
            "available_years"    => $dbYears,
            "comparison_matrix"  => array_values($matrix),
            "project_list"       => $projectList
        ]
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
exit();