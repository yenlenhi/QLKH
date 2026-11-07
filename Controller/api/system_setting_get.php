<?php
if (!defined('ALLOW_ACCESS')) {
    http_response_code(403);
    die(json_encode(["status" => "error", "message" => "Access Denied"]));
}

ini_set('display_errors', 0);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

require_once __DIR__ . '/../../Model/db_connect.php';

try {
    $stmt = $pdo->prepare("SELECT university_name, university_short_name, system_title, logo_path FROM tbl_system_setting WHERE id = 1 LIMIT 1");
    $stmt->execute();
    $setting = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$setting) {
        $setting = [
            'university_name' => 'Trường Đại học An ninh nhân dân',
            'university_short_name' => 'ANND',
            'system_title' => 'Hệ thống Quản lý nhiệm vụ khoa học công nghệ và đổi mới sáng tạo',
            'logo_path' => 'asset/img/logoT04.png'
        ];
    } else {
        if (empty($setting['system_title'])) {
            $setting['system_title'] = 'Hệ thống Quản lý nhiệm vụ khoa học công nghệ và đổi mới sáng tạo';
        }
    }

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "data" => $setting
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Lỗi truy vấn CSDL: " . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
exit();
?>