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

$user_id = isset($currentUser['user_id']) ? intval($currentUser['user_id']) : 0;
if ($user_id <= 0) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Phiên đăng nhập hết hạn."], JSON_UNESCAPED_UNICODE);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

$full_name = trim($input['full_name'] ?? '');
$academic_rank = trim($input['academic_rank'] ?? '');
$academic_degree = trim($input['academic_degree'] ?? '');
$birth_date = !empty($input['birth_date']) ? trim($input['birth_date']) : null;
$gender = trim($input['gender'] ?? 'Nam');
$id_card_number = trim($input['id_card_number'] ?? '');
$phone_number = trim($input['phone_number'] ?? '');
$email_work = trim($input['email_work'] ?? '');
$research_fields = trim($input['research_fields'] ?? '');
$education_history = $input['education_history'] ?? '[]';
$work_history = $input['work_history'] ?? '[]';
$published_articles = $input['published_articles'] ?? '[]';
$projects_participated = $input['projects_participated'] ?? '[]';

// 1. Kiểm tra Họ và tên
if (empty($full_name)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Vui lòng nhập họ và tên."], JSON_UNESCAPED_UNICODE);
    exit();
}

// 2. Kiểm tra độ tuổi (Phải từ đủ 18 tuổi trở lên)
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

// 3. Kiểm tra Số CCCD / CMND (Chuẩn 9 hoặc 12 số)
if (!preg_match('/^(?:\d{9}|\d{12})$/', $id_card_number)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Số CCCD/CMND không hợp lệ (Bắt buộc 12 số đối với CCCD hoặc 9 số đối với CMND cũ)."], JSON_UNESCAPED_UNICODE);
    exit();
}

// 4. Kiểm tra Số điện thoại (Đầu số Việt Nam: 10 chữ số)
if (!preg_match('/^(0|\+84)(3|5|7|8|9)[0-9]{8}$/', $phone_number)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Số điện thoại không hợp lệ (Phải là số điện thoại 10 số của Việt Nam, ví dụ: 0912345678)."], JSON_UNESCAPED_UNICODE);
    exit();
}

// 5. Kiểm tra Email
if (!filter_var($email_work, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Định dạng email công vụ không hợp lệ."], JSON_UNESCAPED_UNICODE);
    exit();
}

try {
    $stmtCheck = $pdo->prepare("SELECT cv_id FROM tbl_scientific_curriculum_vitae WHERE user_id = ?");
    $stmtCheck->execute([$user_id]);
    $exist = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if ($exist) {
        $sql = "UPDATE tbl_scientific_curriculum_vitae SET 
                    academic_rank = ?, academic_degree = ?, birth_date = ?, 
                    gender = ?, id_card_number = ?, phone_number = ?, email_work = ?, 
                    research_fields = ?, education_history = ?, work_history = ?, 
                    published_articles = ?, projects_participated = ?, updated_at = NOW()
                WHERE user_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $academic_rank, $academic_degree, $birth_date,
            $gender, $id_card_number, $phone_number, $email_work,
            $research_fields, $education_history, $work_history,
            $published_articles, $projects_participated,
            $user_id
        ]);
        $cv_id = $exist['cv_id'];
    } else {
        $sql = "INSERT INTO tbl_scientific_curriculum_vitae (
                    user_id, academic_rank, academic_degree, birth_date, 
                    gender, id_card_number, phone_number, email_work, 
                    research_fields, education_history, work_history, 
                    published_articles, projects_participated
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $user_id, $academic_rank, $academic_degree, $birth_date,
            $gender, $id_card_number, $phone_number, $email_work,
            $research_fields, $education_history, $work_history,
            $published_articles, $projects_participated
        ]);
        $cv_id = $pdo->lastInsertId();
    }

    $stmtUser = $pdo->prepare("UPDATE tbl_user SET name = ? WHERE user_id = ?");
    $stmtUser->execute([$full_name, $user_id]);

    http_response_code(200);
    echo json_encode([
        "status" => "success", 
        "message" => "Cập nhật lý lịch khoa học thành công!", 
        "cv_id" => $cv_id
    ], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Lỗi CSDL: " . $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
exit();