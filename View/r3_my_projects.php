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
    <title>Nhiệm Vụ Của Tôi - QL NCKH</title>
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
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        .main-wrapper.expanded {
            margin-left: 0 !important;
            width: 100% !important;
        }

        a.btn-icon {
            width: 28px;
            height: 28px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
            border: 1px solid #cbd5e1;
            background-color: #ffffff;
            color: #475569;
            font-size: 0.75rem;
            text-decoration: none;
            transition: all 0.15s ease;
            cursor: pointer;
        }

        a.btn-icon:hover:not(.disabled) {
            background-color: #f8fafc;
            transform: translateY(-1px);
        }

        a.btn-icon.view:hover {
            color: #2563eb;
            border-color: #93c5fd;
            background-color: #eff6ff;
        }

        a.btn-icon.edit:hover {
            color: #d97706;
            border-color: #fde68a;
            background-color: #fffbeb;
        }

        a.btn-icon.report:hover:not(.disabled) {
            color: #16a34a;
            border-color: #bbf7d0;
            background-color: #f0fdf4;
        }

        a.btn-icon.delete:hover:not(.disabled) {
            color: #dc2626;
            border-color: #fecaca;
            background-color: #fef2f2;
        }

        a.btn-icon.disabled {
            opacity: 0.35;
            cursor: not-allowed;
            pointer-events: none;
            background-color: #f1f5f9;
        }
    </style>
</head>

