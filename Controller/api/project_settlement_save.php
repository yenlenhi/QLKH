<?php
if (!defined('ALLOW_ACCESS')) {
    http_response_code(403);
    exit();
}

require_once __DIR__ . '/auth_middleware.php';
require_once __DIR__ . '/../../Model/db_connect.php';

$project_id        = isset($_POST['project_id']) ? intval($_POST['project_id']) : 0;
$settlement_budget = isset($_POST['settlement_budget']) ? floatval($_POST['settlement_budget']) : 0;

if ($project_id <= 0) {
    echo json_encode(["status" => "error", "message" => "Mã đề tài không hợp lệ."]);
    exit();
}

try {
    $pdo->beginTransaction();

    // 1. Lấy thông tin đề tài
    $stmtProj = $pdo->prepare("SELECT project_code, project_name, project_user_action_id, user_id FROM tbl_project WHERE project_id = ?");
    $stmtProj->execute([$project_id]);
    $proj = $stmtProj->fetch(PDO::FETCH_ASSOC);

    // 2. Cập nhật kinh phí quyết toán
    $sql = "UPDATE tbl_project SET settlement_budget = ? WHERE project_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$settlement_budget, $project_id]);

    // 3. TỰ ĐỘNG TẠO THÔNG BÁO CHỐT QUYẾT TOÁN CHO GIẢNG VIÊN
    $targetUserId = $proj ? intval($proj['project_user_action_id'] ?: $proj['user_id']) : 0;
    if ($targetUserId > 0) {
        $formattedBudget = number_format($settlement_budget, 0, ',', '.') . ' VNĐ';
        $stmtNoti = $pdo->prepare("INSERT INTO tbl_notifications (user_id, project_id, title, content, type, is_read, created_at) VALUES (?, ?, ?, ?, 'INFO', 0, NOW())");
        $stmtNoti->execute([
            $targetUserId,
            $project_id,
            "Đã chốt tổng quyết toán [{$proj['project_code']}]",
            "Tổng kinh phí quyết toán thực tế của đề tài \"{$proj['project_name']}\" đã được phê duyệt ở mức: $formattedBudget."
        ]);
    }

    $pdo->commit();

    echo json_encode([
        "status" => "success",
        "message" => "Đã chốt tổng quyết toán thành công!"
    ], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
exit();