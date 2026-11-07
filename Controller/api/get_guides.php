<?php
if (!defined('ALLOW_ACCESS')) {
    http_response_code(403);
    die(json_encode(["status" => "error", "message" => "Access Denied"]));
}
header('Content-Type: application/json; charset=utf-8');

// Nhúng middleware xác thực JWT
require_once __DIR__ . '/auth_middleware.php';
require_once __DIR__ . '/../../Model/db_connect.php';

try {
    // Trường hợp 1: Nếu client truyền id trên URL (?id=...) -> Lấy chi tiết 1 bài viết
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = intval($_GET['id']);
        
        $stmt = $pdo->prepare("SELECT * FROM tbl_guides WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $guide = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($guide) {
            // Định dạng ngày đăng
            $rawDate = $guide['created_at'] ?? $guide['published_at'] ?? $guide['ngay_dang'] ?? null;
            if (!empty($rawDate)) {
                $guide['created_at_formatted'] = date('d/m/Y H:i', strtotime($rawDate));
                $guide['created_date'] = date('d/m/Y', strtotime($rawDate));
            } else {
                $guide['created_at'] = date('Y-m-d H:i:s');
                $guide['created_at_formatted'] = date('d/m/Y H:i');
                $guide['created_date'] = date('d/m/Y');
            }

            // Giải mã file đính kèm
            $files = !empty($guide['file_guides']) ? json_decode($guide['file_guides'], true) : [];
            $guide['files_count'] = is_array($files) ? count($files) : 0;
            $guide['file_guides'] = $files;

            echo json_encode([
                "status" => "success",
                "data" => $guide
            ], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(404);
            echo json_encode([
                "status" => "error",
                "message" => "Không tìm thấy bài hướng dẫn có ID: " . $id
            ], JSON_UNESCAPED_UNICODE);
        }
    } 
    // Trường hợp 2: Không truyền id -> Lấy danh sách toàn bộ bài viết
    else {
        $stmt = $pdo->query("SELECT * FROM tbl_guides ORDER BY id DESC");
        $guides = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($guides as &$guide) {
            // Định dạng ngày đăng cho danh sách
            $rawDate = $guide['created_at'] ?? $guide['published_at'] ?? $guide['ngay_dang'] ?? null;
            if (!empty($rawDate)) {
                $guide['created_at_formatted'] = date('d/m/Y H:i', strtotime($rawDate));
                $guide['created_date'] = date('d/m/Y', strtotime($rawDate));
            } else {
                $guide['created_at'] = date('Y-m-d H:i:s');
                $guide['created_at_formatted'] = date('d/m/Y H:i');
                $guide['created_date'] = date('d/m/Y');
            }

            $files = !empty($guide['file_guides']) ? json_decode($guide['file_guides'], true) : [];
            $guide['files_count'] = is_array($files) ? count($files) : 0;
            $guide['file_guides'] = $files;
        }

        echo json_encode(['status' => 'success', 'data' => $guides], JSON_UNESCAPED_UNICODE);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Lỗi truy vấn CSDL: " . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>