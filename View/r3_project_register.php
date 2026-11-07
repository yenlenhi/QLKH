<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <base href="/QLNCKH/View/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../asset/script/auth_check.js"></script>
    <link rel="stylesheet" href="../asset/css/all.min.css">
    <link href="../asset/css/bootstrap5.css" rel="stylesheet">
    <link rel="stylesheet" href="../asset/css/style.css">
    <title>Hồ Sơ Nhiệm Vụ Khoa Học Công Nghệ & ĐMST</title>
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

        .file-upload-row {
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 12px;
            transition: all 0.2s;
        }

        .file-upload-row:hover {
            border-color: #3b82f6;
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
        }

        .toast-card.danger {
            border-left-color: #ef4444;
        }
    </style>
</head>

<body>
    <div class="toast-floating-container" id="toastFloatingContainer"></div>

    <!-- SIDEBAR R3 -->
    <?php include '../Model/r3_sidebar.php'; ?>

    <div class="main-wrapper" id="mainWrapper">
        <!-- TOPBAR -->
        <?php
        $pageTitle = "HỒ SƠ NHIỆM VỤ NCKH & ĐMST";
        $pageIcon = "fa-solid fa-file-signature";
        include '../Model/r3_topbar.php';
        ?>

        <main class="container-fluid px-4 py-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <button type="button" class="btn-back" onclick="handleGoBack()">
                    <i class="fa-solid fa-arrow-left"></i> Quay lại
                </button>
                <span class="badge px-3 py-2" id="badgeFormMode">Đang nạp trạng thái...</span>
            </div>

            <form id="formFullProjectRegister" onsubmit="handleFormRegisterSubmit(event)">
                <input type="hidden" id="targetTaskId" name="target_task_id" value="">
                <input type="hidden" id="hiddenRegisterTypeId" name="register_type_id" value="">
                <input type="hidden" id="regLeaderUserId" name="leader_user_id" value="">

                <!-- 1. THÔNG TIN CHUNG VỀ NHIỆM VỤ -->
                <div class="form-section-card">
                    <div class="form-section-title">
                        <span><i class="fa-solid fa-circle-info me-2"></i>1. Thông Tin Chung Về Nhiệm Vụ Khoa Học Công Nghệ & ĐMST</span>
                        <span class="badge bg-light text-muted border fw-normal" id="badgeSectionLockStatus" style="font-size: 0.75rem;"></span>
                    </div>

                    <div class="row">
                        <!-- MÃ NHIỆM VỤ -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold text-secondary">
                                Mã Nhiệm Vụ / Đề Tài <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-sm" id="regProjCode" name="project_code" required placeholder="VD: NV2026-01...">
                        </div>

                        <!-- LOẠI ĐĂNG KÝ -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold text-secondary">
                                Loại Hình Đăng Ký <span class="text-danger">*</span>
                                <small class="text-muted fw-normal">(Cố định đối với GV)</small>
                            </label>
                            <select class="form-select form-select-sm select-readonly" id="regProjCategory" tabindex="-1" aria-disabled="true" required>
                                <option value="" selected disabled>-- Đang nạp danh mục --</option>
                            </select>
                        </div>

                        <!-- LOẠI ĐỀ TÀI -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold text-secondary">
                                Loại Nhiệm Vụ <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-sm" id="regProjSubSystem" name="sub_system_id" required onchange="handleR3SubSystemChange(this.value)">
                                <option value="" selected disabled>-- Chọn loại nhiệm vụ --</option>
                            </select>
                        </div>
                    </div>

                    <!-- TÊN NHIỆM VỤ -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">
                            Tên Nhiệm Vụ / Đề Tài <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" id="regProjTitle" name="project_name" rows="2" required placeholder="Nhập tên nhiệm vụ khoa học và đổi mới sáng tạo..."></textarea>
                    </div>

                    <div class="row">
                        <!-- CẤP QUẢN LÝ -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold text-secondary">
                                Cấp Quản Lý <span class="text-danger">*</span>
                                <small class="text-muted fw-normal">(Tự động theo loại)</small>
                            </label>
                            <select class="form-select form-select-sm select-readonly" id="regProjLevel" name="level_id" required tabindex="-1" aria-disabled="true">
                                <option value="" selected disabled>-- Vui lòng chọn loại đề tài trước --</option>
                            </select>
                        </div>

                        <!-- ĐỘ MẬT -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold text-secondary">
                                Độ Mật <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-sm" id="regProjSecurity" name="security_level_id" required>
                                <option value="" selected disabled>-- Chọn độ mật --</option>
                            </select>
                        </div>

                        <!-- DỰ TOÁN KINH PHÍ -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold text-secondary">Dự Toán Kinh Phí Đề Xuất (VNĐ)</label>
                            <input type="number" class="form-control form-control-sm" id="regProjBudget" name="estimated_budget" placeholder="Nhập số tiền..." min="0" step="500000">
                        </div>
                    </div>

                    <!-- THỜI HẠN & ĐỢT BÁO CÁO -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-secondary">
                                <i class="fa-regular fa-calendar-check me-1"></i>Thời hạn thực hiện (tháng) <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control form-control-sm" id="regProjDuration" name="duration_months" value="12" min="1" max="60" required placeholder="VD: 12">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-secondary">
                                <i class="fa-solid fa-list-ol me-1"></i>Đợt báo cáo tiến độ <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-sm" id="regProjReportPhases" name="report_phases" required>
                                <option value="2" selected>2 đợt (Báo cáo giữa kỳ và cuối kỳ)</option>
                                <option value="3">3 đợt</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- 2. THÀNH VIÊN THỰC HIỆN -->
                <div class="form-section-card">
                    <div class="form-section-title">
                        <span><i class="fa-solid fa-users me-2"></i>2. Danh Sách Cá Nhân Tham Gia Thực Hiện (Tối đa 5 thành viên)</span>
                        <span class="badge bg-primary text-white fw-normal" style="font-size: 0.75rem;">Chủ nhiệm mặc định theo tài khoản</span>
                    </div>

                    <div class="p-3 bg-light border border-primary border-opacity-25 rounded-3 mb-3">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-7">
                                <label class="form-label small fw-bold text-primary mb-1">
                                    1. Chủ nhiệm nhiệm vụ / đề tài: <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-sm is-locked" name="thanh_vien_1" id="regLeaderName" readonly required placeholder="Đang tải thông tin Chủ nhiệm...">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label small text-muted mb-1">Đơn vị / Khoa / Phòng ban: <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm is-locked" name="don_vi_1" id="regLeaderUnit" readonly required placeholder="Đang tải đơn vị công tác...">
                            </div>
                        </div>
                    </div>

                    <div class="row g-2 mb-2 align-items-center">
                        <div class="col-md-7">
                            <input type="text" class="form-control form-control-sm" name="thanh_vien_2" placeholder="2. Thành viên tham gia nghiên cứu...">
                        </div>
                        <div class="col-md-5">
                            <input type="text" class="form-control form-control-sm" name="don_vi_2" placeholder="Đơn vị / Bộ môn (không bắt buộc)">
                        </div>
                    </div>

                    <div class="row g-2 mb-2 align-items-center">
                        <div class="col-md-7">
                            <input type="text" class="form-control form-control-sm" name="thanh_vien_3" placeholder="3. Thành viên tham gia nghiên cứu...">
                        </div>
                        <div class="col-md-5">
                            <input type="text" class="form-control form-control-sm" name="don_vi_3" placeholder="Đơn vị / Bộ môn (không bắt buộc)">
                        </div>
                    </div>

                    <div class="row g-2 mb-2 align-items-center">
                        <div class="col-md-7">
                            <input type="text" class="form-control form-control-sm" name="thanh_vien_4" placeholder="4. Thành viên tham gia nghiên cứu...">
                        </div>
                        <div class="col-md-5">
                            <input type="text" class="form-control form-control-sm" name="don_vi_4" placeholder="Đơn vị / Bộ môn (không bắt buộc)">
                        </div>
                    </div>

                    <div class="row g-2 mb-2 align-items-center">
                        <div class="col-md-7">
                            <input type="text" class="form-control form-control-sm" name="thanh_vien_5" placeholder="5. Thành viên tham gia nghiên cứu...">
                        </div>
                        <div class="col-md-5">
                            <input type="text" class="form-control form-control-sm" name="don_vi_5" placeholder="Đơn vị / Bộ môn (không bắt buộc)">
                        </div>
                    </div>
                </div>

                <!-- 3. HỒ SƠ & TÀI LIỆU MINH CHỨNG -->
                <div class="form-section-card">
                    <div class="form-section-title">
                        <span><i class="fa-solid fa-paperclip me-2"></i>3. Hồ Sơ Đăng Ký & Minh Chứng (Định dạng .pdf, .docx, .doc, .zip)</span>
                        <span class="badge bg-primary text-white fw-normal" style="font-size: 0.75rem;">Yêu cầu đính kèm tệp</span>
                    </div>

                    <div class="file-upload-row">
                        <label class="form-label small fw-bold text-dark mb-1">
                            1. Đơn đăng ký chủ trì thực hiện nhiệm vụ KHCN & ĐMST <span class="text-danger">*</span>
                        </label>
                        <input type="file" class="form-control form-control-sm" name="file_don_dang_ky" accept=".pdf,.doc,.docx" required>
                    </div>

                    <div class="file-upload-row">
                        <label class="form-label small fw-bold text-dark mb-1">
                            2. Thuyết minh nhiệm vụ KHCN & ĐMST <span class="text-danger">*</span>
                        </label>
                        <input type="file" class="form-control form-control-sm" name="file_thuyet_minh" accept=".pdf,.doc,.docx" required>
                    </div>

                    <div class="file-upload-row">
                        <label class="form-label small fw-bold text-dark mb-1">
                            3. Tài liệu chứng minh tư cách pháp lý của tổ chức chủ trì
                        </label>
                        <input type="file" class="form-control form-control-sm" name="file_tu_cach_phap_ly" accept=".pdf,.doc,.docx,.zip">
                    </div>

                    <div class="file-upload-row">
                        <label class="form-label small fw-bold text-dark mb-1">
                            4. Thông tin về năng lực của tổ chức đề xuất
                        </label>
                        <input type="file" class="form-control form-control-sm" name="file_nang_luc_to_chuc" accept=".pdf,.doc,.docx,.zip">
                    </div>

                    <div class="file-upload-row">
                        <label class="form-label small fw-bold text-dark mb-1">
                            5. Lý lịch của cá nhân đăng ký làm chủ nhiệm <span class="text-danger">*</span>
                        </label>
                        <input type="file" class="form-control form-control-sm" name="file_ly_lich_chu_nhiem" accept=".pdf,.doc,.docx" required>
                    </div>

                    <div class="file-upload-row mb-0">
                        <label class="form-label small fw-bold text-dark mb-1">
                            6. Hồ sơ, tài liệu khác (Cho phép chọn nhiều tệp đính kèm)
                        </label>
                        <input type="file" class="form-control form-control-sm" name="files_khac[]" multiple accept=".pdf,.doc,.docx,.zip,.rar">
                        <small class="text-muted" style="font-size: 0.75rem;">Giữ phím Ctrl để chọn cùng lúc nhiều file.</small>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 mb-5">
                    <button type="button" class="btn btn-outline-secondary px-4 fw-semibold" onclick="handleGoBack()">
                        <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
                    </button>
                    <button type="submit" class="btn btn-primary px-5 fw-bold shadow" id="btnSubmitFullForm">
                        <i class="fa-solid fa-paper-plane me-2"></i> Gửi Hồ Sơ Đăng Ký
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
        const API_URL = '/QLNCKH/index_api.php';

        if (typeof window.fetchWithAuth !== 'function') {
            window.fetchWithAuth = async function(url, options = {}) {
                const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
                options.headers = options.headers || {};
                if (token) {
                    if (options.headers instanceof Headers) {
                        options.headers.set('Authorization', `Bearer ${token}`);
                    } else {
                        options.headers['Authorization'] = `Bearer ${token}`;
                    }
                }
                return fetch(url, options);
            };
        }

        let r3SubSystemLevelMapping = {};

        function getCurrentUserId() {
            const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
            if (token) {
                try {
                    const payload = JSON.parse(atob(token.split('.')[1]));
                    return parseInt(payload.user_id || payload.id || 0);
                } catch (e) {}
            }
            return parseInt(sessionStorage.getItem('user_id') || 0);
        }

        function showNotification(message, type = 'success') {
            const container = document.getElementById('toastFloatingContainer');
            if (!container) return;

            const toastCard = document.createElement('div');
            toastCard.className = `toast-card ${type === 'danger' ? 'danger' : ''}`;
            const iconHtml = type === 'success' ?
                '<i class="fa-solid fa-circle-check text-success fs-5"></i>' :
                '<i class="fa-solid fa-triangle-exclamation text-danger fs-5"></i>';

            toastCard.innerHTML = `${iconHtml}<div class="text-dark fw-semibold">${message}</div>`;
            container.appendChild(toastCard);

            setTimeout(() => {
                toastCard.style.transition = 'all 0.3s ease';
                toastCard.style.opacity = '0';
                toastCard.style.transform = 'translateX(100%)';
                setTimeout(() => toastCard.remove(), 300);
            }, 3000);
        }

        function handleGoBack() {
            if (document.referrer && document.referrer.includes(window.location.host)) {
                window.location.href = document.referrer;
            } else if (window.history.length > 1) {
                window.history.back();
            } else {
                window.location.href = '/QLNCKH/nhiem-vu-de-xuat-thuc-hien';
            }
        }

        document.addEventListener("DOMContentLoaded", async function() {
            await Promise.all([
                loadR3Dropdown('project_register_type_get', 'regProjCategory'),
                loadR3Dropdown('project_level_get', 'regProjLevel'),
                loadR3Dropdown('project_sup_get', 'regProjSubSystem'),
                loadR3Dropdown('project_security_level_get', 'regProjSecurity'),
                loadR3Mapping(),
                autofillLeaderInfo()
            ]);

            await checkAndApplyMode();
            lockProposedRegisterType();
        });

        function lockProposedRegisterType() {
            const categorySelect = document.getElementById('regProjCategory');
            const hiddenCategory = document.getElementById('hiddenRegisterTypeId');
            if (!categorySelect) return;

            let foundVal = '';
            for (let i = 0; i < categorySelect.options.length; i++) {
                const optText = categorySelect.options[i].text.toLowerCase();
                if (optText.includes("đặt hàng đề xuất") || optText.includes("đề xuất")) {
                    categorySelect.selectedIndex = i;
                    foundVal = categorySelect.options[i].value;
                    break;
                }
            }

            if (!foundVal && categorySelect.options.length > 1) {
                categorySelect.selectedIndex = 1;
                foundVal = categorySelect.options[1].value;
            }

            hiddenCategory.value = foundVal;
            categorySelect.classList.add('select-readonly');
        }

        async function loadR3Mapping() {
            try {
                const res = await fetchWithAuth(`${API_URL}?detect=sub_system_level_get`).then(r => r.json());
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
                console.error("Lỗi nạp mapping R3:", e);
            }
        }

        async function loadR3Dropdown(detect, elId) {
            const el = document.getElementById(elId);
            if (!el) return;
            try {
                const res = await fetchWithAuth(`${API_URL}?detect=${detect}`).then(r => r.json());
                if (res.status === 'success' && Array.isArray(res.data)) {
                    el.innerHTML = '<option value="" selected disabled>-- Chọn --</option>';
                    res.data.forEach(item => {
                        const val = item.id !== undefined ? item.id : (item.level_id || item.sub_system_id || item.register_type_id || item.security_level_id);
                        const txt = item.name !== undefined ? item.name : (item.level_name || item.system_name || item.type_name || item.security_name);
                        el.innerHTML += `<option value="${val}">${txt}</option>`;
                    });
                }
            } catch (err) {
                console.error(`Lỗi dropdown ${detect}:`, err);
            }
        }

        function handleR3SubSystemChange(subSystemId) {
            const levelSelect = document.getElementById('regProjLevel');
            if (!levelSelect) return;

            const targetLevelId = r3SubSystemLevelMapping[String(subSystemId)];
            if (targetLevelId) {
                levelSelect.value = targetLevelId;
                levelSelect.classList.add('select-readonly');
            } else {
                levelSelect.value = "";
                levelSelect.classList.remove('select-readonly');
            }
        }

        async function autofillLeaderInfo() {
            const leaderNameInput = document.getElementById('regLeaderName');
            const leaderUnitInput = document.getElementById('regLeaderUnit');
            const leaderUserIdInput = document.getElementById('regLeaderUserId');

            try {
                let res = await fetchWithAuth(`${API_URL}?detect=get_user`).then(r => r.json());
                if (!res || res.status !== 'success' || !res.data) {
                    res = await fetchWithAuth(`${API_URL}?detect=cv_get`).then(r => r.json());
                }

                if (res && res.status === 'success' && res.data) {
                    const d = res.data;
                    const userId = d.user_id || getCurrentUserId();
                    const leaderName = d.name || '';
                    const leaderUnit = d.unit_name || (d.unit_code ? `Khoa / Đơn vị ${d.unit_code}` : 'Chưa phân công đơn vị');

                    if (leaderUserIdInput) leaderUserIdInput.value = userId;
                    if (leaderNameInput) {
                        leaderNameInput.value = leaderName;
                        leaderNameInput.classList.add('is-locked');
                    }
                    if (leaderUnitInput) {
                        leaderUnitInput.value = leaderUnit;
                        leaderUnitInput.classList.add('is-locked');
                    }
                }
            } catch (e) {
                console.error("Lỗi lấy thông tin Chủ nhiệm:", e);
            }
        }

        async function checkAndApplyMode() {
            const params = new URLSearchParams(window.location.search);
            const taskId = params.get('id') || params.get('project_id');
            const codeInput = document.getElementById('regProjCode');

            if (taskId) {
                document.getElementById('targetTaskId').value = taskId;
                document.getElementById('badgeFormMode').innerText = "Đăng ký nhiệm vụ có sẵn";
                document.getElementById('badgeFormMode').className = "badge bg-success px-3 py-2";
                document.getElementById('badgeSectionLockStatus').innerHTML = '<i class="fa-solid fa-lock text-warning me-1"></i> Mã nhiệm vụ cố định, các trường khác được phép điều chỉnh';

                try {
                    const res = await fetchWithAuth(`${API_URL}?detect=project_get&project_id=${encodeURIComponent(taskId)}`);
                    const result = await res.json();

                    if (res.ok && result.status === 'success' && result.data) {
                        const task = result.data;

                        codeInput.value = task.project_code || ('NV' + task.project_id);
                        codeInput.readOnly = true;
                        codeInput.classList.add('is-locked');

                        document.getElementById('regProjTitle').value = task.project_name || '';

                        if (task.estimated_budget) {
                            document.getElementById('regProjBudget').value = parseFloat(task.estimated_budget);
                        }
                        if (task.sub_system_id) {
                            document.getElementById('regProjSubSystem').value = task.sub_system_id;
                            handleR3SubSystemChange(task.sub_system_id);
                        }
                        if (task.level_id) {
                            document.getElementById('regProjLevel').value = task.level_id;
                        }
                        if (task.security_level_id) {
                            document.getElementById('regProjSecurity').value = task.security_level_id;
                        }
                        if (task.duration_months) {
                            document.getElementById('regProjDuration').value = task.duration_months;
                        }
                        if (task.report_phases) {
                            document.getElementById('regProjReportPhases').value = task.report_phases;
                        }
                    }
                } catch (err) {
                    console.error("Lỗi khi tải thông tin nhiệm vụ:", err);
                }
            } else {
                document.getElementById('targetTaskId').value = "";
                document.getElementById('badgeFormMode').innerText = "Đề xuất nhiệm vụ mới";
                document.getElementById('badgeFormMode').className = "badge bg-primary px-3 py-2";
                document.getElementById('badgeSectionLockStatus').innerHTML = '<i class="fa-solid fa-pen text-primary me-1"></i> Điền đầy đủ thông tin nhiệm vụ đề xuất';

                codeInput.readOnly = false;
                codeInput.classList.remove('is-locked');
            }
        }

        async function handleFormRegisterSubmit(event) {
            event.preventDefault();
            const form = event.target;
            const submitBtn = document.getElementById('btnSubmitFullForm');
            const currentUserId = parseInt(document.getElementById('regLeaderUserId').value) || getCurrentUserId();

            if (!currentUserId) {
                showNotification("Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại!", "danger");
                return;
            }

            const formData = new FormData(form);
            formData.append('project_user_action_id', currentUserId);

            const hiddenCategory = document.getElementById('hiddenRegisterTypeId');
            if (hiddenCategory && hiddenCategory.value) {
                formData.set('register_type_id', hiddenCategory.value);
            }

            const thanhVien = [];
            for (let i = 1; i <= 5; i++) {
                const nameInput = form.querySelector(`[name="thanh_vien_${i}"]`);
                const unitInput = form.querySelector(`[name="don_vi_${i}"]`);
                if (nameInput && nameInput.value.trim() !== "") {
                    const isLeader = (i === 1 ? 1 : 0);
                    thanhVien.push({
                        user_id: isLeader ? currentUserId : null,
                        full_name: nameInput.value.trim(),
                        don_vi: unitInput ? unitInput.value.trim() : "",
                        trung_doi: unitInput ? unitInput.value.trim() : "",
                        is_chu_nhiem: isLeader
                    });
                }
            }
            formData.append('danh_sach_thanh_vien', JSON.stringify(thanhVien));

            submitBtn.disabled = true;
            submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status"></span>Đang gửi hồ sơ...`;

            try {
                const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
                const res = await fetch(`${API_URL}?detect=project_create`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    },
                    body: formData
                });

                const result = await res.json();

                if (res.ok && result.status === 'success') {
                    showNotification(result.message || "Đăng ký đề tài thành công!", "success");
                    setTimeout(() => {
                        window.location.href = '/QLNCKH/nhiem-vu-de-xuat-thuc-hien';
                    }, 800);
                } else {
                    showNotification(result.message || "Không thể nộp hồ sơ, vui lòng kiểm tra lại!", "danger");
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = `<i class="fa-solid fa-paper-plane me-2"></i> Gửi Hồ Sơ Đăng Ký`;
                }
            } catch (err) {
                console.error("Lỗi gửi hồ sơ:", err);
                showNotification("Lỗi kết nối máy chủ. Vui lòng thử lại!", "danger");
                submitBtn.disabled = false;
                submitBtn.innerHTML = `<i class="fa-solid fa-paper-plane me-2"></i> Gửi Hồ Sơ Đăng Ký`;
            }
        }
    </script>
</body>

</html>