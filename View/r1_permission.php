<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <!-- THÊM DÒNG NÀY ĐỂ ĐỊNH VỊ GỐC CHO ĐƯỜNG DẪN TƯƠNG ĐỐI -->
    <base href="/QLNCKH/View/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Quản Lý NCKH - Admin Dashboard</title>
    <script src="../asset/script/auth_check.js"></script>
    <!-- Bootstrap 5 CSS -->
    <link href="../asset/css/bootstrap5.css" rel="stylesheet">
    <!-- FontAwesome Icon -->
    <link rel="stylesheet" href="../asset/css/all.min.css">
    <link rel="stylesheet" href="../asset/css/style_admin.css">
</head>

<body>
    <!-- Sidebar Menu -->
    <?php include '../Model/sidebar.php'; ?>

    <!-- Main Content -->
    <div id="main-content">
        <!-- Topbar -->
        <?php include '../Model/r1_topbar.php'; ?>

        <!-- Tab Content -->
        <div id="adminTabContent">
            <!-- TAB: DANH SÁCH TÀI KHOẢN & PHÂN QUYỀN -->
            <div id="tab-phanquyen" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 fw-bold text-primary">
                            <i class="fa-solid fa-user-shield me-2"></i>Quản Lý & Phân Quyền Tài Khoản (Đã cấp quyền Quản trị)
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle border text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-start">Tên Đăng Nhập (Username)</th>
                                        <th class="text-start">Họ và Tên</th>
                                        <th>Loại Tài Khoản</th>
                                        <th>Trạng Thái Quyền</th>
                                        <th>Trạng Thái TK</th>
                                        <th class="text-center">Thao Tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="6" class="py-4 text-muted"><i class="fa-solid fa-spinner fa-spin me-2"></i>Đang tải dữ liệu...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL 1: PHÂN QUYỀN CHI TIẾT -->
    <div class="modal fade" id="modalPhanQuyen" tabindex="-1" aria-labelledby="modalPhanQuyenLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalPhanQuyenLabel">
                        <i class="fa-solid fa-user-gear me-2"></i>Cấu Hình Quyền Hạn Cho Tài Khoản:
                        <span id="modalUsername" class="fw-bold text-warning"></span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">Tick chọn các quyền hạn tương ứng bạn muốn cấp cho tài khoản này:</p>
                    <form id="formPhanQuyenModal">
                        <input type="hidden" id="permUserId">
                        <div class="row g-3"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary fw-bold" id="btnSavePermission" onclick="savePermission()">
                        <i class="fa-solid fa-floppy-disk me-1"></i>Lưu Thay Đổi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL 2: CẬP NHẬT TÀI KHOẢN -->
    <div class="modal fade" id="modalCapNhat" tabindex="-1" aria-labelledby="modalCapNhatLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold" id="modalCapNhatLabel">
                        <i class="fa-solid fa-user-pen me-2"></i>Cập Nhật Thông Tin Tài Khoản
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formCapNhatTaiKhoan">
                        <input type="hidden" id="updateUserId" name="user_id">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tên đăng nhập (Username)</label>
                            <input type="text" class="form-control bg-light" id="displayUsername" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Họ và Tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="updateName" name="name" required placeholder="Nhập Họ và Tên...">
                        </div>

                        <!-- BỔ SUNG TRƯỜNG CHỌN KHOA / PHÒNG / BAN -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Khoa / Phòng / Ban</label>
                            <select class="form-select" id="updateUnitId" name="unit_id">
                                <option value="">-- Chưa phân công / Trực thuộc trường --</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Mật khẩu mới</label>
                            <input type="password" class="form-control" id="updatePassword" name="password" placeholder="Để trống nếu không muốn đổi mật khẩu">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-warning fw-bold" onclick="saveUserUpdate()">
                        <i class="fa-solid fa-check me-1"></i>Cập Nhật
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL 3: XÁC NHẬN HÀNH ĐỘNG (THAY THẾ CONFIRM LOCALHOST) -->
    <div class="modal fade" id="modalConfirmAction" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow">
                <div class="modal-body text-center p-4">
                    <div id="confirmIcon" class="mb-3">
                        <i class="fa-solid fa-triangle-exclamation text-warning fa-3x"></i>
                    </div>
                    <h5 class="fw-bold mb-2" id="confirmTitle">Xác nhận</h5>
                    <p class="text-muted small mb-4" id="confirmMessage">Bạn có chắc chắn muốn thực hiện hành động này?</p>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Hủy bỏ</button>
                        <button type="button" class="btn btn-danger btn-sm px-3" id="btnConfirmExecute">Xác nhận</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TOAST THÔNG BÁO TÙY BIẾN (THAY THẾ ALERT LOCALHOST) -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1090;">
        <div id="liveToast" class="toast align-items-center text-white border-0 shadow" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center">
                    <i id="toastIcon" class="fa-solid fa-circle-check me-2 fs-5"></i>
                    <span id="toastMessage">Nội dung thông báo</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="../asset/script/bootstrap5.js"></script>
    <script src="../asset/script/sidebar.js"></script>
    <!-- File Script riêng biệt của trang phân quyền -->
    <script src="../asset/script/r1_permission.js"></script>
</body>

</html>