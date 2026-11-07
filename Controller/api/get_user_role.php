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

// Nhúng middleware xác thực JWT và kết nối CSDL
require_once __DIR__ . '/auth_middleware.php';
require_once __DIR__ . '/../../Model/db_connect.php';

// Lấy user_id từ tham số GET trên URL (ví dụ: get_user_role.php?user_id=5)
$user_id = isset($_REQUEST['user_id']) ? intval($_REQUEST['user_id']) : 0;

if ($user_id <= 0) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Thiếu hoặc sai định dạng user_id."
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

try {
    // Truy vấn các permission_id từ bảng tbl_role của user_id này
    $sql = "SELECT permission_id FROM tbl_role WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    $permissions = $stmt->fetchAll(PDO::FETCH_COLUMN); // Trả về mảng chứa các ID quyền

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "data" => $permissions
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Lỗi truy vấn CSDL: " . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
exit();
?>