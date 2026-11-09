<?php
if (!defined('ALLOW_ACCESS')) {
    http_response_code(403);
    die(json_encode(["status" => "error", "message" => "Access Denied"]));
}

ini_set('display_errors', 0);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/auth_middleware.php';
require_once __DIR__ . '/../../Model/db_connect.php';
require_once __DIR__ . '/../../Model/BlobStorage.php';
require_once __DIR__ . '/../../Model/Notification.php';

$activeUser = isset($auth_user) ? $auth_user : (isset($currentUser) ? $currentUser : []);
$user_id = isset($activeUser['user_id']) ? intval($activeUser['user_id']) : (isset($activeUser['id']) ? intval($activeUser['id']) : 0);

if ($user_id <= 0) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Phiên đăng nhập không hợp lệ hoặc đã hết hạn."]);
    exit();
}

try {
    $payment_id    = isset($_POST['payment_id']) ? intval($_POST['payment_id']) : 0;
    $project_id    = isset($_POST['project_id']) ? intval($_POST['project_id']) : 0;
    $payment_phase = isset($_POST['payment_phase']) ? trim($_POST['payment_phase']) : '';
    $paid_amount   = isset($_POST['paid_amount']) ? floatval($_POST['paid_amount']) : 0;
    $payment_date  = isset($_POST['payment_date']) ? trim($_POST['payment_date']) : '';
    $status        = isset($_POST['status']) ? trim($_POST['status']) : 'chua_quyet_toan';
    $note          = isset($_POST['note']) ? trim($_POST['note']) : '';

    if ($project_id <= 0 || empty($payment_phase) || $paid_amount <= 0 || empty($payment_date)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Vui lòng điền đầy đủ thông tin bắt buộc (Đợt, Số tiền > 0, Ngày nhận)."]);
        exit();
    }

    // Xử lý upload tệp chứng từ nếu có
    $uploadedFilePath = null;
    $uploadedFileName = null;

    if (isset($_FILES['payment_file']) && $_FILES['payment_file']['error'] === UPLOAD_ERR_OK) {
        $origName = basename($_FILES['payment_file']['name']);
        $uploadedFileName = $origName;
        $uploadedFilePath = storeUploadedFile($_FILES['payment_file'], 'payments/' . $project_id);
    }

    $pdo->beginTransaction();

    // Lấy thông tin đề tài để gửi thông báo
    $stmtProj = $pdo->prepare("SELECT project_code, project_name, project_user_action_id, user_id FROM tbl_project WHERE project_id = ?");
    $stmtProj->execute([$project_id]);
    $proj = $stmtProj->fetch(PDO::FETCH_ASSOC);

    if (!$proj) {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "Không tìm thấy đề tài tương ứng."]);
        exit();
    }

    $targetUserId = intval($proj['project_user_action_id'] ?: $proj['user_id']);
    $formattedMoney = number_format($paid_amount, 0, ',', '.') . ' VNĐ';

    if ($payment_id > 0) {
        if ($uploadedFilePath !== null) {
            $sql = "UPDATE tbl_project_payment 
                    SET payment_phase = ?, paid_amount = ?, payment_date = ?, status = ?, note = ?, file_path = ?, file_name = ? 
                    WHERE payment_id = ? AND project_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$payment_phase, $paid_amount, $payment_date, $status, $note, $uploadedFilePath, $uploadedFileName, $payment_id, $project_id]);
        } else {
            $sql = "UPDATE tbl_project_payment 
                    SET payment_phase = ?, paid_amount = ?, payment_date = ?, status = ?, note = ? 
                    WHERE payment_id = ? AND project_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$payment_phase, $paid_amount, $payment_date, $status, $note, $payment_id, $project_id]);
        }
        $msg = "Cập nhật đợt thanh toán thành công!";
        $notiTitle = "Cập nhật thanh toán [{$proj['project_code']}]";
        $notiContent = "Đợt thanh toán \"$payment_phase\" của đề tài \"{$proj['project_name']}\" đã được cập nhật số tiền: $formattedMoney.";
    } else {
        $sql = "INSERT INTO tbl_project_payment (project_id, payment_phase, paid_amount, payment_date, status, note, file_path, file_name) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$project_id, $payment_phase, $paid_amount, $payment_date, $status, $note, $uploadedFilePath, $uploadedFileName]);
        $msg = "Thêm đợt thanh toán thành công!";
        $notiTitle = "Giải ngân kinh phí [{$proj['project_code']}]";
        $notiContent = "Đề tài \"{$proj['project_name']}\" vừa được ghi nhận giải ngân đợt \"$payment_phase\" với số tiền: $formattedMoney.";
    }

    if ($targetUserId > 0) {
        $notificationId = createNotification($pdo, $targetUserId, $project_id, $notiTitle, $notiContent, 'SUCCESS');
    }

    if (file_exists(__DIR__ . '/../../Model/function.php')) {
        require_once __DIR__ . '/../../Model/function.php';
        if (function_exists('writeActivityLog')) {
            writeActivityLog($pdo, $user_id, 'PAYMENT', 'Tài chính Đề tài', $project_id, "Ghi nhận thanh toán: $payment_phase - $formattedMoney");
        }
    }

    $pdo->commit();
    if (isset($notificationId)) {
        publishNotificationSafely($targetUserId, $notificationId, $project_id, $notiTitle, 'SUCCESS');
    }
    echo json_encode(["status" => "success", "message" => $msg], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Lỗi CSDL: " . $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
exit();
