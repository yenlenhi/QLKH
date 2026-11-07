<?php
define('ALLOW_ACCESS', true);
// File: index.php (Nằm ở thư mục gốc, ngoài Controller)
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Cấu hình Headers chuẩn RESTful API
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With");

// 1. Đọc tham số detect từ request (hỗ trợ cả GET, POST form và Raw JSON)
$detect = $_REQUEST['detect'] ?? '';
if (empty($detect)) {
    $rawInput = json_decode(file_get_contents('php://input'), true);
    if (is_array($rawInput)) {
        $_REQUEST = array_merge($_REQUEST, $rawInput);
        $detect = $_REQUEST['detect'] ?? '';
    }
}

if (empty($detect)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'detect parameter not found !'
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

// 2. KHU VỰC CÔNG KHAI (KHÔNG CẦN TOKEN): Định nghĩa các action không cần xác thực
$public_actions = ['login', 'system_setting_get'];

if (in_array($detect, $public_actions)) {
    // Chỉ kết nối CSDL, không gọi auth_middleware.php
    require_once __DIR__ . '/Model/db_connect.php';

    switch ($detect) {
        case 'login': {
                include_once __DIR__ . '/Controller/api/login.php';
                break;
            }
        case 'system_setting_get': {
                include_once __DIR__ . '/Controller/api/system_setting_get.php';
                break;
            }
            // Thêm các action công khai khác nếu cần
    }
    exit();
}


require_once __DIR__ . '/Controller/api/auth_middleware.php';
require_once __DIR__ . '/Model/db_connect.php';
$detect = $_REQUEST['detect'];

// 3. Điều hướng (Router) vào thư mục Controller/api/

switch ($detect) {
    case 'add_role_user': {
            require_once __DIR__ . '/Controller/api/add_role_user.php';
            break;
        }
    case 'create_guides': {
            require_once __DIR__ . '/Controller/api/create_guides.php';
            break;
        }
    case 'delete_guides': {
            require_once __DIR__ . '/Controller/api/delete_guides.php';
            break;
        }
    case 'delete_project': {
            require_once __DIR__ . '/Controller/api/delete_project.php';
            break;
        }
    case 'delete_single_file_guide': {
            require_once __DIR__ . '/Controller/api/delete_single_file_guide.php';
            break;
        }
    case 'delete_user': {
            require_once __DIR__ . '/Controller/api/delete_user.php';
            break;
        }
    case 'get_guides': {
            require_once __DIR__ . '/Controller/api/get_guides.php';
            break;
        }
    case 'get_list_permission': {
            require_once __DIR__ . '/Controller/api/get_list_permission.php';
            break;
        }
    case 'get_permission_users': {
            require_once __DIR__ . '/Controller/api/get_permission_users.php';
            break;
        }
    case 'get_profile': {
            require_once __DIR__ . '/Controller/api/get_profile.php';
            break;
        }
    case 'get_user_role': {
            require_once __DIR__ . '/Controller/api/get_user_role.php';
            break;
        }
    case 'get_users': {
            require_once __DIR__ . '/Controller/api/get_users.php';
            break;
        }
        // case 'login': {
        //     require_once __DIR__ . '/Controller/api/login.php';
        //     break;
        // }

    case 'get_notifications': {
            require_once __DIR__ . '/Controller/api/get_notifications.php';
            break;
        }
    case 'mark_notifications_read': {
            require_once __DIR__ . '/Controller/api/mark_notifications_read.php';
            break;
        }
    case 'read_notification': {
            require_once __DIR__ . '/Controller/api/read_notification.php';
            break;
        }
    case 'register_user': {
            require_once __DIR__ . '/Controller/api/register_user.php';
            break;
        }
    case 'stream_notifications': {
            // 1. Giải phóng Session Lock ngay lập tức để không chặn các request/trang khác trên Local
            if (session_status() === PHP_SESSION_ACTIVE) {
                session_write_close();
            }

            // 2. Chuyển header sang dạng Stream (ghi đè header JSON ở đầu file index_api.php)
            header("Content-Type: text/event-stream");
            header("Cache-Control: no-cache");
            header("Connection: keep-alive");
            header("X-Accel-Buffering: no");

            // 3. Tắt bộ đệm đầu ra của Apache/PHP
            @ini_set('zlib.output_compression', '0');
            @ini_set('implicit_flush', '1');
            while (ob_get_level() > 0) {
                ob_end_flush();
            }
            flush();
            require_once __DIR__ . '/Controller/api/stream_notifications.php';
            break;
        }
    case 'update_guides': {
            require_once __DIR__ . '/Controller/api/update_guides.php';
            break;
        }
    case 'update_profile': {
            require_once __DIR__ . '/Controller/api/update_profile.php';
            break;
        }
    case 'update_user_info': {
            require_once __DIR__ . '/Controller/api/update_user_info.php';
            break;
        }
    case 'update_user_status': {
            require_once __DIR__ . '/Controller/api/update_user_status.php';
            break;
        }
    case 'update_report': {
            require_once __DIR__ . '/Controller/api/update_report.php';
            break;
        }
        // Quản lý dự án
    case 'project_create': {
            require_once __DIR__ . '/Controller/api/project_create.php';
            break;
        }
    case 'project_get': {
            require_once __DIR__ . '/Controller/api/project_get.php';
            break;
        }
    case 'project_update': {
            require_once __DIR__ . '/Controller/api/project_update.php';
            break;
        }
    case 'project_delete': {
            require_once __DIR__ . '/Controller/api/project_delete.php';
            break;
        }
    case 'project_register_type_get': {
            require_once __DIR__ . '/Controller/api/project_register_type_get.php';
            break;
        }
    case 'project_level_get': {
            require_once __DIR__ . '/Controller/api/project_level_get.php';
            break;
        }
    case 'project_type_get': {
            require_once __DIR__ . '/Controller/api/project_type_get.php';
            break;
        }
    case 'project_security_level_get': {
            require_once __DIR__ . '/Controller/api/project_security_level_get.php';
            break;
        }
    case 'project_sup_get': {
            require_once __DIR__ . '/Controller/api/project_sub_system_get.php';
            break;
        }
    case 'project_progress_report': {
            require_once __DIR__ . '/Controller/api/project_progress_report.php';
            break;
        }
    case 'project_reject': {
            require_once __DIR__ . '/Controller/api/project_reject.php';
            break;
        }
    case 'user_teacher_get': {
            require_once __DIR__ . '/Controller/api/project_user_teacher_get.php';
            break;
        }
    case 'user_permission_get': {
            require_once __DIR__ . '/Controller/api/user_permission_get.php';
            break;
        }
    case 'project_payment_save': {
            require_once __DIR__ . '/Controller/api/project_payment_save.php';
            break;
        }
    case 'project_payment_get': {
            require_once __DIR__ . '/Controller/api/project_payment_get.php';
            break;
        }
    case 'project_settlement_save': {
            require_once __DIR__ . '/Controller/api/project_settlement_save.php';
            break;
        }
    case 'project_restore': {
            require_once __DIR__ . '/Controller/api/project_restore.php';
            break;
        }
    case 'project_force_delete': {
            require_once __DIR__ . '/Controller/api/project_force_delete.php';
            break;
        }
    case 'project_trash_get': {
            require_once __DIR__ . '/Controller/api/project_trash_get.php';
            break;
        }
    case 'user_project_stats': {
            require_once __DIR__ . '/Controller/api/user_project_stats.php';
            break;
        }
        // DASHBOARD STATS
    case 'dashboard_stats': {
            require_once __DIR__ . '/Controller/api/dashboard_stats.php';
            break;
        }

        // Sidebar menu
    case 'sidebar_get': {
            require_once __DIR__ . '/Controller/api/sidebar_get.php';
            break;
        }
        // Lịch sử
    case 'get_activity_logs': {
            require_once __DIR__ . '/Controller/api/get_activity_logs.php';
            break;
        }
    case 'sidebar_admin_get':
    case 'sidebar_admin_save':
    case 'sidebar_admin_delete':
    case 'system_category_get':
    case 'system_category_save':
    case 'system_category_delete':
    case 'system_setting_get':
    case 'system_setting_save': {
            require_once __DIR__ . '/Controller/api/system_category_api.php';
            break;
        }
        // THÔNG BÁO
    case 'send_notification': {
            require_once __DIR__ . '/Controller/api/send_notification.php';
            break;
        }
    case 'sub_system_level_get': {
            require_once __DIR__ . '/Controller/api/sub_system_level_get.php';
            break;
        }
    case 'project_import_excel': {
            require_once __DIR__ . '/Controller/api/project_import_excel.php';
            break;
        }
        // Lý lịch khoa học
    case 'cv_get': {
            require_once __DIR__ . '/Controller/api/cv_get.php';
            break;
        }
    case 'cv_save': {
            require_once __DIR__ . '/Controller/api/cv_save.php';
            break;
        }
    case 'org_unit_get': {
            require_once __DIR__ . '/Controller/api/org_unit_get.php';
            break;
        }
    case 'project_task_get':
    case 'project_task_create':
    case 'project_task_toggle_status':
    case 'project_task_assign':
    case 'project_task_delete': {
            require_once __DIR__ . '/Controller/api/api_project_task.php';
            break;
        }
    // case 'export_excel_runner': {
    //         require_once __DIR__ . '/Controller/api/export_excel_runner.php';
    //         break;
    //     }




    default: {
            http_response_code(404);
            echo json_encode([
                'status' => 'error',
                'message' => 'detect has been failed'
            ], JSON_UNESCAPED_UNICODE);
            break;
        }
}
exit();
