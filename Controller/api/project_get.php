<?php
if (!defined('ALLOW_ACCESS')) {
    http_response_code(403);
    die(json_encode(["status" => "error", "message" => "Access Denied"]));
}
ini_set('display_errors', 0);
error_reporting(E_ALL);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

require_once __DIR__ . '/auth_middleware.php';
require_once __DIR__ . '/../../Model/db_connect.php';

// Hỗ trợ đọc cả project_id và id
$project_id = isset($_REQUEST['project_id']) ? intval($_REQUEST['project_id']) : (isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0);

// Đọc tham số lọc theo tài khoản thực hiện / tạo nhiệm vụ (nếu có truyền)
$action_user_id = isset($_REQUEST['action_user_id']) ? intval($_REQUEST['action_user_id']) : 0;
if ($action_user_id <= 0 && isset($_REQUEST['user_action_id'])) {
    $action_user_id = intval($_REQUEST['user_action_id']);
}
if ($action_user_id <= 0 && isset($_REQUEST['user_id'])) {
    $action_user_id = intval($_REQUEST['user_id']);
}

// Đọc tham số lọc trạng thái giao nhận nhiệm vụ từ sidebar (assigned: 1 hoặc 0)
$assigned = isset($_REQUEST['assigned']) ? trim((string)$_REQUEST['assigned']) : '';

try {
    if ($project_id > 0) {
        // --- 1. LẤY CHI TIẾT 1 NHIỆM VỤ / ĐỀ TÀI ---
        $sqlProj = "SELECT p.*, 
                   l.level_name, 
                   r.type_code AS register_type_code,
                   r.type_name AS register_type_name, 
                   sec.security_name,
                   sub.system_code AS sub_system_code,
                   sub.system_name AS sub_system_name,
                   u_teacher.name AS teacher_in_charge_name,
                   u_teacher.name AS action_user_name,
                   ou.unit_name AS action_user_unit,  -- BỔ SUNG TÊN ĐƠN VỊ CỦA USER THỰC HIỆN
                   u_owner.name AS owner_name,
                   (SELECT m.full_name FROM tbl_project_member m WHERE m.project_id = p.project_id AND m.is_leader = 1 LIMIT 1) AS leader_name,
                   (SELECT m.platoon FROM tbl_project_member m WHERE m.project_id = p.project_id AND m.is_leader = 1 LIMIT 1) AS leader_unit
            FROM tbl_project p
            LEFT JOIN tbl_project_level l ON p.level_id = l.level_id
            LEFT JOIN tbl_project_register_type r ON p.register_type_id = r.register_type_id
            LEFT JOIN tbl_project_security_level sec ON p.security_level_id = sec.security_level_id
            LEFT JOIN tbl_sub_system sub ON p.sub_system_id = sub.sub_system_id
            LEFT JOIN tbl_user u_teacher ON p.project_user_action_id = u_teacher.user_id
            LEFT JOIN tbl_org_unit ou ON u_teacher.unit_id = ou.unit_id  -- LIÊN KẾT ĐƠN VỊ
            LEFT JOIN tbl_user u_owner ON p.user_id = u_owner.user_id
            WHERE p.project_id = ? AND p.is_deleted = 0";
        $stmtProj = $pdo->prepare($sqlProj);
        $stmtProj->execute([$project_id]);
        $project = $stmtProj->fetch(PDO::FETCH_ASSOC);

        if (!$project) {
            http_response_code(404);
            echo json_encode(["status" => "error", "message" => "Không tìm thấy đề tài hoặc đề tài đã bị xóa."], JSON_UNESCAPED_UNICODE);
            exit();
        }

        // Danh sách thành viên tham gia
        $stmtMem = $pdo->prepare("SELECT * FROM tbl_project_member WHERE project_id = ? ORDER BY is_leader DESC, member_id ASC");
        $stmtMem->execute([$project_id]);
        $project['members'] = $stmtMem->fetchAll(PDO::FETCH_ASSOC);

        // Danh sách tệp đính kèm
        $stmtFile = $pdo->prepare("SELECT * FROM tbl_project_file WHERE project_id = ? ORDER BY file_id DESC");
        $stmtFile->execute([$project_id]);
        $project['files'] = $stmtFile->fetchAll(PDO::FETCH_ASSOC);

        // Danh sách các đợt thanh toán (hỗ trợ trang tài chính và tính kinh phí)
        $stmtPay = $pdo->prepare("SELECT * FROM tbl_project_payment WHERE project_id = ? ORDER BY payment_date ASC");
        $stmtPay->execute([$project_id]);
        $project['payments'] = $stmtPay->fetchAll(PDO::FETCH_ASSOC);

        $data = $project;
    } else {
        // --- 2. LẤY DANH SÁCH NHIỆM VỤ / ĐỀ TÀI ---
        $whereClauses = ["p.is_deleted = 0"];
        $params = [];

        // Lọc theo người thực hiện cụ thể (nếu có truyền)
        if ($action_user_id > 0) {
            $whereClauses[] = "p.project_user_action_id = :action_user_id";
            $params[':action_user_id'] = $action_user_id;
        }

        // Lọc theo trạng thái đã giao / chưa giao từ Sidebar con
        if ($assigned === '1') {
            $whereClauses[] = "(p.project_user_action_id IS NOT NULL AND p.project_user_action_id > 0)";
        } elseif ($assigned === '0') {
            $whereClauses[] = "(p.project_user_action_id IS NULL OR p.project_user_action_id = 0)";
        }

        $whereSql = implode(" AND ", $whereClauses);

        $sql = "SELECT p.*, 
                    --    t.type_code AS project_type_code,
                    --    t.type_name AS project_type_name,
                       l.level_name,
                       r.type_code AS register_type_code,
                       r.type_name AS register_type_name,
                       sec.security_name,
                       sub.system_code AS sub_system_code,
                       sub.system_name AS sub_system_name,
                       u.name AS creator_name,
                       u_teacher.name AS teacher_in_charge_name,
                       u_teacher.name AS action_user_name,
                       (SELECT m.full_name FROM tbl_project_member m WHERE m.project_id = p.project_id AND m.is_leader = 1 LIMIT 1) AS leader_name
                FROM tbl_project p 
                -- LEFT JOIN tbl_project_type t ON p.project_type_id = t.project_type_id
                LEFT JOIN tbl_project_level l ON p.level_id = l.level_id
                LEFT JOIN tbl_project_register_type r ON p.register_type_id = r.register_type_id
                LEFT JOIN tbl_project_security_level sec ON p.security_level_id = sec.security_level_id
                LEFT JOIN tbl_sub_system sub ON p.sub_system_id = sub.sub_system_id
                LEFT JOIN tbl_user u ON p.user_id = u.user_id 
                LEFT JOIN tbl_user u_teacher ON p.project_user_action_id = u_teacher.user_id
                WHERE {$whereSql}
                ORDER BY p.project_id DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    http_response_code(200);
    echo json_encode(["status" => "success", "data" => $data], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
exit();
