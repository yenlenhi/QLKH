<?php
if (!defined('ALLOW_ACCESS')) {
    define('ALLOW_ACCESS', true);
}

ini_set('display_errors', 0);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

require_once __DIR__ . '/auth_middleware.php';
require_once __DIR__ . '/../../Model/db_connect.php';

// // 1. Kiểm tra quyền CONFIG_SYSTEM của user hiện tại
// $userId = intval($currentUser['user_id'] ?? $currentUser->user_id ?? 0);
// $stmtCheck = $pdo->prepare("
//     SELECT COUNT(*) 
//     FROM tbl_role r 
//     JOIN tbl_permission p ON r.permission_id = p.id 
//     WHERE r.user_id = ? AND UPPER(TRIM(p.permission_code)) = 'CONFIG_SYSTEM'
// ");
// $stmtCheck->execute([$userId]);
// $hasConfigPermission = $stmtCheck->fetchColumn() > 0;

// if (!$hasConfigPermission) {
//     http_response_code(403);
//     echo json_encode(["status" => "error", "message" => "Bạn không có quyền CONFIG_SYSTEM để quản trị danh mục."], JSON_UNESCAPED_UNICODE);
//     exit();
// }
// 1. Lấy thông tin user hiện tại và toàn bộ quyền của họ
$userId = intval($currentUser['user_id'] ?? $currentUser->user_id ?? 0);
$stmtUserPerms = $pdo->prepare("
    SELECT UPPER(TRIM(p.permission_code)) 
    FROM tbl_role r 
    JOIN tbl_permission p ON r.permission_id = p.id 
    WHERE r.user_id = ?
");
$stmtUserPerms->execute([$userId]);
$myPermissions = $stmtUserPerms->fetchAll(PDO::FETCH_COLUMN);

// Kiểm tra quyền chung CONFIG_SYSTEM
if (!in_array('CONFIG_SYSTEM', $myPermissions)) {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Bạn không có quyền CONFIG_SYSTEM để quản trị danh mục."], JSON_UNESCAPED_UNICODE);
    exit();
}

// 2. Chặn riêng các thao tác can thiệp Sidebar: Bắt buộc phải có quyền MANAGE_SIDEBAR (Kỹ thuật)
$sidebarActions = ['sidebar_admin_get', 'sidebar_admin_save', 'sidebar_admin_delete'];
$detectCheck = $_GET['detect'] ?? '';
if (empty($detectCheck)) {
    $rawDetect = json_decode(file_get_contents("php://input"), true) ?? [];
    $detectCheck = $rawDetect['detect'] ?? '';
}

if (in_array($detectCheck, $sidebarActions)) {
    if (!in_array('MANAGE_SIDEBAR', $myPermissions)) {
        http_response_code(403);
        echo json_encode([
            "status" => "error",
            "message" => "Truy cập bị từ chối! Bạn không có quyền MANAGE_SIDEBAR (Chỉ dành riêng cho Kỹ thuật hệ thống)."
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }
}

// 2. Định nghĩa cấu trúc chuẩn hóa cho 5 bảng danh mục
$categoryMap = [
    'level' => [
        'table' => 'tbl_project_level',
        'pk' => 'level_id',
        'code_col' => 'level_code',
        'name_col' => 'level_name',
        'fk_in_project' => 'level_id',
        'label' => 'Cấp đề tài'
    ],
    'type' => [
        'table' => 'tbl_project_type',
        'pk' => 'project_type_id',
        'code_col' => 'type_code',
        'name_col' => 'type_name',
        'fk_in_project' => 'project_type_id',
        'label' => 'Loại hình đề tài'
    ],
    'register_type' => [
        'table' => 'tbl_project_register_type',
        'pk' => 'register_type_id',
        'code_col' => 'type_code',
        'name_col' => 'type_name',
        'fk_in_project' => 'register_type_id',
        'label' => 'Hình thức đăng ký'
    ],
    'sub_system' => [
        'table' => 'tbl_sub_system',
        'pk' => 'sub_system_id',
        'code_col' => 'system_code',
        'name_col' => 'system_name',
        'fk_in_project' => 'sub_system_id',
        'label' => 'Phân hệ NCKH'
    ],
    'security' => [
        'table' => 'tbl_project_security_level',
        'pk' => 'security_level_id',
        'code_col' => 'security_code',
        'name_col' => 'security_name',
        'fk_in_project' => 'security_level_id',
        'label' => 'Độ mật'
    ]
];

// Lấy tham số nghiệp vụ
$detect = $_GET['detect'] ?? '';
$input = json_decode(file_get_contents("php://input"), true) ?? [];
if (empty($detect)) {
    $detect = $input['detect'] ?? '';
}

try {
    // ==========================================
    // CASE 1: LẤY DANH SÁCH DANH MỤC
    // ==========================================
    if ($detect === 'system_category_get') {
        $type = $_GET['type'] ?? $input['type'] ?? 'level';
        if (!isset($categoryMap[$type])) {
            throw new Exception("Loại danh mục không hợp lệ.");
        }

        $cfg = $categoryMap[$type];
        $sql = "SELECT {$cfg['pk']} AS id, {$cfg['code_col']} AS code, {$cfg['name_col']} AS name, created_at 
                FROM {$cfg['table']} 
                ORDER BY {$cfg['pk']} ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "data" => $items
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // ==========================================
    // CASE 2: THÊM MỚI HOẶC CẬP NHẬT DANH MỤC
    // ==========================================
    if ($detect === 'system_category_save') {
        $type = trim($input['type'] ?? '');
        $id = intval($input['id'] ?? 0);
        $code = trim($input['code'] ?? '');
        $name = trim($input['name'] ?? '');

        if (!isset($categoryMap[$type])) {
            throw new Exception("Loại danh mục không hợp lệ.");
        }
        if (empty($code) || empty($name)) {
            throw new Exception("Vui lòng điền đầy đủ mã và tên danh mục.");
        }

        $cfg = $categoryMap[$type];

        // Kiểm tra trùng mã code
        if ($id > 0) {
            $stmtCheckCode = $pdo->prepare("SELECT COUNT(*) FROM {$cfg['table']} WHERE {$cfg['code_col']} = ? AND {$cfg['pk']} != ?");
            $stmtCheckCode->execute([$code, $id]);
        } else {
            $stmtCheckCode = $pdo->prepare("SELECT COUNT(*) FROM {$cfg['table']} WHERE {$cfg['code_col']} = ?");
            $stmtCheckCode->execute([$code]);
        }
        if ($stmtCheckCode->fetchColumn() > 0) {
            throw new Exception("Mã định danh '{$code}' đã tồn tại, vui lòng chọn mã khác.");
        }

        if ($id > 0) {
            // Cập nhật
            $stmtUpdate = $pdo->prepare("UPDATE {$cfg['table']} SET {$cfg['code_col']} = ?, {$cfg['name_col']} = ? WHERE {$cfg['pk']} = ?");
            $stmtUpdate->execute([$code, $name, $id]);
            $msg = "Cập nhật {$cfg['label']} thành công!";
        } else {
            // Thêm mới
            $stmtInsert = $pdo->prepare("INSERT INTO {$cfg['table']} ({{$cfg['code_col']}}, {{$cfg['name_col']}}) VALUES (?, ?)");
            $stmtInsert->execute([$code, $name]);
            $msg = "Thêm mới {$cfg['label']} thành công!";
        }

        http_response_code(200);
        echo json_encode(["status" => "success", "message" => $msg], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // ==========================================
    // CASE 3: XÓA DANH MỤC (KIỂM TRA RÀNG BUỘC KHÓA NGOẠI)
    // ==========================================
    if ($detect === 'system_category_delete') {
        $type = trim($input['type'] ?? '');
        $id = intval($input['id'] ?? 0);

        if (!isset($categoryMap[$type])) {
            throw new Exception("Loại danh mục không hợp lệ.");
        }
        if ($id <= 0) {
            throw new Exception("ID mục cần xóa không hợp lệ.");
        }

        $cfg = $categoryMap[$type];

        // Kiểm tra xem ID này có đang được bảng tbl_project sử dụng không
        $stmtCheckUsage = $pdo->prepare("SELECT COUNT(*) FROM tbl_project WHERE {$cfg['fk_in_project']} = ?");
        $stmtCheckUsage->execute([$id]);
        $usageCount = $stmtCheckUsage->fetchColumn();

        if ($usageCount > 0) {
            throw new Exception("Không thể xóa mục này vì đang có {$usageCount} đề tài NCKH sử dụng liên kết!");
        }

        $stmtDel = $pdo->prepare("DELETE FROM {$cfg['table']} WHERE {$cfg['pk']} = ?");
        $stmtDel->execute([$id]);

        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Đã xóa mục thuộc {$cfg['label']} thành công!"
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // ==========================================
    // CASE 4: LẤY THÔNG TIN CẤU HÌNH TRƯỜNG & LOGO
    // ==========================================
    if ($detect === 'system_setting_get') {
        $stmt = $pdo->prepare("SELECT university_name, university_short_name, logo_path FROM tbl_system_setting WHERE id = 1 LIMIT 1");
        $stmt->execute();
        $setting = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$setting) {
            $setting = [
                'university_name' => 'Trường Đại học An ninh nhân dân',
                'university_short_name' => 'ANND',
                'logo_path' => 'asset/img/logoT04.png'
            ];
        }

        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "data" => $setting
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }


    // ==========================================
    // CASE 5: CẬP NHẬT THÔNG TIN TRƯỜNG, TÊN HỆ THỐNG & UPLOAD LOGO
    // ==========================================
    if ($detect === 'system_setting_save') {
        $uniName = trim($_POST['university_name'] ?? '');
        $shortName = trim($_POST['university_short_name'] ?? '');
        $systemTitle = trim($_POST['system_title'] ?? '');

        if (empty($uniName)) {
            throw new Exception("Tên đơn vị không được để trống.");
        }
        if (empty($systemTitle)) {
            $systemTitle = 'Hệ thống Quản lý nhiệm vụ khoa học công nghệ và đổi mới sáng tạo';
        }

        // Lấy đường dẫn logo hiện tại
        $stmtOld = $pdo->prepare("SELECT logo_path FROM tbl_system_setting WHERE id = 1 LIMIT 1");
        $stmtOld->execute();
        $currentLogo = $stmtOld->fetchColumn() ?: 'asset/img/logoT04.png';

        // Xử lý nếu có tải lên file logo mới
        if (isset($_FILES['logo_file']) && $_FILES['logo_file']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['logo_file'];
            $allowedExts = ['png', 'jpg', 'jpeg', 'svg', 'webp'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, $allowedExts)) {
                throw new Exception("Định dạng file logo không hợp lệ (chỉ chấp nhận PNG, JPG, JPEG, SVG, WEBP).");
            }

            if ($file['size'] > 5 * 1024 * 1024) {
                throw new Exception("Dung lượng file logo không được vượt quá 5MB.");
            }

            $uploadDir = __DIR__ . '/../../asset/img/upload/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $newFileName = 'school_logo_' . time() . '.' . $ext;
            $destination = $uploadDir . $newFileName;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $currentLogo = 'asset/img/upload/' . $newFileName;
            } else {
                throw new Exception("Không thể lưu file logo tải lên máy chủ.");
            }
        }

        // Lưu vào CSDL
        $stmtSave = $pdo->prepare("
            INSERT INTO tbl_system_setting (id, university_name, university_short_name, system_title, logo_path)
            VALUES (1, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
                university_name = VALUES(university_name),
                university_short_name = VALUES(university_short_name),
                system_title = VALUES(system_title),
                logo_path = VALUES(logo_path)
        ");
        $stmtSave->execute([$uniName, $shortName, $systemTitle, $currentLogo]);

        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Cập nhật cấu hình đơn vị & logo thành công!",
            "data" => [
                "university_name" => $uniName,
                "university_short_name" => $shortName,
                "system_title" => $systemTitle,
                "logo_path" => $currentLogo
            ]
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // ==========================================
    // CASE: LẤY DANH SÁCH TOÀN BỘ SIDEBAR ĐỂ QUẢN TRỊ
    // ==========================================
    if ($detect === 'sidebar_admin_get') {
        $stmt = $pdo->prepare("SELECT * FROM tbl_sidebar ORDER BY sort_order ASC, sidebar_id ASC");
        $stmt->execute();
        $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

        http_response_code(200);
        echo json_encode(["status" => "success", "data" => $menus], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // ==========================================
    // CASE: LƯU (THÊM / SỬA) MENU SIDEBAR
    // ==========================================
    if ($detect === 'sidebar_admin_save') {
        $id = intval($input['sidebar_id'] ?? 0);
        $name = trim($input['menu_name'] ?? '');
        $link = trim($input['menu_link'] ?? '');
        $icon = trim($input['icon_class'] ?? 'fa-solid fa-circle');
        $perm = trim($input['required_permission'] ?? '');
        $order = intval($input['sort_order'] ?? 0);
        $active = intval($input['is_active'] ?? 1);

        if (empty($name) || empty($link)) {
            throw new Exception("Tên menu và liên kết file không được để trống.");
        }

        $permVal = empty($perm) ? null : $perm;

        if ($id > 0) {
            $stmt = $pdo->prepare("
                UPDATE tbl_sidebar 
                SET menu_name = ?, menu_link = ?, icon_class = ?, required_permission = ?, sort_order = ?, is_active = ?
                WHERE sidebar_id = ?
            ");
            $stmt->execute([$name, $link, $icon, $permVal, $order, $active, $id]);
            $msg = "Cập nhật menu thành công!";
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO tbl_sidebar (menu_name, menu_link, icon_class, required_permission, sort_order, is_active)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$name, $link, $icon, $permVal, $order, $active]);
            $msg = "Thêm menu mới thành công!";
        }

        http_response_code(200);
        echo json_encode(["status" => "success", "message" => $msg], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // ==========================================
    // CASE: XÓA MENU SIDEBAR
    // ==========================================
    if ($detect === 'sidebar_admin_delete') {
        $id = intval($input['sidebar_id'] ?? 0);
        if ($id <= 0) {
            throw new Exception("ID menu không hợp lệ.");
        }

        $stmt = $pdo->prepare("DELETE FROM tbl_sidebar WHERE sidebar_id = ?");
        $stmt->execute([$id]);

        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "Đã xóa menu thành công!"], JSON_UNESCAPED_UNICODE);
        exit();
    }
    throw new Exception("Yêu cầu không được hỗ trợ.");
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    exit();
}
