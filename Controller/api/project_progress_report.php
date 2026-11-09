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
require_once __DIR__ . '/../../Model/BlobStorage.php';

$project_id = isset($_POST['project_id']) ? intval($_POST['project_id']) : 0;
$report_phase = isset($_POST['report_phase']) ? intval($_POST['report_phase']) : 1; // Đợt báo cáo (1, 2, 3, 4 tương ứng 3, 6, 9, 12 tháng)
$note = $_POST['note'] ?? '';

if ($project_id <= 0) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Thiếu mã đề tài báo cáo tiến độ."], JSON_UNESCAPED_UNICODE);
    exit();
}

try {
    $pdo->beginTransaction();

    // 1. Kiểm tra đề tài tồn tại và đang ở trạng thái running
    $stmt = $pdo->prepare("SELECT status FROM tbl_project WHERE project_id = ?");
    $stmt->execute([$project_id]);
    $proj = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$proj) {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "Không tìm thấy đề tài."], JSON_UNESCAPED_UNICODE);
        exit();
    }

    if ($proj['status'] !== 'running') {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Chỉ có thể nộp báo cáo tiến độ khi đề tài đang ở trạng thái Đang tiến hành (running)."], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // 2. Lưu file báo cáo tiến độ vào bảng tbl_project_file (có thể gắn thêm phân loại nếu muốn, ở đây lưu chung vào bảng file đề tài)
    if (isset($_FILES['report_file']) && $_FILES['report_file']['error'] === UPLOAD_ERR_OK) {
        $originalName = basename($_FILES['report_file']['name']);
        $fileSize = $_FILES['report_file']['size'];
        $sqlFile = "INSERT INTO tbl_project_file (project_id, file_name, file_path, file_size) VALUES (?, ?, ?, ?)";
        $stmtFile = $pdo->prepare($sqlFile);
        $stmtFile->execute([$project_id, "[Báo cáo Đợt $report_phase] " . $originalName, storeUploadedFile($_FILES['report_file'], 'reports/' . $project_id), $fileSize]);
    }

    

    $pdo->commit();
    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "message" => "Nộp báo cáo tiến độ đợt $report_phase thành công!"
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Lỗi xử lý: " . $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
exit();
