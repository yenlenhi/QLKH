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
require_once __DIR__ . '/../../Model/function.php';
require_once __DIR__ . '/../../Model/BlobStorage.php';
require_once __DIR__ . '/../../Model/Notification.php';

$user_id = isset($currentUser['user_id']) ? intval($currentUser['user_id']) : 0;
if ($user_id <= 0) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Không xác định được tài khoản người dùng từ Token."], JSON_UNESCAPED_UNICODE);
    exit();
}

$project_code = trim($_POST['project_code'] ?? '');
$register_type_id = isset($_POST['register_type_id']) ? intval($_POST['register_type_id']) : 0;
$project_name = trim($_POST['project_name'] ?? '');
$project_user_action_id = isset($_POST['project_user_action_id']) ? intval($_POST['project_user_action_id']) : $user_id;
$level_id = isset($_POST['level_id']) ? intval($_POST['level_id']) : 0;

$sub_system_id = isset($_POST['sub_system_id']) ? intval($_POST['sub_system_id']) : 0;
$security_level_id = isset($_POST['security_level_id']) ? intval($_POST['security_level_id']) : 0;
$estimated_budget = isset($_POST['estimated_budget']) ? floatval($_POST['estimated_budget']) : 0;
$duration_months = isset($_POST['duration_months']) ? intval($_POST['duration_months']) : 12;
$report_phases = isset($_POST['report_phases']) ? intval($_POST['report_phases']) : 2;
$members_json = $_POST['danh_sach_thanh_vien'] ?? '[]';

if (empty($project_code) || empty($project_name) || $register_type_id <= 0 || $level_id <= 0) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Vui lòng điền đầy đủ Mã đề tài, Tên đề tài và các thông tin bắt buộc."], JSON_UNESCAPED_UNICODE);
    exit();
}

