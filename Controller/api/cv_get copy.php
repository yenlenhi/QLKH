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

require_once __DIR__ . '/auth_middleware.php';
require_once __DIR__ . '/../../Model/db_connect.php';

$user_id = isset($currentUser['user_id']) ? intval($currentUser['user_id']) : 0;
if ($user_id <= 0) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Phiên đăng nhập hết hạn."], JSON_UNESCAPED_UNICODE);
    exit();
}

try {
    // Truy vấn thông tin user kết hợp bảng khoa/phòng và lý lịch khoa học
    $sql = "SELECT 
                u.user_id, 
                u.username, 
                u.name, 
                u.avatar,
                o.unit_name,
                o.unit_code,
                c.cv_id,
                c.academic_rank,
                c.academic_degree,
                c.birth_date,
                c.gender,
                c.id_card_number,
                c.phone_number,
                c.email_work,
                c.research_fields,
                c.education_history,
                c.work_history,
                c.published_articles,
                c.projects_participated,
                c.updated_at
            FROM tbl_user u
            LEFT JOIN tbl_org_unit o ON u.unit_id = o.unit_id
            LEFT JOIN tbl_scientific_curriculum_vitae c ON u.user_id = c.user_id
            WHERE u.user_id = ?
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    // Lấy tên trường động từ cấu hình hệ thống
    $stmtSetting = $pdo->query("SELECT university_name FROM tbl_system_setting LIMIT 1");
    $setting = $stmtSetting->fetch(PDO::FETCH_ASSOC);
    $schoolName = $setting['university_name'] ?? 'Học viện An ninh nhân dân';

    if ($data) {
        $data['university_name'] = $schoolName;
    } else {
        $data = [
            'user_id' => $user_id,
            'name' => $currentUser['name'] ?? '',
            'university_name' => $schoolName,
            'unit_name' => '',
            'unit_code' => ''
        ];
    }

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "data" => $data
    ], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Lỗi CSDL: " . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
exit();
