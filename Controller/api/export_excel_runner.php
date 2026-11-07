<?php
// Tắt buffer thừa để stream file chuẩn
if (ob_get_level()) ob_end_clean();

require_once __DIR__ . '/auth_middleware.php';
require_once __DIR__ . '/../Model/db_connect.php';

try {
    // 1. Lấy dữ liệu tổng quan và danh sách nhiệm vụ từ PDO sẵn có
    $stmtOverview = $pdo->query("
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN status = 'running' THEN 1 ELSE 0 END) as running,
            SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
            SUM(CASE WHEN status = 'awaiting_payment' THEN 1 ELSE 0 END) as awaiting_payment,
            SUM(estimated_budget) as total_budget
        FROM tbl_projects
    ");
    $overview = $stmtOverview->fetch(PDO::FETCH_ASSOC);

    $stmtProjects = $pdo->query("
        SELECT 
            p.project_id, p.project_code, p.project_name, 
            s.system_name, p.status, p.estimated_budget, p.approved_budget,
            u.name as leader_name, u.unit_name as leader_unit
        FROM tbl_projects p
        LEFT JOIN tbl_sub_system s ON p.sub_system_id = s.sub_system_id
        LEFT JOIN tbl_users u ON p.project_user_action_id = u.user_id
        ORDER BY p.project_id DESC
    ");
    $projects = $stmtProjects->fetchAll(PDO::FETCH_ASSOC);

    $stmtSub = $pdo->query("SELECT sub_system_id as id, system_name, system_code FROM tbl_sub_system");
    $subSystems = [];
    $letters = range('A', 'Z');
    $idx = 0;
    while ($row = $stmtSub->fetch(PDO::FETCH_ASSOC)) {
        $row['key'] = $letters[$idx++] ?? 'X';
        $subSystems[] = $row;
    }

} catch (PDOException $e) {
    http_response_code(500);
    die(json_encode(['status' => 'error', 'message' => 'Lỗi CSDL: ' . $e->getMessage()]));
}

// 2. Gom dữ liệu sang file JSON tạm
$exportData = [
    'unit_name' => 'Toàn trường',
    'overview' => $overview,
    'sub_systems' => $subSystems,
    'projects' => $projects
];

$tempDir = __DIR__ . '/../asset/file/reports';
if (!is_dir($tempDir)) {
    mkdir($tempDir, 0777, true);
}

$tempJson = $tempDir . '/data_' . uniqid() . '.json';
$outputExcel = $tempDir . '/Bao_Cao_NCKH_' . date('Ymd_His') . '.xlsx';
file_put_contents($tempJson, json_encode($exportData, JSON_UNESCAPED_UNICODE));

// 3. Tìm đường dẫn Python trên máy
$pythonCmd = 'python';
// Kiểm tra nếu trên Windows XAMPP chưa nhận PATH python thì thử py
exec("python --version 2>&1", $testPy, $codePy);
if ($codePy !== 0) {
    exec("py --version 2>&1", $testPy2, $codePy2);
    if ($codePy2 === 0) {
        $pythonCmd = 'py';
    }
}

$pythonScript = __DIR__ . '/export_excel.py';
$command = "$pythonCmd " . escapeshellarg($pythonScript) . " " . escapeshellarg($tempJson) . " " . escapeshellarg($outputExcel) . " 2>&1";

exec($command, $output, $returnVar);

// Xóa file JSON tạm
if (file_exists($tempJson)) {
    unlink($tempJson);
}

// 4. Kiểm tra và trả file Excel về cho trình duyệt
if ($returnVar === 0 && file_exists($outputExcel)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . basename($outputExcel) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($outputExcel));
    readfile($outputExcel);
    unlink($outputExcel); // Xóa file sau khi tải xong
    exit;
} else {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'status' => 'error',
        'message' => 'Lỗi thực thi Python: ' . implode("\n", $output)
    ], JSON_UNESCAPED_UNICODE);
}
?>