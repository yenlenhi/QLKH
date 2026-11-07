<?php
// db.php
$host = 'localhost';
$dbname = 'db_qlnckh'; // Thay tên database của bạn vào đây
$username = 'root';    // Username mặc định của phpMyAdmin
$password = '';        // Mật khẩu mặc định của phpMyAdmin (để trống trên XAMPP)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    die("Lỗi kết nối CSDL: " . $e->getMessage());
}
?>