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
            <!-- TAB: ĐĂNG KÝ TÀI KHOẢN -->
            <div id="tab-taikhoan" role="tabpanel">
                <div class="card shadow-sm border-0 col-md-6 mx-auto">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 fw-bold text-primary">
                            <i class="fa-solid fa-user-plus me-2"></i> Đăng Ký Tài Khoản Mới
                        </h6>
                    </div>
                    <div class="card-body">
                        <!-- Thông báo phản hồi từ API -->
                        <div id="alertMessage" class="alert d-none" role="alert"></div>
                        <form id="formDangKyTaiKhoan">
                            <!-- Input Chọn Loại người dùng -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Loại người dùng
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="userType" name="user_type" required>
                                    <option value="" disabled selected>-- Chọn loại người dùng --</option>
                                    <option value="LD">Lãnh đạo</option>
                                    <option value="CB">Cán bộ Phòng</option>
                                    <option value="GV">Giảng viên Khoa</option>
                                </select>
                            </div>
                            <!-- BỔ SUNG: Chọn Khoa / Phòng ban từ tbl_org_unit -->
                            <div class="mb-3" id="groupOrgUnit">
                                <label class="form-label fw-semibold">
                                    Đơn vị / Khoa / Phòng <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="orgUnit" name="unit_id" required>
                                    <option value="" disabled selected>-- Đang nạp danh sách Khoa/Phòng... --</option>
                                </select>
                                <div class="form-text">Dùng để phân loại và thống kê theo từng Khoa/Phòng công tác.</div>
                            </div>
                            <!-- Input Mã định danh (Cho phép Chữ hoa, chữ thường, số - Không cho ký tự đặc biệt) -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Mã định danh
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text fw-bold text-primary" id="prefixAddon">--</span>
                                    <input type="text" class="form-control" id="rawUsername" name="username" required pattern="[a-zA-Z0-9]{6,}" minlength="6" placeholder="Nhập mã định danh (Ví dụ: 000001, UserA1)">
                                </div>
                                <div class="form-text">Chỉ chấp nhận chữ cái và số (không bao gồm ký tự đặc biệt), ít nhất 6 chữ số.</div>
                                <div class="form-text">
                                    Tên đăng nhập hoàn chỉnh:
                                    <strong id="previewUsername" class="text-success">...</strong>
                                </div>
                            </div>
                            <!-- Input Mật khẩu -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Mật khẩu (Password)
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="password" class="form-control" id="password" name="password" required minlength="6" placeholder="Nhập mật khẩu (ít nhất 6 ký tự)...">
                            </div>
                            <button type="submit" class="btn btn-success w-100" id="btnSubmit">
                                <i class="fa-solid fa-plus-circle me-2"></i>
                                Khởi Tạo Tài Khoản
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap 5 JS -->
    <script src="../asset/script/bootstrap5.js"></script>
    <script src="../asset/script/sidebar.js"></script>
    <script>
        const API_URL = "/QLNCKH/index_api.php";

        // 2. Xử lý UI Tiền tố tự động & Preview Username
        const userTypeSelect = document.getElementById('userType');
        const prefixAddon = document.getElementById('prefixAddon');
        const rawUsernameInput = document.getElementById('rawUsername');
        const previewUsername = document.getElementById('previewUsername');
        const orgUnitSelect = document.getElementById('orgUnit');

        // 1. Nạp danh sách Khoa/Phòng từ tbl_org_unit qua API
        async function loadOrgUnits() {
            try {
                const response = await fetchWithAuth(`${API_URL}?detect=org_unit_get`, {
                    method: 'GET'
                });
                const result = await response.json();

                if (response.ok && result.status === 'success' && Array.isArray(result.data)) {
                    orgUnitSelect.innerHTML = '<option value="" disabled selected>-- Chọn Khoa / Phòng ban --</option>';
                    result.data.forEach(item => {
                        const uId = item.unit_id || item.id;
                        const uName = item.unit_name || item.name;
                        const uCode = item.unit_code ? ` [${item.unit_code}]` : '';
                        orgUnitSelect.innerHTML += `<option value="${uId}">${uName}${uCode}</option>`;
                    });
                } else {
                    orgUnitSelect.innerHTML = '<option value="" disabled>Không thể tải danh sách đơn vị</option>';
                }
            } catch (e) {
                console.error("Lỗi nạp danh sách Khoa/Phòng:", e);
                orgUnitSelect.innerHTML = '<option value="" disabled>Lỗi kết nối danh mục đơn vị</option>';
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            loadOrgUnits();
        });

        // 2. Xử lý UI Tiền tố tự động & Preview Username
        function updatePreview() {
            const prefix = userTypeSelect.value || '--';
            prefixAddon.innerText = prefix;

            rawUsernameInput.value = rawUsernameInput.value.replace(/[^a-zA-Z0-9]/g, '');
            let cleanPart = rawUsernameInput.value.replace(/^(LD|CB|GV)/i, '');

            if (cleanPart && userTypeSelect.value) {
                previewUsername.innerText = prefix + cleanPart;
            } else {
                previewUsername.innerText = '...';
            }
        }

        userTypeSelect.addEventListener('change', updatePreview);
        rawUsernameInput.addEventListener('input', updatePreview);

        // 3. Xử lý Gửi Form qua AJAX API (Kèm unit_id và type_user)
        document.getElementById('formDangKyTaiKhoan').addEventListener('submit', async function(e) {
            e.preventDefault();

            const alertDiv = document.getElementById('alertMessage');
            const btnSubmit = document.getElementById('btnSubmit');

            const userType = userTypeSelect.value;
            const unitId = orgUnitSelect.value; // Lấy unit_id đã chọn
            const rawUsername = rawUsernameInput.value.trim();
            const password = document.getElementById('password').value.trim();

            if (!userType) {
                alertDiv.className = 'alert alert-warning';
                alertDiv.innerText = 'Vui lòng chọn loại người dùng!';
                alertDiv.classList.remove('d-none');
                return;
            }

            if (!unitId) {
                alertDiv.className = 'alert alert-warning';
                alertDiv.innerText = 'Vui lòng chọn Đơn vị / Khoa / Phòng ban!';
                alertDiv.classList.remove('d-none');
                return;
            }

            if (!/^[a-zA-Z0-9]+$/.test(rawUsername) || rawUsername.length < 6) {
                alertDiv.className = 'alert alert-warning';
                alertDiv.innerText = 'Mã định danh phải có ít nhất 6 ký tự và không chứa ký tự đặc biệt!';
                alertDiv.classList.remove('d-none');
                return;
            }

            if (password.length < 6) {
                alertDiv.className = 'alert alert-warning';
                alertDiv.innerText = 'Mật khẩu phải có độ dài ít nhất 6 ký tự!';
                alertDiv.classList.remove('d-none');
                return;
            }

            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Đang xử lý...';

            try {
                const response = await fetchWithAuth(`${API_URL}?detect=register_user`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json; charset=UTF-8'
                    },
                    body: JSON.stringify({
                        user_type: userType,
                        type_user: userType, // Gửi đồng bộ trường type_user
                        unit_id: parseInt(unitId), // Gửi unit_id lên server
                        username: rawUsername,
                        password: password
                    })
                });

                const result = await response.json();

                if (response.ok && result.status === 'success') {
                    alertDiv.className = 'alert alert-success';
                    alertDiv.innerText = `${result.message} Username: ${result.data.username}`;
                    alertDiv.classList.remove('d-none');
                    document.getElementById('formDangKyTaiKhoan').reset();
                    updatePreview();
                } else {
                    alertDiv.className = 'alert alert-danger';
                    alertDiv.innerText = result.message || 'Đã có lỗi xảy ra!';
                    alertDiv.classList.remove('d-none');
                }
            } catch (error) {
                alertDiv.className = 'alert alert-danger';
                alertDiv.innerText = 'Lỗi kết nối tới hệ thống!';
                alertDiv.classList.remove('d-none');
            } finally {
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = '<i class="fa-solid fa-plus-circle me-2"></i>Khởi Tạo Tài Khoản';
            }
        });
    </script>
</body>

</html>
