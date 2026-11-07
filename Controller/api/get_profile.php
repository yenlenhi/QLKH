<?php
if (!defined('ALLOW_ACCESS')) {
    http_response_code(403);
    die(json_encode(["status" => "error", "message" => "Access Denied"]));
}
header('Content-Type: application/json; charset=utf-8');

// Nhúng kết nối CSDL
// Nhúng middleware xác thực JWT (Sẽ tự động kiểm tra Token và exit nếu lỗi)
require_once __DIR__ . '/auth_middleware.php';
require_once __DIR__ . '/../../Model/db_connect.php';

if (!isset($pdo)) {
    echo json_encode(["status" => "error", "message" => "Không tìm thấy kết nối CSDL!"]);
    exit;
}

// RESTful API GET: Lấy user_id từ query parameter
$userId = $_GET['user_id'] ?? '';

if (empty($userId)) {
    http_response_code(400); // Bad Request
    echo json_encode(["status" => "error", "message" => "Thiếu tham số user_id!"]);
    exit;
}

try {
    // Truy vấn dữ liệu người dùng từ tbl_user
    $sql = "SELECT user_id, name, avatar FROM tbl_user WHERE user_id = :user_id LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        http_response_code(200); // OK
        echo json_encode([
            "status" => "success",
            "data"   => [
                "user_id" => $user['user_id'],
                "name"    => $user['name'],
                "avatar"  => $user['avatar'],
            ]
        ]);
    } else {
        http_response_code(404); // Not Found
        echo json_encode(["status" => "error", "message" => "Không tìm thấy người dùng!"]);
    }
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(["status" => "error", "message" => "Lỗi CSDL: " . $e->getMessage()]);
}
?>