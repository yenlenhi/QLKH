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
$upload_dir = '../../asset/file/guides/';

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Xử lý Upload nhiều file
if (isset($_FILES['attachments']) && is_array($_FILES['attachments']['name'])) {
    $file_count = count($_FILES['attachments']['name']);
    for ($i = 0; $i < $file_count; $i++) {
        if ($_FILES['attachments']['error'][$i] === UPLOAD_ERR_OK) {
            $tmp_name = $_FILES['attachments']['tmp_name'][$i];
            $original_name = basename($_FILES['attachments']['name'][$i]);
            $file_ext = pathinfo($original_name, PATHINFO_EXTENSION);
            
            $new_filename = time() . '_' . uniqid() . '.' . $file_ext;
            $target_path = $upload_dir . $new_filename;

            if (move_uploaded_file($tmp_name, $target_path)) {
                $uploaded_files[] = [
                    'original_name' => $original_name,
                    'file_path' => 'asset/file/guides/' . $new_filename
                ];
            }
        }
    }
}

try {
    $file_guides_json = json_encode($uploaded_files, JSON_UNESCAPED_UNICODE);

    $sql = "INSERT INTO tbl_guides (title, content, hotline, number_phone, file_guides) 
            VALUES (:title, :content, :hotline, :number_phone, :file_guides)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':title' => $title,
        ':content' => $content,
        ':hotline' => $hotline,
        ':number_phone' => $number_phone,
        ':file_guides' => $file_guides_json
    ]);

    $newGuideId = $pdo->lastInsertId(); // <-- BỔ SUNG DÒNG NÀY ĐỂ LẤY ID VỪA TẠO

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