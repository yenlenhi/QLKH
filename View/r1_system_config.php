<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <!-- THÊM DÒNG NÀY ĐỂ ĐỊNH VỊ GỐC CHO ĐƯỜNG DẪN TƯƠNG ĐỐI -->
    <base href="/QLNCKH/View/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cấu Hình Hệ Thống & Danh Mục Chung - QL NCKH</title>
    <script src="../asset/script/auth_check.js"></script>
    <!-- Bootstrap 5 CSS -->
    <link href="../asset/css/bootstrap5.css" rel="stylesheet">
    <!-- FontAwesome Icon -->
    <link rel="stylesheet" href="../asset/css/all.min.css">
    <link rel="stylesheet" href="../asset/css/style_admin.css">
    <style>
        :root {
            --sidebar-width: 260px;
            --primary-color: #0d6efd;
            --dark-color: #1e293b;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
        }

        #main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
        }

        .nav-tabs .nav-link {
            color: #475569;
            font-weight: 600;
            border-top: 3px solid transparent;
        }

        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            border-top-color: var(--primary-color);
            background-color: #fff;
        }
    </style>
</head>

<body>
    <!-- Sidebar Menu -->
    <?php include '../Model/sidebar.php'; ?>

    <!-- Main Content -->
    <div id="main-content">
        <!-- Topbar -->
        <?php include '../Model/r1_topbar.php'; ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="m-0 text-secondary fw-bold">
                    <i class="fa-solid fa-sliders text-primary me-2"></i>Cấu Hình Hệ Thống & Danh Mục Chung
                </h4>
                <small class="text-muted">Quản lý các danh mục cơ sở phục vụ đăng ký và phân loại đề tài NCKH</small>
            </div>
            <div>
                <button type="button" class="btn btn-primary btn-sm px-3 fw-bold" id="btnAddCategoryItem" onclick="openAddModal()">
                    <i class="fa-solid fa-plus me-1"></i> Thêm Mục Mới
                </button>
            </div>
        </div>

        <!-- TABS DANH MỤC -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 pt-3 px-3 pb-0">
                <ul class="nav nav-tabs card-header-tabs" id="configTabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-type="level" type="button">
                            <i class="fa-solid fa-layer-group me-1"></i> Cấp Đề Tài
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-type="type" type="button">
                            <i class="fa-solid fa-shapes me-1"></i> Loại Hình
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-type="register_type" type="button">
                            <i class="fa-solid fa-file-pen me-1"></i> Hình Thức Đăng Ký
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-type="sub_system" type="button">
                            <i class="fa-solid fa-folder-tree me-1"></i> Phân Hệ NCKH
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-type="security" type="button">
                            <i class="fa-solid fa-shield-halved me-1"></i> Độ Mật
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-type="general_setting" type="button">
                            <i class="fa-solid fa-school me-1"></i> Thông Tin Trường & Logo
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-type="sidebar_menu" type="button">
                            <i class="fa-solid fa-bars me-1"></i> Quản Lý Sidebar
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body p-4">
                <!-- KHUNG QUẢN LÝ 5 BẢNG DANH MỤC -->
                <div id="categoryTableContainer">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0" id="tableCategory">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 70px;" class="text-center">STT</th>
                                    <th style="width: 250px;">Mã định danh (Code)</th>
                                    <th>Tên hiển thị</th>
                                    <th style="width: 200px;">Thời điểm tạo</th>
                                    <th style="width: 140px;" class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fa-solid fa-spinner fa-spin me-2"></i>Đang tải dữ liệu...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- KHUNG TAB: THÔNG TIN TRƯỜNG & LOGO -->
                <div id="generalSettingContainer" style="display: none;">
                    <form id="formGeneralSetting" enctype="multipart/form-data">
                        <div class="row g-4">
                            <div class="col-md-7">
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-secondary">Tên đơn vị / Trường đại học <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="cfgUniversityName" name="university_name" required placeholder="VD: Trường Đại học An ninh nhân dân">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-secondary">Tên viết tắt / Tên hiển thị ngắn gọn</label>
                                    <input type="text" class="form-control" id="cfgShortName" name="university_short_name" placeholder="VD: ANND">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-secondary">Tên hệ thống phần mềm <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="cfgSystemTitle" name="system_title" required placeholder="VD: Hệ thống Quản lý nhiệm vụ khoa học công nghệ và đổi mới sáng tạo">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-secondary">Chọn tệp Logo mới (PNG, JPG, SVG, WebP)</label>
                                    <input type="file" class="form-control" id="cfgLogoFile" name="logo_file" accept="image/*" onchange="previewLogo(this)">
                                    <small class="text-muted">Logo mặc định: <code>logoT04.png</code>.</small>
                                </div>
                                <div class="mt-4">
                                    <button type="button" class="btn btn-primary px-4 fw-bold" onclick="saveGeneralSetting()">
                                        <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Cấu Hình Đơn Vị
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-5 text-center border-start">
                                <label class="form-label fw-bold small text-secondary d-block mb-3">Logo hiện tại / Bản xem trước</label>
                                <div class="p-3 border rounded bg-light d-inline-block shadow-sm" style="min-width: 180px; min-height: 180px;">
                                    <img id="imgLogoPreview" src="../asset/img/logoT04.png" alt="Logo Trường" style="max-height: 160px; max-width: 100%; object-fit: contain;">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- KHUNG QUẢN LÝ SIDEBAR -->
                <div id="sidebarMenuContainer" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-secondary m-0">Danh sách các menu trên thanh điều hướng</h6>
                        <button type="button" class="btn btn-success btn-sm fw-bold" onclick="openAddSidebarModal()">
                            <i class="fa-solid fa-plus me-1"></i> Thêm Menu Mới
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0" id="tableSidebar">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 70px;" class="text-center">Thứ tự</th>
                                    <th style="width: 70px;" class="text-center">Icon</th>
                                    <th>Tên Menu</th>
                                    <th>Đường dẫn file (Link)</th>
                                    <th>Quyền yêu cầu</th>
                                    <th style="width: 120px;" class="text-center">Trạng thái</th>
                                    <th style="width: 130px;" class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4"><i class="fa-solid fa-spinner fa-spin me-2"></i>Đang tải menu...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- MODAL THÊM / SỬA SIDEBAR -->
                <div class="modal fade" id="modalSidebar" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header bg-light">
                                <h5 class="modal-title fw-bold text-primary" id="modalSidebarTitle">Thêm Menu Sidebar</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body py-3">
                                <form id="formSidebar">
                                    <input type="hidden" id="sidebarId" value="">
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Tên hiển thị <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" id="sidebarName" required placeholder="VD: Thống kê báo cáo">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Tên file / Liên kết (Link) <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" id="sidebarLink" required placeholder="VD: r1_reports.php">
                                        <small class="text-muted">Tên file PHP tương ứng trong thư mục View.</small>
                                    </div>
                                    <!-- <div class="mb-3">
                                        <label class="form-label small fw-bold">FontAwesome Icon Class</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text"><i id="iconPreview" class="fa-solid fa-circle-question"></i></span>
                                            <input type="text" class="form-control" id="sidebarIcon" placeholder="VD: fa-solid fa-chart-line" onkeyup="document.getElementById('iconPreview').className = this.value || 'fa-solid fa-circle-question'">
                                        </div>
                                    </div> -->
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Chọn Biểu Tượng (Icon) <span class="text-danger">*</span></label>
                                        <input type="hidden" id="sidebarIcon" value="fa-solid fa-circle-question">

                                        <!-- Thanh hiển thị Icon đang chọn -->
                                        <div class="d-flex align-items-center mb-2 p-2 border rounded bg-light">
                                            <i id="selectedIconDisplay" class="fa-solid fa-circle-question text-primary fa-xl me-2" style="width: 25px; text-align: center;"></i>
                                            <span id="selectedIconName" class="small text-muted fw-semibold">Chưa chọn (Mặc định)</span>
                                        </div>

                                        <!-- Bảng chọn Icon trực quan -->
                                        <div class="border rounded p-2" style="max-height: 150px; overflow-y: auto; background-color: #fff;">
                                            <div class="d-flex flex-wrap gap-2" id="iconPickerList">
                                                <button type="button" class="btn btn-outline-secondary btn-sm icon-opt" data-icon="fa-solid fa-chart-pie" title="Tổng quan / Dashboard"><i class="fa-solid fa-chart-pie"></i></button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm icon-opt" data-icon="fa-solid fa-chart-line" title="Thống kê / Tiến độ"><i class="fa-solid fa-chart-line"></i></button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm icon-opt" data-icon="fa-solid fa-file-signature" title="Đăng ký đề tài"><i class="fa-solid fa-file-signature"></i></button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm icon-opt" data-icon="fa-solid fa-folder-tree" title="Phân hệ / Danh mục"><i class="fa-solid fa-folder-tree"></i></button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm icon-opt" data-icon="fa-solid fa-book-bookmark" title="Hướng dẫn / Biểu mẫu"><i class="fa-solid fa-book-bookmark"></i></button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm icon-opt" data-icon="fa-solid fa-graduation-cap" title="Đào tạo / Sinh viên"><i class="fa-solid fa-graduation-cap"></i></button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm icon-opt" data-icon="fa-solid fa-user-plus" title="Tạo tài khoản"><i class="fa-solid fa-user-plus"></i></button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm icon-opt" data-icon="fa-solid fa-user-shield" title="Phân quyền bảo mật"><i class="fa-solid fa-user-shield"></i></button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm icon-opt" data-icon="fa-solid fa-users-gear" title="Quản lý người dùng"><i class="fa-solid fa-users-gear"></i></button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm icon-opt" data-icon="fa-solid fa-trash-arrow-up" title="Thùng rác"><i class="fa-solid fa-trash-arrow-up"></i></button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm icon-opt" data-icon="fa-solid fa-sack-dollar" title="Kinh phí / Tài chính"><i class="fa-solid fa-sack-dollar"></i></button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm icon-opt" data-icon="fa-solid fa-sliders" title="Cấu hình hệ thống"><i class="fa-solid fa-sliders"></i></button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm icon-opt" data-icon="fa-solid fa-bell" title="Thông báo"><i class="fa-solid fa-bell"></i></button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm icon-opt" data-icon="fa-solid fa-bullhorn" title="Thông tri / Quyết định"><i class="fa-solid fa-bullhorn"></i></button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm icon-opt" data-icon="fa-solid fa-award" title="Khen thưởng / Giải thưởng"><i class="fa-solid fa-award"></i></button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm icon-opt" data-icon="fa-solid fa-print" title="Báo cáo in ấn"><i class="fa-solid fa-print"></i></button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm icon-opt" data-icon="fa-solid fa-school" title="Đơn vị / Trường học"><i class="fa-solid fa-school"></i></button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm icon-opt" data-icon="fa-solid fa-circle-question" title="Mặc định / Khác"><i class="fa-solid fa-circle-question"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Quyền yêu cầu truy cập</label>
                                        <select class="form-select form-select-sm" id="sidebarPermission">
                                            <option value="">-- Đang tải danh sách quyền hệ thống... --</option>
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label small fw-bold">Thứ tự sắp xếp</label>
                                            <input type="number" class="form-control form-control-sm" id="sidebarOrder" value="10">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label small fw-bold">Trạng thái</label>
                                            <select class="form-select form-select-sm" id="sidebarActive">
                                                <option value="1">Hiển thị</option>
                                                <option value="0">Ẩn</option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer bg-light py-2">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
                                <button type="button" class="btn btn-primary btn-sm px-4 fw-bold" onclick="saveSidebarItem()">Lưu dữ liệu</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL THÊM / SỬA DANH MỤC -->
    <div class="modal fade" id="modalCategory" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold text-primary" id="modalCategoryTitle">Thêm mục danh mục</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3">
                    <form id="formCategory">
                        <input type="hidden" id="itemRecordId" value="">

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Mã định danh (Code) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="itemCode" placeholder="VD: cap_co_so, cap_bo..." required>
                            <small class="text-muted">Viết liền không dấu, dùng dấu gạch dưới (VD: de_tai_sv).</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Tên hiển thị <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="itemName" placeholder="VD: Cấp cơ sở..." required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="button" class="btn btn-primary btn-sm px-4 fw-bold" onclick="saveCategoryItem()">Lưu dữ liệu</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL XÁC NHẬN HÀNH ĐỘNG -->
    <div class="modal fade" id="modalConfirmAction" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow">
                <div class="modal-body text-center p-4">
                    <div id="confirmIcon" class="mb-3">
                        <i class="fa-solid fa-triangle-exclamation text-danger fa-3x"></i>
                    </div>
                    <h5 class="fw-bold mb-2" id="confirmTitle">Xác nhận</h5>
                    <p class="text-muted small mb-4" id="confirmMessage">Bạn có chắc chắn muốn thực hiện hành động này?</p>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Hủy</button>
                        <button type="button" class="btn btn-danger btn-sm px-3" id="btnConfirmExecute">Xác nhận</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TOAST THÔNG BÁO NỔI -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1090;">
        <div id="liveToast" class="toast align-items-center text-white border-0 shadow" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center">
                    <i id="toastIcon" class="fa-solid fa-circle-check me-2 fs-5"></i>
                    <span id="toastMessage">Thông báo</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="../asset/script/bootstrap5.js"></script>
    <script src="../asset/script/sidebar.js"></script>
    <script>
        const API_URL = "/QLNCKH/index_api.php";
        let currentTabType = "level";
        let categoryModal = null;
        let confirmModal = null;
        let sidebarModal = null;
        let pendingConfirmCallback = null;
        let toastInstance = null;
        let currentDataCache = [];
        let currentSidebarList = [];

        document.addEventListener("DOMContentLoaded", function() {
            categoryModal = new bootstrap.Modal(document.getElementById('modalCategory'));
            confirmModal = new bootstrap.Modal(document.getElementById('modalConfirmAction'));
            const modalSidebarEl = document.getElementById('modalSidebar');
            if (modalSidebarEl) {
                sidebarModal = new bootstrap.Modal(modalSidebarEl);
            }

            document.getElementById('btnConfirmExecute').addEventListener('click', function() {
                if (typeof pendingConfirmCallback === 'function') {
                    pendingConfirmCallback();
                }
                if (confirmModal) confirmModal.hide();
            });

            // LẮNG NGHE SỰ KIỆN CHUYỂN TAB DUY NHẤT
            const tabButtons = document.querySelectorAll('#configTabs button[data-bs-toggle="tab"]');
            tabButtons.forEach(btn => {
                btn.addEventListener('shown.bs.tab', function(e) {
                    currentTabType = e.target.getAttribute('data-type');
                    const btnAdd = document.getElementById('btnAddCategoryItem');
                    const catContainer = document.getElementById('categoryTableContainer');
                    const genContainer = document.getElementById('generalSettingContainer');
                    const sideContainer = document.getElementById('sidebarMenuContainer');

                    // Ẩn tất cả các khung trước khi hiển thị khung được chọn
                    if (catContainer) catContainer.style.display = 'none';
                    if (genContainer) genContainer.style.display = 'none';
                    if (sideContainer) sideContainer.style.display = 'none';
                    if (btnAdd) btnAdd.style.display = 'none';

                    if (currentTabType === 'general_setting') {
                        if (genContainer) genContainer.style.display = 'block';
                        loadGeneralSetting();
                    } else if (currentTabType === 'sidebar_menu') {
                        if (sideContainer) sideContainer.style.display = 'block';
                        loadSidebarAdminData(); // Gọi đúng API quản lý sidebar
                    } else {
                        if (btnAdd) btnAdd.style.display = 'inline-block';
                        if (catContainer) catContainer.style.display = 'block';
                        loadCategoryData(currentTabType); // Gọi 5 bảng danh mục đề tài
                    }
                });
            });

            // Khởi tạo tải dữ liệu tab đầu tiên
            loadCategoryData(currentTabType);
            // Kiểm tra quyền MANAGE_SIDEBAR: Nếu không có thì ẩn hẳn tab Quản lý Sidebar
            fetchWithAuth(`${API_URL}?detect=sidebar_admin_get`, {
                    method: 'GET'
                })
                .then(res => res.json())
                .then(res => {
                    if (res.status !== 'success') {
                        const tabSidebarBtn = document.querySelector('button[data-type="sidebar_menu"]');
                        if (tabSidebarBtn) {
                            const parentLi = tabSidebarBtn.closest('.nav-item');
                            if (parentLi) parentLi.style.display = 'none';
                        }
                    }
                })
                .catch(() => {
                    const tabSidebarBtn = document.querySelector('button[data-type="sidebar_menu"]');
                    if (tabSidebarBtn) {
                        const parentLi = tabSidebarBtn.closest('.nav-item');
                        if (parentLi) parentLi.style.display = 'none';
                    }
                });
        });

        function showNotification(message, type = "success") {
            const toastEl = document.getElementById('liveToast');
            const toastMsg = document.getElementById('toastMessage');
            const toastIcon = document.getElementById('toastIcon');
            if (!toastEl) return;

            toastEl.className = 'toast align-items-center text-white border-0 shadow';
            if (type === "success") {
                toastEl.classList.add('bg-success');
                toastIcon.className = 'fa-solid fa-circle-check me-2 fs-5';
            } else if (type === "danger" || type === "error") {
                toastEl.classList.add('bg-danger');
                toastIcon.className = 'fa-solid fa-circle-xmark me-2 fs-5';
            } else {
                toastEl.classList.add('bg-warning', 'text-dark');
                toastIcon.className = 'fa-solid fa-triangle-exclamation me-2 fs-5';
            }

            toastMsg.textContent = message;
            if (!toastInstance) {
                toastInstance = new bootstrap.Toast(toastEl, {
                    delay: 3500
                });
            }
            toastInstance.show();
        }

        // ==========================================
        // 1. XỬ LÝ 5 BẢNG DANH MỤC ĐỀ TÀI
        // ==========================================
        async function loadCategoryData(tabType) {
            const tbody = document.querySelector("#tableCategory tbody");
            tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted py-4"><i class="fa-solid fa-spinner fa-spin me-2"></i>Đang tải dữ liệu...</td></tr>`;

            try {
                const response = await fetchWithAuth(`${API_URL}?detect=system_category_get&type=${tabType}`, {
                    method: 'GET'
                });
                const result = await response.json();

                if (response.ok && result.status === 'success') {
                    currentDataCache = result.data || [];
                    renderTable(currentDataCache);
                } else {
                    tbody.innerHTML = `<tr><td colspan="5" class="text-center text-danger py-4">${result.message || 'Lỗi khi tải dữ liệu'}</td></tr>`;
                }
            } catch (err) {
                console.error("Lỗi tải danh mục:", err);
                tbody.innerHTML = `<tr><td colspan="5" class="text-center text-danger py-4">Không thể kết nối đến máy chủ.</td></tr>`;
            }
        }

        function renderTable(items) {
            const tbody = document.querySelector("#tableCategory tbody");
            tbody.innerHTML = "";

            if (!items || items.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted py-4">Chưa có dữ liệu cho mục này.</td></tr>`;
                return;
            }

            items.forEach((item, index) => {
                const tr = document.createElement("tr");
                tr.innerHTML = `
                <td class="text-center fw-bold">${index + 1}</td>
                <td><code>${escapeHtml(item.code)}</code></td>
                <td class="fw-semibold text-dark">${escapeHtml(item.name)}</td>
                <td class="text-muted small">${item.created_at || '---'}</td>
                <td class="text-center">
                    <button class="btn btn-sm btn-outline-primary me-1" onclick="openEditModal(${item.id})" title="Chỉnh sửa">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete(${item.id}, '${escapeHtml(item.name)}')" title="Xóa">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            `;
                tbody.appendChild(tr);
            });
        }

        function openAddModal() {
            document.getElementById('formCategory').reset();
            document.getElementById('itemRecordId').value = "";
            document.getElementById('modalCategoryTitle').innerText = "Thêm mục danh mục mới";
            categoryModal.show();
        }

        function openEditModal(id) {
            const item = currentDataCache.find(x => parseInt(x.id) === parseInt(id));
            if (!item) return;

            document.getElementById('itemRecordId').value = item.id;
            document.getElementById('itemCode').value = item.code;
            document.getElementById('itemName').value = item.name;
            document.getElementById('modalCategoryTitle').innerText = "Cập nhật mục danh mục";
            categoryModal.show();
        }

        async function saveCategoryItem() {
            const id = document.getElementById('itemRecordId').value;
            const code = document.getElementById('itemCode').value.trim();
            const name = document.getElementById('itemName').value.trim();

            if (!code || !name) {
                showNotification("Vui lòng nhập đầy đủ mã và tên danh mục!", "warning");
                return;
            }

            try {
                const response = await fetchWithAuth(`${API_URL}?detect=system_category_save`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        type: currentTabType,
                        id: id,
                        code: code,
                        name: name
                    })
                });
                const result = await response.json();

                if (response.ok && result.status === 'success') {
                    categoryModal.hide();
                    showNotification(result.message || "Lưu dữ liệu thành công!", "success");
                    loadCategoryData(currentTabType);
                } else {
                    showNotification("Lỗi: " + (result.message || "Không thể lưu"), "danger");
                }
            } catch (err) {
                console.error("Lỗi khi lưu danh mục:", err);
                showNotification("Lỗi kết nối máy chủ.", "danger");
            }
        }

        function confirmDelete(id, name) {
            document.getElementById('confirmTitle').innerText = "Xóa mục danh mục";
            document.getElementById('confirmMessage').innerText = `Bạn có chắc muốn xóa "${name}" không? Lưu ý: Nếu mục này đã được liên kết với đề tài, hệ thống sẽ ngăn chặn việc xóa.`;

            pendingConfirmCallback = async function() {
                try {
                    const response = await fetchWithAuth(`${API_URL}?detect=system_category_delete`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            type: currentTabType,
                            id: id
                        })
                    });
                    const result = await response.json();

                    if (response.ok && result.status === 'success') {
                        showNotification(result.message || "Đã xóa thành công!", "success");
                        loadCategoryData(currentTabType);
                    } else {
                        showNotification("Không thể xóa: " + result.message, "danger");
                    }
                } catch (err) {
                    console.error("Lỗi khi xóa:", err);
                    showNotification("Lỗi kết nối đến máy chủ.", "danger");
                }
            };

            confirmModal.show();
        }

        // ==========================================
        // 2. XỬ LÝ THÔNG TIN TRƯỜNG & LOGO
        // ==========================================
        function previewLogo(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imgLogoPreview').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        async function loadGeneralSetting() {
            try {
                const res = await fetchWithAuth(`${API_URL}?detect=system_setting_get`, {
                    method: 'GET'
                });
                const result = await res.json();
                if (res.ok && result.status === 'success' && result.data) {
                    document.getElementById('cfgUniversityName').value = result.data.university_name || '';
                    document.getElementById('cfgShortName').value = result.data.university_short_name || '';
                    document.getElementById('cfgSystemTitle').value = result.data.system_title || 'Hệ thống Quản lý nhiệm vụ khoa học công nghệ và đổi mới sáng tạo';
                    if (result.data.logo_path) {
                        document.getElementById('imgLogoPreview').src = '../' + result.data.logo_path;
                    }
                }
            } catch (err) {
                console.error("Lỗi lấy cấu hình trường:", err);
            }
        }

        async function saveGeneralSetting() {
            const uniName = document.getElementById('cfgUniversityName').value.trim();
            const sysTitle = document.getElementById('cfgSystemTitle').value.trim();
            if (!uniName) {
                showNotification("Vui lòng nhập tên trường!", "warning");
                return;
            }
            if (!sysTitle) {
                showNotification("Vui lòng nhập tên hệ thống!", "warning");
                return;
            }

            const formEl = document.getElementById('formGeneralSetting');
            const formData = new FormData(formEl);

            try {
                const res = await fetchWithAuth(`${API_URL}?detect=system_setting_save`, {
                    method: 'POST',
                    body: formData
                });
                const result = await res.json();

                if (res.ok && result.status === 'success') {
                    // Cập nhật lại cache public để trang login index.php đồng bộ ngay
                    if (result.data) {
                        localStorage.setItem("cached_public_system_setting", JSON.stringify(result.data));
                    }
                    showNotification(result.message || "Cập nhật thông tin thành công!", "success");
                    loadGeneralSetting();
                } else {
                    showNotification("Lỗi: " + (result.message || "Không thể cập nhật"), "danger");
                }
            } catch (err) {
                console.error("Lỗi lưu cấu hình:", err);
                showNotification("Lỗi kết nối khi lưu cấu hình.", "danger");
            }
        }

        // ==========================================
        // 3. XỬ LÝ QUẢN LÝ SIDEBAR
        // ==========================================
        function loadSidebarAdminData() {
            const tbody = document.querySelector("#tableSidebar tbody");
            tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted py-4"><i class="fa-solid fa-spinner fa-spin me-2"></i>Đang tải dữ liệu...</td></tr>`;

            fetchWithAuth(`${API_URL}?detect=sidebar_admin_get`, {
                    method: 'GET'
                })
                .then(res => res.json())
                .then(res => {
                    if (res.status === 'success') {
                        currentSidebarList = res.data || [];
                        renderSidebarTable(currentSidebarList);
                    } else {
                        tbody.innerHTML = `<tr><td colspan="7" class="text-center text-danger py-4">${res.message}</td></tr>`;
                    }
                })
                .catch(err => {
                    tbody.innerHTML = `<tr><td colspan="7" class="text-center text-danger py-4">Lỗi kết nối máy chủ.</td></tr>`;
                });
        }

        function renderSidebarTable(items) {
            const tbody = document.querySelector("#tableSidebar tbody");
            tbody.innerHTML = "";
            if (!items.length) {
                tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted py-4">Chưa có menu nào.</td></tr>`;
                return;
            }

            items.forEach(item => {
                const isActive = parseInt(item.is_active) === 1;
                const tr = document.createElement("tr");
                tr.innerHTML = `
                <td class="text-center fw-bold">${item.sort_order}</td>
                <td class="text-center"><i class="${escapeHtml(item.icon_class)} fa-lg text-primary"></i></td>
                <td class="fw-bold">${escapeHtml(item.menu_name)}</td>
                <td><code>${escapeHtml(item.menu_link)}</code></td>
                <td>${item.required_permission ? `<span class="badge bg-secondary">${escapeHtml(item.required_permission)}</span>` : '<span class="text-muted small">Công khai</span>'}</td>
                <td class="text-center">
                    <span class="badge ${isActive ? 'bg-success' : 'bg-danger'}">${isActive ? 'Hiển thị' : 'Đang ẩn'}</span>
                </td>
                <td class="text-center">
                    <button class="btn btn-sm btn-outline-primary me-1" onclick="openEditSidebarModal(${item.sidebar_id})"><i class="fa-solid fa-pen-to-square"></i></button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteSidebarItem(${item.sidebar_id}, '${escapeHtml(item.menu_name)}')"><i class="fa-solid fa-trash"></i></button>
                </td>
            `;
                tbody.appendChild(tr);
            });
        }

        // Hàm hỗ trợ chọn và highlight Icon trực quan
        function selectSidebarIcon(iconClass, iconTitle) {
            document.getElementById('sidebarIcon').value = iconClass;
            document.getElementById('selectedIconDisplay').className = `${iconClass} text-primary fa-xl me-2`;
            document.getElementById('selectedIconName').innerText = `Đang chọn: ${iconTitle || iconClass}`;

            // Đổi màu viền nút icon được chọn
            document.querySelectorAll('.icon-opt').forEach(btn => {
                if (btn.getAttribute('data-icon') === iconClass) {
                    btn.className = "btn btn-primary btn-sm icon-opt";
                } else {
                    btn.className = "btn btn-outline-secondary btn-sm icon-opt";
                }
            });
        }

        // Bắt sự kiện click chọn Icon khi trang vừa tải
        document.addEventListener("click", function(e) {
            const btn = e.target.closest('.icon-opt');
            if (btn) {
                selectSidebarIcon(btn.getAttribute('data-icon'), btn.getAttribute('title'));
            }
        });

        // Hàm gọi API lấy danh sách phân quyền từ bảng tbl_permission
        async function loadPermissionOptions(selectedValue = '') {
            const selectEl = document.getElementById('sidebarPermission');
            if (!selectEl) return;

            try {
                // Gọi API lấy danh sách quyền hệ thống (Giả sử endpoint hoặc detect là permission_get hoặc lấy từ tbl_permission)
                // Nếu hệ thống chưa có sẵn detect riêng cho permission, ta có thể tạo nhanh hoặc dùng detect chuẩn
                const res = await fetchWithAuth(`${API_URL}?detect=get_list_permission`, {
                    method: 'GET'
                });
                const result = await res.json();

                if (res.ok && result.status === 'success') {
                    let optionsHtml = `<option value="">-- Mọi người đều thấy (Không yêu cầu quyền) --</option>`;

                    (result.data || []).forEach(perm => {
                        const code = perm.permission_code;
                        const name = perm.permission_name || code;
                        const isSelected = (code === selectedValue) ? 'selected' : '';
                        optionsHtml += `<option value="${code}" ${isSelected}>${code} (${name})</option>`;
                    });

                    selectEl.innerHTML = optionsHtml;
                } else {
                    selectEl.innerHTML = `<option value="">-- Không thể tải danh sách quyền --</option>`;
                }
            } catch (e) {
                console.error("Lỗi tải danh sách quyền:", e);
                // Fallback dự phòng nếu có lỗi kết nối
                selectEl.innerHTML = `
            <option value="">-- Mọi người đều thấy (Không yêu cầu quyền) --</option>
            <option value="CONFIG_SYSTEM">CONFIG_SYSTEM (Cấu hình hệ thống & danh mục chung)</option>
            <option value="MANAGE_ACCOUNT">MANAGE_ACCOUNT (Quản lý tài khoản & phân quyền)</option>
            <option value="CONFIRM_CHECK">CONFIRM_CHECK (Phê duyệt nhiệm vụ NCKH)</option>
            <option value="MANAGE_SUP_PROJ">MANAGE_SUP_PROJ (Quản lý phân hệ)</option>
            <option value="MANAGE_GUIDE">MANAGE_GUIDE (Quản lý hướng dẫn)</option>
            <option value="REGISTER_PROJECT">REGISTER_PROJECT (Đăng ký đề tài NCKH)</option>
            <option value="MANAGE_FEE">MANAGE_FEE (Quản lý kinh phí & tài chính)</option>
            <option value="DATA_LOG">DATA_LOG (Xem lịch sử hoạt động hệ thống)</option>
        `;
            }
        }

        function openAddSidebarModal() {
            if (!sidebarModal) {
                sidebarModal = new bootstrap.Modal(document.getElementById('modalSidebar'));
            }
            document.getElementById('formSidebar').reset();
            document.getElementById('sidebarId').value = "";
            document.getElementById('modalSidebarTitle').innerText = "Thêm Menu Sidebar Mới";
            // Mặc định chọn icon đầu tiên
            selectSidebarIcon('fa-solid fa-chart-pie', 'Tổng quan / Dashboard');
            sidebarModal.show();
            loadPermissionOptions(); // Nạp danh sách quyền trống
        }

        function openEditSidebarModal(id) {
            if (!sidebarModal) {
                sidebarModal = new bootstrap.Modal(document.getElementById('modalSidebar'));
            }
            const item = currentSidebarList.find(x => parseInt(x.sidebar_id) === parseInt(id));
            if (!item) return;

            document.getElementById('sidebarId').value = item.sidebar_id;
            document.getElementById('sidebarName').value = item.menu_name;
            document.getElementById('sidebarLink').value = item.menu_link;
            document.getElementById('sidebarPermission').value = item.required_permission || '';
            document.getElementById('sidebarOrder').value = item.sort_order;
            document.getElementById('sidebarActive').value = item.is_active;

            // Gán và highlight icon hiện tại của menu
            const currentIcon = item.icon_class || 'fa-solid fa-circle-question';
            selectSidebarIcon(currentIcon, currentIcon);

            document.getElementById('modalSidebarTitle').innerText = "Cập nhật Menu Sidebar";
            sidebarModal.show();
            loadPermissionOptions(item.required_permission || ''); // Nạp danh sách và chọn sẵn quyền hiện tại của menu
        }

        async function saveSidebarItem() {
            const id = document.getElementById('sidebarId').value;
            const name = document.getElementById('sidebarName').value.trim();
            const link = document.getElementById('sidebarLink').value.trim();
            const icon = document.getElementById('sidebarIcon').value.trim();
            const perm = document.getElementById('sidebarPermission').value.trim();
            const order = document.getElementById('sidebarOrder').value;
            const active = document.getElementById('sidebarActive').value;

            if (!name || !link) {
                showNotification("Vui lòng nhập Tên và Link menu!", "warning");
                return;
            }

            try {
                const res = await fetchWithAuth(`${API_URL}?detect=sidebar_admin_save`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        sidebar_id: id,
                        menu_name: name,
                        menu_link: link,
                        icon_class: icon,
                        required_permission: perm,
                        sort_order: order,
                        is_active: active
                    })
                });
                const result = await res.json();
                if (res.ok && result.status === 'success') {
                    sidebarModal.hide();
                    // Xóa cache sidebar trên sessionStorage để thanh bên tự động đồng bộ lại ngay lập tức
                    const currentUserId = (typeof getUserIdFromToken === 'function' ? getUserIdFromToken() : null) || sessionStorage.getItem('user_id') || 'default';
                    sessionStorage.removeItem(`cached_sidebar_menus_${currentUserId}`);
                    sessionStorage.removeItem('cached_sidebar_menus');

                    showNotification(result.message, "success");
                    loadSidebarAdminData();
                } else {
                    showNotification(result.message || "Lỗi lưu dữ liệu", "danger");
                }
            } catch (e) {
                showNotification("Lỗi kết nối máy chủ", "danger");
            }
        }

        function deleteSidebarItem(id, name) {
            document.getElementById('confirmTitle').innerText = "Xóa Menu Sidebar";
            document.getElementById('confirmMessage').innerText = `Bạn có chắc chắn muốn xóa menu "${name}" không?`;

            pendingConfirmCallback = async function() {
                try {
                    const res = await fetchWithAuth(`${API_URL}?detect=sidebar_admin_delete`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            sidebar_id: id
                        })
                    });
                    const result = await res.json();
                    if (res.ok && result.status === 'success') {
                        const currentUserId = (typeof getUserIdFromToken === 'function' ? getUserIdFromToken() : null) || sessionStorage.getItem('user_id') || 'default';
                        sessionStorage.removeItem(`cached_sidebar_menus_${currentUserId}`);
                        sessionStorage.removeItem('cached_sidebar_menus');

                        showNotification(result.message, "success");
                        loadSidebarAdminData();
                    } else {
                        showNotification(result.message, "danger");
                    }
                } catch (e) {
                    showNotification("Lỗi xóa menu", "danger");
                }
            };

            confirmModal.show();
        }

        function escapeHtml(str) {
            if (!str) return '';
            return String(str)
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }
    </script>
</body>

</html>