try {
    $pdo->beginTransaction();

    // 1. Thêm thông tin đề tài (Đúng chuẩn 12 cột khớp 12 giá trị)
    $sqlProject = "INSERT INTO tbl_project (
                        user_id, 
                        project_user_action_id, 
                        project_code, 
                        register_type_id, 
                        project_name, 
                        level_id, 
                        sub_system_id, 
                        security_level_id, 
                        estimated_budget, 
                        duration_months, 
                        report_phases, 
                        registration_deadline, 
                        status
                   ) VALUES (
                        ?, ?, ?, ?, 
                        ?, ?, ?, ?, 
                        ?, ?, ?, 
                        NOW() + INTERVAL '30 days', 'pending'
                   ) RETURNING project_id";
    $stmtProject = $pdo->prepare($sqlProject);
    $stmtProject->execute([
        $user_id,
        $project_user_action_id,
        $project_code,
        $register_type_id,
        $project_name,
        $level_id,
        $sub_system_id,
        $security_level_id,
        $estimated_budget,
        $duration_months,
        $report_phases
    ]);

    $project_id = (int) $stmtProject->fetchColumn();

    // 2. Thêm danh sách thành viên (Gắn user_id cho Chủ nhiệm)
    // 2. Thêm danh sách thành viên
    $members = json_decode($members_json, true);
    if (!empty($members) && is_array($members)) {
        // CÂU LỆNH INSERT ĐẦY ĐỦ CẢ user_id VÀ platoon (KHOA PHÒNG)
        $sqlMember = "INSERT INTO tbl_project_member (project_id, user_id, full_name, platoon, is_leader) 
                      VALUES (?, ?, ?, ?, ?)";
        $stmtMember = $pdo->prepare($sqlMember);

        foreach ($members as $idx => $mem) {
            $fullName = trim($mem['full_name'] ?? '');

            // Lấy khoa phòng / đơn vị
            $platoon = !empty($mem['don_vi']) ? trim($mem['don_vi']) : (!empty($mem['trung_doi']) ? trim($mem['trung_doi']) : null);
            $isLeader = (isset($mem['is_chu_nhiem']) && intval($mem['is_chu_nhiem']) === 1) || ($idx === 0) ? 1 : 0;

            // NẾU LÀ CHỦ NHIỆM: GÁN user_id BẰNG project_user_action_id
            $memberUserId = ($isLeader === 1) ? $project_user_action_id : (!empty($mem['user_id']) ? intval($mem['user_id']) : null);

            // NẾU CHƯA CÓ PLATOON MÀ LÀ CHỦ NHIỆM -> TỰ ĐỘNG LẤY TỪ tbl_org_unit CỦA USER ĐÓ
            if ($isLeader === 1 && empty($platoon) && $memberUserId > 0) {
                $stmtUnit = $pdo->prepare("SELECT o.unit_name FROM tbl_user u LEFT JOIN tbl_org_unit o ON u.unit_id = o.unit_id WHERE u.user_id = ?");
                $stmtUnit->execute([$memberUserId]);
                $platoon = $stmtUnit->fetchColumn() ?: 'Chưa phân khoa/phòng';
            }

            if (!empty($fullName)) {
                $stmtMember->execute([
                    $project_id,
                    $memberUserId,
                    $fullName,
                    $platoon,
                    (bool) $isLeader
                ]);
            }
        }
    }

    // 3. Xử lý lưu file đính kèm
    $hasFileCategory = (bool) $pdo->query("SELECT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema = 'public' AND table_name = 'tbl_project_file' AND column_name = 'file_category')")->fetchColumn();

    if ($hasFileCategory) {
        $sqlFile = "INSERT INTO tbl_project_file (project_id, file_category, file_name, file_path, file_size) VALUES (?, ?, ?, ?, ?)";
    } else {
        $sqlFile = "INSERT INTO tbl_project_file (project_id, file_name, file_path, file_size) VALUES (?, ?, ?, ?)";
    }
    $stmtFile = $pdo->prepare($sqlFile);

    // 5 file đơn lẻ
    $singleFileCategories = [
        'file_don_dang_ky'      => 'Đơn đăng ký',
        'file_thuyet_minh'      => 'Thuyết minh nhiệm vụ',
        'file_tu_cach_phap_ly'  => 'Tư cách pháp lý',
        'file_nang_luc_to_chuc' => 'Năng lực tổ chức',
        'file_ly_lich_chu_nhiem' => 'Lý lịch chủ nhiệm'
    ];

    foreach ($singleFileCategories as $catKey => $catLabel) {
        if (isset($_FILES[$catKey]) && $_FILES[$catKey]['error'] === UPLOAD_ERR_OK) {
            $origName = basename($_FILES[$catKey]['name']);
            $fSize = $_FILES[$catKey]['size'];
            $blobUrl = storeUploadedFile($_FILES[$catKey], 'projects/' . $project_id);
            if ($hasFileCategory) {
                $stmtFile->execute([$project_id, $catKey, $origName, $blobUrl, $fSize]);
            } else {
                $stmtFile->execute([$project_id, $origName, $blobUrl, $fSize]);
            }
        }
    }

    // Mảng files_khac[]
    if (isset($_FILES['files_khac']) && is_array($_FILES['files_khac']['name'])) {
        $countFiles = count($_FILES['files_khac']['name']);
        for ($i = 0; $i < $countFiles; $i++) {
            if ($_FILES['files_khac']['error'][$i] === UPLOAD_ERR_OK) {
                $origName = basename($_FILES['files_khac']['name'][$i]);
                $fSize = $_FILES['files_khac']['size'][$i];
                $file = ['name' => $_FILES['files_khac']['name'][$i], 'tmp_name' => $_FILES['files_khac']['tmp_name'][$i], 'error' => $_FILES['files_khac']['error'][$i]];
                $blobUrl = storeUploadedFile($file, 'projects/' . $project_id);
                if ($hasFileCategory) {
                    $stmtFile->execute([$project_id, 'files_khac', $origName, $blobUrl, $fSize]);
                } else {
                    $stmtFile->execute([$project_id, $origName, $blobUrl, $fSize]);
                }
            }
        }
    }

    // 4. Tạo thông báo
    if ($project_user_action_id > 0 && $project_id > 0) {
        $notificationId = createNotification($pdo, $project_user_action_id, $project_id,
            "Đề xuất nhiệm vụ mới [$project_code]",
            "Nhiệm vụ \"$project_name\" đã được nộp đề xuất và đang chờ cấp trên xét duyệt.", 'INFO');
    }

    // 5. Ghi log hoạt động qua function.php
    if (function_exists('writeActivityLog')) {
        writeActivityLog($pdo, $user_id, 'CREATE', 'Quản lý Đề tài', $project_id, "Đăng ký mới đề tài: [$project_code] $project_name");
    }

    $pdo->commit();
    if (isset($notificationId)) {
        publishNotificationSafely($project_user_action_id, $notificationId, $project_id, "Đề xuất nhiệm vụ mới [$project_code]", 'INFO');
    }

    // Dọn sạch output buffer để tránh rác ký tự làm vỡ JSON
    if (ob_get_length()) ob_clean();

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "message" => "Đăng ký đề tài thành công!",
        "project_id" => $project_id
    ], JSON_UNESCAPED_UNICODE);
    exit();
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    if (ob_get_length()) ob_clean();
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Lỗi xử lý hệ thống: " . $e->getMessage()], JSON_UNESCAPED_UNICODE);
    exit();
}
