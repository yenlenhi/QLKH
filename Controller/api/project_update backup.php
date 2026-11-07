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

$project_id = isset($_POST['project_id']) ? intval($_POST['project_id']) : 0;
$status = isset($_POST['status']) ? trim($_POST['status']) : '';
$project_name = isset($_POST['project_name']) ? trim($_POST['project_name']) : '';

if ($project_id <= 0) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Thiếu mã đề tài cần cập nhật."], JSON_UNESCAPED_UNICODE);
    exit();
}

try {
    $pdo->beginTransaction();

    // 1. Lấy thông tin hiện tại của đề tài
    $stmtCheck = $pdo->prepare("SELECT project_code, project_name, project_user_action_id, user_id, status, approved_date FROM tbl_project WHERE project_id = ?");
    $stmtCheck->execute([$project_id]);
    $currentProj = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if (!$currentProj) {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "Không tìm thấy đề tài."], JSON_UNESCAPED_UNICODE);
        exit();
    }

    $oldStatus = $currentProj['status'];
    $existingApprovedDate = $currentProj['approved_date'];
    $targetUserId = intval($currentProj['project_user_action_id'] ?: $currentProj['user_id']);
    $projCode = $currentProj['project_code'];
    $projTitle = !empty($project_name) ? $project_name : $currentProj['project_name'];

    // 2. Xây dựng câu lệnh cập nhật động
    $updateFields = [];
    $params = [];

    if (!empty($status)) {
        $updateFields[] = "status = ?";
        $params[] = $status;

        if ($oldStatus === 'pending' && $status === 'running' && empty($existingApprovedDate)) {
            $updateFields[] = "approved_date = NOW()";
        }
    }

    if (!empty($project_name)) {
        $updateFields[] = "project_name = ?";
        $params[] = $project_name;
    }

    if (count($updateFields) > 0) {
        $params[] = $project_id;
        $sql = "UPDATE tbl_project SET " . implode(", ", $updateFields) . " WHERE project_id = ?";
        $stmtUpdate = $pdo->prepare($sql);
        $stmtUpdate->execute($params);
    }

    // 3. Xử lý upload file bổ sung (nếu có)
    if (isset($_FILES['files']) && !empty($_FILES['files']['name'][0])) {
        $uploadDir = __DIR__ . '/../../asset/file/project/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $sqlFile = "INSERT INTO tbl_project_file (project_id, file_name, file_path, file_size) VALUES (?, ?, ?, ?)";
        $stmtFile = $pdo->prepare($sqlFile);

        $fileCount = count($_FILES['files']['name']);
        for ($i = 0; $i < $fileCount; $i++) {
            if ($_FILES['files']['error'][$i] === UPLOAD_ERR_OK) {
                $originalName = basename($_FILES['files']['name'][$i]);
                $fileSize = $_FILES['files']['size'][$i];

                $uniqueFileName = time() . '_' . mt_rand(1000, 9999) . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $originalName);
                $destination = $uploadDir . $uniqueFileName;
                $relativePath = 'asset/file/project/' . $uniqueFileName;

                if (move_uploaded_file($_FILES['files']['tmp_name'][$i], $destination)) {
                    $stmtFile->execute([$project_id, $originalName, $relativePath, $fileSize]);
                }
            }
        }
    }

    // 4. TỰ ĐỘNG TẠO THÔNG BÁO KHI THAY ĐỔI TRẠNG THÁI ĐỀ TÀI
    if (!empty($status) && $status !== $oldStatus && $targetUserId > 0) {
        $notiTitle = "";
        $notiContent = "";
        $notiType = "INFO";

        if ($status === 'running') {
            $notiTitle = "Đã xét duyệt đề tài [$projCode]";
            $notiContent = "Đề tài \"$projTitle\" đã được Hội đồng phê duyệt và kích hoạt tiến độ thực hiện.";
            $notiType = "SUCCESS";
        } elseif ($status === 'awaiting_acceptance') {
            $notiTitle = "Chuyển giai đoạn nghiệm thu [$projCode]";
            $notiContent = "Đề tài \"$projTitle\" đã được chuyển sang giai đoạn Chờ nghiệm thu.";
            $notiType = "INFO";
        } elseif ($status === 'awaiting_payment') {
            $notiTitle = "Chờ thanh quyết toán [$projCode]";
            $notiContent = "Đề tài \"$projTitle\" đã nghiệm thu và chuyển sang giai đoạn Chờ thanh toán kinh phí.";
            $notiType = "WARNING";
        } elseif ($status === 'completed') {
            $notiTitle = "Hoàn thành đề tài [$projCode]";
            $notiContent = "Chúc mừng! Đề tài \"$projTitle\" đã hoàn tất toàn bộ quy trình nghiệm thu và thanh quyết toán.";
            $notiType = "SUCCESS";
        }

        if (!empty($notiTitle)) {
            $stmtNoti = $pdo->prepare("INSERT INTO tbl_notifications (user_id, project_id, title, content, type, is_read, created_at) VALUES (?, ?, ?, ?, ?, 0, NOW())");
            $stmtNoti->execute([$targetUserId, $project_id, $notiTitle, $notiContent, $notiType]);
        }
    }

    $pdo->commit();
    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "message" => "Cập nhật đề tài thành công!"
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Lỗi xử lý: " . $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
exit();