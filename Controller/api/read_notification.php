<?php
if (!defined('ALLOW_ACCESS')) {
    http_response_code(403);
    die(json_encode(["status" => "error", "message" => "Access Denied"]));
}
// Bật hiển thị lỗi để dễ kiểm tra nếu còn sự cố
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");

// 1. Nhúng kết nối CSDL an toàn bằng __DIR__
// Nhúng middleware xác thực JWT (Sẽ tự động kiểm tra Token và exit nếu lỗi)
require_once __DIR__ . '/auth_middleware.php';
require_once __DIR__ . '/../../Model/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Phương thức không được hỗ trợ!"]);
    exit();
}

if (!isset($pdo)) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Không tìm thấy biến kết nối $pdo!"]);
    exit();
}

// 2. Tự động đọc dữ liệu (Hỗ trợ cả JSON Payload và Form Data)
$rawInput = file_get_contents('php://input');
$inputData = json_decode($rawInput, true);

$notiId = $_POST['id'] ?? $inputData['id'] ?? null;
// Lấy user_id từ Token đã giải mã trong auth_middleware.php hoặc từ tham số gửi lên
$userId = $currentUser['user_id'] ?? $currentUser['id'] ?? $_POST['user_id'] ?? $inputData['user_id'] ?? null;

if (!$notiId) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Thiếu ID thông báo!"]);
    exit();
}

try {
    // 3. Thực thi cập nhật is_read = 1 cho đúng người dùng
    if ($userId) {
        $sql = "UPDATE tbl_notifications SET is_read = 1 WHERE id = :id AND user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id' => $notiId,
            ':user_id' => $userId
        ]);
    } else {
        $sql = "UPDATE tbl_notifications SET is_read = 1 WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $notiId]);
    }

    http_response_code(200);
    echo json_encode([
        "status"  => "success",
        "message" => "Đã đánh dấu đọc thông báo ID: " . $notiId
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status"  => "error",
        "message" => "Lỗi CSDL: " . $e->getMessage()
    ]);
}
?>