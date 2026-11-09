<?php
if (!defined('ALLOW_ACCESS')) {
    http_response_code(403);
    die(json_encode(["status" => "error", "message" => "Access Denied"]));
}
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Phương thức HTTP không được hỗ trợ!"], JSON_UNESCAPED_UNICODE);
    exit();
}

require_once __DIR__ . '/../../Model/db_connect.php'; 
require_once __DIR__ . '/../../Model/library.php'; 
require_once __DIR__ . '/../../Model/JwtHelper.php'; 

$data = json_decode(file_get_contents("php://input"), true);
$username = isset($data['username']) ? trim($data['username']) : null;
$password = isset($data['password']) ? trim($data['password']) : null;

if (empty($username) || empty($password)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Vui lòng nhập đầy đủ Tên đăng nhập và Mật khẩu!"], JSON_UNESCAPED_UNICODE);
    exit();
}

try {
    // PostgreSQL string comparison is case-sensitive by default.
    $stmt = $pdo->prepare("SELECT user_id, username, password, status, can_grant_permission FROM tbl_user WHERE username = :username LIMIT 1");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user) {
        $isPasswordValid = password_verify($password, $user['password']) || ($password === $user['password']);

        if ($isPasswordValid) {
            if (isset($user['status']) && (int)$user['status'] === 0) {
                http_response_code(403);
                echo json_encode(["status" => "error", "message" => "Tài khoản của bạn hiện đang bị khóa!"], JSON_UNESCAPED_UNICODE);
                exit();
            }

            $response = ['status' => 'error', 'message' => ''];
            checkTypeAccount($user['username'], $response, 'dashboard', 'trang-chu');

            if ($response['status'] === 'success') {
                // 1. SINH MÃ PHIÊN ĐĂNG NHẬP DUY NHẤT (SINGLE DEVICE LOGIN)
                $session_token = bin2hex(random_bytes(32));

                // 2. CẬP NHẬT MÃ PHIÊN MỚI VÀO CSDL (PHIÊN ĐĂNG NHẬP CŨ Ở NƠI KHÁC SẼ BỊ VÔ HIỆU HÓA)
                $stmtUpdateToken = $pdo->prepare("UPDATE tbl_user SET current_token = :st WHERE user_id = :uid");
                $stmtUpdateToken->execute([
                    ':st' => $session_token,
                    ':uid' => $user['user_id']
                ]);

                // 3. TẠO JWT TOKEN (GIỮ NGUYÊN CƠ CHẾ VÀ BỔ SUNG session_token)
                $jwtPayload = [
                    "user_id" => $user['user_id'],
                    "username" => $user['username'],
                    "can_grant_permission" => isset($user['can_grant_permission']) ? (int)$user['can_grant_permission'] : 0,
                    "session_token" => $session_token
                ];
                $jwtToken = JwtHelper::generate_jwt($jwtPayload, 86400); // 24 giờ

                http_response_code(200);
                echo json_encode([
                    "status" => "success",
                    "message" => $response['message'],
                    "token" => $jwtToken,
                    "data" => [
                        "user_id" => $user['user_id'],
                        "username" => $user['username'],
                        "can_grant_permission" => (int)$user['can_grant_permission'],
                        "redirect" => $response['redirect']
                    ]
                ], JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(400);
                echo json_encode(["status" => "error", "message" => $response['message']], JSON_UNESCAPED_UNICODE);
            }
            exit();
        }
    }

    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Tên đăng nhập hoặc mật khẩu không chính xác!"], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Lỗi CSDL: " . $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
?>
