<?php
if (!defined('ALLOW_ACCESS')) {
    http_response_code(403);
    die(json_encode(["status" => "error", "message" => "Access Denied"]));
}
// Tắt hiển thị lỗi HTML trực tiếp để tránh làm hỏng cấu trúc JSON
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Cấu hình Header chuẩn RESTful API
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

// Nhúng middleware xác thực JWT (Sẽ tự động kiểm tra Token và exit nếu lỗi)
require_once __DIR__ . '/auth_middleware.php';
require_once __DIR__ . '/../../Model/db_connect.php';

try {
    // Truy vấn danh sách quyền từ bảng tbl_permission (Loại trừ quyền đặc biệt của Kỹ thuật)
    // $sql = "SELECT id, permission_name FROM tbl_permission WHERE permission_code != 'MANAGE_SIDEBAR' ORDER BY id ASC";
    $sql = "SELECT id, permission_name, permission_code FROM tbl_permission ORDER BY id ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $permissions = $stmt->fetchAll();

    // Phản hồi JSON thành công
    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "data" => $permissions
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    // Phản hồi lỗi CSDL dạng JSON
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Lỗi truy vấn CSDL: " . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
exit();
?>