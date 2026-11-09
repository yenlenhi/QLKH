<?php
if (!defined('ALLOW_ACCESS')) {
    http_response_code(403);
    die(json_encode(["status" => "error", "message" => "Access Denied"]));
}
header('Content-Type: application/json; charset=utf-8');
// Nhúng middleware xác thực JWT (Sẽ tự động kiểm tra Token và exit nếu lỗi)
require_once __DIR__ . '/auth_middleware.php';
require_once __DIR__ . '/../../Model/db_connect.php';
require_once __DIR__ . '/../../Model/BlobStorage.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Phương thức không hợp lệ.']);
    exit();
}

$id = intval($_POST['id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$content = trim($_POST['content'] ?? '');
$hotline = trim($_POST['hotline'] ?? '');
$number_phone = trim($_POST['phone'] ?? '');

if ($id <= 0 || empty($title) || empty($content)) {
    echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ.']);
    exit();
}

try {
    // Lấy file cũ
    $stmtOld = $pdo->prepare("SELECT file_guides FROM tbl_guides WHERE id = :id");
    $stmtOld->execute([':id' => $id]);
    $oldData = $stmtOld->fetch();
    
    $existing_files = ($oldData && !empty($oldData['file_guides'])) ? json_decode($oldData['file_guides'], true) : [];
    if (!is_array($existing_files)) $existing_files = [];

    // Xử lý upload thêm file mới
    if (isset($_FILES['attachments']) && is_array($_FILES['attachments']['name'])) {
        $file_count = count($_FILES['attachments']['name']);
        for ($i = 0; $i < $file_count; $i++) {
            if ($_FILES['attachments']['error'][$i] === UPLOAD_ERR_OK) {
                $original_name = basename($_FILES['attachments']['name'][$i]);
                $file = ['name' => $_FILES['attachments']['name'][$i], 'tmp_name' => $_FILES['attachments']['tmp_name'][$i], 'error' => $_FILES['attachments']['error'][$i]];
                $existing_files[] = [
                    'original_name' => $original_name,
                    'file_path' => storeUploadedFile($file, 'guides'),
                ];
            }
        }
    }

    $file_guides_json = json_encode($existing_files, JSON_UNESCAPED_UNICODE);

    $sql = "UPDATE tbl_guides 
            SET title = :title, content = :content, hotline = :hotline, number_phone = :number_phone, file_guides = :file_guides 
            WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':title' => $title,
        ':content' => $content,
        ':hotline' => $hotline,
        ':number_phone' => $number_phone,
        ':file_guides' => $file_guides_json,
        ':id' => $id
    ]);

    // --- BỔ SUNG GHI LOG ---
    require_once __DIR__ . '/../../Model/function.php';
    $current_user_id = $currentUser['user_id'] ?? 0;
    writeActivityLog($pdo, $current_user_id, 'UPDATE', 'Quản lý Hướng dẫn', $id, "Đã cập nhật nội dung bài hướng dẫn: $title");
    // -----------------------

    echo json_encode(['status' => 'success', 'message' => 'Cập nhật hướng dẫn thành công!']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi CSDL: ' . $e->getMessage()]);
}
?>
