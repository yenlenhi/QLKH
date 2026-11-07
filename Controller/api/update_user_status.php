<?php
if (!defined('ALLOW_ACCESS')) {
    http_response_code(403);
    die(json_encode(["status" => "error", "message" => "Access Denied"]));
}

ini_set('display_errors', 0);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, PATCH, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/auth_middleware.php';
require_once __DIR__ . '/../../Model/db_connect.php';

$data = json_decode(file_get_contents("php://input"), true);
if (!is_array($data) || empty($data)) {
    $data = $_REQUEST;
}

if (!isset($data['user_id']) || !isset($data['status'])) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Thiếu tham số user_id hoặc status!"
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

$user_id = intval($data['user_id']);
$status = intval($data['status']); // 1 hoặc 0

// KIỂM TRA PHÂN QUYỀN VÀ CHỐNG TỰ KHÓA CHÍNH MÌNH
$current_user_id = isset($currentUser['user_id']) ? intval($currentUser['user_id']) : 0;
$is_admin = isset($currentUser['can_grant_permission']) ? intval($currentUser['can_grant_permission']) : 0;

if ($is_admin !== 1) {
    http_response_code(403);
    echo json_encode([
        "status" => "error",
        "message" => "Cảnh báo bảo mật: Bạn không có quyền thay đổi trạng thái tài khoản!"
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

if ($user_id === $current_user_id && $status === 0) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Bạn không thể tự vô hiệu hóa tài khoản của chính mình!"
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

try {
    // Nếu vô hiệu hóa (status = 0), đồng thời xóa current_token để kick phiên đăng nhập ra ngay
    if ($status === 0) {
        $sql = "UPDATE tbl_user SET status = 0, current_token = NULL WHERE user_id = :user_id";
    } else {
        $sql = "UPDATE tbl_user SET status = 1 WHERE user_id = :user_id";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $stmtName = $pdo->prepare("SELECT name FROM tbl_user WHERE user_id = ?");
        $stmtName->execute([$user_id]);
        $uInfo = $stmtName->fetch(PDO::FETCH_ASSOC);
        $targetUserName = $uInfo['name'] ?? "ID: $user_id";

        require_once __DIR__ . '/../../Model/function.php';
        $actionName = ($status === 1) ? "kích hoạt" : "vô hiệu hóa";

        if (function_exists('writeActivityLog')) {
            writeActivityLog(
                $pdo,
                $current_user_id,
                'UPDATE',
                'Trạng thái Tài khoản',
                $user_id,
                "Đã $actionName tài khoản người dùng: $targetUserName"
            );
        }

        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => ($status === 1) ? "Đã kích hoạt tài khoản!" : "Đã khóa tài khoản và ngắt phiên làm việc!"
        ], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(200);
        echo json_encode([
            "status" => "warning",
            "message" => "Trạng thái không thay đổi hoặc tài khoản không tồn tại!"
        ], JSON_UNESCAPED_UNICODE);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Lỗi CSDL: " . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
exit();