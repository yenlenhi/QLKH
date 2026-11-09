-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 26, 2026 lúc 02:47 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `db_qlnckh`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_activity_logs`
--

CREATE TABLE `tbl_activity_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'ID người thực hiện',
  `action_type` varchar(50) NOT NULL COMMENT 'Loại hành động: CREATE, UPDATE, DELETE, PAYMENT, v.v.',
  `target_object` varchar(100) NOT NULL COMMENT 'Đối tượng tác động: Đề tài, Tài khoản, Thanh toán, Hướng dẫn...',
  `object_id` int(11) DEFAULT NULL COMMENT 'ID của đối tượng bị tác động',
  `description` text NOT NULL COMMENT 'Mô tả chi tiết nội dung',
  `ip_address` varchar(45) DEFAULT NULL COMMENT 'Địa chỉ IP',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_activity_logs`
--

INSERT INTO `tbl_activity_logs` (`log_id`, `user_id`, `action_type`, `target_object`, `object_id`, `description`, `ip_address`, `created_at`) VALUES
(1, 7, 'CREATE', 'Quản lý Tài khoản', 8, 'Đã tạo mới tài khoản hệ thống: CBQLTAIKHOAN01 (Loại: CB)', '::1', '2026-10-25 20:41:48'),
(2, 8, 'CREATE', 'Quản lý Đề tài', 6, 'Đăng ký mới đề tài: [DTSVMoi098] DMOIJQLWNBE', '::1', '2026-10-25 20:53:59'),
(3, 8, 'UPDATE', 'Tài khoản người dùng', 8, 'Đã cập nhật thông tin hồ sơ cho tài khoản ID: 8', '::1', '2026-10-25 21:11:05'),
(4, 8, 'UPDATE', 'Tài khoản người dùng', 8, 'Đã cập nhật thông tin hồ sơ cho tài khoản ID: 8', '::1', '2026-10-25 21:12:10'),
(5, 8, 'UPDATE', 'Tài khoản người dùng', 8, 'Đã cập nhật thông tin hồ sơ cho tài khoản: Nguyễn Văn Tuất', '::1', '2026-10-25 21:26:23'),
(6, 8, 'UPDATE', 'Tài khoản người dùng', 2, 'Đã cập nhật thông tin hồ sơ cho tài khoản: Nguyễn Thanh Xà', '::1', '2026-10-25 21:32:19'),
(7, 8, 'UPDATE', 'Tài khoản người dùng', 2, 'Đã cập nhật thông tin hồ sơ cho tài khoản: Nguyễn Thi Xà Nữ', '::1', '2026-10-25 21:47:56'),
(8, 8, 'UPDATE', 'Quản lý Đề tài', 1, 'Chỉnh sửa thông tin đề tài ID: 1', '::1', '2026-10-25 22:00:20'),
(9, 8, 'UPDATE', 'Quản lý Đề tài', 1, 'Chỉnh sửa thông tin đề tài ID: 1', '::1', '2026-10-25 22:00:44'),
(10, 8, 'DELETE', 'Quản lý Đề tài', 4, 'Đã chuyển đề tài [HT123] HT1234564335 vào thùng rác', '::1', '2026-10-25 22:14:53'),
(11, 8, 'UPDATE', 'Thùng rác Đề tài', 4, 'Đã khôi phục lại đề tài [HT123] HT1234564335 từ thùng rác', '::1', '2026-10-25 22:18:21'),
(12, 8, 'UPDATE', 'Trạng thái Tài khoản', 2, 'Đã vô hiệu hóa tài khoản người dùng ID: 2', '::1', '2026-10-25 22:18:37'),
(13, 8, 'UPDATE', 'Trạng thái Tài khoản', 2, 'Đã kích hoạt tài khoản người dùng: Nguyễn Thi Xà Nữ', '::1', '2026-10-25 22:20:28'),
(14, 8, 'CREATE', 'Quản lý Tài khoản', 9, 'Đã tạo mới tài khoản hệ thống: GV0909090 (Loại: GV)', '::1', '2026-10-25 22:22:30'),
(15, 8, 'DELETE', 'Quản lý Tài khoản', 9, 'Đã xóa vĩnh viễn tài khoản người dùng: Người dùng', '::1', '2026-10-25 22:22:42'),
(16, 7, 'UPDATE', 'Quản lý Đề tài', 6, 'Đã cập nhật đề tài [DTSVMoi098] DMOIJQLWNBE sang trạng thái: Đang tiến hành', '::1', '2026-10-26 19:16:35');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_guides`
--

