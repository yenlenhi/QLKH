<?php
if (!defined('ALLOW_ACCESS')) {
    http_response_code(403);
    die(json_encode(["status" => "error", "message" => "Access Denied"]));
}

ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST");

// 1. Nhúng middleware xác thực và kết nối CSDL
require_once __DIR__ . '/auth_middleware.php';
require_once __DIR__ . '/../../Model/db_connect.php';
require_once __DIR__ . '/../../Model/Notification.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Phương thức không được hỗ trợ!"]);
    exit();
}

if (!isset($pdo)) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Không tìm thấy biến kết nối $pdo!"]);
    exit();
}

// 2. Đọc dữ liệu đầu vào (hỗ trợ cả JSON body lẫn FormData)
$rawInput = file_get_contents('php://input');
$inputData = json_decode($rawInput, true);
if (!is_array($inputData)) {
    $inputData = [];
}

$projectId    = intval($_POST['project_id'] ?? $inputData['project_id'] ?? 0);
// Nhận explicitUid từ user_id hoặc project_user_action_id do client truyền lên
$explicitUid  = intval($_POST['user_id'] ?? $inputData['user_id'] ?? ($_POST['project_user_action_id'] ?? $inputData['project_user_action_id'] ?? 0));
$title        = trim($_POST['title'] ?? $inputData['title'] ?? '');
$content      = trim($_POST['content'] ?? $inputData['content'] ?? '');
$type         = trim($_POST['type'] ?? $inputData['type'] ?? 'INFO');

// 3. Kiểm tra tính hợp lệ cơ bản
if ($projectId <= 0) {
    http_response_code(400);
    echo json_encode([
        "status"  => "error",
        "message" => "Thiếu mã nhiệm vụ (project_id) hợp lệ!"
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

if (empty($title) || empty($content)) {
    http_response_code(400);
    echo json_encode([
        "status"  => "error",
        "message" => "Vui lòng nhập đầy đủ tiêu đề và nội dung thông báo!"
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

try {
    // 4. Truy vấn thông tin nhiệm vụ
    $stmtProj = $pdo->prepare("SELECT project_id, project_code, project_name, user_id, project_user_action_id 
                               FROM tbl_project 
                               WHERE project_id = :pid AND is_deleted = 0 
                               LIMIT 1");
    $stmtProj->execute([':pid' => $projectId]);
    $projectRow = $stmtProj->fetch(PDO::FETCH_ASSOC);

    if (!$projectRow) {
        http_response_code(404);
        echo json_encode([
            "status"  => "error",
            "message" => "Không tìm thấy nhiệm vụ (ID: {$projectId}) hoặc nhiệm vụ đã bị xóa!"
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // ƯU TIÊN 1: Lấy user_id do JavaScript truyền lên trực tiếp
    if ($explicitUid > 0) {
        $targetUserId = $explicitUid;
    } else {
        // ƯU TIÊN 2: Lấy từ cột project_user_action_id trong CSDL
        $targetUserId = intval($projectRow['project_user_action_id'] ?? 0);
    }

    if ($targetUserId <= 0) {
        http_response_code(400);
        echo json_encode([
            "status"  => "error",
            "message" => "Chưa xác định được tài khoản Giảng viên tiếp nhận thông báo!"
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // 5. Kiểm tra thông tin tài khoản nhận
    $stmtUser = $pdo->prepare("SELECT user_id, username, type_user, name FROM tbl_user WHERE user_id = :uid LIMIT 1");
    $stmtUser->execute([':uid' => $targetUserId]);
    $userRow = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if (!$userRow) {
        http_response_code(404);
        echo json_encode([
            "status"  => "error",
            "message" => "Tài khoản người thực hiện (ID: {$targetUserId}) không tồn tại trong hệ thống!"
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    $newId = createNotification($pdo, $targetUserId, $projectId, $title, $content, $type);
    publishNotificationSafely($targetUserId, $newId, $projectId, $title, $type);

    http_response_code(200);
    echo json_encode([
        "status"  => "success",
        "message" => "Đã gửi thông báo thành công đến Giảng viên: " . ($userRow['name'] ?? ''),
        "data"    => [
            "id"                      => (int)$newId,
            "project_id"              => $projectId,
            "project_user_action_id"  => $targetUserId,
            "teacher_name"            => $userRow['name'] ?? '',
            "title"                   => $title,
            "type"                    => $type
        ]
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status"  => "error",
        "message" => "Lỗi CSDL: " . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>
