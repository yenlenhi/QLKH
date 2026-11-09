<?php
if (!defined('ALLOW_ACCESS')) {
    http_response_code(403);
    die(json_encode(["status" => "error", "message" => "Access Denied"]));
}

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/auth_middleware.php';
require_once __DIR__ . '/../../Model/db_connect.php';

if (!isset($pdo)) {
    echo json_encode(["status" => "error", "message" => "Không tìm thấy kết nối CSDL!"]);
    exit();
}

try {
    $sql = "SELECT l.log_id, l.action_type, l.target_object, l.object_id, l.description, l.ip_address, 
                   TO_CHAR(l.created_at, 'DD/MM/YYYY HH24:MI:SS') AS created_at,
                   u.name AS cb_name, u.username AS cb_username
            FROM tbl_activity_logs l
            JOIN tbl_user u ON l.user_id = u.user_id
            ORDER BY l.log_id DESC
            LIMIT 100";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "data" => $logs
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Lỗi CSDL: " . $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
exit();
