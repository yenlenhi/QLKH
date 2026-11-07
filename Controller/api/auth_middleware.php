<?php
if (!defined('ALLOW_ACCESS')) {
    http_response_code(403);
    die(json_encode(["status" => "error", "message" => "Access Denied"]));
}
// Cấu hình các Header CORS cơ bản
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Xử lý Request OPTIONS (Preflight request của trình duyệt)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../../Model/JwtHelper.php';
require_once __DIR__ . '/../../Model/db_connect.php';

/**
 * Hàm xác thực người dùng qua JWT và kiểm tra phiên đơn duy nhất
 * @return array Trả về thông tin user trong payload của Token nếu hợp lệ
 */
function authenticateJWT() {
    global $pdo;
    $authHeader = null;

    // 1. Trích xuất Authorization Header
    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
    } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) { // Dành cho Apache / FastCGI
        $authHeader = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
    } elseif (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
        if (isset($requestHeaders['Authorization'])) {
            $authHeader = $requestHeaders['Authorization'];
        }
    }

    // 2. Kiểm tra xem Header có định dạng "Bearer <token>" không
    $token = null;
    if ($authHeader && preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        $token = $matches[1];
    } elseif (isset($_GET['token']) && !empty($_GET['token'])) {
        $token = $_GET['token'];
    } elseif (isset($_POST['token']) && !empty($_POST['token'])) {
        $token = $_POST['token'];
    }

    if (!$token) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "message" => "Truy cập bị từ chối! Không tìm thấy Token xác thực."
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // 3. Giải mã và kiểm tra chữ ký/thời hạn của JWT
    $userData = JwtHelper::validate_jwt($token);

    if (!$userData) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "message" => "Xác thực thất bại! Token không hợp lệ hoặc đã hết hạn."
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // 4. KIỂM TRA PHIÊN DUY NHẤT (SINGLE DEVICE LOGIN)
    $userId = isset($userData['user_id']) ? intval($userData['user_id']) : (isset($userData['id']) ? intval($userData['id']) : 0);
    $tokenSession = isset($userData['session_token']) ? $userData['session_token'] : null;

    if ($userId > 0 && $tokenSession !== null) {
        try {
            $stmt = $pdo->prepare("SELECT current_token, status FROM tbl_user WHERE user_id = :uid LIMIT 1");
            $stmt->execute([':uid' => $userId]);
            $dbUser = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$dbUser || (isset($dbUser['status']) && (int)$dbUser['status'] === 0)) {
                http_response_code(401);
                echo json_encode([
                    "status" => "error",
                    "code" => "ACCOUNT_LOCKED",
                    "message" => "Tài khoản hiện đang bị khóa hoặc không tồn tại."
                ], JSON_UNESCAPED_UNICODE);
                exit();
            }

            // Nếu mã phiên trong JWT không trùng với mã phiên hiện tại trong CSDL
            if (!empty($dbUser['current_token']) && $dbUser['current_token'] !== $tokenSession) {
                http_response_code(401);
                echo json_encode([
                    "status" => "error",
                    "code" => "SESSION_TERMINATED",
                    "message" => "Tài khoản của bạn đã được đăng nhập từ một thiết bị hoặc trình duyệt khác. Phiên làm việc này đã kết thúc!"
                ], JSON_UNESCAPED_UNICODE);
                exit();
            }
        } catch (PDOException $e) {
            // Lỗi CSDL bỏ qua hoặc log lại
        }
    }

    // Trả về dữ liệu người dùng để các API phía sau sử dụng
    return $userData;
}

// Tự động gọi hàm xác thực ngay khi file này được require/include
$currentUser = authenticateJWT();
?>