# Chạy QLNCKH local

Yêu cầu: PHP có `pdo_mysql`, MariaDB/MySQL và Python 3 (chỉ cần cho chức năng export Excel).

## Database

Ứng dụng mặc định kết nối `localhost`, database `db_qlnckh`, user `root`, mật khẩu rỗng (xem `Model/db_connect.php`). Với MariaDB/MySQL local, tạo và import database:

```powershell
mariadb -u root -e "CREATE DATABASE db_qlnckh CHARACTER SET utf8mb4"
mariadb -u root db_qlnckh < "db_qlnckh (1).sql"
```

Nếu máy bạn dùng mật khẩu khác cho `root`, cập nhật các biến `$host`, `$dbname`, `$username`, `$password` trong `Model/db_connect.php` trước khi chạy.

## Web server phát triển

Từ thư mục repository, chạy:

```powershell
php -S 127.0.0.1:8080 dev-router.php
```

Mở `http://127.0.0.1:8080/QLNCKH/index.php`. Router hỗ trợ cả các URL thân thiện của `.htaccess`, gồm `/QLNCKH/trang-chu`.

Tài khoản mẫu trong dump: `CB000000` / `123456`.

## Lưu ý về dump

Dump hiện có schema cho quản lý đơn vị và dashboard. Source vẫn còn tham chiếu các bảng `tbl_project_task`, `tbl_project_task_assignment`, `tbl_scientific_curriculum_vitae` và `tbl_sub_system_level`, nhưng không có migration/schema tương ứng trong repository. Các chức năng liên quan sẽ cần schema gốc từ tác giả dự án.
