<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <base href="/QLNCKH/View/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Quản Lý NCKH - Danh Sách Nhiệm Vụ</title>
    <script src="../asset/script/auth_check.js"></script>
    <!-- Bootstrap 5 CSS -->
    <link href="../asset/css/bootstrap5.css" rel="stylesheet">
    <!-- FontAwesome Icon -->
    <link rel="stylesheet" href="../asset/css/all.min.css">
    <link rel="stylesheet" href="../asset/css/style_admin.css">
    <style>
        /* NÚT THAO TÁC ICON NHỎ GỌN TINH TẾ (28x28px) */
        a.btn-action-icon,
        button.btn-action-icon {
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

        a.btn-action-icon:hover,
        button.btn-action-icon:hover {
            background-color: #f8fafc;
            transform: translateY(-1px);
        }

        a.btn-action-icon.view:hover {
            color: #2563eb;
            border-color: #93c5fd;
            background-color: #eff6ff;
        }

        button.btn-action-icon.delete:hover {
            color: #dc2626;
            border-color: #fecaca;
            background-color: #fef2f2;
        }

        /* BADGE THỐNG KÊ TỔNG GÓC PHẢI TIÊU ĐỀ */
        .stat-badge {
            font-size: 0.75rem;
            font-weight: 500;
            padding: 4px 12px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid #e2e8f0;
            background-color: #f1f5f9;
            color: #334155;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
        }

        .stat-num {
            font-weight: 700;
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

        <!-- KHU VỰC THÔNG BÁO -->
        <div id="alertContainer"></div>

        <div class="card shadow-sm border-0 m-3">
            <div class="card-header bg-white py-3 border-0 d-flex flex-wrap justify-content-between align-items-center gap-2">
                <h5 class="fw-bold text-dark m-0 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-list-check text-primary"></i> Quản Lý Danh Sách Nhiệm Vụ NCKH
                </h5>

                <!-- NHÃN TỔNG SỐ LƯỢNG TINH TẾ -->
                <div class="d-flex align-items-center" id="headerStatBadges">
                    <span class="stat-badge" title="Tổng số nhiệm vụ">
                        <i class="fa-solid fa-folder-open text-secondary"></i> Tổng: <span class="stat-num" id="statTotalCount">0</span>
                    </span>
                </div>
            </div>

            <div class="card-body pt-0">
                <!-- BỘ LỌC TÌM KIẾM CHUNG & LỌC THEO HÌNH THỨC ĐĂNG KÝ -->
                <div class="row g-2 mb-3 bg-light p-3 rounded border align-items-end">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary mb-1">
                            <i class="fa-solid fa-magnifying-glass me-1"></i>Tìm kiếm chung
                        </label>
                        <input type="text" class="form-control form-control-sm" id="searchDeTai" onkeyup="applyFilters()" placeholder="Nhập mã nhiệm vụ, tên nhiệm vụ, tên chủ nhiệm...">
                    </div>
                    <!-- BỘ LỌC HÌNH THỨC ĐĂNG KÝ -->
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-secondary mb-1">
                            <i class="fa-solid fa-filter me-1"></i>Hình thức đăng ký
                        </label>
                        <select class="form-select form-select-sm" id="filterRegisterType" onchange="applyFilters()">
                            <option value="">-- Tất cả hình thức --</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-sm btn-outline-secondary w-100" onclick="resetFilterDeTai()">
                            <i class="fa-solid fa-rotate-left me-1"></i>Đặt lại
                        </button>
                    </div>
                </div>

                <!-- BẢNG DANH SÁCH NHIỆM VỤ NCKH -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0" id="tableDeTaiNCKH" style="font-size: 0.875rem;">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;" class="text-center">STT</th>
                                <th style="width: 120px;">Mã Nhiệm vụ</th>
                                <th>Tên Nhiệm vụ</th>
                                <th style="width: 170px;">Hình thức</th>
                                <th style="width: 170px;">Tên Chủ nhiệm</th>
                                <th style="width: 130px;" class="text-center">Trạng thái</th>
                                <th style="width: 220px;">Đếm thời gian</th>
                                <th style="width: 80px;" class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="projectTableBody">
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fa-solid fa-spinner fa-spin me-2"></i>Đang tải dữ liệu hệ thống...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- PHÂN TRANG -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <small class="text-muted" id="pageInfo">Đang nạp dữ liệu...</small>
                    <ul class="pagination pagination-sm mb-0" id="paginationProject"></ul>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL XÁC NHẬN XÓA -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-danger text-white py-2">
                    <h6 class="modal-title fw-bold">
                        <i class="fa-solid fa-triangle-exclamation me-1"></i> Xác nhận xóa
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3 text-center">
                    <p class="mb-0 text-secondary fs-7">Thao tác này sẽ xóa nhiệm vụ khỏi hệ thống. Bạn có chắc chắn muốn xóa không?</p>
                </div>
                <div class="modal-footer bg-light py-2 justify-content-center gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="button" class="btn btn-danger btn-sm px-3" id="btnConfirmDelete">Xóa ngay</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="../asset/script/bootstrap5.js"></script>
    <script src="../asset/script/sidebar.js"></script>
    <script>
        const API_URL = '../index_api.php';
        let globalProjects = [];
        let filteredProjects = [];
        let deleteTargetId = null;

        const ITEMS_PER_PAGE = 10;
        let currentPage = 1;

        document.addEventListener("DOMContentLoaded", function() {
            loadRegisterTypes();
            loadInitialData();
        });

        function showUINotification(message, type = "success") {
            const container = document.getElementById('alertContainer');
            if (!container) return;

            const alertId = 'alert_' + Date.now();
            container.innerHTML = `
                <div id="${alertId}" class="alert alert-${type} alert-dismissible fade show shadow-sm m-3 mb-0" role="alert">
                    <i class="fa-solid ${type === 'success' ? 'fa-circle-check' : 'fa-triangle-exclamation'} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;

            setTimeout(() => {
                const alertEl = document.getElementById(alertId);
                if (alertEl) {
                    let alertInstance = bootstrap.Alert.getOrCreateInstance(alertEl);
                    alertInstance.close();
                }
            }, 4000);
        }

        async function loadRegisterTypes() {
            const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
            const selectEl = document.getElementById('filterRegisterType');
            try {
                const res = await fetch(`${API_URL}?detect=project_register_type_get`, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                }).then(r => r.json());

                if (res.status === 'success' && Array.isArray(res.data)) {
                    selectEl.innerHTML = `<option value="">-- Tất cả hình thức --</option>`;
                    res.data.forEach(item => {
                        const val = item.type_name || item.name;
                        selectEl.innerHTML += `<option value="${val}">${val}</option>`;
                    });
                }
            } catch (err) {
                console.warn("Không tải được danh mục hình thức từ API, sẽ tự trích từ dữ liệu bảng:", err);
            }
        }

        async function loadInitialData() {
            const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');

            const urlParams = new URLSearchParams(window.location.search);
            const assignedParam = urlParams.get('assigned');

            let fetchUrl = `${API_URL}?detect=project_get`;
            if (assignedParam !== null && assignedParam !== '') {
                fetchUrl += `&assigned=${encodeURIComponent(assignedParam)}`;
            }

            updatePageTitleByAssigned(assignedParam);

            try {
                const projRes = await fetch(fetchUrl, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                }).then(res => res.json());

                if (projRes.status === "success") {
                    globalProjects = projRes.data || [];
                    populateRegisterTypeOptionsIfEmpty();

                    // Cập nhật nhãn đếm tổng số lượng
                    document.getElementById('statTotalCount').innerText = globalProjects.length;

                    applyFilters();
                } else {
                    showUINotification("Không thể tải dữ liệu nhiệm vụ.", "danger");
                }
            } catch (err) {
                console.error("Lỗi kết nối dữ liệu:", err);
                showUINotification("Lỗi kết nối đến máy chủ.", "danger");
            }
        }

        function populateRegisterTypeOptionsIfEmpty() {
            const selectEl = document.getElementById('filterRegisterType');
            if (selectEl.options.length <= 1) {
                const types = [...new Set(globalProjects.map(p => p.register_type_name).filter(Boolean))];
                types.forEach(t => {
                    selectEl.innerHTML += `<option value="${t}">${t}</option>`;
                });
            }
        }

        function updatePageTitleByAssigned(assigned) {
            const titleEl = document.querySelector('.card-header h5');
            if (!titleEl) return;

            if (assigned === '1') {
                titleEl.innerHTML = `<i class="fa-solid fa-user-check text-primary me-2"></i> Danh Sách Nhiệm Vụ Đã Giao Tiếp Nhận`;
                document.title = "Nhiệm Vụ Đã Giao Tiếp Nhận - Quản Lý NCKH";
            } else if (assigned === '0') {
                titleEl.innerHTML = `<i class="fa-solid fa-user-clock text-warning me-2"></i> Danh Sách Nhiệm Vụ Chờ Tiếp Nhận`;
                document.title = "Nhiệm Vụ Chờ Tiếp Nhận - Quản Lý NCKH";
            } else {
                titleEl.innerHTML = `<i class="fa-solid fa-list-check text-primary me-2"></i> Quản Lý Danh Sách Nhiệm Vụ NCKH`;
            }
        }

        // BỘ LỌC TÌM KIẾM CHUNG & HÌNH THỨC ĐĂNG KÝ
        function applyFilters() {
            const searchText = document.getElementById('searchDeTai').value.toLowerCase().trim();
            const selectedRegisterType = document.getElementById('filterRegisterType').value.trim();

            filteredProjects = globalProjects.filter(proj => {
                const leader = proj.leader_name || proj.teacher_in_charge_name || '';
                const code = proj.project_code || ('DT' + proj.project_id);
                const title = proj.project_name || '';
                const regType = proj.register_type_name || '';

                // 1. Khớp từ khóa tìm kiếm
                const rowText = `${code} ${title} ${leader} ${regType}`.toLowerCase();
                const matchesSearch = (searchText === "" || rowText.includes(searchText));

                // 2. Khớp hình thức đăng ký
                const matchesRegType = (selectedRegisterType === "" || regType === selectedRegisterType);

                return matchesSearch && matchesRegType;
            });

            currentPage = 1;
            renderProjectTable();
        }

        function renderProjectTable() {
            const tbody = document.getElementById("projectTableBody");
            if (!tbody) return;

            tbody.innerHTML = "";

            if (!filteredProjects || filteredProjects.length === 0) {
                tbody.innerHTML = `<tr><td colspan="8" class="text-center text-muted py-4">Không tìm thấy nhiệm vụ nào phù hợp.</td></tr>`;
                document.getElementById('pageInfo').innerText = '0 / 0 nhiệm vụ';
                document.getElementById('paginationProject').innerHTML = '';
                return;
            }

            const start = (currentPage - 1) * ITEMS_PER_PAGE;
            const paginated = filteredProjects.slice(start, start + ITEMS_PER_PAGE);

            paginated.forEach((proj, idx) => {
                let badgeClass = "bg-warning text-dark";
                let statusText = "Chờ duyệt";

                if (proj.status === "running") {
                    badgeClass = "bg-primary";
                    statusText = "Đang tiến hành";
                } else if (proj.status === "awaiting_acceptance") {
                    badgeClass = "bg-info text-dark";
                    statusText = "Chờ nghiệm thu";
                } else if (proj.status === "awaiting_payment") {
                    badgeClass = "bg-secondary";
                    statusText = "Chờ thanh toán";
                } else if (proj.status === "completed") {
                    badgeClass = "bg-success";
                    statusText = "Đã hoàn thành";
                } else if (proj.status === "rejected") {
                    badgeClass = "bg-danger";
                    statusText = "Đã từ chối";
                }

                const projectCode = proj.project_code || ('DT' + proj.project_id);
                const leaderName = proj.leader_name || proj.teacher_in_charge_name || 'Chưa phân công';
                const regTypeName = proj.register_type_name || '---';

                let regBadgeClass = "bg-light text-dark border";
                if (regTypeName.includes("giao trực tiếp")) {
                    regBadgeClass = "bg-warning bg-opacity-25 text-dark border border-warning";
                } else if (regTypeName.includes("tuyển chọn")) {
                    regBadgeClass = "bg-info bg-opacity-25 text-dark border border-info";
                } else if (regTypeName.includes("đặt hàng")) {
                    regBadgeClass = "bg-primary bg-opacity-10 text-primary border border-primary";
                }

                const approvedDate = proj.approved_date ? proj.approved_date.split(' ')[0] : '';
                const durationMonths = proj.duration_months ? parseInt(proj.duration_months) : 12;
                const reportPhases = proj.report_phases ? parseInt(proj.report_phases) : 2;
                const registrationDeadline = proj.registration_deadline ? proj.registration_deadline.split(' ')[0] : null;

                const timeHtml = generateCountdownHtml(proj.status, approvedDate, durationMonths, reportPhases, registrationDeadline);
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td class="text-center fw-bold">${start + idx + 1}</td>
                    <td class="fw-bold"><code>${projectCode}</code></td>
                    <td class="fw-semibold text-dark">${proj.project_name}</td>
                    <td>
                        <span class="badge ${regBadgeClass} fw-normal text-wrap text-start">
                            ${regTypeName}
                        </span>
                    </td>
                    <td>
                        <i class="fa-solid fa-user-tie text-secondary me-1"></i>${leaderName}
                    </td>
                    <td class="text-center"><span class="badge ${badgeClass}">${statusText}</span></td>
                    <td>${timeHtml}</td>
                    <td class="text-center">
                        <div class="d-inline-flex align-items-center justify-content-center gap-1">
                            <a href="/QLNCKH/noi-dung-nhiem-vu?id=${proj.project_id}&code=${encodeURIComponent(projectCode)}&title=${encodeURIComponent(proj.project_name)}" class="btn-action-icon view" title="Xem chi tiết">
                                <i class="fa-regular fa-eye"></i>
                            </a>
                            <button type="button" class="btn-action-icon delete" onclick="promptDeleteProject(${proj.project_id})" title="Xóa">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(tr);
            });

            document.getElementById('pageInfo').innerText = `Hiển thị ${paginated.length} / ${filteredProjects.length} nhiệm vụ`;
            renderPagination(filteredProjects.length);
        }

        function generateCountdownHtml(status, approvedDateStr, durationMonths, reportPhases = 2, registrationDeadlineStr = null) {
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            // 1. Kiểm tra hạn xét duyệt khi còn ở trạng thái pending
            if (status === 'pending') {
                if (registrationDeadlineStr) {
                    const deadlineDate = new Date(registrationDeadlineStr);
                    deadlineDate.setHours(0, 0, 0, 0);
                    const diffDays = Math.ceil((deadlineDate - today) / (1000 * 60 * 60 * 24));

                    if (diffDays < 0) {
                        return `
                    <div class="badge bg-danger p-1 text-wrap w-100 mb-1">
                        <i class="fa-solid fa-triangle-exclamation me-1"></i> Quá hạn xét duyệt!
                    </div>
                    <div class="small text-danger fw-bold">Trễ ${Math.abs(diffDays)} ngày (Hạn duyệt: ${formatDate(deadlineDate)})</div>
                `;
                    } else {
                        return `
                    <span class="badge bg-secondary text-light mb-1">Chờ duyệt</span>
                    <div class="small text-muted" style="font-size:0.75rem;">Hạn duyệt: ${formatDate(deadlineDate)} (Còn ${diffDays} ngày)</div>
                `;
                    }
                }
                return `<span class="badge bg-secondary text-light">Chưa kích hoạt (Chờ duyệt)</span>`;
            }

            if (status === 'rejected') {
                return `<span class="badge bg-danger">Đã từ chối</span>`;
            }

            if (status !== 'running') {
                return `<span class="badge bg-secondary">Đã kết thúc tiến độ</span>`;
            }

            if (!approvedDateStr) {
                return `<span class="small text-muted">Chưa cập nhật ngày kích hoạt</span>`;
            }

            // 2. Tính toán tiến độ và các đợt báo cáo theo duration_months / report_phases
            const approvedDate = new Date(approvedDateStr);
            approvedDate.setHours(0, 0, 0, 0);

            const durMonths = parseInt(durationMonths) || 12;
            const numPhases = parseInt(reportPhases) || 2;
            const monthsPerPhase = Math.floor(durMonths / numPhases);

            const endDate = new Date(approvedDate);
            endDate.setMonth(endDate.getMonth() + durMonths);

            const diffTimeToEnd = endDate - today;
            const daysUntilEnd = Math.ceil(diffTimeToEnd / (1000 * 60 * 60 * 24));

            // Tìm đợt báo cáo tiếp theo gần nhất
            let nextReportDate = null;
            let nextPhaseNum = 0;

            for (let p = 1; p <= numPhases; p++) {
                let tempDate = new Date(approvedDate);
                tempDate.setMonth(tempDate.getMonth() + (p * monthsPerPhase));
                if (tempDate >= today) {
                    nextReportDate = tempDate;
                    nextPhaseNum = p;
                    break;
                }
            }

            let daysUntilReport = 999;
            if (nextReportDate) {
                daysUntilReport = Math.ceil((nextReportDate - today) / (1000 * 60 * 60 * 24));
            }

            // Cảnh báo quá hạn kết thúc nhiệm vụ
            if (daysUntilEnd < 0) {
                return `
            <div class="badge bg-danger p-1 text-wrap w-100 mb-1">
                <i class="fa-solid fa-triangle-exclamation me-1"></i> Quá hạn nộp nhiệm vụ!
            </div>
            <div class="small text-danger fw-bold">Trễ ${Math.abs(daysUntilEnd)} ngày (Hạn: ${formatDate(endDate)})</div>
        `;
            }
            // Cảnh báo sắp hết hạn toàn bộ nhiệm vụ
            else if (daysUntilEnd <= 7) {
                return `
            <div class="badge bg-danger p-1 text-wrap w-100 mb-1">
                <i class="fa-solid fa-triangle-exclamation me-1"></i> Sắp hết hạn nộp!
            </div>
            <div class="small text-danger fw-bold">Còn ${daysUntilEnd} ngày (Hạn: ${formatDate(endDate)})</div>
        `;
            }
            // Cảnh báo trước 14 ngày cho đợt báo cáo tiến độ
            else if (nextReportDate && daysUntilReport <= 14 && daysUntilReport >= 0) {
                const isLastPhase = (nextPhaseNum === numPhases);
                const phaseTitle = isLastPhase ? 'Báo cáo cuối kỳ' : `Báo cáo Đợt ${nextPhaseNum}`;
                return `
            <div class="badge bg-warning text-dark p-1 w-100 mb-1">
                <i class="fa-solid fa-clock me-1"></i> Tới hạn ${phaseTitle}
            </div>
            <div class="small text-warning-emphasis fw-bold">Còn ${daysUntilReport} ngày tới hạn! (Hạn: ${formatDate(nextReportDate)})</div>
        `;
            }
            // Tiến độ bình thường
            else {
                return `
            <div class="small mb-1">Hạn cuối: <strong>${formatDate(endDate)}</strong></div>
            <div class="badge bg-info text-dark">Còn ${daysUntilEnd} ngày thực hiện</div>
        `;
            }
        }

        function formatDate(dateObj) {
            return dateObj.toLocaleDateString('vi-VN');
        }

        function renderPagination(totalItems) {
            const ul = document.getElementById('paginationProject');
            ul.innerHTML = '';
            const totalPages = Math.ceil(totalItems / ITEMS_PER_PAGE) || 1;

            const prevLi = document.createElement('li');
            prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
            prevLi.innerHTML = `<a class="page-link" href="javascript:void(0)">&laquo;</a>`;
            if (currentPage > 1) {
                prevLi.addEventListener('click', () => {
                    currentPage--;
                    renderProjectTable();
                });
            }
            ul.appendChild(prevLi);

            for (let i = 1; i <= totalPages; i++) {
                const li = document.createElement('li');
                li.className = `page-item ${i === currentPage ? 'active' : ''}`;
                li.innerHTML = `<a class="page-link" href="javascript:void(0)">${i}</a>`;
                li.addEventListener('click', () => {
                    currentPage = i;
                    renderProjectTable();
                });
                ul.appendChild(li);
            }

            const nextLi = document.createElement('li');
            nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
            nextLi.innerHTML = `<a class="page-link" href="javascript:void(0)">&raquo;</a>`;
            if (currentPage < totalPages) {
                nextLi.addEventListener('click', () => {
                    currentPage++;
                    renderProjectTable();
                });
            }
            ul.appendChild(nextLi);
        }

        function resetFilterDeTai() {
            document.getElementById('searchDeTai').value = "";
            document.getElementById('filterRegisterType').value = "";
            applyFilters();
        }

        function promptDeleteProject(projectId) {
            deleteTargetId = projectId;
            const modalInstance = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
            modalInstance.show();
        }

        document.getElementById('btnConfirmDelete').addEventListener('click', function() {
            const modalEl = document.getElementById('deleteConfirmModal');
            const modalInstance = bootstrap.Modal.getInstance(modalEl);
            modalInstance.hide();

            if (!deleteTargetId) return;

            const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');

            fetch(`${API_URL}?detect=project_delete`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        project_id: deleteTargetId
                    })
                })
                .then(res => res.json())
                .then(res => {
                    if (res.status === "success") {
                        showUINotification(res.message || "Xóa nhiệm vụ thành công!", "success");
                        loadInitialData();
                    } else {
                        showUINotification("Lỗi: " + res.message, "danger");
                    }
                })
                .catch(err => {
                    console.error("Lỗi xóa nhiệm vụ:", err);
                    showUINotification("Không thể kết nối đến máy chủ.", "danger");
                });
        });
    </script>
</body>

</html>