CREATE TABLE `tbl_guides` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `hotline` varchar(15) NOT NULL,
  `number_phone` varchar(15) NOT NULL,
  `file_guides` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_guides`
--

INSERT INTO `tbl_guides` (`id`, `title`, `content`, `hotline`, `number_phone`, `file_guides`) VALUES
(3, 'Tổng Bí thư, Chủ tịch nước kêu gọi cộng đồng người Việt tại Mỹ khép lại quá khứ, chung tay kiến tạo tương lai', 'Theo TTXVN, ngày 21-9 (giờ địa phương), trong khuôn khổ chuyến công tác tại Mỹ, Tổng Bí thư, Chủ tịch nước Tô Lâm cùng đoàn đại biểu cấp cao Việt Nam đã tham dự sự kiện văn hóa nghệ thuật Việt Nam Hành trình Tổ quốc qua điện ảnh và âm nhạc và gặp gỡ cộng đồng người Việt ở Mỹ.\r\n\r\nTham dự sự kiện có hơn 2.000 đại biểu, gồm các quan chức của Liên hợp quốc, bạn bè Mỹ, quốc tế và đông đảo đại diện cộng đồng người Việt Nam tại Mỹ.\r\n\r\nĐây là sự kiện gặp gỡ giữa lãnh đạo Đảng, Nhà nước với cộng đồng diễn ra ở nước ngoài có số lượng kiều bào tham dự lớn nhất từ trước đến nay.', '098765555', '0876756456', '[{\"original_name\":\"BM-02. Thuyết minh nhiệm vụ khoa học công nghệ và đổi mới sáng tạo.docx\",\"file_path\":\"asset\\/file\\/guides\\/1790069115_6ab2497b768f5.docx\"},{\"original_name\":\"BM-03. Thuyết minh nhiệm vụ sản xuất thử nghiệm.docx\",\"file_path\":\"asset\\/file\\/guides\\/1790069115_6ab2497b76b39.docx\"},{\"original_name\":\"BM-04. Lý lịch khoa học.docx\",\"file_path\":\"asset\\/file\\/guides\\/1790069115_6ab2497b76db1.docx\"}]'),
(4, 'Trực tiếp ASIAD 2026 - Cập nhật liên tục các môn thi đấu tại ASIAD 20, đặc biệt là những nội dung có sự góp mặt của VĐV Việt Nam diễn ra trong hôm nay, ngày 23/9.', 'Bộ ba xạ thủ gồm Trịnh Thu Vinh, Nguyễn Thùy Trang và Bùi Thúy Thu Thủy giành huy chương đồng nội dung súng ngắn. HCV 10m súng ngắn Olympic 2024 Oh Ye jin dừng bước ngay từ vòng loại.\r\n\r\nThùy Trang xuất sắc giành quyền vào thi chung kết, tranh huy chương nội dung 10m súng ngắn cá nhân, diễn ra vào lúc 12h30 hôm nay. Đáng tiếc niềm hi vọng Thu Vinh không vượt qua vòng loại.', '0987678765', '8765456098', '[{\"original_name\":\"BM-04. Lý lịch khoa học.docx\",\"file_path\":\"asset\\/file\\/guides\\/1790132127_6ab33f9f3df9e.docx\"},{\"original_name\":\"BM-05. Tóm tắt năng lực của đơn vị đăng ký chủ trì nhiệm vụ.docx\",\"file_path\":\"asset\\/file\\/guides\\/1790132127_6ab33f9f3e556.docx\"},{\"original_name\":\"BM-06. Văn bản cam kết phối hợp thực hiện nhiệm vụ.docx\",\"file_path\":\"asset\\/file\\/guides\\/1790132127_6ab33f9f3e78a.docx\"}]');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_notifications`
--

CREATE TABLE `tbl_notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `type` varchar(50) NOT NULL,
  `is_read` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_notifications`
--

