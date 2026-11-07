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

// Xác định user_id: Hỗ trợ cả Token ($currentUser) lẫn param truyền lên (nếu Admin xem)
$user_id = 0;
if (isset($currentUser['user_id'])) {
    $user_id = intval($currentUser['user_id']);
} elseif (isset($currentUser['id'])) {
    $user_id = intval($currentUser['id']);
}

$is_admin = isset($currentUser['can_grant_permission']) ? intval($currentUser['can_grant_permission']) : 0;
if (isset($_REQUEST['user_id']) && intval($_REQUEST['user_id']) > 0 && $is_admin === 1) {
    $user_id = intval($_REQUEST['user_id']);
}

if ($user_id <= 0) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Phiên đăng nhập không hợp lệ hoặc đã hết hạn."], JSON_UNESCAPED_UNICODE);
    exit();
}

try {
    // Truy vấn đầy đủ các trường của tbl_user, tbl_org_unit và tbl_scientific_curriculum_vitae
    $sql = "SELECT 
                u.user_id, 
                u.username, 
                u.name, 
                u.avatar,
                o.unit_name,
                o.unit_code,
                c.cv_id,
                c.reg_role,
                c.academic_rank,
                c.academic_rank_year,
                c.academic_degree,
                c.academic_degree_year,
                c.birth_date,
                c.gender,
                c.job_title,
                c.position,
                c.talent_category,
                c.id_card_number,
                c.home_address,
                c.phone_number,
                c.email_work,
                c.work_address,
                c.org_head_name,
                c.org_head_phone,
                c.research_fields,
                c.education_history,
                c.work_history,
                c.published_articles,
                c.patents,
                c.applied_works,
                c.projects_participated,
                c.awards,
                c.other_achievements,
                c.cv_file_path,
                c.updated_at
            FROM tbl_user u
            LEFT JOIN tbl_org_unit o ON u.unit_id = o.unit_id
            LEFT JOIN tbl_scientific_curriculum_vitae c ON u.user_id = c.user_id
            WHERE u.user_id = ?
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    // Lấy tên trường động từ bảng tbl_system_setting
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
            'unit_code' => '',
            'reg_role' => 'chu_nhiem',
            'talent_category' => 3
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