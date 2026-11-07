<?php
if (!defined('ALLOW_ACCESS')) {
    define('ALLOW_ACCESS', true);
}

ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once __DIR__ . '/auth_middleware.php';
require_once __DIR__ . '/../../Model/db_connect.php';

$currentUserId = isset($auth_user['user_id']) ? intval($auth_user['user_id']) : 0;
$method = $_SERVER['REQUEST_METHOD'];
$detect = $_REQUEST['detect'] ?? '';

try {
    // -------------------------------------------------------------
    // 1. LẤY TOÀN BỘ DANH SÁCH NHIỆM VỤ, THÀNH VIÊN VÀ PHÂN CÔNG
    // -------------------------------------------------------------
    if ($detect === 'project_task_get') {
        $projectId = isset($_REQUEST['project_id']) ? intval($_REQUEST['project_id']) : (isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0);

        if ($projectId <= 0) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Thiếu mã đề tài hợp lệ."], JSON_UNESCAPED_UNICODE);
            exit();
        }

        // Lấy thông tin đề tài
        $stmtProj = $pdo->prepare("SELECT project_id, user_id, project_user_action_id, status, report_phases FROM tbl_project WHERE project_id = ? AND is_deleted = 0");
        $stmtProj->execute([$projectId]);
        $project = $stmtProj->fetch(PDO::FETCH_ASSOC);

        if (!$project) {
            http_response_code(404);
            echo json_encode(["status" => "error", "message" => "Không tìm thấy đề tài."], JSON_UNESCAPED_UNICODE);
            exit();
        }

        $projectUserActionId = intval($project['project_user_action_id'] ?? 0);
        $creatorUserId = intval($project['user_id'] ?? 0);
        $statusVal = strtolower($project['status'] ?? '');
        $statusAllowed = in_array($statusVal, ['pending', 'running', 'cho_duyet']);

        // Xác thực Chủ nhiệm: khớp project_user_action_id, hoặc người tạo đề tài
        $isAssignedUser = ($currentUserId > 0 && ($currentUserId === $projectUserActionId || $currentUserId === $creatorUserId));
        $isAdmin = ($currentUserId === 1 || $currentUserId === 7);

        // canEditTask cho phép khi là Chủ nhiệm/người thực hiện và đề tài đang mở
        $canEdit = ($isAssignedUser || $isAdmin) && $statusAllowed;

        // Danh sách thành viên đề tài
        // $stmtMem = $pdo->prepare("SELECT member_id, full_name, platoon, is_leader FROM tbl_project_member WHERE project_id = ? ORDER BY is_leader DESC, member_id ASC");
        $stmtMem = $pdo->prepare("SELECT member_id, user_id, full_name, platoon, is_leader FROM tbl_project_member WHERE project_id = ? ORDER BY is_leader DESC, member_id ASC");
        $stmtMem->execute([$projectId]);
        $members = $stmtMem->fetchAll(PDO::FETCH_ASSOC);

        // Danh sách nhiệm vụ
        $stmtTasks = $pdo->prepare("SELECT * FROM tbl_project_task WHERE project_id = ? ORDER BY phase ASC, task_id ASC");
        $stmtTasks->execute([$projectId]);
        $tasks = $stmtTasks->fetchAll(PDO::FETCH_ASSOC);

        $stmtAssign = $pdo->prepare("
            SELECT a.task_id, a.member_id, m.user_id, m.full_name, m.platoon, m.is_leader 
            FROM tbl_project_task_assignment a
            INNER JOIN tbl_project_member m ON a.member_id = m.member_id
            WHERE m.project_id = ?
        ");
        $stmtAssign->execute([$projectId]);
        $assignments = $stmtAssign->fetchAll(PDO::FETCH_ASSOC);

        // Gộp danh sách thành viên vào từng task
        $assignMap = [];
        foreach ($assignments as $as) {
            $assignMap[$as['task_id']][] = [
                'member_id' => $as['member_id'],
                'user_id'   => $as['user_id'],
                'full_name' => $as['full_name'],
                'platoon'   => $as['platoon'],
                'is_leader' => $as['is_leader']
            ];
        }

        foreach ($tasks as &$task) {
            $task['assigned_members'] = $assignMap[$task['task_id']] ?? [];
        }

        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "can_edit" => $canEdit,
            "project_user_action_id" => $projectUserActionId,
            "report_phases" => intval($project['report_phases'] ?? 2),
            "project_status" => $project['status'],
            "data" => [
                "tasks" => $tasks,
                "members" => $members
            ]
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    $rawInput = file_get_contents('php://input');
    $input = json_decode($rawInput, true) ?: $_POST;

    // -------------------------------------------------------------
    // 2. TẠO NHIỆM VỤ MỚI (LẬP DANH SÁCH NHIỆM VỤ)
    // -------------------------------------------------------------
    if ($detect === 'project_task_create') {
        $projectId = intval($input['project_id'] ?? 0);
        $taskTitle = trim($input['task_title'] ?? '');
        $phase = intval($input['phase'] ?? 1);
        $dueDate = !empty($input['due_date']) ? trim($input['due_date']) : null;
        $description = trim($input['description'] ?? '');

        if ($projectId <= 0 || empty($taskTitle)) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Vui lòng nhập tên nhiệm vụ."], JSON_UNESCAPED_UNICODE);
            exit();
        }

        $stmtInsert = $pdo->prepare("
            INSERT INTO tbl_project_task (project_id, task_title, description, phase, due_date, status)
            VALUES (?, ?, ?, ?, ?, 'todo')
        ");
        $stmtInsert->execute([$projectId, $taskTitle, $description, $phase, $dueDate]);

        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "Đã thêm nhiệm vụ vào danh sách."], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // -------------------------------------------------------------
    // 3. PHÂN CÔNG THÀNH VIÊN CHO NHIỆM VỤ (QUAN HỆ N-N)
    // -------------------------------------------------------------
    if ($detect === 'project_task_assign') {
        $taskId = intval($input['task_id'] ?? 0);
        $memberIds = isset($input['member_ids']) && is_array($input['member_ids']) ? $input['member_ids'] : [];

        if ($taskId <= 0) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Mã nhiệm vụ không hợp lệ."], JSON_UNESCAPED_UNICODE);
            exit();
        }

        // Xóa các phân công cũ của task này
        $stmtDel = $pdo->prepare("DELETE FROM tbl_project_task_assignment WHERE task_id = ?");
        $stmtDel->execute([$taskId]);

        // Thêm danh sách thành viên mới được chọn
        if (!empty($memberIds)) {
            $stmtInsert = $pdo->prepare("INSERT INTO tbl_project_task_assignment (task_id, member_id) VALUES (?, ?)");
            foreach ($memberIds as $mId) {
                $mId = intval($mId);
                if ($mId > 0) {
                    $stmtInsert->execute([$taskId, $mId]);
                }
            }
        }

        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "Cập nhật phân công thành công."], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // -------------------------------------------------------------
    // 4. TÍCH CHỌN HOÀN THÀNH
    // -------------------------------------------------------------
    if ($detect === 'project_task_toggle_status') {
        $taskId = intval($input['task_id'] ?? 0);
        $isCompleted = !empty($input['is_completed']);

        $status = $isCompleted ? 'completed' : 'todo';
        $completedAt = $isCompleted ? date('Y-m-d H:i:s') : null;

        $stmtUpdate = $pdo->prepare("UPDATE tbl_project_task SET status = ?, completed_at = ? WHERE task_id = ?");
        $stmtUpdate->execute([$status, $completedAt, $taskId]);

        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "Đã cập nhật tiến độ."], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // -------------------------------------------------------------
    // 5. XÓA NHIỆM VỤ
    // -------------------------------------------------------------
    if ($detect === 'project_task_delete') {
        $taskId = intval($input['task_id'] ?? 0);

        $stmtDelete = $pdo->prepare("DELETE FROM tbl_project_task WHERE task_id = ?");
        $stmtDelete->execute([$taskId]);

        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "Đã xóa nhiệm vụ."], JSON_UNESCAPED_UNICODE);
        exit();
    }

    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Hành động detect không hợp lệ."], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Lỗi CSDL: " . $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
exit();
