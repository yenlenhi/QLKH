<?php
if (!defined('ALLOW_ACCESS')) {
    http_response_code(403);
    die(json_encode(["status" => "error", "message" => "Access Denied"]));
}

if (function_exists('apache_setenv')) {
    @apache_setenv('no-gzip', 1);
}
@ini_set('zlib.output_compression', 0);
@ini_set('implicit_flush', 1);

while (ob_get_level() > 0) {
    ob_end_flush();
}

require_once __DIR__ . '/auth_middleware.php';
require_once __DIR__ . '/../../Model/db_connect.php';

header('Content-Type: text/event-stream; charset=UTF-8');
header('Cache-Control: no-cache');
header('Connection: keep-alive');
header('X-Accel-Buffering: no');

set_time_limit(0);

// Lấy trực tiếp user_id từ Token
$userId = intval($currentUser['user_id'] ?? 0);
$lastUnreadCount = -1;

while (true) {
    if (connection_aborted() || $userId <= 0) {
        break;
    }

    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_notifications WHERE user_id = :user_id AND is_read = 0");
        $stmt->execute([':user_id' => $userId]);
        $currentUnread = (int)$stmt->fetchColumn();

        if ($currentUnread !== $lastUnreadCount) {
            echo "data: " . json_encode(['unread_count' => $currentUnread]) . "\n\n";
            $lastUnreadCount = $currentUnread;
            flush();
        }
    } catch (PDOException $e) {
        // Bỏ qua lỗi ngắt quãng tạm thời
    }

    sleep(2);
}
exit();