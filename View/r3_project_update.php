<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <base href="/QLNCKH/View/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh Sửa Hồ Sơ Nhiệm Vụ NCKH & ĐMST</title>
    <script src="../asset/script/auth_check.js"></script>
    <link rel="stylesheet" href="../asset/css/all.min.css">
    <link href="../asset/css/bootstrap5.css" rel="stylesheet">
    <link rel="stylesheet" href="../asset/css/style.css">
    <style>
        :root {
            --sidebar-width: 260px;
        }

        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            background-color: #f1f5f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* KHUNG VÀ NÚT TOGGLE SIDEBAR */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background-color: #1e3a8a;
            color: #fff;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar.collapsed {
            transform: translateX(-100%);
        }

        .btn-toggle-sidebar {
            position: absolute;
            top: 16px;
            right: -32px;
            width: 32px;
            height: 32px;
            background: #ffffff !important;
            color: #0f172a;
            border: 1px solid #cbd5e1;
            border-left: none;
            border-radius: 0 6px 6px 0;
            cursor: pointer;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s, border-color 0.2s;
            z-index: 1001;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.05);
        }

        .btn-toggle-sidebar:hover {
            color: #2563eb;
            border-color: #2563eb;
        }

        /* KHUNG NỘI DUNG CHÍNH */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1), width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-sizing: border-box;
        }

        .main-wrapper.expanded {
            margin-left: 0 !important;
            width: 100% !important;
        }

        /* TOPBAR CHUẨN */
        .top-header {
            padding-left: 45px !important;
            transition: padding-left 0.3s ease;
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-left: 10px;
            border-left: 1px solid #e2e8f0;
            cursor: pointer;
        }

        .avatar {
            width: 36px;
            height: 36px;
            background: #2563eb;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.9rem;
            overflow: hidden;
        }

        /* KHỐI FORM CARDS */
        .form-section-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            margin-bottom: 20px;
        }

        .form-section-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1e3a8a;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 10px;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .is-locked {
            background-color: #f1f5f9 !important;
            color: #475569 !important;
            font-weight: 700;
            border: 1px solid #cbd5e1 !important;
            cursor: not-allowed !important;
        }

        .select-readonly {
            pointer-events: none;
            background-color: #f1f5f9 !important;
            color: #475569 !important;
            font-weight: 600;
            cursor: not-allowed;
        }

        .file-box {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #3b82f6;
            border-radius: 8px;
            padding: 10px 14px;
            transition: all 0.2s;
        }

        .file-box:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .file-upload-zone {
            background: #f8fafc;
            border: 2px dashed #cbd5e1;
            border-radius: 10px;
            padding: 18px;
            transition: all 0.2s;
        }

        .file-upload-zone:hover {
            border-color: #2563eb;
            background: #eff6ff;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border: 1px solid #cbd5e1;
            background-color: #ffffff;
            color: #334155;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s;
            cursor: pointer;
        }

        .btn-back:hover {
            background-color: #f1f5f9;
            color: #0f172a;
        }

        /* TOAST FLOATING */
        .toast-floating-container {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 99999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .toast-card {
            min-width: 280px;
            max-width: 400px;
            background: #ffffff;
            border-radius: 8px;
            padding: 12px 16px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
            border-left: 4px solid #10b981;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.875rem;
            animation: slideInRight 0.25s ease-out;
        }

        .toast-card.danger {
            border-left-color: #ef4444;
        }

        .toast-card.warning {
            border-left-color: #f59e0b;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <!-- KHUNG CHỨA TOAST NOTIFICATION -->
    <div class="toast-floating-container" id="toastFloatingContainer"></div>

    <!-- SIDEBAR -->
    <?php include '../Model/r3_sidebar.php'; ?>

    <div class="main-wrapper" id="mainWrapper">
        <!-- TOPBAR -->
        <?php
        $pageTitle = "CHỈNH SỬA HỒ SƠ ĐỀ TÀI";
        $pageIcon = "fa-solid fa-pen-to-square";
        include '../Model/r3_topbar.php';
        ?>

        <main class="container-fluid px-4 py-3">
            <!-- NÚT QUAY LẠI VÀ HUY HIỆU TRẠNG THÁI -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <button type="button" class="btn-back" onclick="handleGoBack()">
                    <i class="fa-solid fa-arrow-left"></i> Quay lại
                </button>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-secondary px-3 py-2" id="displayProjStatus">Đang tải trạng thái...</span>
                    <span class="badge bg-primary px-3 py-2 fw-bold" id="displayProjCode">---</span>
                </div>
            </div>

            <form id="editProjectForm" onsubmit="handleSaveProject(event)">
                <input type="hidden" id="editProjectId" name="project_id">
                <input type="hidden" id="hiddenRegisterTypeId" name="register_type_id">

                <!-- 1. THÔNG TIN CHUNG VỀ NHIỆM VỤ -->
                <div class="form-section-card">
                    <div class="form-section-title">
                        <span><i class="fa-solid fa-circle-info me-2"></i>1. Thông Tin Chung Về Nhiệm Vụ Khoa Học Công Nghệ & ĐMST</span>
                        <span class="badge bg-light text-muted border fw-normal" style="font-size: 0.75rem;">
                            <i class="fa-solid fa-pen-to-square text-primary me-1"></i> Chế độ chỉnh sửa hồ sơ
                        </span>
                    </div>

                    <div class="row">
                        <!-- MÃ NHIỆM VỤ -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold text-secondary">
                                Mã Nhiệm Vụ / Đề Tài <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-sm is-locked" id="editProjCode" name="project_code" readonly>
                        </div>

                        <!-- HÌNH THỨC ĐĂNG KÝ (KHÓA THEO QUY CHUẨN) -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold text-secondary">
                                Loại Hình Đăng Ký <span class="text-danger">*</span>
                                <small class="text-muted fw-normal">(Cố định đối với GV)</small>
                            </label>
                            <select class="form-select form-select-sm select-readonly" id="editRegisterType" tabindex="-1" aria-disabled="true" required>
                                <option value="" selected disabled>-- Đang nạp danh mục --</option>
                            </select>
                        </div>

                        <!-- LOẠI ĐỀ TÀI -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold text-secondary">
                                Loại Đề Tài <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-sm" id="editProjSubSystem" name="sub_system_id" required onchange="handleR3SubSystemChange(this.value)">
                                <option value="" selected disabled>-- Chọn loại đề tài --</option>
                            </select>
                        </div>
                    </div>

                    <!-- TÊN NHIỆM VỤ -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">
                            Tên Nhiệm Vụ / Đề Tài <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" id="editProjTitle" name="project_name" rows="2" required placeholder="Nhập tên nhiệm vụ nghiên cứu khoa học..."></textarea>
                    </div>

                    <div class="row">
                        <!-- CẤP QUẢN LÝ (TỰ ĐỘNG THEO LOẠI ĐỀ TÀI) -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold text-secondary">
                                Cấp Quản Lý <span class="text-danger">*</span>
                                <small class="text-muted fw-normal">(Tự động theo loại)</small>
                            </label>
                            <select class="form-select form-select-sm select-readonly" id="editProjLevel" name="level_id" required tabindex="-1" aria-disabled="true">
                                <option value="" selected disabled>-- Đang xác định cấp quản lý --</option>
                            </select>
                        </div>

                        <!-- ĐỘ MẬT -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold text-secondary">
                                Độ Mật <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-sm" id="editProjSecurity" name="security_level_id" required>
                                <option value="" selected disabled>-- Chọn độ mật --</option>
                            </select>
                        </div>

                        <!-- DỰ TOÁN KINH PHÍ -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold text-secondary">Dự Toán Kinh Phí Đề Xuất (VNĐ)</label>
                            <input type="number" class="form-control form-control-sm" id="editProjBudget" name="estimated_budget" placeholder="Nhập số tiền..." min="0" step="500000">
                        </div>
                    </div>

                    <!-- THỜI HẠN & ĐỢT BÁO CÁO -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-secondary">
                                <i class="fa-regular fa-calendar-check me-1"></i>Thời hạn thực hiện (tháng) <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control form-control-sm" id="editProjDuration" name="duration_months" value="12" min="1" max="60" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-secondary">
                                <i class="fa-solid fa-list-ol me-1"></i>Đợt báo cáo tiến độ <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-sm" id="editProjReportPhases" name="report_phases" required>
                                <option value="2">2 đợt (Báo cáo giữa kỳ và cuối kỳ)</option>
                                <option value="3">3 đợt (Mỗi 4 tháng một lần)</option>
                                <option value="4">4 đợt (Định kỳ hàng quý)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- 2. DANH SÁCH THÀNH VIÊN THAM GIA -->
                <div class="form-section-card">
                    <div class="form-section-title">
                        <span><i class="fa-solid fa-users me-2"></i>2. Danh Sách Cá Nhân Tham Gia Thực Hiện (Tối đa 5 thành viên)</span>
                        <button type="button" class="btn btn-outline-primary btn-sm px-2 py-1 fs-7 d-flex align-items-center gap-1" id="btnAddMemberBtn" onclick="addMemberRow()">
                            <i class="fa-solid fa-user-plus"></i> Thêm thành viên
                        </button>
                    </div>

                    <div id="membersContainer" class="d-flex flex-column gap-2 mb-2">
                        <!-- Danh sách thành viên được nạp động từ CSDL -->
                    </div>
                </div>

                <!-- 3. TÀI LIỆU & HỒ SƠ MINH CHỨNG -->
                <div class="form-section-card">
                    <div class="form-section-title">
                        <span><i class="fa-solid fa-paperclip me-2"></i>3. Hồ Sơ Đăng Ký & Tài Liệu Minh Chứng</span>
                        <span class="badge bg-primary text-white fw-normal" style="font-size: 0.75rem;">Đính kèm tệp</span>
                    </div>

                    <!-- DANH SÁCH FILE HIỆN CÓ TRÊN HỆ THỐNG -->
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary mb-2">Tệp hồ sơ hiện có trong đề tài:</label>
                        <div id="existingFilesContainer" class="d-flex flex-column gap-2"></div>
                    </div>

                    <!-- KHU VỰC TẢI LÊN FILE MỚI BỔ SUNG -->
                    <div class="file-upload-zone">
                        <label for="editProjFiles" class="form-label small fw-bold text-primary d-flex align-items-center gap-1 mb-1">
                            <i class="fa-solid fa-cloud-arrow-up"></i> Tải lên bổ sung tệp đính kèm mới (.pdf, .docx, .zip)
                        </label>
                        <p class="text-muted small mb-2" style="font-size: 0.8rem;">
                            Có thể chọn cùng lúc nhiều file minh chứng, thuyết minh hoàn thiện hoặc công văn đề xuất.
                        </p>
                        <input type="file" id="editProjFiles" class="form-control form-control-sm" multiple accept=".pdf,.doc,.docx,.zip,.rar" onchange="previewNewFiles(this)">
                        <div id="newFilesPreview" class="d-flex flex-column gap-1 mt-2"></div>
                    </div>
                </div>

                <!-- NÚT THAO TÁC Ở CHÂN TRANG -->
                <div class="d-flex justify-content-end gap-3 mb-5">
                    <button type="button" class="btn btn-outline-secondary px-4 fw-semibold" onclick="handleGoBack()">
                        <i class="fa-solid fa-xmark me-1"></i> Hủy thay đổi
                    </button>
                    <button type="submit" class="btn btn-primary px-5 fw-bold shadow" id="btnSaveProject">
                        <i class="fa-solid fa-floppy-disk me-2"></i> Lưu Cập Nhật Hồ Sơ
                    </button>
                </div>
            </form>
        </main>
    </div>

    <!-- MODAL TOPBAR -->
    <?php include '../Model/r3_modal_topbar.php'; ?>

    <script src="../asset/script/bootstrap5.js"></script>
    <script src="../asset/script/sidebar.js"></script>

    <script>
        const API_URL = "../index_api.php";
        let currentProjectId = 0;
        let existingFiles = [];
        let deletedFileIds = [];
        let r3SubSystemLevelMapping = {};

        // Hàm giải mã lấy user_id từ JWT Token trong sessionStorage/localStorage
        function getCurrentUserId() {
            const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
            if (token) {
                try {
                    const base64Url = token.split('.')[1];
                    const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
                    const jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
                        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
                    }).join(''));
                    const payload = JSON.parse(jsonPayload);
                    return parseInt(payload.user_id || payload.id || 0);
                } catch (e) {
                    console.error("Lỗi đọc token:", e);
                }
            }
            return 0;
        }
        // HIỂN THỊ THÔNG BÁO TOAST TINH TẾ
        function showNotification(message, type = 'success') {
            const container = document.getElementById('toastFloatingContainer');
            if (!container) return;

            const toastCard = document.createElement('div');
            toastCard.className = `toast-card ${type === 'danger' ? 'danger' : (type === 'warning' ? 'warning' : '')}`;

            let iconHtml = '<i class="fa-solid fa-circle-check text-success fs-5"></i>';
            if (type === 'danger') iconHtml = '<i class="fa-solid fa-triangle-exclamation text-danger fs-5"></i>';
            else if (type === 'warning') iconHtml = '<i class="fa-solid fa-circle-exclamation text-warning fs-5"></i>';

            toastCard.innerHTML = `
                ${iconHtml}
                <div class="text-dark fw-semibold">${message}</div>
            `;

            container.appendChild(toastCard);

            setTimeout(() => {
                toastCard.style.transition = 'all 0.3s ease';
                toastCard.style.opacity = '0';
                toastCard.style.transform = 'translateX(100%)';
                setTimeout(() => toastCard.remove(), 300);
            }, 3000);
        }

        // XỬ LÝ QUAY LẠI TRANG TRƯỚC
        function handleGoBack() {
            if (document.referrer && document.referrer.includes(window.location.host)) {
                window.location.href = document.referrer;
            } else if (window.history.length > 1) {
                window.history.back();
            } else {
                window.location.href = '/QLNCKH/nhiem-vu-cua-toi';
            }
        }

        document.addEventListener("DOMContentLoaded", async function() {
            const urlParams = new URLSearchParams(window.location.search);
            currentProjectId = urlParams.get('id');

            if (!currentProjectId) {
                showNotification("Không tìm thấy mã định danh đề tài!", "danger");
                setTimeout(() => window.location.href = "/QLNCKH/nhiem-vu-cua-toi", 1500);
                return;
            }

            const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');

            // Nạp đồng thời các danh mục & cấu trúc mapping
            await Promise.all([
                loadDropdown('project_register_type_get', 'editRegisterType'),
                loadDropdown('project_level_get', 'editProjLevel'),
                loadDropdown('project_sup_get', 'editProjSubSystem'),
                loadDropdown('project_security_level_get', 'editProjSecurity'),
                loadR3Mapping(token)
            ]);

            // Nạp chi tiết dữ liệu đề tài
            await loadProjectData(currentProjectId);
        });

        async function loadR3Mapping(token) {
            try {
                const res = await fetch(`${API_URL}?detect=sub_system_level_get`, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                }).then(r => r.json());

                if (res.status === 'success' && res.data) {
                    if (res.data.mapping) {
                        r3SubSystemLevelMapping = res.data.mapping;
                    } else if (Array.isArray(res.data.list)) {
                        res.data.list.forEach(item => {
                            const subId = String(item.sub_system_id);
                            if (!r3SubSystemLevelMapping[subId] || parseInt(item.is_default) === 1) {
                                r3SubSystemLevelMapping[subId] = String(item.level_id);
                            }
                        });
                    }
                }
            } catch (e) {
                console.error("Lỗi nạp mapping cấp đề tài:", e);
            }
        }

        async function loadDropdown(detectAction, selectId) {
            const selectEl = document.getElementById(selectId);
            try {
                const res = await fetchWithAuth(`${API_URL}?detect=${detectAction}`);
                const result = await res.json();
                if (res.ok && result.status === 'success' && Array.isArray(result.data)) {
                    selectEl.innerHTML = '<option value="" selected disabled>-- Chọn --</option>';
                    result.data.forEach(item => {
                        const val = item.id !== undefined ? item.id : (item.level_id || item.sub_system_id || item.register_type_id || item.security_level_id);
                        const txt = item.name !== undefined ? item.name : (item.level_name || item.system_name || item.type_name || item.security_name);
                        selectEl.innerHTML += `<option value="${val}">${txt}</option>`;
                    });
                }
            } catch (e) {
                console.error(`Lỗi nạp danh mục ${detectAction}:`, e);
            }
        }

        function handleR3SubSystemChange(subSystemId) {
            const levelSelect = document.getElementById('editProjLevel');
            if (!levelSelect) return;

            const targetLevelId = r3SubSystemLevelMapping[String(subSystemId)];
            if (targetLevelId) {
                levelSelect.value = targetLevelId;
                levelSelect.classList.add('select-readonly');
            } else {
                levelSelect.classList.remove('select-readonly');
            }
        }

        async function loadProjectData(id) {
            try {
                const response = await fetchWithAuth(`${API_URL}?detect=project_get&project_id=${encodeURIComponent(id)}`);
                const result = await response.json();

                if (response.ok && result.status === 'success' && result.data) {
                    const data = result.data;

                    document.getElementById('editProjectId').value = data.project_id || id;
                    document.getElementById('displayProjCode').innerText = data.project_code || ('NV' + data.project_id);
                    document.getElementById('editProjCode').value = data.project_code || ('NV' + data.project_id);
                    document.getElementById('editProjTitle').value = data.project_name || '';

                    // Cập nhật trạng thái
                    const statusBadge = document.getElementById('displayProjStatus');
                    let statusLabel = "Chờ duyệt";
                    let statusClass = "bg-primary";
                    if (data.status === "running") {
                        statusLabel = "Đang tiến hành";
                        statusClass = "bg-success";
                    } else if (data.status === "awaiting_acceptance") {
                        statusLabel = "Chờ nghiệm thu";
                        statusClass = "bg-warning text-dark";
                    } else if (data.status === "completed") {
                        statusLabel = "Đã hoàn thành";
                        statusClass = "bg-info text-dark";
                    } else if (data.status === "rejected") {
                        statusLabel = "Từ chối";
                        statusClass = "bg-danger";
                    }

                    statusBadge.innerText = statusLabel;
                    statusBadge.className = `badge ${statusClass} px-3 py-2`;

                    // Gán Loại hình đăng ký
                    const regSelect = document.getElementById('editRegisterType');
                    const hiddenReg = document.getElementById('hiddenRegisterTypeId');
                    if (data.register_type_id) {
                        regSelect.value = data.register_type_id;
                        hiddenReg.value = data.register_type_id;
                    } else if (regSelect.options.length > 1) {
                        regSelect.selectedIndex = 1;
                        hiddenReg.value = regSelect.value;
                    }

                    // Gán Loại đề tài & Cấp quản lý
                    if (data.sub_system_id) {
                        document.getElementById('editProjSubSystem').value = data.sub_system_id;
                        handleR3SubSystemChange(data.sub_system_id);
                    }
                    if (data.level_id) {
                        document.getElementById('editProjLevel').value = data.level_id;
                    }

                    if (data.security_level_id) document.getElementById('editProjSecurity').value = data.security_level_id;
                    document.getElementById('editProjBudget').value = parseFloat(data.estimated_budget || 0);
                    document.getElementById('editProjDuration').value = parseInt(data.duration_months || 12);
                    if (data.report_phases) document.getElementById('editProjReportPhases').value = data.report_phases;

                    // Nạp tệp đính kèm hiện có
                    existingFiles = Array.isArray(data.files) ? data.files : [];
                    renderExistingFiles();

                    // Nạp danh sách thành viên tham gia
                    const container = document.getElementById('membersContainer');
                    container.innerHTML = '';

                    // Ưu tiên thông tin người thực hiện đề tài từ CSDL
                    const fallbackLeaderName = data.action_user_name || data.leader_name || '';
                    const fallbackLeaderUnit = data.action_user_unit || data.leader_unit || '';

                    if (Array.isArray(data.members) && data.members.length > 0) {
                        data.members.forEach((m, idx) => {
                            const isLeader = parseInt(m.is_leader) === 1 || idx === 0;
                            const memberName = isLeader ? (m.full_name || fallbackLeaderName) : m.full_name;
                            const memberUnit = isLeader ? (m.department || m.platoon || m.don_vi || fallbackLeaderUnit) : (m.department || m.platoon || m.don_vi || '');

                            addMemberRow(memberName, memberUnit, isLeader);
                        });
                    } else {
                        // Nếu chưa có thành viên, tự động tạo dòng Chủ nhiệm với thông tin của user thực hiện
                        addMemberRow(fallbackLeaderName, fallbackLeaderUnit, true);
                    }
                } else {
                    showNotification(result.message || "Không thể tải thông tin đề tài!", "danger");
                }
            } catch (err) {
                console.error("Lỗi nạp dữ liệu đề tài:", err);
                showNotification("Lỗi kết nối máy chủ!", "danger");
            }
        }

        function renderExistingFiles() {
            const container = document.getElementById('existingFilesContainer');
            container.innerHTML = '';

            if (existingFiles.length === 0) {
                container.innerHTML = `<div class="text-muted small fst-italic">Chưa có tệp đính kèm nào được lưu.</div>`;
                return;
            }

            existingFiles.forEach((fileObj, index) => {
                const fileBox = document.createElement('div');
                fileBox.className = 'file-box d-flex align-items-center justify-content-between';
                fileBox.innerHTML = `
                    <div class="d-flex align-items-center gap-2 text-truncate me-2">
                        <i class="fa-solid fa-file-lines text-primary fs-5"></i>
                        <span class="fw-semibold small text-dark text-truncate">${fileObj.file_name || fileObj.name || ('Tệp hồ sơ #' + (index + 1))}</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        ${fileObj.file_url ? `<a href="${fileObj.file_url}" target="_blank" class="btn btn-outline-primary btn-sm py-1 px-2" style="font-size: 0.75rem;"><i class="fa-regular fa-eye"></i> Xem</a>` : ''}
                        <button type="button" class="btn btn-outline-danger btn-sm py-1 px-2" style="font-size: 0.75rem;" onclick="removeExistingFile(${index})">
                            <i class="fa-solid fa-trash-can"></i> Xóa
                        </button>
                    </div>
                `;
                container.appendChild(fileBox);
            });
        }

        function removeExistingFile(index) {
            const fileObj = existingFiles[index];
            if (!fileObj) return;

            if (confirm(`Bạn có chắc muốn xóa tệp "${fileObj.file_name || 'này'}" khỏi hồ sơ?`)) {
                if (fileObj.file_id || fileObj.id) {
                    deletedFileIds.push(fileObj.file_id || fileObj.id);
                }
                existingFiles.splice(index, 1);
                renderExistingFiles();
            }
        }

        function previewNewFiles(input) {
            const previewContainer = document.getElementById('newFilesPreview');
            previewContainer.innerHTML = '';

            if (input.files && input.files.length > 0) {
                Array.from(input.files).forEach(file => {
                    const sizeInMB = (file.size / (1024 * 1024)).toFixed(2);
                    const item = document.createElement('div');
                    item.className = 'd-flex align-items-center justify-content-between p-2 bg-white border rounded shadow-sm';
                    item.style.fontSize = '0.8rem';
                    item.innerHTML = `
                        <div class="text-truncate me-2">
                            <i class="fa-regular fa-file-lines text-success me-1"></i>
                            <strong>${file.name}</strong>
                        </div>
                        <span class="badge bg-secondary">${sizeInMB} MB</span>
                    `;
                    previewContainer.appendChild(item);
                });
            }
        }

        function addMemberRow(fullName = '', unit = '', isLeader = false) {
            const container = document.getElementById('membersContainer');
            const currentRows = container.getElementsByClassName('member-item').length;

            if (currentRows >= 5) {
                showNotification("Mỗi nhiệm vụ chỉ cho phép tối đa 5 cá nhân tham gia!", "warning");
                return;
            }

            const memberDiv = document.createElement('div');
            memberDiv.className = 'member-item';

            if (isLeader) {
                memberDiv.innerHTML = `
            <div class="p-3 bg-light border border-primary border-opacity-25 rounded-3 mb-2">
                <div class="row g-3 align-items-center">
                    <div class="col-md-7">
                        <label class="form-label small fw-bold text-primary mb-1">
                            1. Chủ nhiệm nhiệm vụ / đề tài: <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control form-control-sm member-name is-locked" placeholder="Họ và tên Chủ nhiệm đề tài..." value="${fullName}" readonly required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-muted mb-1">Đơn vị / Khoa / Phòng ban: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm member-unit is-locked" placeholder="Nhập đơn vị công tác..." value="${unit}" readonly required>
                    </div>
                    <div class="col-md-1 text-end pt-3">
                        <button type="button" class="btn btn-sm text-muted border-0 disabled" title="Chủ nhiệm cố định theo người thực hiện"><i class="fa-solid fa-lock"></i></button>
                    </div>
                </div>
            </div>
        `;
            } else {
                const memberIndex = currentRows + 1;
                memberDiv.innerHTML = `
            <div class="row g-2 mb-2 align-items-center">
                <div class="col-md-7">
                    <input type="text" class="form-control form-control-sm member-name" placeholder="${memberIndex}. Thành viên tham gia nghiên cứu..." value="${fullName}" required>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control form-control-sm member-unit" placeholder="Đơn vị / Bộ môn (không bắt buộc)" value="${unit}">
                </div>
                <div class="col-md-1 text-end">
                    <button type="button" class="btn btn-sm text-danger border-0 p-1" onclick="this.closest('.member-item').remove()" title="Xóa thành viên"><i class="fa-solid fa-trash-can"></i></button>
                </div>
            </div>
        `;
            }
            container.appendChild(memberDiv);
        }

        async function handleSaveProject(event) {
            event.preventDefault();

            const form = event.target;
            const btnSave = document.getElementById('btnSaveProject');
            const memberElements = document.querySelectorAll('#membersContainer .member-item');
            const membersList = [];

            // Lấy chính xác user_id của tài khoản GV
            const currentUserId = getCurrentUserId();

            memberElements.forEach((item, index) => {
                const nameInput = item.querySelector('.member-name');
                const unitInput = item.querySelector('.member-unit');
                const nameVal = nameInput ? nameInput.value.trim() : '';
                const unitVal = unitInput ? unitInput.value.trim() : '';
                const isLeader = (index === 0 ? 1 : 0);

                if (nameVal) {
                    membersList.push({
                        user_id: isLeader ? currentUserId : null,
                        full_name: nameVal,
                        don_vi: unitVal, // Tên Khoa / Phòng ban
                        trung_doi: unitVal,
                        is_chu_nhiem: isLeader
                    });
                }
            });

            if (membersList.length === 0) {
                showNotification("Vui lòng nhập thông tin Chủ nhiệm đề tài!", "warning");
                return;
            }

            const formData = new FormData();
            formData.append('project_id', document.getElementById('editProjectId').value);
            formData.append('project_code', document.getElementById('editProjCode').value.trim());
            formData.append('project_name', document.getElementById('editProjTitle').value.trim());
            formData.append('register_type_id', document.getElementById('hiddenRegisterTypeId').value || document.getElementById('editRegisterType').value);
            formData.append('level_id', document.getElementById('editProjLevel').value);
            formData.append('sub_system_id', document.getElementById('editProjSubSystem').value);
            formData.append('security_level_id', document.getElementById('editProjSecurity').value);
            formData.append('estimated_budget', document.getElementById('editProjBudget').value || 0);
            formData.append('duration_months', document.getElementById('editProjDuration').value || 12);
            formData.append('report_phases', document.getElementById('editProjReportPhases').value || 2);
            formData.append('danh_sach_thanh_vien', JSON.stringify(membersList));
            formData.append('deleted_file_ids', JSON.stringify(deletedFileIds));

            const fileInput = document.getElementById('editProjFiles');
            if (fileInput && fileInput.files.length > 0) {
                for (let i = 0; i < fileInput.files.length; i++) {
                    formData.append('files[]', fileInput.files[i]);
                }
            }

            try {
                btnSave.disabled = true;
                btnSave.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status"></span>Đang lưu hồ sơ...`;

                const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
                const response = await fetch(`${API_URL}?detect=project_update`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    },
                    body: formData
                });

                const result = await response.json();

                if (response.ok && result.status === 'success') {
                    showNotification(result.message || "Cập nhật hồ sơ đề tài thành công!", "success");
                    setTimeout(() => {
                        window.location.href = `/QLNCKH/chi-tiet-de-tai?id=${encodeURIComponent(currentProjectId)}`;
                    }, 900);
                } else {
                    showNotification("Lỗi: " + (result.message || "Không thể cập nhật hồ sơ!"), "danger");
                    btnSave.disabled = false;
                    btnSave.innerHTML = `<i class="fa-solid fa-floppy-disk me-2"></i> Lưu Cập Nhật Hồ Sơ`;
                }
            } catch (err) {
                console.error("Lỗi cập nhật đề tài:", err);
                showNotification("Lỗi kết nối máy chủ!", "danger");
                btnSave.disabled = false;
                btnSave.innerHTML = `<i class="fa-solid fa-floppy-disk me-2"></i> Lưu Cập Nhật Hồ Sơ`;
            }
        }
    </script>
</body>

</html>