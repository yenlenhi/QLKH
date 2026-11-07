<?php
if (!defined('ALLOW_ACCESS')) {
    http_response_code(403);
    die(json_encode(["status" => "error", "message" => "Access Denied"]));
}

ini_set('display_errors', 0);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require_once __DIR__ . '/auth_middleware.php';
require_once __DIR__ . '/../../Model/db_connect.php';

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST;
}

$project_id = isset($input['project_id']) ? intval($input['project_id']) : 0;

if ($project_id <= 0) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Thiếu hoặc sai định dạng project_id."], JSON_UNESCAPED_UNICODE);
    exit();
}

try {
    // Xóa vĩnh viễn khỏi CSDL (Nhờ có CASCADE nên các bảng liên quan như payment sẽ tự động xóa theo)
    $sql = "DELETE FROM tbl_project WHERE project_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$project_id]);

    if ($stmt->rowCount() > 0) {
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Đã xóa vĩnh viễn đề tài khỏi hệ thống!"
        ], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "message" => "Không tìm thấy đề tài để xóa."
        ], JSON_UNESCAPED_UNICODE);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Lỗi CSDL: " . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
exit();
?>