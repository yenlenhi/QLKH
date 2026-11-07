<?php
if (!defined('ALLOW_ACCESS')) {
    http_response_code(403);
    die(json_encode(["status" => "error", "message" => "Access Denied"]));
}

ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

require_once __DIR__ . '/auth_middleware.php';
require_once __DIR__ . '/../../Model/db_connect.php';

try {
    // 1. Tự động lấy đúng trường theo tbl_project, tbl_user, tbl_project_member
    $sql = "SELECT 
                p.project_id,
                p.project_code,
                p.project_name,
                p.user_id,
                p.project_user_action_id,
                p.deleted_at,
                p.created_at,
                -- Lấy Chủ nhiệm từ tbl_project_member (is_leader = 1), nếu chưa có thì lấy theo p.user_id
                COALESCE(
                    (SELECT m.full_name FROM tbl_project_member m WHERE m.project_id = p.project_id AND m.is_leader = 1 LIMIT 1),
                    u_owner.name,
                    'Chưa cập nhật'
                ) AS leader_name,
                -- Lấy Tài khoản tạo nhiệm vụ từ tbl_user thông qua project_user_action_id
                COALESCE(u_creator.name, u_owner.name, 'Hệ thống') AS creator_name,
                COALESCE(u_creator.username, u_owner.username, '') AS creator_username,
                COALESCE(u_creator.type_user, u_owner.type_user, '') AS creator_type
            FROM tbl_project p
            LEFT JOIN tbl_user u_owner ON p.user_id = u_owner.user_id
            LEFT JOIN tbl_user u_creator ON p.project_user_action_id = u_creator.user_id
            WHERE p.is_deleted = 1 OR p.deleted_at IS NOT NULL
            ORDER BY p.deleted_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $trashList = $stmt->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "data"   => $trashList
    ], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status"  => "error",
        "message" => "Lỗi CSDL: " . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
exit();
