<?php
/**
 * Kiểm tra tiền tố của username và gán đường dẫn chuyển hướng tương ứng.
 * 
 * @param string $username Tên đăng nhập người dùng
 * @param array $response Mảng phản hồi trả về
 * @param string $redirect1 Đường dẫn dành cho CB, LD (ví dụ: View/r1_home.html)
 * @param string $redirect2 Đường dẫn dành cho GV (ví dụ: View/r3_home.html)
 * @return array
 */
function checkTypeAccount($username, &$response, $redirect1, $redirect2)
{
    // 1. Lấy 2 ký tự đầu tiên của username (chuyển thành chữ hoa)
    $prefix = strtoupper(substr(trim($username), 0, 2));

    // 2. So sánh tiền tố và gán trang điều hướng
    switch ($prefix) {
        case 'CB': // Cán bộ
        case 'LD': // Lãnh đạo
            $response['status'] = 'success';
            $response['message'] = 'Đăng nhập thành công!';
            $response['redirect'] = $redirect1;
            break;

        case 'GV': // Giảng viên
            $response['status'] = 'success';
            $response['message'] = 'Đăng nhập thành công!';
            $response['redirect'] = $redirect2;
            break;

        default:
            $response['status'] = 'error';
            $response['message'] = 'Loại tài khoản không được hỗ trợ!';
            break;
    }

    return $response;
}