<?php
// Nội dung cho project_payment_get.php
if (!defined('ALLOW_ACCESS')) { http_response_code(403); exit(); }
require_once __DIR__ . '/auth_middleware.php';
require_once __DIR__ . '/../../Model/db_connect.php';

$project_id = isset($_GET['project_id']) ? intval($_GET['project_id']) : 0;
try {
    $stmt = $pdo->prepare("SELECT * FROM tbl_project_payment WHERE project_id = ? ORDER BY payment_date ASC, payment_id ASC");
    $stmt->execute([$project_id]);
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["status" => "success", "data" => $payments], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
exit();