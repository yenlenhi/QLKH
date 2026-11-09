<?php
// Không cần kiểm tra ALLOW_ACCESS nếu đây là script chạy ngầm qua Cronjob hoặc gọi tự động
ini_set('display_errors', 0);
error_reporting(E_ALL);

header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../../Model/db_connect.php'; // Điều chỉnh đường dẫn kết nối CSDL cho phù hợp

try {
    // Câu lệnh quét và xóa vĩnh viễn các đề tài ở thùng rác (is_deleted = 1) quá 30 ngày
    $sql = "DELETE FROM tbl_project WHERE is_deleted = 1 AND deleted_at < (NOW() - INTERVAL '30 days')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $deletedCount = $stmt->rowCount();

    // Ghi log hoặc trả về kết quả nếu gọi trực tiếp
    echo json_encode([
        "status" => "success",
        "message" => "Đã tự động dọn dẹp $deletedCount đề tài quá hạn 30 ngày trong thùng rác.",
        "deleted_count" => $deletedCount
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Lỗi dọn dẹp thùng rác tự động: " . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
exit();
?>
