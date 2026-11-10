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
require_once __DIR__ . '/../../Model/Notification.php';

// Hỗ trợ đọc cả POST Form-Data và JSON Body payload
$rawInput = json_decode(file_get_contents('php://input'), true);
if (!is_array($rawInput)) {
    $rawInput = [];
}

// Hàm lấy giá trị an toàn từ $_POST hoặc JSON
$getParam = function ($key, $default = null) use ($rawInput) {
    if (isset($_POST[$key])) return $_POST[$key];
    if (isset($rawInput[$key])) return $rawInput[$key];
    return $default;
};

$project_id = intval($getParam('project_id', 0));
$status = trim((string)$getParam('status', ''));
$project_name = trim((string)$getParam('project_name', ''));

if ($project_id <= 0) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Thiếu mã đề tài cần cập nhật."], JSON_UNESCAPED_UNICODE);
    exit();
}

try {
    $pdo->beginTransaction();

    // 1. Kiểm tra đề tài hiện tại
    $stmtCheck = $pdo->prepare("SELECT * FROM tbl_project WHERE project_id = ? AND is_deleted IS FALSE");
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

    // 2. Xây dựng các trường cập nhật động cho tbl_project
    $updateFields = [];
    $params = [];

    if (!empty($status)) {
        $updateFields[] = "status = ?";
        $params[] = $status;
        if (($oldStatus === 'pending' || $oldStatus === 'cho_duyet') && $status === 'running' && empty($existingApprovedDate)) {
            $updateFields[] = "approved_date = NOW()";
        }
    }

    if (!empty($project_name)) {
        $updateFields[] = "project_name = ?";
        $params[] = $project_name;
    }

    // BỔ SUNG: project_user_action_id vào danh sách các trường số nguyên
    $optionalIntFields = [
        'register_type_id',
        'level_id',
        // 'project_type_id',
        'sub_system_id',
        'security_level_id',
        'duration_months',
        'project_user_action_id' // Cập nhật GV phụ trách khi giao nhiệm vụ
    ];

    foreach ($optionalIntFields as $field) {
        $val = $getParam($field, null);
        if ($val !== null && intval($val) > 0) {
            $updateFields[] = "$field = ?";
            $params[] = intval($val);
        }
    }

    // BỔ SUNG: Hạn chót đăng ký (registration_deadline)
    $regDeadline = $getParam('registration_deadline', null);
    if ($regDeadline !== null) {
        if (!empty($regDeadline)) {
            $updateFields[] = "registration_deadline = ?";
            $params[] = date('Y-m-d', strtotime($regDeadline));
        } else {
            $updateFields[] = "registration_deadline = NULL";
        }
    }

    $estimatedBudget = $getParam('estimated_budget', null);
    if ($estimatedBudget !== null && $estimatedBudget !== '') {
        $updateFields[] = "estimated_budget = ?";
        $params[] = floatval($estimatedBudget);
    }

    if (count($updateFields) > 0) {
        $params[] = $project_id;
        $sql = "UPDATE tbl_project SET " . implode(", ", $updateFields) . " WHERE project_id = ?";
        $stmtUpdate = $pdo->prepare($sql);
        $stmtUpdate->execute($params);
    }

    // 3. Cập nhật danh sách thành viên (Gắn user_id và đơn vị cho Chủ nhiệm)
    $rawMembers = $getParam('danh_sach_thanh_vien', null);
    if ($rawMembers !== null) {
        $members = is_array($rawMembers) ? $rawMembers : json_decode($rawMembers, true);
        if (is_array($members) && count($members) > 0) {
            // Xóa danh sách thành viên cũ
            $stmtDelMem = $pdo->prepare("DELETE FROM tbl_project_member WHERE project_id = ?");
            $stmtDelMem->execute([$project_id]);

            // Xác định project_user_action_id hiện tại của đề tài
            $actionUserId = intval($getParam('project_user_action_id', $currentProj['project_user_action_id'] ?: $currentProj['user_id']));

            // Chuẩn bị câu lệnh chèn đầy đủ user_id và platoon
            $stmtInsMem = $pdo->prepare("INSERT INTO tbl_project_member (project_id, user_id, full_name, platoon, is_leader) VALUES (?, ?, ?, ?, ?)");

            foreach ($members as $idx => $mem) {
                $fullName = trim($mem['full_name'] ?? '');
                $platoon = !empty($mem['don_vi']) ? trim($mem['don_vi']) : (!empty($mem['trung_doi']) ? trim($mem['trung_doi']) : (!empty($mem['platoon']) ? trim($mem['platoon']) : null));
                $isLeader = (isset($mem['is_chu_nhiem']) && intval($mem['is_chu_nhiem']) === 1) || (isset($mem['is_leader']) && intval($mem['is_leader']) === 1) || ($idx === 0) ? 1 : 0;

                // Nếu là Chủ nhiệm -> gán user_id bằng actionUserId
                $memberUserId = ($isLeader === 1) ? $actionUserId : (!empty($mem['user_id']) ? intval($mem['user_id']) : null);

                // Nếu là Chủ nhiệm mà chưa có platoon -> tự động lấy từ tbl_org_unit của user
                if ($isLeader === 1 && empty($platoon) && $memberUserId > 0) {
                    $stmtUnit = $pdo->prepare("SELECT o.unit_name FROM tbl_user u LEFT JOIN tbl_org_unit o ON u.unit_id = o.unit_id WHERE u.user_id = ?");
                    $stmtUnit->execute([$memberUserId]);
                    $platoon = $stmtUnit->fetchColumn() ?: null;
                }

                if ($fullName !== '') {
                    $stmtInsMem->execute([$project_id, $memberUserId, $fullName, $platoon, (bool) $isLeader]);
                }
            }
        }
    }

    // 4. Xóa các file cũ nếu người dùng bấm xóa trên giao diện (deleted_file_ids)
    $rawDelIds = $getParam('deleted_file_ids', null);
    if ($rawDelIds !== null) {
        $delIds = is_array($rawDelIds) ? $rawDelIds : json_decode($rawDelIds, true);
        if (is_array($delIds) && count($delIds) > 0) {
            $stmtGetFile = $pdo->prepare("SELECT file_path FROM tbl_project_file WHERE file_id = ? AND project_id = ?");
            $stmtDelFile = $pdo->prepare("DELETE FROM tbl_project_file WHERE file_id = ? AND project_id = ?");
            foreach ($delIds as $fid) {
                $fidInt = intval($fid);
                $stmtGetFile->execute([$fidInt, $project_id]);
                $fRow = $stmtGetFile->fetch(PDO::FETCH_ASSOC);
                if ($fRow && !empty($fRow['file_path'])) {
                    deleteFromBlob($fRow['file_path']);
                }
                $stmtDelFile->execute([$fidInt, $project_id]);
            }
        }
    }

    // 5. Lưu các file mới tải lên (nếu có từ FormData $_FILES)
    if (isset($_FILES['files']) && !empty($_FILES['files']['name'][0])) {
        $sqlFile = "INSERT INTO tbl_project_file (project_id, file_name, file_path, file_size) VALUES (?, ?, ?, ?)";
        $stmtFile = $pdo->prepare($sqlFile);

        $fileCount = count($_FILES['files']['name']);
        for ($i = 0; $i < $fileCount; $i++) {
            if ($_FILES['files']['error'][$i] === UPLOAD_ERR_OK) {
                $originalName = basename($_FILES['files']['name'][$i]);
                $fileSize = $_FILES['files']['size'][$i];

                $file = ['name' => $_FILES['files']['name'][$i], 'tmp_name' => $_FILES['files']['tmp_name'][$i], 'error' => $_FILES['files']['error'][$i]];
                $stmtFile->execute([$project_id, $originalName, storeUploadedFile($file, 'projects/' . $project_id), $fileSize]);
            }
        }
    }

    // 6. Tự động tạo thông báo nếu có chuyển đổi trạng thái
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
            $notificationId = createNotification($pdo, $targetUserId, $project_id, $notiTitle, $notiContent, $notiType);
        }
    }

    // 7. Ghi nhật ký hoạt động (Activity Log)
    if (file_exists(__DIR__ . '/../../Model/function.php')) {
        require_once __DIR__ . '/../../Model/function.php';
        if (function_exists('writeActivityLog')) {
            $statusMap = [
                'pending' => 'Chờ duyệt',
                'running' => 'Đang tiến hành',
                'awaiting_acceptance' => 'Chờ nghiệm thu',
                'awaiting_payment' => 'Chờ thanh toán',
                'completed' => 'Đã hoàn thành',
                'rejected' => 'Đã từ chối'
            ];

            $newActionUser = $getParam('project_user_action_id', null);
            if ($newActionUser !== null && intval($newActionUser) > 0) {
                $logDescription = "Đã giao/phân công nhiệm vụ [$projCode] cho cán bộ ID: $newActionUser";
            } elseif (!empty($status) && $status !== $oldStatus && isset($statusMap[$status])) {
                $statusText = $statusMap[$status];
                $logDescription = "Đã cập nhật đề tài [$projCode] $projTitle sang trạng thái: $statusText";
            } else {
                $logDescription = "Đã cập nhật thông tin chi tiết cho đề tài: [$projCode] $projTitle";
            }

            writeActivityLog(
                $pdo,
                $currentUser['user_id'] ?? 0,
                'UPDATE',
                'Quản lý Đề tài',
                $project_id,
                $logDescription
            );
        }
    }

    $pdo->commit();
    if (isset($notificationId)) {
        publishNotificationSafely($targetUserId, $notificationId, $project_id, $notiTitle, $notiType);
    }
    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "message" => "Cập nhật thông tin đề tài thành công!"
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Lỗi xử lý: " . $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
exit();
