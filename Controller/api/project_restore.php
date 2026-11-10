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
    // 1. Lấy thông tin mã và tên đề tài trước khi khôi phục để ghi log rõ ràng
    $stmtInfo = $pdo->prepare("SELECT project_code, project_name FROM tbl_project WHERE project_id = ?");
    $stmtInfo->execute([$project_id]);
    $projInfo = $stmtInfo->fetch(PDO::FETCH_ASSOC);

    $pCode = $projInfo['project_code'] ?? "ID: $project_id";
    $pName = $projInfo['project_name'] ?? "";

    // 2. Khôi phục: Đưa is_deleted về FALSE và reset deleted_at về NULL
    $sql = "UPDATE tbl_project SET is_deleted = FALSE, deleted_at = NULL WHERE project_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$project_id]);

    if ($stmt->rowCount() > 0) {
        // --- 3. BỔ SUNG GHI LOG VỚI TÊN ĐỀ TÀI CỤ THỂ ---
        require_once __DIR__ . '/../../Model/function.php';
        $current_user_id = $currentUser['user_id'] ?? 0;

        writeActivityLog(
            $pdo,
            $current_user_id,
            'UPDATE',
            'Thùng rác Đề tài',
            $project_id,
            "Đã khôi phục lại đề tài [$pCode] $pName từ thùng rác"
        );
        // ----------------------------------------------

        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Đã khôi phục đề tài thành công!"
        ], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "message" => "Không tìm thấy đề tài để khôi phục."
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