<body>
    <!-- SIDEBAR -->
    <?php include '../Model/r3_sidebar.php'; ?>

    <div class="main-wrapper" id="mainWrapper">
        <!-- TOPBAR -->
        <?php
        $pageTitle = "NHIỆM VỤ CỦA TÔI";
        $pageIcon = "fa-solid fa-list-check";
        include '../Model/r3_topbar.php';
        ?>

        <main class="container-fluid px-4 py-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark m-0 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-folder-open text-primary"></i> Danh sách Đề tài / Nhiệm vụ đang đảm nhiệm
                    </h5>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex gap-2">
                            <div style="position: relative;">
                                <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem;"></i>
                                <input type="text" id="searchInput" oninput="applyFilters()" placeholder="Tìm theo tên hoặc mã đề tài..." class="form-control form-control-sm" style="padding-left: 32px; width: 280px;">
                            </div>
                            <select id="statusFilter" onchange="applyFilters()" class="form-select form-select-sm" style="width: 180px;">
                                <option value="ALL">Tất cả trạng thái</option>
                                <option value="running">Đang tiến hành</option>
                                <option value="awaiting_acceptance">Chờ nghiệm thu</option>
                                <option value="awaiting_payment">Chờ thanh toán</option>
                                <option value="completed">Đã hoàn thành</option>
                                <option value="rejected">Đã từ chối</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0" style="font-size: 0.875rem;">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 130px;">Mã đề tài</th>
                                    <th>Tên đề tài NCKH</th>
                                    <th style="width: 130px;">Cấp đề tài</th>
                                    <th style="width: 180px;">Loại nhiệm vụ</th>
                                    <th style="width: 170px;">Thời hạn / Giai đoạn</th>
                                    <th style="width: 125px;" class="text-center">Trạng thái</th>
                                    <th style="width: 140px;" class="text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody id="projectTableBody">
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="fa-solid fa-spinner fa-spin me-2"></i> Đang tải dữ liệu nhiệm vụ...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- MODAL TOPBAR -->
    <?php include '../Model/r3_modal_topbar.php'; ?>

    <!-- MODAL NỘP BÁO CÁO TIẾN ĐỘ -->
    <div class="modal fade" id="submitReportModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light py-3">
                    <h5 class="modal-title fs-6 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="fa-solid fa-paper-plane text-primary"></i> Nộp Báo Cáo Tiến Độ Đề Tài
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="submitReportForm" onsubmit="handleUploadReport(event)">
                    <div class="modal-body p-4">
                        <input type="hidden" id="reportProjectId">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Đề tài đang chọn</label>
                            <input type="text" id="reportProjectTitle" class="form-control form-control-sm bg-light fw-bold" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="reportPhase" class="form-label small fw-semibold text-secondary">Đợt báo cáo định kỳ <span class="text-danger">*</span></label>
                            <select id="reportPhase" class="form-select form-select-sm" required>
                                <option value="1">Báo cáo Đợt 1</option>
                                <option value="2">Báo cáo Đợt 2</option>
                                <option value="3">Báo cáo Đợt 3</option>
                                <option value="4">Báo cáo Đợt 4</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="reportFiles" class="form-label small fw-semibold text-secondary">Chọn tệp báo cáo (.pdf, .docx, .zip) <span class="text-danger">*</span></label>
                            <input type="file" id="reportFiles" class="form-control form-control-sm" accept=".pdf,.doc,.docx,.zip,.rar" required>
                        </div>
                        <div class="mb-3">
                            <label for="reportNote" class="form-label small fw-semibold text-secondary">Ghi chú gửi kèm</label>
                            <textarea id="reportNote" class="form-control form-control-sm" rows="2" placeholder="Ghi chú thêm..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-2 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm fw-semibold" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary btn-sm fw-semibold" id="btnSubmitReport">
                            <i class="fa-solid fa-cloud-arrow-up me-1"></i> Nộp báo cáo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL XÁC NHẬN CHUYỂN VÀO THÙNG RÁC -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-danger text-white py-2">
                    <h6 class="modal-title fw-bold">
                        <i class="fa-solid fa-triangle-exclamation me-1"></i> Xác nhận xóa
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-3">
                    <input type="hidden" id="deleteProjectId">
                    <p class="mb-1 text-secondary" style="font-size: 0.85rem;">Bạn có chắc muốn chuyển đề tài này vào thùng rác?</p>
                    <p class="fw-bold text-dark mb-0" id="deleteProjectTitleDisplay">---</p>
                </div>
                <div class="modal-footer bg-light py-2 justify-content-center gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3 fw-semibold" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger btn-sm px-3 fw-semibold" id="btnConfirmDelete" onclick="handleConfirmDelete()">
                        <i class="fa-solid fa-trash-can me-1"></i> Đồng ý xóa
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
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

        let allProjects = [];

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

        document.addEventListener("DOMContentLoaded", function() {
            fetchProjects();
        });

        async function fetchProjects() {
            const tbody = document.getElementById('projectTableBody');
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        <i class="fa-solid fa-spinner fa-spin me-2"></i> Đang tải dữ liệu nhiệm vụ...
                    </td>
                </tr>
            `;

            const currentUserId = getCurrentUserId();
            if (!currentUserId) {
                tbody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-warning">Phiên đăng nhập hết hạn. Vui lòng đăng nhập lại!</td></tr>`;
                return;
            }

            try {
                const response = await fetchWithAuth(`${API_URL}?detect=project_get&action_user_id=${currentUserId}`, {
                    method: 'GET'
                });
                const result = await response.json();

                if (response.ok && result.status === 'success') {
                    let data = result.data || [];

                    // Lọc đề tài mình thực hiện và không phải trạng thái chờ duyệt (đề xuất)
                    data = data.filter(p => {
                        const isMyAction = parseInt(p.project_user_action_id || 0) === currentUserId;
                        const st = (p.status || '').toLowerCase();
                        return isMyAction && st !== 'pending' && st !== 'cho_duyet';
                    });

                    allProjects = data.map(p => {
                        let statusText = "Đang tiến hành";
                        if (p.status === "awaiting_acceptance") statusText = "Chờ nghiệm thu";
                        else if (p.status === "awaiting_payment") statusText = "Chờ thanh toán";
                        else if (p.status === "completed") statusText = "Đã hoàn thành";
                        else if (p.status === "rejected") statusText = "Đã từ chối";
                        else if (p.status === "running") statusText = "Đang tiến hành";

                        let timeline = "Chưa kích hoạt";
                        let isExpiring = false;

                        if (p.status === "running" && p.approved_date) {
                            const approvedDate = new Date(p.approved_date);
                            const durationMonths = parseInt(p.duration_months) || 12;
                            const endDate = new Date(approvedDate);
                            endDate.setMonth(endDate.getMonth() + durationMonths);

                            const today = new Date();
                            const diffDays = Math.ceil((endDate - today) / (1000 * 60 * 60 * 24));

                            if (diffDays < 0) {
                                timeline = `Quá hạn ${Math.abs(diffDays)} ngày`;
                                isExpiring = true;
                            } else if (diffDays <= 15) {
                                timeline = `Sắp đến hạn (${diffDays} ngày)`;
                                isExpiring = true;
                            } else {
                                timeline = `Còn ${diffDays} ngày`;
                            }
                        } else if (p.status === "completed") {
                            timeline = "Đã hoàn thành";
                        }

                        return {
                            id: p.project_id,
                            project_code: p.project_code || ('NV' + p.project_id),
                            title: p.project_name,
                            type: p.level_name || 'Cấp cơ sở',
                            subType: p.sub_system_name || 'Nghiên cứu',
                            timeline: timeline,
                            status: statusText,
                            rawStatus: p.status || 'running',
                            isExpiring: isExpiring
                        };
                    });

                    renderTable(allProjects);
                } else {
                    tbody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-danger">${result.message || 'Lỗi server'}</td></tr>`;
                }
            } catch (err) {
                console.error("Lỗi khi kết nối API:", err);
                tbody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-danger">Không thể kết nối đến máy chủ.</td></tr>`;
            }
        }

        function renderTable(projects) {
            const tbody = document.getElementById('projectTableBody');
            tbody.innerHTML = '';

            if (projects.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-muted">Bạn chưa có nhiệm vụ nào được phân công hoặc xét duyệt.</td></tr>`;
                return;
            }

            projects.forEach(p => {
                const tr = document.createElement('tr');
                let badgeClass = 'bg-secondary';
                if (p.rawStatus === 'running') badgeClass = 'bg-primary';
                else if (p.rawStatus === 'awaiting_acceptance') badgeClass = 'bg-info text-dark';
                else if (p.rawStatus === 'awaiting_payment') badgeClass = 'bg-warning text-dark';
                else if (p.rawStatus === 'completed') badgeClass = 'bg-success';
                else if (p.rawStatus === 'rejected') badgeClass = 'bg-danger';

                const isPending = (p.rawStatus === 'pending' || p.rawStatus === 'cho_duyet');
                const canReport = (p.rawStatus === 'running');

                tr.innerHTML = `
                    <td class="fw-bold"><code>${p.project_code}</code></td>
                    <td class="fw-semibold text-dark">${p.title}</td>
                    <td><span class="badge bg-light text-dark border">${p.type}</span></td>
                    <td><small class="text-muted">${p.subType}</small></td>
                    <td class="${p.isExpiring ? 'text-danger fw-semibold' : ''}">${p.timeline}</td>
                    <td class="text-center"><span class="badge ${badgeClass}">${p.status}</span></td>
                    <td class="text-center">
                        <div class="d-inline-flex align-items-center justify-content-center gap-1">
                            <a href="/QLNCKH/chi-tiet-de-tai?id=${p.id}&code=${encodeURIComponent(p.project_code)}&title=${encodeURIComponent(p.title)}" class="btn-icon view" title="Xem chi tiết">
                                <i class="fa-regular fa-eye"></i>
                            </a>
                            <a href="javascript:void(0)" onclick="updateProjectApi('${p.id}')" class="btn-icon edit" title="Cập nhật">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>
                            <a href="javascript:void(0)" onclick="submitReportApi('${p.id}')" class="btn-icon report ${p.isExpiring ? 'text-danger border-danger' : ''} ${!canReport ? 'disabled' : ''}" title="${canReport ? 'Nộp báo cáo tiến độ' : 'Chỉ nộp khi đề tài đang thực hiện'}">
                                <i class="fa-solid fa-paper-plane"></i>
                            </a>
                            <a href="javascript:void(0)" onclick="deleteProjectApi('${p.id}')" class="btn-icon delete ${!isPending ? 'disabled' : ''}" title="${isPending ? 'Chuyển vào thùng rác' : 'Chỉ được xóa khi đề tài đang chờ duyệt'}">
                                <i class="fa-regular fa-trash-can"></i>
                            </a>
                        </div>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        function applyFilters() {
            const keyword = document.getElementById('searchInput').value.toLowerCase().trim();
            const selectedStatus = document.getElementById('statusFilter').value;

            const filteredProjects = allProjects.filter(p => {
                const matchesKeyword = p.title.toLowerCase().includes(keyword) || p.project_code.toLowerCase().includes(keyword);
                const matchesStatus = (selectedStatus === 'ALL') || (p.rawStatus === selectedStatus);
                return matchesKeyword && matchesStatus;
            });

            renderTable(filteredProjects);
        }

        function updateProjectApi(id) {
            window.location.href = `/QLNCKH/cap-nhat-de-tai?id=${encodeURIComponent(id)}`;
        }

        function deleteProjectApi(id) {
            const project = allProjects.find(p => String(p.id) === String(id));
            if (!project) return;

            document.getElementById('deleteProjectId').value = project.id;
            document.getElementById('deleteProjectTitleDisplay').innerText = `[${project.project_code}] ${project.title}`;

            const modalEl = document.getElementById('deleteConfirmModal');
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        }

        async function handleConfirmDelete() {
            const projectId = document.getElementById('deleteProjectId').value;
            const btnDelete = document.getElementById('btnConfirmDelete');
            if (!projectId) return;

            try {
                btnDelete.disabled = true;
                btnDelete.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> Đang xóa...`;

                const response = await fetchWithAuth(`${API_URL}?detect=project_delete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        project_id: projectId
                    })
                });

                const result = await response.json();

                if (response.ok && result.status === 'success') {
                    alert(result.message || "Đã chuyển đề tài vào thùng rác!");
                    const modalEl = document.getElementById('deleteConfirmModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) modalInstance.hide();
                    fetchProjects();
                } else {
                    alert("Lỗi: " + (result.message || "Không thể xóa đề tài!"));
                }
            } catch (err) {
                console.error("Lỗi xóa đề tài:", err);
                alert("Lỗi kết nối máy chủ!");
            } finally {
                btnDelete.disabled = false;
                btnDelete.innerHTML = `<i class="fa-solid fa-trash-can me-1"></i> Đồng ý xóa`;
            }
        }

        function submitReportApi(projectId) {
            const project = allProjects.find(p => String(p.id) === String(projectId));
            if (!project) return;

            document.getElementById('reportProjectId').value = project.id;
            document.getElementById('reportProjectTitle').value = `[${project.project_code}] - ${project.title}`;
            document.getElementById('reportFiles').value = '';
            document.getElementById('reportNote').value = '';

            const modalEl = document.getElementById('submitReportModal');
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        }

        async function handleUploadReport(event) {
            event.preventDefault();

            const projectId = document.getElementById('reportProjectId').value;
            const phase = document.getElementById('reportPhase').value;
            const note = document.getElementById('reportNote').value.trim();
            const fileInput = document.getElementById('reportFiles');
            const submitBtn = document.getElementById('btnSubmitReport');

            if (fileInput.files.length === 0) {
                alert("Vui lòng chọn tệp báo cáo!");
                return;
            }

            const formData = new FormData();
            formData.append('project_id', projectId);
            formData.append('report_phase', phase);
            formData.append('note', note);
            formData.append('report_file', fileInput.files[0]);

            try {
                submitBtn.disabled = true;
                submitBtn.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> Đang tải lên...`;

                const response = await fetchWithAuth(`${API_URL}?detect=project_progress_report`, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (response.ok && result.status === 'success') {
                    alert(result.message || "Nộp báo cáo tiến độ thành công!");
                    const modalEl = document.getElementById('submitReportModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) modalInstance.hide();
                    fetchProjects();
                } else {
                    alert("Lỗi: " + (result.message || "Không thể gửi báo cáo!"));
                }
            } catch (err) {
                console.error("Lỗi nộp báo cáo:", err);
                alert("Lỗi kết nối máy chủ!");
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = `<i class="fa-solid fa-cloud-arrow-up me-1"></i> Nộp báo cáo`;
            }
        }
    </script>
</body>

</html>