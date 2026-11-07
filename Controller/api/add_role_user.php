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
header("Access-Control-Allow-Methods: POST");

// Nhúng middleware xác thực JWT và kết nối CSDL
// require_once __DIR__ . '/auth_middleware.php';
// require_once "../../Model/db_connect.php"; 
require_once __DIR__ . '/auth_middleware.php';
require_once __DIR__ . '/../../Model/db_connect.php';

// KIỂM TRA PHÂN QUYỀN: Chỉ tài khoản có can_grant_permission = 1 mới được phép phân quyền
$is_admin = isset($currentUser['can_grant_permission']) ? intval($currentUser['can_grant_permission']) : 0;
if ($is_admin !== 1) {
    http_response_code(403);
    echo json_encode([
        "status" => "error",
        "message" => "Cảnh báo bảo mật: Bạn không có quyền thực hiện thao tác phân quyền này!"
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

// Nhận dữ liệu JSON từ Frontend gửi lên
$data = json_decode(file_get_contents("php://input"), true);
if (!is_array($data) || empty($data)) {
    $data = $_REQUEST; // Lấy từ $_REQUEST đã được index.php giải mã sẵn
}

$user_id = isset($data['user_id']) ? intval($data['user_id']) : 0;
$permissions = isset($data['permissions']) && is_array($data['permissions']) ? $data['permissions'] : [];

if ($user_id <= 0) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Thiếu hoặc sai định dạng user_id."
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

try {
    // Bắt đầu Transaction để đảm bảo an toàn dữ liệu
    $pdo->beginTransaction();

    // 1. Xóa toàn bộ quyền cũ của user này trong tbl_role
    $stmtDelete = $pdo->prepare("DELETE FROM tbl_role WHERE user_id = ?");
    $stmtDelete->execute([$user_id]);

    // 2. Thêm các quyền mới (nếu người dùng có tick chọn)
    $insertedCount = 0;
    if (!empty($permissions)) {
        $stmtInsert = $pdo->prepare("INSERT INTO tbl_role (user_id, permission_id) VALUES (?, ?)");
        foreach ($permissions as $perm_id) {
            $perm_id_int = intval($perm_id);
            if ($perm_id_int > 0) {
                $stmtInsert->execute([$user_id, $perm_id_int]);
                $insertedCount++;
            }
        }
    }

    // Hoàn tất lưu vào Database
    $pdo->commit();

    // Kiểm tra xem có ít nhất 1 quyền hay không
    $has_permission = ($insertedCount > 0);

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "message" => "Cập nhật phân quyền thành công!",
        "has_permission" => $has_permission,
        "permission_count" => $insertedCount
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    // Nếu có lỗi thì hoàn tác (Rollback)
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Lỗi CSDL: " . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
exit();
?>