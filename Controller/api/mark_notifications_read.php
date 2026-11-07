<?php
if (!defined('ALLOW_ACCESS')) {
    http_response_code(403);
    die(json_encode(["status" => "error", "message" => "Access Denied"]));
}
header('Content-Type: application/json; charset=utf-8');
// Nhúng middleware xác thực JWT (Sẽ tự động kiểm tra Token và exit nếu lỗi)
require_once __DIR__ . '/auth_middleware.php';
require_once __DIR__ . '/../../Model/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Lấy user_id từ dữ liệu xác thực Token trong auth_middleware.php
        $rawInput = file_get_contents('php://input');
        $inputData = json_decode($rawInput, true);
        $userId = $currentUser['user_id'] ?? $currentUser['id'] ?? $_POST['user_id'] ?? $inputData['user_id'] ?? null;

        // Cập nhật tất cả thông báo về trạng thái đã đọc (is_read = 1) theo ĐÚNG user_id
        if ($userId) {
            $sql = "UPDATE tbl_notifications SET is_read = 1 WHERE is_read = 0 AND user_id = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':user_id' => $userId]);
        } else {
            $sql = "UPDATE tbl_notifications SET is_read = 1 WHERE is_read = 0";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
        }

        echo json_encode([
            "status" => "success",
            "message" => "Đã đánh dấu tất cả là đã đọc!"
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
}
?>