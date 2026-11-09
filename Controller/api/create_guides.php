<?php
if (!defined('ALLOW_ACCESS')) {
    http_response_code(403);
    die(json_encode(["status" => "error", "message" => "Access Denied"]));
}
header('Content-Type: application/json; charset=utf-8');
// Nhúng middleware xác thực JWT (Sẽ tự động kiểm tra Token và exit nếu lỗi)
// require_once __DIR__ . '/auth_middleware.php';
// require_once '../../Model/db_connect.php';
require_once __DIR__ . '/auth_middleware.php';
require_once __DIR__ . '/../../Model/db_connect.php';
require_once __DIR__ . '/../../Model/BlobStorage.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Phương thức không hợp lệ.']);
    exit();
}

$title = trim($_POST['title'] ?? '');
$content = trim($_POST['content'] ?? '');
$hotline = trim($_POST['hotline'] ?? '');
$number_phone = trim($_POST['phone'] ?? '');

if (empty($title) || empty($content)) {
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhập đầy đủ tiêu đề và nội dung.']);
    exit();
}

$uploaded_files = [];
// Xử lý Upload nhiều file
if (isset($_FILES['attachments']) && is_array($_FILES['attachments']['name'])) {
    $file_count = count($_FILES['attachments']['name']);
    for ($i = 0; $i < $file_count; $i++) {
        if ($_FILES['attachments']['error'][$i] === UPLOAD_ERR_OK) {
            $original_name = basename($_FILES['attachments']['name'][$i]);
            $file = [
                'name' => $_FILES['attachments']['name'][$i],
                'tmp_name' => $_FILES['attachments']['tmp_name'][$i],
                'error' => $_FILES['attachments']['error'][$i],
            ];
            $uploaded_files[] = [
                'original_name' => $original_name,
                'file_path' => storeUploadedFile($file, 'guides'),
            ];
        }
    }
}

try {
    $file_guides_json = json_encode($uploaded_files, JSON_UNESCAPED_UNICODE);

    $sql = "INSERT INTO tbl_guides (title, content, hotline, number_phone, file_guides) 
            VALUES (:title, :content, :hotline, :number_phone, :file_guides) RETURNING id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':title' => $title,
        ':content' => $content,
        ':hotline' => $hotline,
        ':number_phone' => $number_phone,
        ':file_guides' => $file_guides_json
    ]);

    $newGuideId = $stmt->fetchColumn();

    require_once __DIR__ . '/../../Model/function.php';
    // --- GHI LOG HOẠT ĐỘNG ---
    $userId = $GLOBALS['user_id'] ?? null; 
    $actionType = 'CREATE';
    $targetObject = 'tbl_guides';
    $objectId = $newGuideId;
    $description = "Đã tạo mới hướng dẫn: " . $title;
    
    writeActivityLog($pdo, $userId, $actionType, $targetObject, $objectId, $description);

    echo json_encode(['status' => 'success', 'message' => 'Tạo hướng dẫn thành công!']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi CSDL: ' . $e->getMessage()]);
}
?>
