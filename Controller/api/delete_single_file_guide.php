<?php
if (!defined('ALLOW_ACCESS')) {
    http_response_code(403);
    die(json_encode(["status" => "error", "message" => "Access Denied"]));
}
header('Content-Type: application/json; charset=utf-8');
// Nhúng middleware xác thực JWT (Sẽ tự động kiểm tra Token và exit nếu lỗi)
require_once __DIR__ . '/auth_middleware.php';
require_once __DIR__ . '/../../Model/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Phương thức không hợp lệ.']);
    exit();
}

$guide_id = intval($_POST['guide_id'] ?? 0);
$file_path = trim($_POST['file_path'] ?? '');

if ($guide_id <= 0 || empty($file_path)) {
    echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ.']);
    exit();
}

try {
    // Lấy thông tin file_guides của bài viết
    $stmt = $pdo->prepare("SELECT file_guides FROM tbl_guides WHERE id = :id");
    $stmt->execute([':id' => $guide_id]);
    $guide = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$guide) {
        echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy bài viết.']);
        exit();
    }

    $files = !empty($guide['file_guides']) ? json_decode($guide['file_guides'], true) : [];
    if (!is_array($files)) {
        $files = [];
    }

    $updated_files = [];
    $is_deleted = false;

    // Lọc bỏ file cần xóa và thực hiện xóa file trên đĩa
    foreach ($files as $file) {
        if (($file['file_path'] ?? '') === $file_path) {
            $physical_path = '../../' . $file_path;
            if (file_exists($physical_path)) {
                @unlink($physical_path);
            }
            $is_deleted = true;
        } else {
            $updated_files[] = $file;
        }
    }

    if (!$is_deleted) {
        echo json_encode(['status' => 'error', 'message' => 'File không tồn tại trên hệ thống.']);
        exit();
    }

    // Cập nhật lại chuỗi JSON mới trong CSDL
    $file_guides_json = json_encode(array_values($updated_files), JSON_UNESCAPED_UNICODE);
    $updateStmt = $pdo->prepare("UPDATE tbl_guides SET file_guides = :file_guides WHERE id = :id");
    $updateStmt->execute([
        ':file_guides' => $file_guides_json,
        ':id' => $guide_id
    ]);

    echo json_encode(['status' => 'success', 'message' => 'Xóa file đính kèm thành công!']);

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi Server: ' . $e->getMessage()]);
}
?>