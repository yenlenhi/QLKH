<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <!-- <base href="/QLNCKH/View/"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../asset/script/auth_check.js"></script>
    <link rel="stylesheet" href="../asset/css/all.min.css">
    <link href="../asset/css/bootstrap5.css" rel="stylesheet">
    <link rel="stylesheet" href="../asset/css/style.css">
    <title>Trang Chủ - Bàn Làm Việc Nhà Khoa Học</title>
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
        }

        .dashboard-section-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 22px 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            margin-bottom: 20px;
        }

        .status-kpi-card {
            border-radius: 10px;
            padding: 16px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #cbd5e1;
            transition: all 0.2s ease;
        }

        .status-kpi-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
        }

        .status-kpi-card.on-time {
            border-left-color: #10b981;
        }

        .status-kpi-card.warning {
            border-left-color: #f59e0b;
        }

        .status-kpi-card.danger {
            border-left-color: #ef4444;
        }
    </style>
</head>

<body>
    <!-- SIDEBAR R3 -->
    <?php include '../Model/r3_sidebar.php'; ?>

    <div class="main-wrapper" id="mainWrapper">
        <!-- TOPBAR -->
        <?php
        $pageTitle = "BÀN LÀM VIỆC NHÀ KHOA HỌC";
        $pageIcon = "fa-solid fa-flask-vial";
        include '../Model/r3_topbar.php';
        ?>

        <main class="container-fluid px-4 py-3">

            <!-- KHỐI 1: TỔNG QUAN TIẾN ĐỘ NHIỆM VỤ NCKH -->
            <div class="dashboard-section-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-dark m-0 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-list-check text-primary"></i> Thống kê tình hình nhiệm vụ NCKH
                    </h6>
                    <div class="d-flex align-items-center gap-2">
                        <span class="small text-muted fw-semibold">Năm:</span>
                        <select id="yearFilter" class="form-select form-select-sm" style="width: 105px;" onchange="loadDashboardKpi()">
                            <option value="2026" selected>2026</option>
                            <option value="2025">2025</option>
                            <option value="ALL">Tất cả</option>
                        </select>
                    </div>
                </div>

                <div class="row align-items-center g-3">
                    <div class="col-lg-3 text-center border-end">
                        <div class="text-muted small fw-semibold text-uppercase">Nhiệm vụ đảm nhiệm</div>
                        <div class="display-5 fw-bold text-primary my-1" id="statTotalProjects">0</div>
                        <a href="/QLNCKH/nhiem-vu-cua-toi" class="badge bg-light text-primary border text-decoration-none px-3 py-1">
                            Xem chi tiết <i class="fa-solid fa-arrow-right ms-1"></i>
                        </a>
                    </div>

                    <div class="col-lg-9">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="status-kpi-card on-time">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-bold small text-dark">Đúng hạn</span>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">Chuẩn tiến độ</span>
                                    </div>
                                    <div class="d-flex align-items-baseline gap-1">
                                        <span class="fs-3 fw-bold text-success" id="statOnTimeCount">0</span>
                                        <small class="text-muted" id="statOnTimePercent">(0%)</small>
                                    </div>
                                    <div class="small text-muted" style="font-size: 0.75rem;">Nhiệm vụ xử lý đúng hạn</div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="status-kpi-card warning">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-bold small text-dark">Sắp đến kỳ báo cáo</span>
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle">Cần nộp</span>
                                    </div>
                                    <div class="d-flex align-items-baseline gap-1">
                                        <span class="fs-3 fw-bold text-warning" id="statWarningCount">0</span>
                                        <small class="text-muted" id="statWarningPercent">(0%)</small>
                                    </div>
                                    <div class="small text-muted" style="font-size: 0.75rem;">Thời hạn dưới 15 ngày</div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="status-kpi-card danger">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-bold small text-dark">Quá hạn</span>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Trễ hạn</span>
                                    </div>
                                    <div class="d-flex align-items-baseline gap-1">
                                        <span class="fs-3 fw-bold text-danger" id="statOverdueCount">0</span>
                                        <small class="text-muted" id="statOverduePercent">(0%)</small>
                                    </div>
                                    <div class="small text-muted" style="font-size: 0.75rem;">Nhiệm vụ bị trễ hạn</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KHỐI 2: ĐĂNG KÝ NHIỆM VỤ (2 TAB, PHÂN TRANG ĐỘC LẬP) -->
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-0 pt-3 px-3 pb-0">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold text-dark m-0 d-flex align-items-center gap-2">
                            <i class="fa-solid fa-file-signature text-primary"></i> Đăng ký nhiệm vụ
                        </h6>
                    </div>
                    <ul class="nav nav-tabs card-header-tabs" id="taskTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active fw-bold text-primary" id="sponsoredTabBtn" data-bs-toggle="tab" data-bs-target="#tabSponsored" type="button">
                                <i class="fa-solid fa-hand-holding-dollar me-1"></i> Nhiệm vụ tài trợ
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link fw-bold text-secondary" id="orderedTabBtn" data-bs-toggle="tab" data-bs-target="#tabOrdered" type="button">
                                <i class="fa-solid fa-bullhorn me-1"></i> Nhiệm vụ đặt hàng
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-3">
                    <div class="tab-content" id="taskTabsContent">

                        <!-- TAB 1: NHIỆM VỤ TÀI TRỢ -->
                        <div class="tab-pane fade show active" id="tabSponsored" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <small class="text-muted">Các nhiệm vụ tài trợ sẵn có (user_id = 0). Chọn đăng ký hoặc đề xuất thêm nhiệm vụ mới.</small>
                                <a href="/QLNCKH/dang-ky-de-tai-nckh" class="btn btn-primary btn-sm fw-semibold shadow-sm" id="btnProposeNew">
                                    <i class="fa-solid fa-plus me-1"></i> Đề xuất đề tài
                                </a>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle mb-0" style="font-size: 0.875rem;">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 60px;" class="text-center">STT</th>
                                            <th>Tên nhiệm vụ</th>
                                            <th style="width: 220px;">Cơ quan tài trợ</th>
                                            <th style="width: 170px;" class="text-center">Thời hạn đăng ký</th>
                                            <th style="width: 90px;" class="text-center">Đợt</th>
                                            <th style="width: 140px;" class="text-center">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody id="sponsoredListBody"></tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <small class="text-muted" id="pageInfoSponsored">Đang nạp dữ liệu...</small>
                                <ul class="pagination pagination-sm mb-0" id="paginationSponsored"></ul>
                            </div>
                        </div>

                        <!-- TAB 2: NHIỆM VỤ ĐẶT HÀNG -->
                        <div class="tab-pane fade" id="tabOrdered" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <small class="text-muted">Các nhiệm vụ đặt hàng tuyển chọn công khai từ Nhà trường và các đơn vị đối tác.</small>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle mb-0" style="font-size: 0.875rem;">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 60px;" class="text-center">STT</th>
                                            <th>Tên nhiệm vụ</th>
                                            <th style="width: 220px;">Cơ quan tài trợ</th>
                                            <th style="width: 170px;" class="text-center">Thời hạn đăng ký</th>
                                            <th style="width: 90px;" class="text-center">Đợt</th>
                                            <th style="width: 140px;" class="text-center">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody id="orderedListBody"></tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <small class="text-muted" id="pageInfoOrdered">Đang nạp dữ liệu...</small>
                                <ul class="pagination pagination-sm mb-0" id="paginationOrdered"></ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- MODAL TOPBAR -->
    <?php include '../Model/r3_modal_topbar.php'; ?>

    <script src="../asset/script/bootstrap5.js"></script>
    <script src="../asset/script/sidebar.js"></script>

    <script>
        // const APP_BASE = '/QLNCKH/';
        const API_URL = `..../index_api.php`;

        let sponsoredData = [];
        let orderedData = [];
        let isUserBlocked = false; // Cờ khóa đăng ký & đề xuất

        const ITEMS_PER_PAGE = 3;
        let pageSponsored = 1;
        let pageOrdered = 1;

        function getCurrentUserId() {
            const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
            if (token) {
                try {
                    const payload = JSON.parse(atob(token.split('.')[1]));
                    return parseInt(payload.user_id || payload.id || 0);
                } catch (e) {}
            }
            return 0;
        }

        document.addEventListener("DOMContentLoaded", async function() {
            await initDashboard();
        });

        async function initDashboard() {
            await loadDashboardKpiAndCheckStatus();
            await fetchAvailableTasks();
        }

        // 1. THỐNG KÊ THEO project_user_action_id VÀ KIỂM TRA ĐIỀU KIỆN KHÓA
        async function loadDashboardKpiAndCheckStatus() {
            const currentUserId = getCurrentUserId();
            if (!currentUserId) return;

            try {
                const selectedYear = document.getElementById('yearFilter') ? document.getElementById('yearFilter').value : 'ALL';

                const response = await fetchWithAuth(`${API_URL}?detect=project_get`, {
                    method: 'GET'
                });
                const result = await response.json();

                if (response.ok && result.status === 'success') {
                    const allProjects = result.data || [];

                    // KIỂM TRA ĐIỀU KIỆN KHÓA:
                    // Nếu user có đề tài đang 'running' hoặc 'pending' mà mình là người thực hiện
                    const activeProject = allProjects.find(p => {
                        const isMine = parseInt(p.project_user_action_id || 0) === currentUserId;
                        const status = (p.status || '').toLowerCase();
                        return isMine && (status === 'running' || status === 'pending');
                    });

                    if (activeProject) {
                        isUserBlocked = true;
                        applyLockUi();
                    }

                    // CHỈ THỐNG KÊ CÁC ĐỀ TÀI DO USER NÀY ĐẢM NHIỆM
                    let myProjects = allProjects.filter(p => parseInt(p.project_user_action_id || 0) === currentUserId);

                    if (selectedYear !== 'ALL') {
                        myProjects = myProjects.filter(p => {
                            const dateStr = p.created_at || p.approved_date || p.start_date || '';
                            if (!dateStr) return false;
                            return new Date(dateStr).getFullYear().toString() === selectedYear;
                        });
                    }

                    let total = myProjects.length,
                        onTime = 0,
                        warning = 0,
                        overdue = 0;

                    const today = new Date();

                    myProjects.forEach(p => {
                        const status = p.status || 'pending';
                        if (status === 'running' && p.approved_date) {
                            const approvedDate = new Date(p.approved_date);
                            const durationMonths = parseInt(p.duration_months) || 12;
                            const endDate = new Date(approvedDate);
                            endDate.setMonth(endDate.getMonth() + durationMonths);

                            const diffDays = Math.ceil((endDate - today) / (1000 * 60 * 60 * 24));
                            if (diffDays < 0) overdue++;
                            else if (diffDays <= 15) warning++;
                            else onTime++;
                        } else if (status === 'running') {
                            onTime++;
                        }
                    });

                    document.getElementById('statTotalProjects').innerText = total;
                    document.getElementById('statOnTimeCount').innerText = onTime;
                    document.getElementById('statWarningCount').innerText = warning;
                    document.getElementById('statOverdueCount').innerText = overdue;

                    const calcPct = count => (total > 0 ? Math.round((count / total) * 100) : 0);
                    document.getElementById('statOnTimePercent').innerText = `(${calcPct(onTime)}%)`;
                    document.getElementById('statWarningPercent').innerText = `(${calcPct(warning)}%)`;
                    document.getElementById('statOverduePercent').innerText = `(${calcPct(overdue)}%)`;
                }
            } catch (err) {
                console.error("Lỗi tải KPI:", err);
            }
        }

        // KHÓA GIAO DIỆN NÚT ĐỀ XUẤT NẾU ĐANG CÓ ĐỀ TÀI RUNNING/PENDING
        function applyLockUi() {
            const btnPropose = document.querySelector('a[href="/QLNCKH/dang-ky-de-tai-nckh"]');
            if (btnPropose) {
                btnPropose.classList.add('disabled', 'opacity-50');
                btnPropose.setAttribute('title', 'Bạn đang có đề tài đang tiến hành hoặc chờ duyệt, không thể đề xuất mới.');
                btnPropose.onclick = (e) => {
                    e.preventDefault();
                    alert("Bạn hiện đang có một đề tài đang tiến hành hoặc chờ xét duyệt. Mỗi giảng viên chỉ được thực hiện một đề tài tại một thời điểm!");
                };
            }
        }

        // 2. LẤY NHIỆM VỤ TÀI TRỢ VÀ ĐẶT HÀNG TỪ API (CHƯA CÓ NGƯỜI LÀM: action_id = 0 hoặc null)
        async function fetchAvailableTasks() {
            try {
                const response = await fetchWithAuth(`${API_URL}?detect=project_get`, {
                    method: 'GET'
                });
                const result = await response.json();

                if (response.ok && result.status === 'success') {
                    const list = result.data || [];

                    // Lọc các đề tài chưa có người làm
                    const unassignedList = list.filter(p => !p.project_user_action_id || parseInt(p.project_user_action_id) === 0);

                    // Tab 1: Nhiệm vụ tài trợ (type_code = tai_tro)
                    sponsoredData = unassignedList.filter(p => {
                        const code = (p.register_type_code || p.type_code || p.project_type_code || '').toLowerCase().trim();
                        return code === 'tai_tro';
                    });

                    // Tab 2: Nhiệm vụ đặt hàng (type_code = dat_hang_tuyen_chon)
                    orderedData = unassignedList.filter(p => {
                        const code = (p.register_type_code || p.type_code || p.project_type_code || '').toLowerCase().trim();
                        return code === 'dat_hang_tuyen_chon';
                    });

                    pageSponsored = 1;
                    pageOrdered = 1;
                    renderSponsoredTable();
                    renderOrderedTable();
                }
            } catch (err) {
                console.error("Lỗi lấy danh sách nhiệm vụ mở:", err);
            }
        }

        function renderSponsoredTable() {
            const tbody = document.getElementById('sponsoredListBody');
            tbody.innerHTML = '';

            if (sponsoredData.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-muted">Hiện chưa có nhiệm vụ tài trợ nào đang mở đăng ký.</td></tr>`;
                document.getElementById('pageInfoSponsored').innerText = `Hiển thị 0 / 0 nhiệm vụ`;
                document.getElementById('paginationSponsored').innerHTML = '';
                return;
            }

            const start = (pageSponsored - 1) * ITEMS_PER_PAGE;
            const paginated = sponsoredData.slice(start, start + ITEMS_PER_PAGE);

            paginated.forEach((item, index) => {
                const tr = document.createElement('tr');
                const deadline = item.registration_deadline || item.end_date || 'Chưa ấn định';
                const round = item.round_name || 'Đợt 1';
                const sponsor = item.sponsor_name || item.organization || 'Hội đồng NCKH';

                const registerBtnAttr = isUserBlocked ?
                    `class="btn btn-sm btn-outline-secondary fw-semibold disabled" title="Đang tiến hành đề tài khác, không thể đăng ký" disabled` :
                    `class="btn btn-sm btn-outline-primary fw-semibold" onclick='goToRegisterPage(${JSON.stringify(item)})'`;

                tr.innerHTML = `
                <td class="text-center fw-bold">${start + index + 1}</td>
                <td class="fw-semibold text-primary">
                    ${item.project_name || item.name}
                    <div class="small text-muted fw-normal">Mã NV: <code>${item.project_code || ('NV' + item.project_id)}</code></div>
                </td>
                <td>${sponsor}</td>
                <td class="text-center text-danger fw-semibold">${deadline}</td>
                <td class="text-center"><span class="badge bg-light text-dark border">${round}</span></td>
                <td class="text-center">
                    <button ${registerBtnAttr}>
                        <i class="fa-solid fa-file-signature me-1"></i> Đăng ký
                    </button>
                </td>
            `;
                tbody.appendChild(tr);
            });

            document.getElementById('pageInfoSponsored').innerText = `Hiển thị ${paginated.length} / ${sponsoredData.length} nhiệm vụ`;
            renderPagination('paginationSponsored', sponsoredData.length, pageSponsored, (p) => {
                pageSponsored = p;
                renderSponsoredTable();
            });
        }

        function renderOrderedTable() {
            const tbody = document.getElementById('orderedListBody');
            tbody.innerHTML = '';

            if (orderedData.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-muted">Hiện chưa có nhiệm vụ đặt hàng nào đang mở.</td></tr>`;
                document.getElementById('pageInfoOrdered').innerText = `Hiển thị 0 / 0 nhiệm vụ`;
                document.getElementById('paginationOrdered').innerHTML = '';
                return;
            }

            const start = (pageOrdered - 1) * ITEMS_PER_PAGE;
            const paginated = orderedData.slice(start, start + ITEMS_PER_PAGE);

            paginated.forEach((item, index) => {
                const tr = document.createElement('tr');
                const deadline = item.registration_deadline || item.end_date || 'Chưa ấn định';
                const round = item.round_name || 'Đợt 1';
                const sponsor = item.sponsor_name || item.organization || 'Trường Đại học An ninh nhân dân';

                const registerBtnAttr = isUserBlocked ?
                    `class="btn btn-sm btn-secondary fw-semibold disabled" title="Đang tiến hành đề tài khác, không thể đăng ký" disabled` :
                    `class="btn btn-sm btn-primary fw-semibold" onclick='goToRegisterPage(${JSON.stringify(item)})'`;

                tr.innerHTML = `
                <td class="text-center fw-bold">${start + index + 1}</td>
                <td class="fw-semibold text-dark">
                    ${item.project_name || item.name}
                    <div class="small text-muted fw-normal">Mã đặt hàng: <code>${item.project_code || ('NVDH' + item.project_id)}</code></div>
                </td>
                <td>${sponsor}</td>
                <td class="text-center text-danger fw-semibold">${deadline}</td>
                <td class="text-center"><span class="badge bg-light text-dark border">${round}</span></td>
                <td class="text-center">
                    <button ${registerBtnAttr}>
                        <i class="fa-solid fa-file-contract me-1"></i> Đăng ký
                    </button>
                </td>
            `;
                tbody.appendChild(tr);
            });

            document.getElementById('pageInfoOrdered').innerText = `Hiển thị ${paginated.length} / ${orderedData.length} nhiệm vụ`;
            renderPagination('paginationOrdered', orderedData.length, pageOrdered, (p) => {
                pageOrdered = p;
                renderOrderedTable();
            });
        }

        function goToRegisterPage(taskItem) {
            if (isUserBlocked) {
                alert("Bạn đang có một đề tài đang tiến hành hoặc chờ xét duyệt. Không thể đăng ký thêm nhiệm vụ!");
                return;
            }
            if (!taskItem) return;

            const targetId = taskItem.project_id || taskItem.id || 0;

            // Thêm dấu "/" ở đầu để trỏ thẳng về route chuẩn của .htaccess
            window.location.href = `/QLNCKH/dang-ky-de-tai-nckh?id=${encodeURIComponent(targetId)}`;
        }

        function renderPagination(containerId, totalItems, currentPage, onPageChange) {
            const ul = document.getElementById(containerId);
            if (!ul) return;
            ul.innerHTML = '';

            const totalPages = Math.ceil(totalItems / ITEMS_PER_PAGE) || 1;
            if (totalPages <= 1) return; // Nếu chỉ có 1 trang thì không cần hiện nút chuyển

            // Nút Trước («)
            const prevLi = document.createElement('li');
            prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
            prevLi.innerHTML = `<a class="page-link" href="javascript:void(0)" aria-label="Previous">&laquo;</a>`;
            if (currentPage > 1) {
                prevLi.onclick = () => onPageChange(currentPage - 1);
            }
            ul.appendChild(prevLi);

            // Các nút số trang
            for (let i = 1; i <= totalPages; i++) {
                const li = document.createElement('li');
                li.className = `page-item ${i === currentPage ? 'active' : ''}`;
                li.innerHTML = `<a class="page-link" href="javascript:void(0)">${i}</a>`;
                if (i !== currentPage) {
                    li.onclick = () => onPageChange(i);
                }
                ul.appendChild(li);
            }

            // Nút Tiếp theo (»)
            const nextLi = document.createElement('li');
            nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
            nextLi.innerHTML = `<a class="page-link" href="javascript:void(0)" aria-label="Next">&raquo;</a>`;
            if (currentPage < totalPages) {
                nextLi.onclick = () => onPageChange(currentPage + 1);
            }
            ul.appendChild(nextLi);
        }
    </script>
</body>

</html>