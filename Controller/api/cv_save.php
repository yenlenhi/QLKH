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

require_once __DIR__ . '/auth_middleware.php';
require_once __DIR__ . '/../../Model/db_connect.php';

$user_id = 0;
if (isset($currentUser['user_id'])) {
    $user_id = intval($currentUser['user_id']);
} elseif (isset($currentUser['id'])) {
    $user_id = intval($currentUser['id']);
}

if ($user_id <= 0) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Phiên đăng nhập hết hạn."], JSON_UNESCAPED_UNICODE);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input) || empty($input)) {
    $input = $_POST;
}

// 1. Nhận các trường dữ liệu
$reg_role = trim($input['reg_role'] ?? 'chu_nhiem');
$full_name = trim($input['full_name'] ?? '');
$id_card_number = trim($input['id_card_number'] ?? '');
$birth_date = !empty($input['birth_date']) ? trim($input['birth_date']) : null;
$gender = trim($input['gender'] ?? 'Nam');

$academic_rank = trim($input['academic_rank'] ?? '');
$academic_rank_year = !empty($input['academic_rank_year']) ? trim($input['academic_rank_year']) : null;
$academic_degree = trim($input['academic_degree'] ?? '');
$academic_degree_year = !empty($input['academic_degree_year']) ? trim($input['academic_degree_year']) : null;

$job_title = trim($input['job_title'] ?? '');
$position = trim($input['position'] ?? '');
$talent_category = intval($input['talent_category'] ?? 3);

$home_address = trim($input['home_address'] ?? '');
$phone_number = trim($input['phone_number'] ?? '');
$email_work = trim($input['email_work'] ?? '');

$work_address = trim($input['work_address'] ?? '');
$org_head_name = trim($input['org_head_name'] ?? '');
$org_head_phone = trim($input['org_head_phone'] ?? '');

$research_fields = trim($input['research_fields'] ?? '');
$other_achievements = trim($input['other_achievements'] ?? '');

// Các mảng dữ liệu JSON
$education_history = is_array($input['education_history'] ?? null) ? json_encode($input['education_history'], JSON_UNESCAPED_UNICODE) : ($input['education_history'] ?? '[]');
$work_history = is_array($input['work_history'] ?? null) ? json_encode($input['work_history'], JSON_UNESCAPED_UNICODE) : ($input['work_history'] ?? '[]');
$published_articles = is_array($input['published_articles'] ?? null) ? json_encode($input['published_articles'], JSON_UNESCAPED_UNICODE) : ($input['published_articles'] ?? '[]');
$patents = is_array($input['patents'] ?? null) ? json_encode($input['patents'], JSON_UNESCAPED_UNICODE) : ($input['patents'] ?? '[]');
$applied_works = is_array($input['applied_works'] ?? null) ? json_encode($input['applied_works'], JSON_UNESCAPED_UNICODE) : ($input['applied_works'] ?? '[]');
$projects_participated = is_array($input['projects_participated'] ?? null) ? json_encode($input['projects_participated'], JSON_UNESCAPED_UNICODE) : ($input['projects_participated'] ?? '[]');
$awards = is_array($input['awards'] ?? null) ? json_encode($input['awards'], JSON_UNESCAPED_UNICODE) : ($input['awards'] ?? '[]');

// 2. Validate dữ liệu
if (empty($full_name)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Vui lòng nhập họ và tên."], JSON_UNESCAPED_UNICODE);
    exit();
}

if (!$birth_date) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Vui lòng chọn ngày tháng năm sinh."], JSON_UNESCAPED_UNICODE);
    exit();
} else {
    $dob = DateTime::createFromFormat('Y-m-d', $birth_date);
    $today = new DateTime();
    if (!$dob) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Định dạng ngày sinh không hợp lệ."], JSON_UNESCAPED_UNICODE);
        exit();
    }
    $age = $today->diff($dob)->y;
    if ($dob > $today || $age < 18) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Người khai lý lịch phải từ đủ 18 tuổi trở lên."], JSON_UNESCAPED_UNICODE);
        exit();
    }
}

