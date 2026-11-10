<?php
if (!defined('ALLOW_ACCESS')) {
    http_response_code(403);
    die(json_encode(["status" => "error", "message" => "Access Denied"]));
}
// Cấu hình Header chuẩn RESTful API
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
// Nhúng middleware xác thực JWT (Sẽ tự động kiểm tra Token và exit nếu lỗi)
require_once __DIR__ . '/auth_middleware.php';

// Chỉ chấp nhận phương thức POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "message" => "Phương thức HTTP không được hỗ trợ!"
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

// -------------------------------------------------------------------
// 1. NHÚNG FILE KẾT NỐI CSDL
// -------------------------------------------------------------------
require_once __DIR__ . '/../../Model/db_connect.php';

// -------------------------------------------------------------------
// 2. NHẬN & KIỂM TRA DỮ LIỆU JSON ĐẦU VÀO
// -------------------------------------------------------------------
$data = json_decode(file_get_contents("php://input"), true);

$userType = isset($data['user_type']) ? trim($data['user_type']) : null; // 'LD', 'CB', 'GV'
$rawUsername = isset($data['username']) ? trim($data['username']) : null;
$password = isset($data['password']) ? trim($data['password']) : null;
// BỔ SUNG: Nhận unit_id (Khoa / Phòng)
$unitId = isset($data['unit_id']) && intval($data['unit_id']) > 0 ? intval($data['unit_id']) : null;

if (empty($userType) || empty($rawUsername) || empty($password)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Vui lòng nhập đầy đủ Loại người dùng, Mã định danh và Mật khẩu!"
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

// BỔ SUNG: Ràng buộc phải chọn Khoa/Phòng
if (empty($unitId)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Vui lòng chọn Khoa / Phòng ban cho tài khoản!"
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

// Kiểm tra loại người dùng hợp lệ
$allowedTypes = ['LD', 'CB', 'GV'];
if (!in_array($userType, $allowedTypes)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Loại người dùng không hợp lệ!"
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

// Kiểm tra mã định danh không chứa ký tự đặc biệt (chỉ chấp nhận a-z, A-Z, 0-9)
if (!preg_match('/^[a-zA-Z0-9]+$/', $rawUsername)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Mã định danh không được chứa ký tự đặc biệt hoặc khoảng trắng!"
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

// -------------------------------------------------------------------
// 3. XỬ LÝ TIỀN TỐ & XÁC ĐỊNH QUYỀN PHÂN QUYỀN
// -------------------------------------------------------------------
// Loại bỏ tiền tố cũ nếu người dùng cố tình nhập lại ở phần mã
$cleanUsername = preg_replace('/^(LD|CB|GV)/i', '', $rawUsername);

// Ghép tiền tố tương ứng vào username
$finalUsername = $userType . $cleanUsername;

// Xác định trường can_grant_permission: LD và CB = 1, GV = 0
$canGrantPermission = ($userType === 'LD' || $userType === 'CB') ? 1 : 0;

// Gán type_user theo đúng loại người dùng tạo
$typeUser = $userType; // 'LD', 'CB', 'GV'

// -------------------------------------------------------------------
// 4. KIỂM TRA TRÙNG LẶP VÀ THÊM NGƯỜI DÙNG MỚI
// -------------------------------------------------------------------
try {
    $checkStmt = $pdo->prepare("SELECT user_id FROM tbl_user WHERE username = :username LIMIT 1");
    $checkStmt->execute(['username' => $finalUsername]);

    if ($checkStmt->fetch()) {
        http_response_code(409); // Conflict
        echo json_encode([
            "status" => "error",
            "message" => "Tên đăng nhập '{$finalUsername}' đã tồn tại trong hệ thống!"
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Mã hóa mật khẩu bảo mật
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Thêm bản ghi mới vào tbl_user (ĐÃ BỔ SUNG CỘT type_user)
    $insertStmt = $pdo->prepare("
        INSERT INTO tbl_user (username, password, type_user, unit_id, can_grant_permission, status) 
        VALUES (:username, :password, :type_user, :unit_id, :can_grant_permission, TRUE)
        RETURNING user_id
    ");

    $insertStmt->execute([
        'username' => $finalUsername,
        'password' => $hashedPassword,
        'type_user' => $typeUser,
        'unit_id' => $unitId,
        'can_grant_permission' => (bool) $canGrantPermission
    ]);

    $newUserId = (int) $insertStmt->fetchColumn();

    // --- BỔ SUNG GHI LOG ---
    require_once __DIR__ . '/../../Model/function.php';
    $current_user_id = $currentUser['user_id'] ?? 0;
    writeActivityLog($pdo, $current_user_id, 'CREATE', 'Quản lý Tài khoản', intval($newUserId), "Đã tạo mới tài khoản hệ thống: $finalUsername (Loại: $typeUser, Đơn vị ID: $unitId)");
    // -----------------------
    http_response_code(201); // Created
    echo json_encode([
        "status" => "success",
        "message" => "Tạo tài khoản thành công!",
        "data" => [
            "user_id" => $newUserId,
            "username" => $finalUsername,
            "type_user" => $typeUser,
            "unit_id" => $unitId,
            "can_grant_permission" => $canGrantPermission
        ]
    ], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Lỗi cơ sở dữ liệu: " . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
