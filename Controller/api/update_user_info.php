<?php
if (!defined('ALLOW_ACCESS')) {
    http_response_code(403);
    die(json_encode(["status" => "error", "message" => "Access Denied"]));
}

ini_set('display_errors', 0);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, PUT, OPTIONS");
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

$target_user_id = isset($data['user_id']) ? intval($data['user_id']) : 0;
$name = isset($data['name']) ? trim($data['name']) : '';
$password = isset($data['password']) ? trim($data['password']) : '';

// Nhận unit_id (cho phép null nếu không thuộc khoa/phòng ban nào)
$unit_id = null;
if (isset($data['unit_id']) && $data['unit_id'] !== '' && intval($data['unit_id']) > 0) {
    $unit_id = intval($data['unit_id']);
}

if ($target_user_id <= 0 || empty($name)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Thiếu thông tin bắt buộc (user_id hoặc Họ và Tên)."
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

/* =========================================================
   CHỐNG IDOR / BOLA TUYỆT ĐỐI BẰNG BIẾN $currentUser
   ========================================================= */
$current_user_id = isset($currentUser['user_id']) ? intval($currentUser['user_id']) : 0;
$is_admin = isset($currentUser['can_grant_permission']) ? intval($currentUser['can_grant_permission']) : 0;

if ($current_user_id !== $target_user_id && $is_admin !== 1) {
    http_response_code(403);
    echo json_encode([
        "status" => "error",
        "message" => "Cảnh báo bảo mật (IDOR): Bạn không có quyền chỉnh sửa thông tin của tài khoản này!"
    ], JSON_UNESCAPED_UNICODE);
    exit();
}
/* ========================================================= */

try {
    // Kiểm tra xem unit_id có tồn tại trong tbl_org_unit không nếu có truyền
    if ($unit_id !== null) {
        $stmtUnitCheck = $pdo->prepare("SELECT unit_id FROM tbl_org_unit WHERE unit_id = ?");
        $stmtUnitCheck->execute([$unit_id]);
        if (!$stmtUnitCheck->fetch()) {
            $unit_id = null; // Nếu ID đơn vị không tồn tại thì fallback về null
        }
    }

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE tbl_user SET name = ?, unit_id = ?, password = ? WHERE user_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $unit_id, $hashed_password, $target_user_id]);
    } else {
        $sql = "UPDATE tbl_user SET name = ?, unit_id = ? WHERE user_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $unit_id, $target_user_id]);
    }

    // Lấy thông tin họ tên và tên đơn vị để ghi log chi tiết
    $stmtName = $pdo->prepare("SELECT u.name, o.unit_name 
                               FROM tbl_user u 
                               LEFT JOIN tbl_org_unit o ON u.unit_id = o.unit_id 
                               WHERE u.user_id = ?");
    $stmtName->execute([$target_user_id]);
    $targetUser = $stmtName->fetch(PDO::FETCH_ASSOC);
    $targetName = $targetUser['name'] ?? "ID: $target_user_id";
    $unitDesc = !empty($targetUser['unit_name']) ? " (Đơn vị: {$targetUser['unit_name']})" : " (Chưa phân công đơn vị)";

    // Ghi log hoạt động
    require_once __DIR__ . '/../../Model/function.php';
    if (function_exists('writeActivityLog')) {
        writeActivityLog(
            $pdo,
            $current_user_id,
            'UPDATE',
            'Tài khoản người dùng',
            $target_user_id,
            "Đã cập nhật thông tin hồ sơ cho tài khoản: $targetName$unitDesc"
        );
    }

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "message" => "Cập nhật thông tin tài khoản thành công!"
    ], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Lỗi truy vấn CSDL: " . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
exit();