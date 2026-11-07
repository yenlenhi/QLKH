<?php
if (!defined('ALLOW_ACCESS')) {
    http_response_code(403);
    die(json_encode(["status" => "error", "message" => "Access Denied"]));
}

ini_set('display_errors', 0);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/auth_middleware.php';
require_once __DIR__ . '/../../Model/db_connect.php';

// Mặc định lấy user_id của chính tài khoản đang đăng nhập
$user_id = isset($currentUser['user_id']) ? intval($currentUser['user_id']) : (isset($currentUser['id']) ? intval($currentUser['id']) : 0);

// Nếu là Admin và truyền user_id trên URL để xem/phân quyền cho tài khoản khác ở modal thì lấy theo user_id đó
$is_admin = isset($currentUser['can_grant_permission']) ? intval($currentUser['can_grant_permission']) : 0;
if (isset($_GET['user_id']) && intval($_GET['user_id']) > 0 && $is_admin === 1) {
    $user_id = intval($_GET['user_id']);
}

if ($user_id <= 0) {
    http_response_code(401);
    echo json_encode([
        "status" => "error",
        "message" => "Không xác định được tài khoản người dùng từ Token."
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

try {
    $sql = "SELECT p.permission_code 
            FROM tbl_role r 
            JOIN tbl_permission p ON r.permission_id = p.id 
            WHERE r.user_id = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    $permissions = $stmt->fetchAll(PDO::FETCH_COLUMN);

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