if (!preg_match('/^(?:\d{9}|\d{12})$/', $id_card_number)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Số CCCD/CMND không hợp lệ (Bắt buộc 12 số CCCD hoặc 9 số CMND cũ)."], JSON_UNESCAPED_UNICODE);
    exit();
}

if (!preg_match('/^(0|\+84)(3|5|7|8|9)[0-9]{8}$/', $phone_number)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Số điện thoại không hợp lệ (Phải là 10 chữ số chuẩn VN)."], JSON_UNESCAPED_UNICODE);
    exit();
}

if (!filter_var($email_work, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Định dạng email không hợp lệ."], JSON_UNESCAPED_UNICODE);
    exit();
}

try {
    $stmtCheck = $pdo->prepare("SELECT cv_id FROM tbl_scientific_curriculum_vitae WHERE user_id = ?");
    $stmtCheck->execute([$user_id]);
    $exist = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if ($exist) {
        $sql = "UPDATE tbl_scientific_curriculum_vitae SET 
                    reg_role = ?,
                    academic_rank = ?, 
                    academic_rank_year = ?,
                    academic_degree = ?, 
                    academic_degree_year = ?,
                    birth_date = ?, 
                    gender = ?, 
                    job_title = ?,
                    position = ?,
                    talent_category = ?,
                    id_card_number = ?, 
                    home_address = ?,
                    phone_number = ?, 
                    email_work = ?, 
                    work_address = ?,
                    org_head_name = ?,
                    org_head_phone = ?,
                    research_fields = ?, 
                    education_history = ?, 
                    work_history = ?, 
                    published_articles = ?, 
                    patents = ?,
                    applied_works = ?,
                    projects_participated = ?, 
                    awards = ?,
                    other_achievements = ?,
                    updated_at = NOW()
                WHERE user_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $reg_role,
            $academic_rank,
            $academic_rank_year,
            $academic_degree,
            $academic_degree_year,
            $birth_date,
            $gender,
            $job_title,
            $position,
            $talent_category,
            $id_card_number,
            $home_address,
            $phone_number,
            $email_work,
            $work_address,
            $org_head_name,
            $org_head_phone,
            $research_fields,
            $education_history,
            $work_history,
            $published_articles,
            $patents,
            $applied_works,
            $projects_participated,
            $awards,
            $other_achievements,
            $user_id
        ]);
        $cv_id = $exist['cv_id'];
    } else {
        $sql = "INSERT INTO tbl_scientific_curriculum_vitae (
                    user_id, reg_role, academic_rank, academic_rank_year,
                    academic_degree, academic_degree_year, birth_date, gender,
                    job_title, position, talent_category, id_card_number,
                    home_address, phone_number, email_work, work_address,
                    org_head_name, org_head_phone, research_fields,
                    education_history, work_history, published_articles,
                    patents, applied_works, projects_participated, awards,
                    other_achievements
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $user_id, $reg_role, $academic_rank, $academic_rank_year,
            $academic_degree, $academic_degree_year, $birth_date, $gender,
            $job_title, $position, $talent_category, $id_card_number,
            $home_address, $phone_number, $email_work, $work_address,
            $org_head_name, $org_head_phone, $research_fields,
            $education_history, $work_history, $published_articles,
            $patents, $applied_works, $projects_participated, $awards,
            $other_achievements
        ]);
        $cv_id = $pdo->lastInsertId();
    }

    // Đồng bộ lại họ tên vào tbl_user
    $stmtUser = $pdo->prepare("UPDATE tbl_user SET name = ? WHERE user_id = ?");
    $stmtUser->execute([$full_name, $user_id]);

    http_response_code(200);
    echo json_encode([
        "status" => "success", 
        "message" => "Cập nhật lý lịch khoa học (BM-04) thành công!", 
        "cv_id" => $cv_id
    ], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Lỗi CSDL: " . $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
exit();