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
require_once __DIR__ . '/../../Model/Notification.php';

$input = json_decode(file_get_contents('php://input'), true);
$project_id = isset($input['project_id']) ? intval($input['project_id']) : (isset($_POST['project_id']) ? intval($_POST['project_id']) : 0);

if ($project_id <= 0) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Thiếu mã đề tài cần từ chối."], JSON_UNESCAPED_UNICODE);
    exit();
}

try {
    $pdo->beginTransaction();

    // 1. Lấy thông tin đề tài để gửi thông báo
    $stmtProj = $pdo->prepare("SELECT project_code, project_name, project_user_action_id, user_id FROM tbl_project WHERE project_id = ?");
    $stmtProj->execute([$project_id]);
    $proj = $stmtProj->fetch(PDO::FETCH_ASSOC);

    if (!$proj) {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "Không tìm thấy đề tài."], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // 2. Cập nhật trạng thái thành 'rejected'
    $sql = "UPDATE tbl_project SET status = 'rejected' WHERE project_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$project_id]);

    // 3. TỰ ĐỘNG GỬI THÔNG BÁO TỪ CHỐI CHO GIẢNG VIÊN
    $targetUserId = intval($proj['project_user_action_id'] ?: $proj['user_id']);
    if ($targetUserId > 0) {
        $notificationId = createNotification($pdo, $targetUserId, $project_id,
            "Đề tài bị từ chối [{$proj['project_code']}]",
            "Đề tài \"{$proj['project_name']}\" không được thông qua. Vui lòng kiểm tra lại hồ sơ thuyết minh.", 'DANGER');
    }

    // --- BỔ SUNG GHI LOG ---
    require_once __DIR__ . '/../../Model/function.php';
    $current_user_id = $currentUser['user_id'] ?? 0;
    writeActivityLog($pdo, $current_user_id, 'REJECT', 'Quản lý Đề tài', $project_id, "Đã từ chối duyệt đề tài: [{$proj['project_code']}] {$proj['project_name']}");
    // -----------------------
    $pdo->commit();
    if (isset($notificationId)) {
        publishNotificationSafely($targetUserId, $notificationId, $project_id, "Đề tài bị từ chối [{$proj['project_code']}]", 'DANGER');
    }
    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "message" => "Đã từ chối đề tài thành công!"
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Lỗi CSDL: " . $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
exit();
