<?php
if (!defined('ALLOW_ACCESS')) {
    http_response_code(403);
    die(json_encode(["status" => "error", "message" => "Access Denied"]));
}
header('Content-Type: application/json; charset=utf-8');
// Nhúng middleware xác thực JWT (Sẽ tự động kiểm tra Token và exit nếu lỗi)

require_once __DIR__ . '/auth_middleware.php';
require_once __DIR__ . '/../../Model/db_connect.php';

if (!isset($pdo)) {
    echo json_encode(["status" => "error", "message" => "Không tìm thấy kết nối CSDL!"]);
    exit;
}

// 2. Lấy mã đề tài từ Request POST
$projectId = $_POST['id'] ?? '';

if (empty($projectId)) {
    echo json_encode(["status" => "error", "message" => "Mã đề tài không hợp lệ!"]);
    exit;
}

// 3. Thực hiện xóa đề tài khỏi Database
try {
    // Thay 'detai' và 'id' bằng tên bảng & tên cột tương ứng trong CSDL của bạn
    $sql = "DELETE FROM detai WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $projectId]);

    if ($stmt->rowCount() > 0) {
        // --- BỔ SUNG GHI LOG ---
        require_once __DIR__ . '/../../Model/function.php';
        $userId = $currentUser['user_id'] ?? 0;
        writeActivityLog($pdo, $userId, 'DELETE', 'Quản lý Đề tài', intval($projectId), "Đã xóa đề tài có ID: $projectId");
        // -----------------------
        echo json_encode([
            "status"  => "success",
            "message" => "Đã xóa đề tài thành công!"
        ]);
    } else {
        echo json_encode([
            "status"  => "error",
            "message" => "Không tìm thấy đề tài để xóa hoặc đề tài đã bị xóa trước đó!"
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        "status"  => "error",
        "message" => "Lỗi CSDL: " . $e->getMessage()
    ]);
}
