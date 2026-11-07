<?php
if (!defined('ALLOW_ACCESS')) {
    http_response_code(403);
    die(json_encode(["status" => "error", "message" => "Access Denied"]));
}
header('Content-Type: application/json; charset=utf-8');

// Nhúng middleware xác thực và kết nối CSDL
require_once __DIR__ . '/auth_middleware.php';
require_once __DIR__ . '/../../Model/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Phương thức không hợp lệ.']);
    exit();
}

// Đọc dữ liệu JSON gửi lên từ fetch
$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input) || empty($input)) {
    $input = $_REQUEST;
}
$userId = intval($input['user_id'] ?? 0);

// KIỂM TRA PHÂN QUYỀN VÀ CHỐNG TỰ XÓA CHÍNH MÌNH
$current_user_id = isset($currentUser['user_id']) ? intval($currentUser['user_id']) : 0;
$is_admin = isset($currentUser['can_grant_permission']) ? intval($currentUser['can_grant_permission']) : 0;

if ($is_admin !== 1) {
    echo json_encode(['status' => 'error', 'message' => 'Cảnh báo bảo mật: Bạn không có quyền xóa tài khoản!']);
    exit();
}

if ($userId === $current_user_id) {
    echo json_encode(['status' => 'error', 'message' => 'Bạn không thể tự xóa tài khoản của chính mình!']);
    exit();
}

if ($userId <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Mã người dùng không hợp lệ.']);
    exit();
}

try {
    // 1. Lấy thông tin tên tài khoản trước khi xóa để ghi log trực quan
    $stmtInfo = $pdo->prepare("SELECT name, username FROM tbl_user WHERE user_id = :user_id");
    $stmtInfo->execute([':user_id' => $userId]);
    $uInfo = $stmtInfo->fetch(PDO::FETCH_ASSOC);
    $targetName = $uInfo['name'] ?? $uInfo['username'] ?? "ID: $userId";

    // 2. Thực thi xóa bản ghi trong bảng tbl_user theo user_id
    $stmt = $pdo->prepare("DELETE FROM tbl_user WHERE user_id = :user_id");
    $stmt->execute([':user_id' => $userId]);

    if ($stmt->rowCount() > 0) {
        require_once __DIR__ . '/../../Model/function.php';

        // --- 3. GHI LOG HOẠT ĐỘNG VỚI TÊN CỤ THỂ ---
        writeActivityLog(
            $pdo,
            $current_user_id,
            'DELETE',
            'Quản lý Tài khoản',
            $userId,
            "Đã xóa vĩnh viễn tài khoản người dùng: $targetName"
        );
        // ------------------------------------------

        echo json_encode(['status' => 'success', 'message' => 'Xóa tài khoản thành công!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy tài khoản để xóa.']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi CSDL: ' . $e->getMessage()]);
}
