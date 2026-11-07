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

try {
    // Thay đổi GROUP BY từ user_id sang project_user_action_id để khớp với giảng viên/người phụ trách hiển thị trên bảng
    $sql = "SELECT project_user_action_id AS user_id, 
                   SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending_count,
                   SUM(CASE WHEN status = 'running' THEN 1 ELSE 0 END) AS running_count,
                   SUM(CASE WHEN status = 'awaiting_payment' THEN 1 ELSE 0 END) AS awaiting_payment_count,
                   SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) AS completed_count
            FROM tbl_project 
            WHERE is_deleted = 0 AND project_user_action_id IS NOT NULL 
            GROUP BY project_user_action_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $stats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Chuyển mảng thành dạng [user_id => [counts...]] để Frontend ánh xạ chuẩn xác vào từng dòng user
    $data = [];
    foreach ($stats as $row) {
        $data[$row['user_id']] = [
            'pending'          => intval($row['pending_count']),
            'running'          => intval($row['running_count']),
            'awaiting_payment' => intval($row['awaiting_payment_count']),
            'completed'        => intval($row['completed_count'])
        ];
    }

    http_response_code(200);
    echo json_encode(["status" => "success", "data" => $data], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
exit();
?>