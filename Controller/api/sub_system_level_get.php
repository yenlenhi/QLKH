<?php
if (!defined('ALLOW_ACCESS')) {
    http_response_code(403);
    die(json_encode(["status" => "error", "message" => "Access Denied"]));
}

// Bật debug hiển thị lỗi nếu có sự cố
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Đảm bảo có biến kết nối PDO
if (!isset($pdo)) {
    require_once __DIR__ . '/../../Model/db_connect.php';
}

try {
    // Truy vấn trực tiếp bảng trung gian tbl_sub_system_level
    $sql = "SELECT sub_system_id, level_id, is_default 
            FROM tbl_sub_system_level 
            ORDER BY sub_system_id ASC, is_default DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Xây dựng mảng mapping { sub_system_id: level_id }
    $mapping = [];
    foreach ($rows as $r) {
        $subId = (string)$r['sub_system_id'];
        $lvlId = (string)$r['level_id'];

        if (!isset($mapping[$subId]) || intval($r['is_default']) === 1) {
            $mapping[$subId] = $lvlId;
        }
    }

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "data" => [
            "mapping" => $mapping,
            "list"    => $rows
        ]
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error", 
        "message" => "Lỗi CSDL: " . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error", 
        "message" => "Lỗi hệ thống: " . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
exit();