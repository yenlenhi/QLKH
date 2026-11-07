<?php
if (!defined('ALLOW_ACCESS')) {
    http_response_code(403);
    die(json_encode(["status" => "error", "message" => "Access Denied"]));
}

ini_set('display_errors', 0);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/auth_middleware.php';
require_once __DIR__ . '/../../Model/db_connect.php';

try {
    // Sắp xếp theo unit_id ASC để hiển thị thứ tự các đơn vị theo quy chuẩn hệ thống
    $sql = "SELECT unit_id, unit_code, unit_name 
            FROM tbl_org_unit 
            ORDER BY unit_id ASC";
    $stmt =$pdo->prepare($sql);$stmt->execute();
    $orgUnits =$stmt->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "data" => $orgUnits
    ], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Lỗi cơ sở dữ liệu: " . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
exit();