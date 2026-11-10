<?php
if (!defined('ALLOW_ACCESS')) {
    http_response_code(403);
    die(json_encode(["status" => "error", "message" => "Access Denied"]));
}
// Tắt hiển thị lỗi HTML trực tiếp để tránh làm hỏng cấu trúc JSON
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Cấu hình Header chuẩn RESTful API
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

try {
    // Biến $pdo đã được khởi tạo sẵn từ index.php
    if (!isset($pdo)) {
        throw new Exception("Lỗi hệ thống: Kết nối cơ sở dữ liệu chưa sẵn sàng.");
    }

    // =========================================================================
    // NHÁNH 1: NẾU GỌI CHO 1 USER CỤ THỂ (Dùng cho autofill r3_project_register)
    // =========================================================================
    $targetUserId = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

    // Nếu gọi detect=get_user hoặc có truyền user_id
    if ($targetUserId > 0) {
        $sqlSingle = "SELECT 
                        u.user_id, 
                        u.username, 
                        u.name, 
                        u.type_user,
                        u.unit_id,
                        o.unit_name,
                        o.unit_code,
                        CASE WHEN u.can_grant_permission IS TRUE THEN 1 ELSE 0 END AS can_grant_permission,
                        CASE WHEN u.status IS TRUE THEN 1 ELSE 0 END AS status
                    FROM tbl_user u 
                    LEFT JOIN tbl_org_unit o ON u.unit_id = o.unit_id
                    WHERE u.user_id = ?
                    LIMIT 1";
        $stmtSingle = $pdo->prepare($sqlSingle);
        $stmtSingle->execute([$targetUserId]);
        $user = $stmtSingle->fetch(PDO::FETCH_ASSOC);

        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "data" => $user ?: []
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // =========================================================================
    // NHÁNH 2: LOGIC GỐC TRẢ VỀ TOÀN BỘ DANH SÁCH USER (GIỮ NGUYÊN 100%)
    // =========================================================================
    $sql = "SELECT 
                u.user_id, 
                u.username, 
                u.name, 
                u.type_user,
                u.unit_id,
                o.unit_name,
                o.unit_code,
                CASE WHEN u.can_grant_permission IS TRUE THEN 1 ELSE 0 END AS can_grant_permission,
                CASE WHEN u.status IS TRUE THEN 1 ELSE 0 END AS status,
                (SELECT COUNT(*) FROM tbl_role r WHERE r.user_id = u.user_id) AS permission_count
            FROM tbl_user u 
            LEFT JOIN tbl_org_unit o ON u.unit_id = o.unit_id
            ORDER BY u.user_id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "data" => $users
    ], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Lỗi truy vấn CSDL: " . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
exit();
