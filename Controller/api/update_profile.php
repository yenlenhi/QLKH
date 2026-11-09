<?php
if (!defined('ALLOW_ACCESS')) {
    http_response_code(403);
    die(json_encode(["status" => "error", "message" => "Access Denied"]));
}

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/auth_middleware.php';
require_once __DIR__ . '/../../Model/db_connect.php';
require_once __DIR__ . '/../../Model/BlobStorage.php';

if (!isset($pdo)) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Không tìm thấy kết nối CSDL!"]);
    exit();
}

// Lấy trực tiếp user_id an toàn từ Token qua auth_middleware
$userId = intval($currentUser['user_id'] ?? $currentUser['id'] ?? 0);
$name = trim($_POST['name'] ?? '');

if ($userId <= 0 || empty($name)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Thông tin truyền vào không hợp lệ!"]);
    exit();
}

$avatarUrl = null;

// Xử lý upload ảnh đại diện nếu có
if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
    $fileExtension = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (in_array($fileExtension, $allowedExtensions)) {
        $avatarUrl = storeUploadedFile($_FILES['avatar'], 'avatars/' . $userId, $allowedExtensions);
    } else {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Định dạng file ảnh không được hỗ trợ!"]);
        exit();
    }
}

try {
    if ($avatarUrl) {
        $stmt = $pdo->prepare("UPDATE tbl_user SET name = :name, avatar = :avatar WHERE user_id = :user_id");
        $stmt->execute([
            ':name'    => $name,
            ':avatar'  => $avatarUrl,
            ':user_id' => $userId
        ]);
    } else {
        $stmt = $pdo->prepare("UPDATE tbl_user SET name = :name WHERE user_id = :user_id");
        $stmt->execute([
            ':name'    => $name,
            ':user_id' => $userId
        ]);
    }

    require_once __DIR__ . '/log_helper.php';
    writeActivityLog($pdo, $userId, 'UPDATE', 'Tài khoản người dùng', $userId, "Cập nhật thông tin hồ sơ cá nhân");
    http_response_code(200);
    echo json_encode([
        "status"    => "success",
        "message"   => "Cập nhật hồ sơ thành công!",
        "name"      => $name,
        "avatarUrl" => $avatarUrl
    ], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status"  => "error",
        "message" => "Lỗi CSDL: " . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
exit();
