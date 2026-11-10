<?php
if (!defined('ALLOW_ACCESS')) {
    define('ALLOW_ACCESS', true);
}

ini_set('display_errors', 0);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

require_once __DIR__ . '/auth_middleware.php';
require_once __DIR__ . '/../../Model/db_connect.php';

try {
    // 1. Lấy trực tiếp user_id từ $currentUser do auth_middleware.php trả về
    $userId = intval($currentUser['user_id'] ?? $currentUser->user_id ?? 0);

    // 2. Lấy danh sách quyền của user từ bảng tbl_role & tbl_permission
    $userPermissions = [];
    if ($userId > 0) {
        $stmtPerm = $pdo->prepare("
            SELECT DISTINCT TRIM(p.permission_code) AS permission_code
            FROM tbl_role r 
            JOIN tbl_permission p ON r.permission_id = p.id 
            WHERE r.user_id = ?
        ");
        $stmtPerm->execute([$userId]);
        $rawPerms = $stmtPerm->fetchAll(PDO::FETCH_COLUMN);

        foreach ($rawPerms as $perm) {
            if (!empty($perm)) {
                $userPermissions[] = strtoupper(trim($perm));
            }
        }
    }

    // 3. Lấy toàn bộ danh sách menu sidebar đang hoạt động từ CSDL
    // BẮT BUỘC PHẢI CÓ CỘT parent_id ĐỂ PHÂN BIỆT MENU CHA VÀ MENU CON
    $stmtMenu = $pdo->prepare("
        SELECT sidebar_id, parent_id, menu_name, menu_link, icon_class, required_permission, sort_order 
        FROM tbl_sidebar 
        WHERE is_active IS TRUE 
        ORDER BY parent_id ASC, sort_order ASC
    ");
    $stmtMenu->execute();
    $allMenus = $stmtMenu->fetchAll(PDO::FETCH_ASSOC);

    // 4. Lọc menu dựa trên quyền hạn thực tế của user
    $filteredMenus = [];
    foreach ($allMenus as $menu) {
        $requiredPerm = strtoupper(trim($menu['required_permission'] ?? ''));

        // Nếu menu không yêu cầu quyền HOẶC user sở hữu quyền đó thì hiển thị
        if (empty($requiredPerm) || in_array($requiredPerm, $userPermissions)) {
            $menu['parent_id'] = intval($menu['parent_id'] ?? 0);
            $menu['sidebar_id'] = intval($menu['sidebar_id']);
            $filteredMenus[] = $menu;
        }
    }

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "data" => $filteredMenus
    ], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
exit();
