<?php
if (!defined('ALLOW_ACCESS')) {
    http_response_code(403);
    die(json_encode(["status" => "error", "message" => "Access Denied"]));
}
// update_user_info.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, PUT");

// Nhúng middleware xác thực JWT (Sẽ tự động kiểm tra Token và exit nếu lỗi)
require_once __DIR__ . '/auth_middleware.php';
require_once __DIR__ . '/../../Model/db_connect.php';

$data = json_decode(file_get_contents("php://input"), true);
if (!is_array($data) || empty($data)) {
    $data = $_REQUEST; // Lấy từ $_REQUEST đã được index.php giải mã sẵn
}

if (!isset($data['user_id']) || !isset($data['name'])) {
    echo json_encode([
        "status" => "error", 
        "message" => "Thiếu tham số user_id hoặc name!"
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

$user_id = $data['user_id'];
$name = trim($data['name']);
$password = isset($data['password']) ? trim($data['password']) : '';

try {
    if (!empty($password)) {
        // Cập nhật cả name và password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $sql = "UPDATE tbl_user 
                SET name = :name, password = :password 
                WHERE user_id = :user_id";
                
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':password', $hashed_password);
    } else {
        // Chỉ cập nhật name
        $sql = "UPDATE tbl_user 
                SET name = :name 
                WHERE user_id = :user_id";
                
        $stmt = $pdo->prepare($sql);
    }

    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode([
            "status" => "success", 
            "message" => "Cập nhật Họ tên thành công!"
        ], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode([
            "status" => "warning", 
            "message" => "Không có thay đổi hoặc user không có quyền phân quyền!"
        ], JSON_UNESCAPED_UNICODE);
    }

} catch (PDOException $e) {
    echo json_encode([
        "status" => "error", 
        "message" => "Lỗi CSDL: " . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>