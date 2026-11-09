<?php

if (!defined('ALLOW_ACCESS')) {
    http_response_code(403);
    exit;
}

require_once __DIR__ . '/auth_middleware.php';
require_once __DIR__ . '/../../Model/Ably.php';

$userId = (int) ($currentUser['user_id'] ?? $currentUser['id'] ?? 0);
if ($userId <= 0) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Phiên đăng nhập không hợp lệ.'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    echo json_encode(issueAblyTokenRequest($userId), JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    error_log('Ably token request failed: ' . $e->getMessage());
    http_response_code(503);
    echo json_encode(['status' => 'error', 'message' => 'Không thể kết nối dịch vụ thông báo.'], JSON_UNESCAPED_UNICODE);
}
