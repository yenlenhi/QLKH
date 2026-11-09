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

if (!isset($pdo)) {
    require_once __DIR__ . '/../../Model/db_connect.php';
}

$rawInput = json_decode(file_get_contents('php://input'), true);
$projects = $rawInput['projects'] ?? [];

if (!is_array($projects) || count($projects) === 0) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Không tìm thấy dữ liệu đề tài trong file."], JSON_UNESCAPED_UNICODE);
    exit();
}

$currentUserId = intval($rawInput['current_user_id'] ?? 1);

try {
    $pdo->beginTransaction();

    // 1. Tải danh mục chuẩn từ CSDL
    $regTypes = $pdo->query("SELECT register_type_id, type_name FROM tbl_project_register_type")->fetchAll(PDO::FETCH_KEY_PAIR);
    $subSystems = $pdo->query("SELECT sub_system_id, system_name FROM tbl_sub_system")->fetchAll(PDO::FETCH_KEY_PAIR);
    $securityLevels = $pdo->query("SELECT security_level_id, security_name FROM tbl_project_security_level")->fetchAll(PDO::FETCH_KEY_PAIR);
    $teachers = $pdo->query("SELECT user_id, name FROM tbl_user")->fetchAll(PDO::FETCH_KEY_PAIR);
    $levelMapping = $pdo->query("SELECT sub_system_id, level_id FROM tbl_sub_system_level")->fetchAll(PDO::FETCH_KEY_PAIR);

    // 2. Câu SQL lưu đề tài đã bao gồm cột registration_deadline
    $sqlProject = "INSERT INTO tbl_project (
        project_code, register_type_id, project_name, user_id, 
        project_user_action_id, sub_system_id, level_id, 
        security_level_id, estimated_budget, duration_months, registration_deadline, status, created_at
    ) VALUES (
        :project_code, :register_type_id, :project_name, :user_id, 
        :project_user_action_id, :sub_system_id, :level_id, 
        :security_level_id, :estimated_budget, :duration_months, :registration_deadline, 'pending', NOW()
    ) RETURNING project_id";
    $stmtProj = $pdo->prepare($sqlProject);

    $sqlMember = "INSERT INTO tbl_project_member (project_id, full_name, platoon, is_leader, created_at) 
                  VALUES (:project_id, :full_name, :platoon, :is_leader, NOW())";
    $stmtMem = $pdo->prepare($sqlMember);

    $successCount = 0;
    $errors = [];

    foreach ($projects as $index => $p) {
        $rowNum = $index + 2;
        $code = trim($p['project_code'] ?? '');
        $name = trim($p['project_name'] ?? '');
        // Nếu cả dòng đều trống thì tự động bỏ qua dòng đó
        if (empty($code) && empty($name)) {
            continue;
        }

        if (empty($code) || empty($name)) {
            $errors[] = "Dòng $rowNum: Thiếu Mã hoặc Tên đề tài.";
            continue;
        }
        if (empty($code) || empty($name)) {
            $errors[] = "Dòng $rowNum: Thiếu Mã hoặc Tên đề tài.";
            continue;
        }

        // Kiểm tra trùng mã đề tài
        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_project WHERE project_code = ? AND is_deleted = 0");
        $checkStmt->execute([$code]);
        if ($checkStmt->fetchColumn() > 0) {
            $errors[] = "Dòng $rowNum: Mã đề tài '$code' đã tồn tại.";
            continue;
        }

        // 1. Loại Đăng Ký
        $regName = trim($p['register_type_id'] ?? '');
        $regTypeId = 2;
        foreach ($regTypes as $id => $val) {
            if (mb_stripos($val, $regName) !== false || mb_stripos($regName, $val) !== false) {
                $regTypeId = $id;
                break;
            }
        }

        // 2. Loại Đề Tài
        $subName = trim($p['sub_system_id'] ?? '');
        $subSysId = 2;
        foreach ($subSystems as $id => $val) {
            if (mb_stripos($val, $subName) !== false || mb_stripos($subName, $val) !== false) {
                $subSysId = $id;
                break;
            }
        }

        // 3. Cấp Đề Tài (Tự động theo ánh xạ)
        $levelId = $levelMapping[$subSysId] ?? 1;

        // 4. Giảng Viên Phụ Trách
        $teacherName = trim($p['project_user_action_id'] ?? '');
        $teacherId = null;
        if (!empty($teacherName)) {
            foreach ($teachers as $id => $val) {
                if (mb_stripos($val, $teacherName) !== false || mb_stripos($teacherName, $val) !== false) {
                    $teacherId = $id;
                    break;
                }
            }
        }

        // 5. Mức Độ Mật
        $secName = trim($p['security_level_id'] ?? '');
        $securId = 1;
        foreach ($securityLevels as $id => $val) {
            if (mb_stripos($val, $secName) !== false) {
                $securId = $id;
                break;
            }
        }

        // 6. Thời hạn đăng ký (Xử lý chuỗi rỗng thành NULL)
        $deadline = !empty($p['registration_deadline']) ? trim($p['registration_deadline']) : null;
        if ($deadline) {
            $deadline = date('Y-m-d', strtotime($deadline));
        }

        // Thực thi thêm đề tài
        $stmtProj->execute([
            ':project_code'           => $code,
            ':register_type_id'       => $regTypeId,
            ':project_name'           => $name,
            ':user_id'                => $currentUserId,
            ':project_user_action_id' => $teacherId,
            ':sub_system_id'          => $subSysId,
            ':level_id'               => $levelId,
            ':security_level_id'      => $securId,
            ':estimated_budget'       => floatval($p['estimated_budget'] ?? 0),
            ':duration_months'        => intval($p['duration_months'] ?? 12),
            ':registration_deadline'  => $deadline
        ]);

        $newProjectId = (int) $stmtProj->fetchColumn();

        // Thêm Chủ nhiệm nếu có
        $leaderName = trim($p['leader_name'] ?? '');
        if (!empty($leaderName)) {
            $stmtMem->execute([
                ':project_id' => $newProjectId,
                ':full_name'  => $leaderName,
                ':platoon'    => trim($p['leader_unit'] ?? ''),
                ':is_leader'  => 1
            ]);
        }

        // Thêm các thành viên tham gia tiếp theo (tối đa 5)
        for ($m = 2; $m <= 5; $m++) {
            $mName = trim($p["member_$m"] ?? '');
            if (!empty($mName)) {
                $stmtMem->execute([
                    ':project_id' => $newProjectId,
                    ':full_name'  => $mName,
                    ':platoon'    => trim($p["member_unit_$m"] ?? ''),
                    ':is_leader'  => 0
                ]);
            }
        }

        $successCount++;
    }

    $pdo->commit();

    http_response_code(200);
    echo json_encode([
        "status"        => "success",
        "message"       => "Đã nhập thành công $successCount đề tài từ file Excel!",
        "success_count" => $successCount,
        "errors"        => $errors
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Lỗi CSDL: " . $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
exit();