INSERT INTO `tbl_notifications` (`id`, `user_id`, `project_id`, `title`, `content`, `type`, `is_read`, `created_at`) VALUES
(3, 2, 1, 'Nhắc nhở cập nhật thông tin', 'Vui lòng bổ sung danh sách thành viên tham gia cho đề tài Tự động hóa trong nông nghiệp.', 'INFO', 1, '2026-09-18 21:51:20'),
(4, 2, 1, 'Hội đồng duyệt Thuyết minh', 'Thuyết minh đề tài Phát triển AI cho chẩn đoán y tế đã được Hội đồng thông qua.', 'SUCCESS', 1, '2026-09-19 22:29:28'),
(5, 2, 1, 'CẢNH BÁO: Quá hạn báo cáo', 'Đề tài Nghiên cứu vật liệu nano mới đã quá hạn nộp báo cáo tiến độ 2 ngày.', 'DANGER', 1, '2026-09-19 22:29:28'),
(6, 2, 4, 'Yêu cầu bổ sung thành viên', 'Vui lòng cập nhật đầy đủ danh sách thành viên tham gia cho đề tài Tự động hóa nông nghiệp.', 'WARNING', 0, '2026-09-19 22:29:28'),
(7, 2, 4, 'Nhắc nhở quyết toán kinh phí', 'Đề nghị Chủ nhiệm đề tài nộp chứng từ thanh quyết toán đợt 1 trước ngày 30/09.', 'INFO', 0, '2026-09-18 22:29:28'),
(8, 2, 4, 'Lịch họp Hội đồng nghiệm thu', 'Lịch họp nghiệm thu cơ sở đề tài Vật liệu nano dự kiến diễn ra vào 14h00 ngày 25/09.', 'INFO', 1, '2026-09-17 22:29:28'),
(9, 2, 1, 'Chuyển giai đoạn nghiệm thu [DTSV1]', 'Đề tài \"DEMON\" đã được chuyển sang giai đoạn Chờ nghiệm thu.', 'INFO', 0, '2026-10-25 17:19:23'),
(11, 2, 6, 'Phân công đề tài mới [DTSVMoi098]', 'Đề tài \"DMOIJQLWNBE\" vừa được đăng ký và phân công cho bạn phụ trách (Trạng thái: Chờ duyệt).', 'INFO', 0, '2026-10-25 20:53:59'),
(12, 2, 1, 'Chờ thanh quyết toán [DTSV1]', 'Đề tài \"DEMON\" đã nghiệm thu và chuyển sang giai đoạn Chờ thanh toán kinh phí.', 'WARNING', 0, '2026-10-25 22:00:20'),
(13, 2, 1, 'Hoàn thành đề tài [DTSV1]', 'Chúc mừng! Đề tài \"DEMON\" đã hoàn tất toàn bộ quy trình nghiệm thu và thanh quyết toán.', 'SUCCESS', 0, '2026-10-25 22:00:44'),
(14, 2, 6, 'Đã xét duyệt đề tài [DTSVMoi098]', 'Đề tài \"DMOIJQLWNBE\" đã được Hội đồng phê duyệt và kích hoạt tiến độ thực hiện.', 'SUCCESS', 0, '2026-10-26 19:16:35');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_permission`
--

CREATE TABLE `tbl_permission` (
  `id` int(11) NOT NULL,
  `permission_code` varchar(100) NOT NULL,
  `permission_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_permission`
--

INSERT INTO `tbl_permission` (`id`, `permission_code`, `permission_name`) VALUES
(1, 'MANAGE_ACCOUNT', 'Quản lý tài khoản'),
(2, 'CONFIG_SYSTEM', 'Cấu hình hệ thống & danh mục chung'),
(3, 'CONFIRM_CHECK', 'Phê duyệt nhiệm vụ NCKH'),
(6, 'MANAGE_FEE', 'Quản lý kinh phí & tài chính'),
(7, 'REGISTER_PROJECT', 'Đăng ký đề tài NCKH'),
(8, 'MANAGE_SUP_PROJ', 'Quản lý phân hệ'),
(9, 'MANAGE_GUIDE', 'Quản lý hướng dẫn'),
(10, 'MANAGE_SIDEBAR', 'Quản trị Sidebar (Chỉ dành cho Kỹ thuật)'),
(11, 'DATA_LOG', 'Quyền xem lịch sử'),
(12, 'ROLE_PERMISSION', 'Phân quyền');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_project`
--

