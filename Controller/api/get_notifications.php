<?php
if (!defined('ALLOW_ACCESS')) {
    http_response_code(403);
    die(json_encode(["status" => "error", "message" => "Access Denied"]));
}

header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");

require_once __DIR__ . '/auth_middleware.php';
require_once __DIR__ . '/../../Model/db_connect.php';

// Lấy trực tiếp user_id từ Token đã xác thực trong auth_middleware.php
$userId = intval($currentUser['user_id'] ?? 0);

if ($userId <= 0) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Không xác định được user_id từ Token!"], JSON_UNESCAPED_UNICODE);
    exit();
}

try {
    // 1. Lấy 10 thông báo mới nhất của đúng user_id
    $sqlNotifications = "SELECT id, project_id, title, content, type,
                                CASE WHEN is_read IS TRUE THEN 1 ELSE 0 END AS is_read,
                                TO_CHAR(created_at, 'DD/MM/YYYY HH24:MI') AS created_at 
                         FROM tbl_notifications 
                         WHERE user_id = :user_id
                         ORDER BY id DESC 
                         LIMIT 10";
                         
    $stmtNoti = $pdo->prepare($sqlNotifications);
    $stmtNoti->execute([':user_id' => $userId]);
    $notifications = $stmtNoti->fetchAll(PDO::FETCH_ASSOC);

    // 2. Đếm số lượng thông báo chưa đọc (is_read = 0) của đúng user_id
    $sqlUnread = "SELECT COUNT(*) FROM tbl_notifications WHERE user_id = :user_id AND is_read IS FALSE";
    $stmtUnread = $pdo->prepare($sqlUnread);
    $stmtUnread->execute([':user_id' => $userId]);
    $unreadCount = (int)$stmtUnread->fetchColumn();

    http_response_code(200);
    echo json_encode([
        "status"        => "success",
        "unread_count"  => $unreadCount,
        "notifications" => $notifications
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status"  => "error",
        "message" => "Lỗi CSDL: " . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
exit();
