<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <base href="/QLNCKH/View/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Quản Lý NCKH - Admin Dashboard</title>
    <script src="../asset/script/auth_check.js"></script>
    <link href="../asset/css/bootstrap5.css" rel="stylesheet">
    <link rel="stylesheet" href="../asset/css/all.min.css">
    <link rel="stylesheet" href="../asset/css/style_admin.css">
    <script src="../asset/script/xlsx.full.min.js"></script>
    <style>
        .select-readonly {
            pointer-events: none;
            background-color: #e9ecef !important;
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
    </style>
</head>

<body>

    <?php include '../Model/sidebar.php'; ?>

    <div id="main-content">

        <?php include '../Model/r1_topbar.php'; ?>

        <main class="container-fluid px-4 py-3">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="m-0 fw-bold text-primary fs-6">
                        <i class="fa-solid fa-pen-to-square me-2"></i> Form Đăng Ký Đề Tài Nghiên Cứu Mới
                    </h6>
                    <button type="button" class="btn btn-success btn-sm px-3 shadow-sm fw-semibold d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalImportExcel">
                        <i class="fa-solid fa-file-excel fs-6"></i>
                        <span>Nhập từ Excel</span>
                    </button>
                </div>
                <div class="card-body p-4">
                    <div id="alertMessage" class="alert d-none" role="alert"></div>

                    <form id="formDangKyDeTai">
                        <!-- Ô ẨN LƯU USER_ID CỦA CHỦ NHIỆM ĐỀ TÀI -->
                        <input type="hidden" id="leaderUserId" name="leader_user_id" value="">

                        <!-- KHỐI 1: THÔNG TIN CƠ BẢN -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary">Mã Đề Tài <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="project_code" required placeholder="VD: DT2026-01...">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary">Loại Đăng Ký <span class="text-danger">*</span></label>
                                <select class="form-select" id="registerTypeId" name="register_type_id" required>
                                    <option value="" selected disabled>-- Đang tải dữ liệu --</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary">Giảng Viên Phụ Trách</label>
                                <select class="form-select" id="projectUserActionId" name="project_user_action_id" onchange="handleTeacherSelect(this.value)">
                                    <option value="" selected>-- Chọn giảng viên (không bắt buộc) --</option>
                                </select>
                            </div>
                        </div>

                        <!-- TÊN ĐỀ TÀI -->
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Tên đề tài NCKH <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="project_name" required placeholder="Nhập tên nhiệm vụ hoặc đề tài nghiên cứu...">
                        </div>

                        <!-- LOẠI ĐỀ TÀI & CẤP ĐỀ TÀI -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Loại Đề Tài <span class="text-danger">*</span></label>
                                <select class="form-select" id="subSystemId" name="sub_system_id" required onchange="handleSubSystemChange(this.value)">
                                    <option value="" selected disabled>-- Chọn loại đề tài --</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">
                                    Cấp Đề Tài <span class="text-danger">*</span>
                                    <small class="text-muted fw-normal">(Tự động theo loại đề tài)</small>
                                </label>
                                <select class="form-select select-readonly" id="levelId" name="level_id" required tabindex="-1" aria-disabled="true">
                                    <option value="" selected disabled>-- Vui lòng chọn loại đề tài trước --</option>
                                </select>
                            </div>
                        </div>

                        <!-- ĐỘ MẬT, KINH PHÍ, THỜI HẠN THỰC HIỆN, THỜI HẠN ĐĂNG KÝ, ĐỢT BÁO CÁO -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-secondary">Mức Độ Mật <span class="text-danger">*</span></label>
                                <select class="form-select" id="securityLevelId" name="security_level_id" required>
                                    <option value="" selected disabled>-- Đang tải --</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-secondary">Dự toán Kinh phí (VNĐ)</label>
                                <input type="number" class="form-control" name="estimated_budget" placeholder="Nhập số tiền..." min="0" step="500000">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-secondary">
                                    <i class="fa-regular fa-calendar-check me-1"></i>Thời hạn (tháng) <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control" name="duration_months" value="12" min="1" max="60" required placeholder="VD: 12">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-secondary">
                                    <i class="fa-solid fa-list-ol me-1"></i>Đợt báo cáo <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" name="report_phases" required>
                                    <option value="2" selected>2 đợt</option>
                                    <option value="3">3 đợt</option>
                                </select>
                            </div>
                            <!-- TRƯỜNG THỜI HẠN ĐĂNG KÝ (KHÔNG BẮT BUỘC) -->
                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-secondary">
                                    <i class="fa-regular fa-clock me-1"></i>Hạn đăng ký
                                </label>
                                <input type="date" class="form-control" name="registration_deadline" title="Hạn chót cho phép đăng ký đề tài">
                            </div>
                        </div>

                        <!-- DANH SÁCH THÀNH VIÊN -->
                        <div class="p-3 border rounded bg-light mb-4">
                            <h6 class="fw-bold text-secondary mb-3">
                                <i class="fa-solid fa-users me-2"></i>Danh Sách Thành Viên Tham Gia (Tối đa 5 thành viên)
                            </h6>

                            <div class="row g-2 mb-2 align-items-center">
                                <div class="col-md-7">
                                    <label class="form-label small fw-bold text-primary mb-1">1. Chủ nhiệm đề tài:</label>
                                    <input type="text" class="form-control form-control-sm" id="thanhVien1" name="thanh_vien_1" placeholder="Họ và tên Chủ nhiệm (tự động theo GV phụ trách hoặc tự nhập)...">
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label small text-muted mb-1">Đơn vị / Khoa / Phòng ban:</label>
                                    <input type="text" class="form-control form-control-sm" id="trungDoi1" name="trung_doi_1" placeholder="Khoa / Phòng / Đơn vị...">
                                </div>
                            </div>

                            <div class="row g-2 mb-2 align-items-center">
                                <div class="col-md-7">
                                    <input type="text" class="form-control form-control-sm" name="thanh_vien_2" placeholder="2. Thành viên tham gia...">
                                </div>
                                <div class="col-md-5">
                                    <input type="text" class="form-control form-control-sm" name="trung_doi_2" placeholder="Đơn vị / Bộ môn (không bắt buộc)">
                                </div>
                            </div>

                            <div class="row g-2 mb-2 align-items-center">
                                <div class="col-md-7">
                                    <input type="text" class="form-control form-control-sm" name="thanh_vien_3" placeholder="3. Thành viên tham gia...">
                                </div>
                                <div class="col-md-5">
                                    <input type="text" class="form-control form-control-sm" name="trung_doi_3" placeholder="Đơn vị / Bộ môn (không bắt buộc)">
                                </div>
                            </div>

                            <div class="row g-2 mb-2 align-items-center">
                                <div class="col-md-7">
                                    <input type="text" class="form-control form-control-sm" name="thanh_vien_4" placeholder="4. Thành viên tham gia...">
                                </div>
                                <div class="col-md-5">
                                    <input type="text" class="form-control form-control-sm" name="trung_doi_4" placeholder="Đơn vị / Bộ môn (không bắt buộc)">
                                </div>
                            </div>

                            <div class="row g-2 mb-2 align-items-center">
                                <div class="col-md-7">
                                    <input type="text" class="form-control form-control-sm" name="thanh_vien_5" placeholder="5. Thành viên tham gia...">
                                </div>
                                <div class="col-md-5">
                                    <input type="text" class="form-control form-control-sm" name="trung_doi_5" placeholder="Đơn vị / Bộ môn (không bắt buộc)">
                                </div>
                            </div>
                        </div>

                        <!-- HỒ SƠ MINH CHỨNG 6 MỤC (TẤT CẢ TÙY CHỌN CHO R1) -->
                        <div class="p-3 border rounded bg-light mb-4">
                            <h6 class="fw-bold text-secondary mb-3">
                                <i class="fa-solid fa-paperclip me-2"></i>Hồ Sơ Đăng Ký & Minh Chứng (Định dạng .pdf, .docx, .doc, .zip)
                            </h6>

                            <div class="file-upload-row">
                                <label class="form-label small fw-bold text-dark mb-1">
                                    1. Đơn đăng ký chủ trì thực hiện nhiệm vụ KHCN & ĐMST
                                </label>
                                <input type="file" class="form-control form-control-sm" name="file_don_dang_ky" accept=".pdf,.doc,.docx">
                            </div>

                            <div class="file-upload-row">
                                <label class="form-label small fw-bold text-dark mb-1">
                                    2. Thuyết minh nhiệm vụ KHCN & ĐMST
                                </label>
                                <input type="file" class="form-control form-control-sm" name="file_thuyet_minh" accept=".pdf,.doc,.docx">
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
                                    5. Lý lịch của cá nhân đăng ký làm chủ nhiệm
                                </label>
                                <input type="file" class="form-control form-control-sm" name="file_ly_lich_chu_nhiem" accept=".pdf,.doc,.docx">
                            </div>

                            <div class="file-upload-row mb-0">
                                <label class="form-label small fw-bold text-dark mb-1">
                                    6. Hồ sơ, tài liệu khác (Cho phép chọn nhiều tệp)
                                </label>
                                <input type="file" class="form-control form-control-sm" name="files_khac[]" multiple accept=".pdf,.doc,.docx,.zip,.rar">
                                <small class="text-muted" style="font-size: 0.75rem;">Giữ phím Ctrl để chọn cùng lúc nhiều file.</small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="reset" class="btn btn-outline-secondary px-4 py-2" onclick="handleResetForm()">
                                <i class="fa-solid fa-rotate-left me-1"></i> Làm mới
                            </button>
                            <button type="submit" id="btnSubmit" class="btn btn-primary px-4 py-2 fw-semibold">
                                <i class="fa-solid fa-paper-plane me-2"></i> Gửi Đăng Ký Đề Tài
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <!-- MODAL NHẬP FILE EXCEL HÀNG LOẠT -->
    <div class="modal fade" id="modalImportExcel" tabindex="-1" aria-labelledby="modalImportExcelLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-success text-white py-3">
                    <h6 class="modal-title fw-bold" id="modalImportExcelLabel">
                        <i class="fa-solid fa-file-excel me-2"></i> Nhập Đề Tài Hàng Loạt Từ Excel
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 d-flex align-items-center justify-content-between py-2 px-3 mb-3">
                        <div class="small">
                            <i class="fa-solid fa-circle-info me-1"></i> Tải file mẫu:
                        </div>
                        <a href="../asset/file/Danh_Sach_Nhiem_Vu_Dang_Ky_Mau.xlsx" download class="btn btn-sm btn-outline-primary bg-white fw-semibold">
                            <i class="fa-solid fa-download me-1"></i> Tải file mẫu (.xlsx)
                        </a>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Chọn tệp Excel danh sách đề tài (.xlsx, .xls) <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="excelFileInput" accept=".xlsx, .xls">
                    </div>

                    <div id="importStatus" class="d-none alert py-2 small mb-0"></div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-success btn-sm px-4 fw-semibold" id="btnExecuteImport" onclick="handleImportExcel()">
                        <i class="fa-solid fa-cloud-arrow-up me-1"></i> Bắt đầu nạp dữ liệu
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="../asset/script/bootstrap5.js"></script>
    <script src="../asset/script/sidebar.js"></script>

    <script>
        const API_URL = '/QLNCKH/index_api.php';
        let subSystemLevelMapping = {};

        document.addEventListener("DOMContentLoaded", async function() {
            const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');

            await Promise.all([
                loadDropdownData('project_register_type_get', 'registerTypeId', '-- Chọn loại đăng ký --'),
                loadDropdownData('project_level_get', 'levelId', '-- Cấp đề tài tự động chọn --'),
                loadDropdownData('project_sup_get', 'subSystemId', '-- Chọn loại đề tài --'),
                loadDropdownData('project_security_level_get', 'securityLevelId', '-- Chọn mức độ mật --'),
                loadDropdownData('user_teacher_get', 'projectUserActionId', '-- Chọn giảng viên (không bắt buộc) --'),
                loadSubSystemLevelMapping(token)
            ]);
        });

        async function loadSubSystemLevelMapping(token) {
            try {
                const response = await fetch(`${API_URL}?detect=sub_system_level_get`, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) return;

                const res = await response.json();
                if (res.status === 'success' && res.data) {
                    if (res.data.mapping) {
                        subSystemLevelMapping = res.data.mapping;
                    } else if (Array.isArray(res.data.list)) {
                        res.data.list.forEach(item => {
                            const subId = String(item.sub_system_id);
                            if (!subSystemLevelMapping[subId] || parseInt(item.is_default) === 1) {
                                subSystemLevelMapping[subId] = String(item.level_id);
                            }
                        });
                    }
                }
            } catch (e) {
                console.error("Lỗi nạp ánh xạ cấp đề tài:", e);
            }
        }

        async function loadDropdownData(detectAction, elementId, defaultText) {
            const selectElement = document.getElementById(elementId);
            if (!selectElement) return;

            try {
                const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
                const response = await fetch(`${API_URL}?detect=${detectAction}`, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });

                const result = await response.json();
                if (response.ok && result.status === 'success' && Array.isArray(result.data)) {
                    selectElement.innerHTML = `<option value="">${defaultText}</option>`;
                    result.data.forEach(item => {
                        const val = item.id !== undefined ? item.id : (item.level_id || item.sub_system_id || item.register_type_id || item.security_level_id || item.user_id);
                        const txt = item.name !== undefined ? item.name : (item.level_name || item.system_name || item.type_name || item.security_name);
                        // Lưu kèm dữ liệu đơn vị/phòng ban nếu có sẵn trong danh mục
                        const unitTxt = item.unit_name || item.department_name || item.don_vi || '';
                        selectElement.innerHTML += `<option value="${val}" data-name="${txt}" data-unit="${unitTxt}">${txt}</option>`;
                    });
                } else {
                    selectElement.innerHTML = `<option value="">Lỗi tải dữ liệu</option>`;
                }
            } catch (err) {
                console.error(`Lỗi tải danh mục ${detectAction}:`, err);
                selectElement.innerHTML = `<option value="">Không thể kết nối máy chủ</option>`;
            }
        }

        function handleSubSystemChange(subSystemId) {
            const levelSelect = document.getElementById('levelId');
            if (!levelSelect) return;

            const targetLevelId = subSystemLevelMapping[String(subSystemId)];
            if (targetLevelId) {
                levelSelect.value = targetLevelId;
                levelSelect.classList.add('select-readonly');
            } else {
                levelSelect.value = "";
                levelSelect.classList.remove('select-readonly');
            }
        }

        // =========================================================================
        // TỰ ĐỘNG GẮN TÊN CHỦ NHIỆM, KHOA PHÒNG & USER_ID KHI CHỌN GIẢNG VIÊN
        // =========================================================================
        async function handleTeacherSelect(userId) {
            const leaderNameInput = document.getElementById('thanhVien1');
            const leaderUnitInput = document.getElementById('trungDoi1');
            const leaderUserIdInput = document.getElementById('leaderUserId');
            const selectTeacher = document.getElementById('projectUserActionId');

            if (!userId) {
                // Nếu bỏ chọn giảng viên: reset và cho phép nhập tay tự do
                leaderUserIdInput.value = "";
                leaderNameInput.value = "";
                leaderUnitInput.value = "";
                return;
            }

            // Gán ngay ID vào input hidden
            leaderUserIdInput.value = userId;

            // Đọc tên hiển thị và data-unit từ option đã chọn trước
            const selectedOpt = selectTeacher.options[selectTeacher.selectedIndex];
            const directName = selectedOpt ? (selectedOpt.getAttribute('data-name') || selectedOpt.text) : '';
            const directUnit = selectedOpt ? (selectedOpt.getAttribute('data-unit') || '') : '';

            if (directName && directName !== '-- Chọn giảng viên (không bắt buộc) --') {
                leaderNameInput.value = directName;
            }
            if (directUnit) {
                leaderUnitInput.value = directUnit;
            }

            // Gọi API lấy dữ liệu chi tiết của giảng viên (tương tự như R3 gọi API)
            try {
                const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
                let res = await fetch(`${API_URL}?detect=get_user&user_id=${encodeURIComponent(userId)}`, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                }).then(r => r.json());

                // Fallback nếu api get_user trả về rỗng
                if (!res || res.status !== 'success' || !res.data) {
                    res = await fetch(`${API_URL}?detect=cv_get&user_id=${encodeURIComponent(userId)}`, {
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json'
                        }
                    }).then(r => r.json());
                }

                if (res && res.status === 'success' && res.data) {
                    const d = res.data;
                    if (d.name || d.full_name) {
                        leaderNameInput.value = d.name || d.full_name;
                    }
                    if (d.unit_name || d.department_name) {
                        leaderUnitInput.value = d.unit_name || d.department_name;
                    } else if (d.unit_code) {
                        leaderUnitInput.value = `Khoa / Đơn vị ${d.unit_code}`;
                    }
                }
            } catch (err) {
                console.warn("Không thể lấy thêm thông tin khoa phòng của giảng viên qua API, sử dụng thông tin hiện có:", err);
            }
        }

        function handleResetForm() {
            document.getElementById('leaderUserId').value = "";
            document.getElementById('levelId').value = "";
            document.getElementById('levelId').classList.remove('select-readonly');
        }

        // ==========================================
        // XỬ LÝ IMPORT FILE EXCEL LÊN HỆ THỐNG
        // ==========================================
        async function handleImportExcel() {
            const fileInput = document.getElementById('excelFileInput');
            const statusBox = document.getElementById('importStatus');
            const btn = document.getElementById('btnExecuteImport');

            if (!fileInput.files || fileInput.files.length === 0) {
                statusBox.className = "alert alert-warning py-2 small mb-0";
                statusBox.innerText = "Vui lòng chọn một tệp Excel trước.";
                statusBox.classList.remove('d-none');
                return;
            }

            const file = fileInput.files[0];
            const reader = new FileReader();

            btn.disabled = true;
            btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1"></span> Đang xử lý...`;
            statusBox.className = "alert alert-info py-2 small mb-0";
            statusBox.innerText = "Đang đọc dữ liệu bảng tính...";
            statusBox.classList.remove('d-none');

            reader.onload = async function(e) {
                try {
                    const data = new Uint8Array(e.target.result);
                    const workbook = XLSX.read(data, {
                        type: 'array',
                        cellDates: true
                    });
                    const firstSheetName = workbook.SheetNames[0];
                    const sheet = workbook.Sheets[firstSheetName];

                    // QUAN TRỌNG: range: 3 nghĩa là bỏ qua 3 dòng đầu (Tiêu đề to, lưu ý, dòng trống)
                    // và lấy DÒNG 4 làm DÒNG TIÊU ĐỀ CHUẨN
                    const rawJson = XLSX.utils.sheet_to_json(sheet, {
                        range: 3,
                        defval: ""
                    });

                    // Lọc chỉ lấy những dòng thực sự CÓ điền Mã hoặc Tên nhiệm vụ
                    const validRows = rawJson.filter(row => {
                        const code = String(row["Mã Nhiệm Vụ (*)"] || row["Mã Nhiệm Vụ"] || row["Mã Đề Tài (*)"] || "").trim();
                        const name = String(row["Tên Nhiệm Vụ (*)"] || row["Tên Nhiệm Vụ"] || row["Tên Đề Tài NCKH (*)"] || "").trim();
                        return code !== "" || name !== "";
                    });

                    if (validRows.length === 0) {
                        statusBox.className = "alert alert-danger py-2 small mb-0";
                        statusBox.innerText = "Không tìm thấy dữ liệu nhiệm vụ hợp lệ nào trong tệp Excel.";
                        btn.disabled = false;
                        btn.innerHTML = `<i class="fa-solid fa-cloud-arrow-up me-1"></i> Bắt đầu nạp dữ liệu`;
                        return;
                    }

                    // Map dữ liệu cột tiếng Việt gửi lên API Backend
                    const formattedProjects = validRows.map(row => {
                        let deadline = row["Thời Hạn Đăng Ký (*)"] || row["Thời Hạn Đăng Ký"] || row["registration_deadline"] || null;
                        if (deadline instanceof Date) {
                            deadline = deadline.toISOString().split('T')[0];
                        }

                        return {
                            project_code: String(row["Mã Nhiệm Vụ (*)"] || row["Mã Nhiệm Vụ"] || row["Mã Đề Tài (*)"] || "").trim(),
                            project_name: String(row["Tên Nhiệm Vụ (*)"] || row["Tên Nhiệm Vụ"] || row["Tên Đề Tài NCKH (*)"] || "").trim(),
                            register_type_id: row["Hình Thức Đăng Ký (*)"] || row["Hình Thức Đăng Ký"] || row["Loại Đăng Ký"] || "Nhiệm vụ đặt hàng",
                            sub_system_id: row["Loại Nhiệm Vụ (*)"] || row["Loại Nhiệm Vụ"] || row["Loại Đề Tài"] || "",
                            project_user_action_id: row["Giảng Viên Phụ Trách"] || null,
                            security_level_id: row["Mức Độ Mật (*)"] || row["Mức Độ Mật"] || "Không mật",
                            registration_deadline: deadline,
                            estimated_budget: 0,
                            duration_months: 12
                        };
                    });

                    const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
                    const userId = (typeof getUserIdFromToken === 'function' ? getUserIdFromToken() : null) || sessionStorage.getItem('user_id') || 1;

                    const res = await fetch(`${API_URL}?detect=project_import_excel`, {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            current_user_id: userId,
                            projects: formattedProjects
                        })
                    });

                    const result = await res.json();
                    if (res.ok && result.status === 'success') {
                        statusBox.className = "alert alert-success py-2 small mb-0";
                        let msg = result.message;
                        if (result.errors && result.errors.length > 0) {
                            msg += `<br><strong>Chi tiết lưu ý:</strong><br>` + result.errors.join('<br>');
                        }
                        statusBox.innerHTML = msg;
                        fileInput.value = '';
                    } else {
                        statusBox.className = "alert alert-danger py-2 small mb-0";
                        statusBox.innerText = result.message || "Đã xảy ra lỗi khi nạp đề tài.";
                    }
                } catch (err) {
                    console.error("Lỗi import:", err);
                    statusBox.className = "alert alert-danger py-2 small mb-0";
                    statusBox.innerText = "Lỗi định dạng file Excel không hợp lệ.";
                } finally {
                    btn.disabled = false;
                    btn.innerHTML = `<i class="fa-solid fa-cloud-arrow-up me-1"></i> Bắt đầu nạp dữ liệu`;
                }
            };

            reader.readAsArrayBuffer(file);
        }

        // ==========================================
        // XỬ LÝ SUBMIT FORM ĐĂNG KÝ THỦ CÔNG
        // ==========================================
        document.getElementById('formDangKyDeTai').addEventListener('submit', async function(e) {
            e.preventDefault();

            const form = e.target;
            const btnSubmit = document.getElementById('btnSubmit');
            const alertMessage = document.getElementById('alertMessage');

            const formData = new FormData(form);
            formData.append('detect', 'project_create');

            // Lấy ID chủ nhiệm từ ô ẩn nếu có
            const leaderUserId = document.getElementById('leaderUserId').value || null;

            const thanhVien = [];
            for (let i = 1; i <= 5; i++) {
                const nameVal = form[`thanh_vien_${i}`].value.trim();
                if (nameVal !== "") {
                    const isLeader = (i === 1 ? 1 : 0);
                    thanhVien.push({
                        user_id: isLeader ? leaderUserId : null,
                        full_name: nameVal,
                        don_vi: form[`trung_doi_${i}`].value.trim(),
                        trung_doi: form[`trung_doi_${i}`].value.trim(),
                        is_chu_nhiem: isLeader
                    });
                }
            }
            formData.append('danh_sach_thanh_vien', JSON.stringify(thanhVien));

            btnSubmit.disabled = true;
            btnSubmit.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Đang gửi...`;

            try {
                const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
                const response = await fetch(`${API_URL}?detect=project_create`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    },
                    body: formData
                });

                const result = await response.json();

                if (response.ok && result.status === 'success') {
                    alertMessage.className = "alert alert-success";
                    alertMessage.innerText = result.message || "Đăng ký đề tài thành công!";
                    form.reset();
                    handleResetForm();
                } else {
                    alertMessage.className = "alert alert-danger";
                    alertMessage.innerText = result.message || "Đã xảy ra lỗi khi gửi dữ liệu.";
                }
            } catch (error) {
                console.error('Lỗi kết nối API:', error);
                alertMessage.className = "alert alert-danger";
                alertMessage.innerText = "Không thể kết nối đến máy chủ. Vui lòng thử lại sau.";
            } finally {
                alertMessage.classList.remove('d-none');
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = `<i class="fa-solid fa-paper-plane me-2"></i>Gửi Đăng Ký Đề Tài`;
            }
        });
    </script>
</body>

</html>