CREATE TABLE `tbl_project` (
  `project_id` int(11) NOT NULL,
  `project_code` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `project_user_action_id` int(11) DEFAULT NULL,
  `register_type_id` int(11) NOT NULL,
  `project_name` text NOT NULL,
  `level_id` int(11) NOT NULL,
  `project_type_id` int(11) NOT NULL,
  `sub_system_id` int(11) NOT NULL,
  `security_level_id` int(11) NOT NULL,
  `estimated_budget` decimal(15,2) DEFAULT 0.00,
  `settlement_budget` decimal(15,2) DEFAULT NULL COMMENT 'Tổng kinh phí quyết toán thực tế được duyệt khi kết thúc đề tài',
  `status` varchar(50) DEFAULT 'cho_duyet',
  `approved_date` datetime DEFAULT NULL,
  `duration_months` int(11) DEFAULT 12,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL COMMENT 'Thời điểm đề tài bị chuyển vào thùng rác',
  `is_deleted` tinyint(1) DEFAULT 0 COMMENT '0: Bình thường, 1: Đang ở trong thùng rác'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_project`
--

INSERT INTO `tbl_project` (`project_id`, `project_code`, `user_id`, `project_user_action_id`, `register_type_id`, `project_name`, `level_id`, `project_type_id`, `sub_system_id`, `security_level_id`, `estimated_budget`, `settlement_budget`, `status`, `approved_date`, `duration_months`, `created_at`, `deleted_at`, `is_deleted`) VALUES
(1, 'DTSV1', 4, 2, 3, 'DEMON', 1, 1, 2, 2, 0.00, NULL, 'completed', '2026-10-22 10:35:50', 12, '2026-10-21 15:06:44', NULL, 0),
(4, 'HT123', 4, 2, 2, 'HT1234564335', 1, 2, 2, 1, 2000000000.00, 1600000000.00, 'completed', '2026-10-23 10:44:48', 12, '2026-10-23 03:40:29', NULL, 0),
(6, 'DTSVMoi098', 8, 2, 2, 'DMOIJQLWNBE', 1, 3, 2, 1, 3000000.00, NULL, 'running', '2026-10-26 19:16:35', 12, '2026-10-25 13:53:59', NULL, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_project_file`
--

CREATE TABLE `tbl_project_file` (
  `file_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_size` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_project_file`
--

INSERT INTO `tbl_project_file` (`file_id`, `project_id`, `file_name`, `file_path`, `file_size`, `created_at`) VALUES
(1, 1, '1. Báo cáo kết quả.doc', 'asset/file/project/1792595204_4802_1._B__o_c__o_k___t_qu___.doc', 55808, '2026-10-21 15:06:44'),
(2, 1, '2.Chương trình chuyên đề 1.docx', 'asset/file/project/1792595204_2242_2.Ch____ng_tr__nh_chuy__n_______1.docx', 34551, '2026-10-21 15:06:44'),
(3, 1, '3. Biên bản.doc', 'asset/file/project/1792595204_7101_3._Bi__n_b___n.doc', 79872, '2026-10-21 15:06:44'),
(4, 1, '4. Danh sách học Nghị quyết TW4.doc', 'asset/file/project/1792595204_2315_4._Danh_s__ch_h___c_Ngh____quy___t_TW4.doc', 54272, '2026-10-21 15:06:44'),
(5, 1, '5. Bài thu hoạch.docx', 'asset/file/project/1792595204_8718_5._B__i_thu_ho___ch.docx', 18686, '2026-10-21 15:06:44'),
(9, 4, '1792595359_7975_5._B__i_thu_ho___ch (1).docx', 'asset/file/project/1792726829_9227_1792595359_7975_5._B__i_thu_ho___ch__1_.docx', 18686, '2026-10-23 03:40:29'),
(10, 4, '1792595359_7975_5._B__i_thu_ho___ch.docx', 'asset/file/project/1792726829_9129_1792595359_7975_5._B__i_thu_ho___ch.docx', 18686, '2026-10-23 03:40:29'),
(12, 6, '3. Biên bản.doc', 'asset/file/project/1792936439_3898_3._Bi__n_b___n.doc', 79872, '2026-10-25 13:53:59');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_project_level`
--

CREATE TABLE `tbl_project_level` (
  `level_id` int(11) NOT NULL,
  `level_code` varchar(50) NOT NULL,
  `level_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_project_level`
--

INSERT INTO `tbl_project_level` (`level_id`, `level_code`, `level_name`, `created_at`) VALUES
(1, 'cap_co_so', 'Cấp cơ sở', '2026-10-21 14:31:52'),
(2, 'cap_bo', 'Cấp bộ', '2026-10-21 14:31:52'),
(3, 'cap_quoc_gia', 'Cấp quốc gia', '2026-10-21 14:31:52');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_project_member`
--

CREATE TABLE `tbl_project_member` (
  `member_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `platoon` varchar(50) DEFAULT NULL,
  `is_leader` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_project_member`
--

INSERT INTO `tbl_project_member` (`member_id`, `project_id`, `full_name`, `platoon`, `is_leader`, `created_at`) VALUES
(10, 4, 'Nguyễn Thị L', 'D34', 1, '2026-10-25 11:56:11'),
(11, 4, 'Nguyễn Thị M', 'D34', 0, '2026-10-25 11:56:11'),
(12, 4, 'Nguyễn Thị N', 'D33', 0, '2026-10-25 11:56:11'),
(13, 4, 'Nguyễn Thị Trâm Anh', NULL, 0, '2026-10-25 11:56:11'),
(15, 1, 'Nguyễn Văn A', 'D32', 1, '2026-10-25 11:57:57'),
(19, 6, 'Nguyễn Huỳnh Khoa A', 'D32', 1, '2026-10-25 13:53:59'),
(20, 6, 'Nguyễn Huỳnh Khoa B', 'D34', 0, '2026-10-25 13:53:59'),
(21, 6, 'Nguyễn Huỳnh Khoa C', 'D33', 0, '2026-10-25 13:53:59');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_project_payment`
--

CREATE TABLE `tbl_project_payment` (
  `payment_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `payment_phase` varchar(255) NOT NULL COMMENT 'Tên đợt thanh toán, ví dụ: Đợt 1, Đợt tạm ứng...',
  `paid_amount` decimal(15,2) NOT NULL DEFAULT 0.00 COMMENT 'Số tiền thực nhận',
  `payment_date` date NOT NULL COMMENT 'Ngày nhận tiền',
  `status` varchar(50) NOT NULL DEFAULT 'chua_quyet_toan' COMMENT 'Trạng thái: chua_quyet_toan hoặc da_quyet_toan',
  `note` text DEFAULT NULL COMMENT 'Ghi chú / Chứng từ kèm theo',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_project_payment`
--

INSERT INTO `tbl_project_payment` (`payment_id`, `project_id`, `payment_phase`, `paid_amount`, `payment_date`, `status`, `note`, `created_at`, `updated_at`) VALUES
(3, 4, 'đợt 2', 1500000000.00, '2026-10-23', 'da_quyet_toan', 'CD232323', '2026-10-23 03:45:47', '2026-10-23 03:45:47'),
(4, 4, 'đợt 2', 100000000.00, '2026-10-23', 'da_quyet_toan', 'DCFSSW', '2026-10-23 04:33:06', '2026-10-23 04:33:06');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_project_register_type`
--

CREATE TABLE `tbl_project_register_type` (
  `register_type_id` int(11) NOT NULL,
  `type_code` varchar(50) NOT NULL,
  `type_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_project_register_type`
--

INSERT INTO `tbl_project_register_type` (`register_type_id`, `type_code`, `type_name`, `created_at`) VALUES
(2, 'dat_hang', 'Đề tài đặt hàng', '2026-10-21 14:31:15'),
(3, 'tai_tro', 'Đề tài tài trợ', '2026-10-21 14:31:15');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_project_report`
--

CREATE TABLE `tbl_project_report` (
  `report_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'Người nộp báo cáo',
  `report_phase` tinyint(2) NOT NULL COMMENT 'Đợt báo cáo: 1, 2, 3, 4',
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_size` int(11) DEFAULT 0,
  `note` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'submitted' COMMENT 'submitted: Đã nộp, approved: Đã duyệt, rejected: Yêu cầu nộp lại',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_project_security_level`
--

CREATE TABLE `tbl_project_security_level` (
  `security_level_id` int(11) NOT NULL,
  `security_code` varchar(50) NOT NULL,
  `security_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_project_security_level`
--

INSERT INTO `tbl_project_security_level` (`security_level_id`, `security_code`, `security_name`, `created_at`) VALUES
(1, 'khong_mat', 'Không mật', '2026-10-21 14:33:20'),
(2, 'mat', 'Mật', '2026-10-21 14:33:20'),
(3, 'toi_mat', 'Tối mật', '2026-10-21 14:33:20'),
(4, 'tuyet_mat', 'Tuyệt mật', '2026-10-21 14:33:20');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_project_type`
--

CREATE TABLE `tbl_project_type` (
  `project_type_id` int(11) NOT NULL,
  `type_code` varchar(50) NOT NULL,
  `type_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_project_type`
--

INSERT INTO `tbl_project_type` (`project_type_id`, `type_code`, `type_name`, `created_at`) VALUES
(1, 'unitech', 'Unitech', '2026-10-21 14:32:35'),
(2, 'co_ban', 'Cơ bản', '2026-10-21 14:32:35'),
(3, 'nghiep_vu', 'Nghiệp vụ', '2026-10-21 14:32:35');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_role`
--

CREATE TABLE `tbl_role` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_role`
--

INSERT INTO `tbl_role` (`id`, `user_id`, `permission_id`) VALUES
(59, 1, 7),
(50, 4, 1),
(51, 4, 2),
(52, 4, 3),
(53, 4, 6),
(54, 4, 7),
(55, 4, 8),
(56, 4, 9),
(57, 4, 11),
(42, 7, 1),
(43, 7, 2),
(44, 7, 3),
(45, 7, 6),
(46, 7, 7),
(47, 7, 8),
(48, 7, 9),
(58, 7, 10),
(49, 7, 11),
(60, 7, 12),
(66, 8, 1),
(67, 8, 3),
(68, 8, 7),
(69, 8, 8);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_sidebar`
--

CREATE TABLE `tbl_sidebar` (
  `sidebar_id` int(11) NOT NULL,
  `menu_name` varchar(255) NOT NULL,
  `menu_link` varchar(255) NOT NULL,
  `icon_class` varchar(100) DEFAULT NULL COMMENT 'Class icon FontAwesome',
  `required_permission` varchar(100) DEFAULT NULL COMMENT 'Mã quyền yêu cầu (trùng với tbl_permission.permission_code), để trống nếu ai cũng thấy',
  `sort_order` int(11) DEFAULT 0 COMMENT 'Thứ tự sắp xếp hiển thị',
  `is_active` tinyint(1) DEFAULT 1 COMMENT 'Trạng thái: 1=Hiển thị, 0=Ẩn'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_sidebar`
--

INSERT INTO `tbl_sidebar` (`sidebar_id`, `menu_name`, `menu_link`, `icon_class`, `required_permission`, `sort_order`, `is_active`) VALUES
(1, 'Dashboard', 'dashboard', 'fa-solid fa-chart-pie', NULL, 1, 1),
(2, 'Đăng ký Đề tài Mới', 'dang-ky-de-tai', 'fa-solid fa-file-signature', 'REGISTER_PROJECT', 2, 1),
(3, 'Quản lý Các Phân hệ', 'quan-ly-phan-he', 'fa-solid fa-folder-tree', 'MANAGE_SUP_PROJ', 3, 1),
(4, 'Đăng ký Tài khoản', 'dang-ky-tai-khoan', 'fa-solid fa-user-plus', 'MANAGE_ACCOUNT', 4, 1),
(5, 'Phân quyền Admin', 'phan-quyen', 'fa-solid fa-user-shield', 'ROLE_PERMISSION', 5, 1),
(6, 'Quản lý hướng dẫn', 'huong-dan', 'fa-solid fa-book-bookmark', 'MANAGE_GUIDE', 6, 1),
(7, 'Quản lý Người dùng', 'quan-ly-nguoi-dung', 'fa-solid fa-users-gear', 'MANAGE_ACCOUNT', 7, 1),
(8, 'Thùng Rác', 'thung-rac', 'fa-solid fa-trash-arrow-up', 'CONFIRM_CHECK', 10, 1),
(9, 'Cấu hình danh mục', 'cau-hinh-he-thong', 'fa-solid fa-sliders', 'CONFIG_SYSTEM', 8, 1),
(10, 'Lịch Sử Hệ Thống', 'lich-su', 'fa-solid fa-clock-rotate-left', 'DATA_LOG', 9, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_sub_system`
--

CREATE TABLE `tbl_sub_system` (
  `sub_system_id` int(11) NOT NULL,
  `system_code` varchar(50) NOT NULL,
  `system_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `icon_class` varchar(100) DEFAULT 'fa-solid fa-folder-open',
  `menu_link` varchar(255) DEFAULT 'r3_home.php',
  `show_in_r3` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1: Hiện trên Sidebar R3, 0: Ẩn'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_sub_system`
--

INSERT INTO `tbl_sub_system` (`sub_system_id`, `system_code`, `system_name`, `created_at`, `icon_class`, `menu_link`, `show_in_r3`) VALUES
(1, 'bai_bao', 'Bài báo', '2026-10-21 14:32:56', 'fa-solid fa-newspaper', 'bai-bao', 0),
(2, 'de_tai_sinh_vien', 'Đề tài sinh viên', '2026-10-21 14:32:56', 'fa-solid fa-folder-open', 'de-tai-nckh', 1),
(3, 'sang_kien', 'Sáng kiến', '2026-10-21 14:32:56', 'fa-regular fa-lightbulb', 'sang-kien', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_system_setting`
--

CREATE TABLE `tbl_system_setting` (
  `id` int(11) NOT NULL,
  `university_name` varchar(255) NOT NULL DEFAULT 'Trường Đại học An ninh nhân dân',
  `university_short_name` varchar(100) DEFAULT 'ANND',
  `system_title` varchar(255) NOT NULL DEFAULT 'Hệ thống Quản lý nhiệm vụ khoa học công nghệ và đổi mới sáng tạo',
  `logo_path` varchar(255) DEFAULT 'asset/img/logo.png',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_system_setting`
--

INSERT INTO `tbl_system_setting` (`id`, `university_name`, `university_short_name`, `system_title`, `logo_path`, `updated_at`) VALUES
(1, 'Học viện An ninh nhân dân', 'T01', 'Hệ thống Quản lý nhiệm vụ khoa học công nghệ và đổi mới sáng tạo', 'asset/img/upload/school_logo_1792771851.png', '2026-10-23 16:10:51');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_user`
--

CREATE TABLE `tbl_user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT 'Người dùng',
  `avatar` varchar(255) DEFAULT NULL,
  `type_user` varchar(2) NOT NULL,
  `can_grant_permission` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `current_token` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_user`
--

INSERT INTO `tbl_user` (`user_id`, `username`, `password`, `name`, `avatar`, `type_user`, `can_grant_permission`, `created_at`, `status`) VALUES
(1, 'CB000000', '123456', 'Nguyễn Văn B', NULL, 'CB', 1, '2026-09-20 05:22:13', 1),
(2, 'GV000000', '123456', 'Nguyễn Thi Xà Nữ', 'asset/img/upload/avatar/avatar_2_1792928129.png', 'GV', 0, '2026-09-20 05:22:40', 1),
(4, 'LDABC123', '123456', 'Nguyễn Vũ Hoàng', NULL, 'LA', 1, '2026-09-20 08:59:40', 1),
(7, 'LDKYTHUAT', '$2y$10$BOSJmvGPHSQOT9K0ld4b/ekn5Mb7eujoLwYlXGwR40ZBYxjd/tCja', 'ADMIN KỸ THUẬT', NULL, '', 1, '2026-10-23 15:29:57', 1),
(8, 'CBQLTAIKHOAN01', '$2y$10$Pcjpe8hD1cDeS4ElYJN7DevDg.3OE5/.t07Fz8.qDeQjDYHwES33O', 'Nguyễn Văn Tuất', NULL, '', 1, '2026-10-25 13:41:48', 1);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `tbl_activity_logs`
--
ALTER TABLE `tbl_activity_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `idx_log_user` (`user_id`),
  ADD KEY `idx_log_date` (`created_at`);

--
-- Chỉ mục cho bảng `tbl_guides`
--
ALTER TABLE `tbl_guides`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_notifications_user` (`user_id`),
  ADD KEY `idx_notifications_project` (`project_id`);

--
-- Chỉ mục cho bảng `tbl_permission`
--
ALTER TABLE `tbl_permission`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tbl_project`
--
ALTER TABLE `tbl_project`
  ADD PRIMARY KEY (`project_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `register_type_id` (`register_type_id`),
  ADD KEY `level_id` (`level_id`),
  ADD KEY `project_type_id` (`project_type_id`),
  ADD KEY `sub_system_id` (`sub_system_id`),
  ADD KEY `security_level_id` (`security_level_id`),
  ADD KEY `fk_project_user_action` (`project_user_action_id`);

--
-- Chỉ mục cho bảng `tbl_project_file`
--
ALTER TABLE `tbl_project_file`
  ADD PRIMARY KEY (`file_id`),
  ADD KEY `project_id` (`project_id`);

--
-- Chỉ mục cho bảng `tbl_project_level`
--
ALTER TABLE `tbl_project_level`
  ADD PRIMARY KEY (`level_id`),
  ADD UNIQUE KEY `level_code` (`level_code`);

--
-- Chỉ mục cho bảng `tbl_project_member`
--
ALTER TABLE `tbl_project_member`
  ADD PRIMARY KEY (`member_id`),
  ADD KEY `project_id` (`project_id`);

--
-- Chỉ mục cho bảng `tbl_project_payment`
--
ALTER TABLE `tbl_project_payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `fk_payment_project` (`project_id`);

--
-- Chỉ mục cho bảng `tbl_project_register_type`
--
ALTER TABLE `tbl_project_register_type`
  ADD PRIMARY KEY (`register_type_id`),
  ADD UNIQUE KEY `type_code` (`type_code`);

--
-- Chỉ mục cho bảng `tbl_project_report`
--
ALTER TABLE `tbl_project_report`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `fk_report_project` (`project_id`),
  ADD KEY `fk_report_user` (`user_id`);

--
-- Chỉ mục cho bảng `tbl_project_security_level`
--
ALTER TABLE `tbl_project_security_level`
  ADD PRIMARY KEY (`security_level_id`),
  ADD UNIQUE KEY `security_code` (`security_code`);

--
-- Chỉ mục cho bảng `tbl_project_type`
--
ALTER TABLE `tbl_project_type`
  ADD PRIMARY KEY (`project_type_id`),
  ADD UNIQUE KEY `type_code` (`type_code`);

--
-- Chỉ mục cho bảng `tbl_role`
--
ALTER TABLE `tbl_role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_permission` (`user_id`,`permission_id`),
  ADD KEY `fk_role_permission` (`permission_id`);

--
-- Chỉ mục cho bảng `tbl_sidebar`
--
ALTER TABLE `tbl_sidebar`
  ADD PRIMARY KEY (`sidebar_id`);

--
-- Chỉ mục cho bảng `tbl_sub_system`
--
ALTER TABLE `tbl_sub_system`
  ADD PRIMARY KEY (`sub_system_id`),
  ADD UNIQUE KEY `system_code` (`system_code`);

--
-- Chỉ mục cho bảng `tbl_system_setting`
--
ALTER TABLE `tbl_system_setting`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `tbl_activity_logs`
--
ALTER TABLE `tbl_activity_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `tbl_guides`
--
ALTER TABLE `tbl_guides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `tbl_permission`
--
ALTER TABLE `tbl_permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `tbl_project`
--
ALTER TABLE `tbl_project`
  MODIFY `project_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `tbl_project_file`
--
ALTER TABLE `tbl_project_file`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `tbl_project_level`
--
ALTER TABLE `tbl_project_level`
  MODIFY `level_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `tbl_project_member`
--
ALTER TABLE `tbl_project_member`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT cho bảng `tbl_project_payment`
--
ALTER TABLE `tbl_project_payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `tbl_project_register_type`
--
ALTER TABLE `tbl_project_register_type`
  MODIFY `register_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `tbl_project_report`
--
ALTER TABLE `tbl_project_report`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `tbl_project_security_level`
--
ALTER TABLE `tbl_project_security_level`
  MODIFY `security_level_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `tbl_project_type`
--
ALTER TABLE `tbl_project_type`
  MODIFY `project_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `tbl_role`
--
ALTER TABLE `tbl_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT cho bảng `tbl_sidebar`
--
ALTER TABLE `tbl_sidebar`
  MODIFY `sidebar_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `tbl_sub_system`
--
ALTER TABLE `tbl_sub_system`
  MODIFY `sub_system_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `tbl_system_setting`
--
ALTER TABLE `tbl_system_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  ADD CONSTRAINT `fk_notifications_project` FOREIGN KEY (`project_id`) REFERENCES `tbl_project` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_notifications_user` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `tbl_project`
--
ALTER TABLE `tbl_project`
  ADD CONSTRAINT `fk_project_user_action` FOREIGN KEY (`project_user_action_id`) REFERENCES `tbl_user` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_project_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_project_ibfk_2` FOREIGN KEY (`register_type_id`) REFERENCES `tbl_project_register_type` (`register_type_id`),
  ADD CONSTRAINT `tbl_project_ibfk_3` FOREIGN KEY (`level_id`) REFERENCES `tbl_project_level` (`level_id`),
  ADD CONSTRAINT `tbl_project_ibfk_4` FOREIGN KEY (`project_type_id`) REFERENCES `tbl_project_type` (`project_type_id`),
  ADD CONSTRAINT `tbl_project_ibfk_5` FOREIGN KEY (`sub_system_id`) REFERENCES `tbl_sub_system` (`sub_system_id`),
  ADD CONSTRAINT `tbl_project_ibfk_6` FOREIGN KEY (`security_level_id`) REFERENCES `tbl_project_security_level` (`security_level_id`);

--
-- Các ràng buộc cho bảng `tbl_project_file`
--
ALTER TABLE `tbl_project_file`
  ADD CONSTRAINT `tbl_project_file_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `tbl_project` (`project_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tbl_project_member`
--
ALTER TABLE `tbl_project_member`
  ADD CONSTRAINT `tbl_project_member_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `tbl_project` (`project_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tbl_project_payment`
--
ALTER TABLE `tbl_project_payment`
  ADD CONSTRAINT `fk_payment_project` FOREIGN KEY (`project_id`) REFERENCES `tbl_project` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `tbl_project_report`
--
ALTER TABLE `tbl_project_report`
  ADD CONSTRAINT `fk_report_project` FOREIGN KEY (`project_id`) REFERENCES `tbl_project` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_report_user` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `tbl_role`
--
ALTER TABLE `tbl_role`
  ADD CONSTRAINT `fk_role_permission` FOREIGN KEY (`permission_id`) REFERENCES `tbl_permission` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_role_user` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

DELIMITER $$
--
-- Sự kiện
--
CREATE DEFINER=`root`@`localhost` EVENT `auto_clean_trash_projects` ON SCHEDULE EVERY 1 DAY STARTS '2026-10-23 09:42:27' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    DELETE FROM tbl_project 
    WHERE is_deleted = 1 AND deleted_at < (NOW() - INTERVAL 30 DAY);
END$$

CREATE DEFINER=`root`@`localhost` EVENT `test_quick_clean_trash` ON SCHEDULE EVERY 1 MINUTE STARTS '2026-10-23 09:51:22' ON COMPLETION NOT PRESERVE ENABLE DO DELETE FROM tbl_project 
    WHERE is_deleted = 1 AND deleted_at < (NOW() - INTERVAL 2 MINUTE)$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
