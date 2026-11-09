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

if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    echo json_encode(['status' => 'error', 'message' => 'Phương thức không hợp lệ.']);
    exit();
}

$id = intval($_GET['id'] ?? $_POST['id'] ?? 0);

if ($id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'ID không hợp lệ.']);
    exit();
}

try {
    // Xóa các file đính kèm vật lý trên ổ đĩa
    $stmtSelect = $pdo->prepare("SELECT file_guides FROM tbl_guides WHERE id = :id");
    $stmtSelect->execute([':id' => $id]);
    $guide = $stmtSelect->fetch();

    if ($guide && !empty($guide['file_guides'])) {
        $files = json_decode($guide['file_guides'], true);
        if (is_array($files)) {
            foreach ($files as $file) {
                deleteFromBlob($file['file_path'] ?? null);
            }
        }
    }

    // Xóa bản ghi trong CSDL
    $stmt = $pdo->prepare("DELETE FROM tbl_guides WHERE id = :id");
    $stmt->execute([':id' => $id]);
    // --- GHI LOG HOẠT ĐỘNG ---
    $userId = $GLOBALS['user_id'] ?? null; // Hoặc lấy từ $_SESSION['user_id'] tùy cấu trúc dự án
    $actionType = 'DELETE';
    $targetObject = 'tbl_guides';
    $objectId = $id;
    $description = "Đã xóa hướng dẫn: " . ($guide['title'] ?? "ID #$id");
    
    writeActivityLog($pdo, $userId, $actionType, $targetObject, $objectId, $description);

    echo json_encode(['status' => 'success', 'message' => 'Đã xóa hướng dẫn thành công.']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi CSDL: ' . $e->getMessage()]);
}
?>
