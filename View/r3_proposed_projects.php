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
    <title>Nhiệm Vụ Đề Xuất Thực Hiện - QL NCKH</title>
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

        a.btn-action-icon {
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

        a.btn-action-icon:hover {
            background-color: #f8fafc;
            transform: translateY(-1px);
        }

        a.btn-action-icon.view:hover {
            color: #2563eb;
            border-color: #93c5fd;
            background-color: #eff6ff;
        }

        a.btn-action-icon.edit:hover {
            color: #d97706;
            border-color: #fde68a;
            background-color: #fffbeb;
        }

        a.btn-action-icon.delete:hover {
            color: #dc2626;
            border-color: #fecaca;
            background-color: #fef2f2;
        }

        .badge-status {
            padding: 4px 8px;
            border-radius: 5px;
            font-weight: 600;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .status-pending {
            background-color: #fffbeb;
            color: #b45309;
            border: 1px solid #fde68a;
        }

        .status-rejected {
            background-color: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }
    </style>
</head>

<body>
    <!-- SIDEBAR -->
    <?php include '../Model/r3_sidebar.php'; ?>

    <div class="main-wrapper" id="mainWrapper">
        <!-- TOPBAR -->
        <?php
        $pageTitle = "NHIỆM VỤ ĐỀ XUẤT THỰC HIỆN";
        $pageIcon = "fa-solid fa-file-signature";
        include '../Model/r3_topbar.php';
        ?>

        <main class="container-fluid px-4 py-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold text-dark m-0 d-flex align-items-center gap-2">
                            <i class="fa-solid fa-list-check text-primary"></i> Danh Sách Nhiệm Vụ Đề Xuất
                        </h5>
                        <small class="text-muted">Theo dõi các đề xuất đang chờ cấp trên xét duyệt hoặc cần cập nhật sau khi bị từ chối</small>
                    </div>
                    <a href="/QLNCKH/dang-ky-de-tai-nckh" class="btn btn-primary btn-sm fw-semibold d-flex align-items-center gap-1 shadow-sm" id="btnNewProposal">
                        <i class="fa-solid fa-plus"></i> Đề xuất nhiệm vụ mới
                    </a>
                </div>

                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex gap-2">
                            <div style="position: relative;">
                                <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem;"></i>
                                <input type="text" id="searchInput" oninput="applyFilter()" placeholder="Tìm theo mã, tên nhiệm vụ, chủ nhiệm..." class="form-control form-control-sm" style="padding-left: 32px; width: 300px;">
                            </div>
                            <select id="statusFilter" onchange="applyFilter()" class="form-select form-select-sm" style="width: 170px;">
                                <option value="ALL">-- Tất cả trạng thái --</option>
                                <option value="pending">Chờ duyệt</option>
                                <option value="rejected">Từ chối</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0" style="font-size: 0.875rem;">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;" class="text-center">STT</th>
                                    <th style="width: 125px;">Mã Nhiệm vụ</th>
                                    <th>Tên Nhiệm vụ</th>
                                    <th style="width: 180px;">Hình thức</th>
                                    <th style="width: 180px;">Tên Chủ nhiệm</th>
                                    <th style="width: 200px;">Tổ chức chủ trì</th>
                                    <th style="width: 130px;" class="text-center">Trạng thái</th>
                                    <th style="width: 110px;" class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="proposedTableBody"></tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <small class="text-muted" id="pageInfo">Đang nạp dữ liệu...</small>
                        <ul class="pagination pagination-sm mb-0" id="paginationProposed"></ul>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- MODAL XÁC NHẬN XÓA HỒ SƠ ĐỀ XUẤT -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-danger text-white py-2">
                    <h6 class="modal-title fw-bold">
                        <i class="fa-solid fa-triangle-exclamation me-1"></i> Xác nhận xóa
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-3">
                    <input type="hidden" id="deleteProposedId">
                    <p class="mb-1 text-secondary" style="font-size: 0.85rem;">Bạn có chắc muốn hủy hồ sơ đề xuất này?</p>
                    <p class="fw-bold text-dark mb-0" id="deleteProposedTitleDisplay">---</p>
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

        let proposedList = [];
        let filteredList = [];
        const ITEMS_PER_PAGE = 5;
        let currentPage = 1;

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
            loadProposedData();
        });

        async function loadProposedData() {
            const currentUserId = getCurrentUserId();
            if (!currentUserId) return;

            try {
                const response = await fetchWithAuth(`${API_URL}?detect=project_get`);
                const result = await response.json();

                if (response.ok && result.status === 'success' && Array.isArray(result.data)) {
                    const allProjects = result.data;

                    // Kiểm tra điều kiện khóa nút Đề xuất mới
                    const hasActiveProject = allProjects.some(p => {
                        const isMine = parseInt(p.project_user_action_id || 0) === currentUserId;
                        const status = (p.status || '').toLowerCase();
                        return isMine && (status === 'running' || status === 'pending' || status === 'cho_duyet');
                    });

                    const btnNewProposal = document.getElementById('btnNewProposal');
                    if (btnNewProposal && hasActiveProject) {
                        btnNewProposal.classList.add('disabled', 'opacity-50');
                        btnNewProposal.setAttribute('title', 'Bạn đang có đề tài tiến hành hoặc chờ duyệt, không thể đề xuất mới.');
                        btnNewProposal.onclick = function(e) {
                            e.preventDefault();
                            alert("Bạn đang có đề tài đang tiến hành hoặc chờ duyệt. Mỗi giảng viên chỉ được thực hiện 1 đề tài duy nhất!");
                        };
                    }

                    // Lọc đề xuất đang chờ duyệt hoặc bị từ chối
                    const myProposals = allProjects.filter(p => {
                        const isMine = parseInt(p.project_user_action_id || 0) === currentUserId;
                        const st = (p.status || '').toLowerCase();
                        const isPendingOrRejected = (st === 'pending' || st === 'cho_duyet' || st === 'rejected');

                        const regTypeName = (p.register_type_name || '').toLowerCase();
                        const regTypeCode = (p.register_type_code || '').toLowerCase();
                        const isProposedType = regTypeName.includes('đặt hàng đề xuất') ||
                            regTypeName.includes('đề xuất') ||
                            regTypeCode.includes('de_xuat');

                        return isMine && isPendingOrRejected && isProposedType;
                    });

                    proposedList = myProposals.map(p => ({
                        id: p.project_id || p.id,
                        code: p.project_code || `NV${p.project_id}`,
                        name: p.project_name || p.name,
                        regTypeName: p.register_type_name || 'Nhiệm vụ đặt hàng đề xuất',
                        leader: p.leader_name || p.action_user_name || 'Chưa phân công',
                        organization: 'Trường Đại học An ninh nhân dân',
                        status: (p.status === 'cho_duyet' ? 'pending' : p.status) || 'pending'
                    }));
                }
            } catch (err) {
                console.error("Lỗi nạp danh sách đề xuất:", err);
            }

            applyFilter();
        }

        function applyFilter() {
            const keyword = document.getElementById('searchInput').value.toLowerCase().trim();
            const status = document.getElementById('statusFilter').value;

            filteredList = proposedList.filter(item => {
                const matchKeyword = item.name.toLowerCase().includes(keyword) ||
                    item.code.toLowerCase().includes(keyword) ||
                    item.leader.toLowerCase().includes(keyword);
                const matchStatus = (status === 'ALL') || (item.status === status);
                return matchKeyword && matchStatus;
            });

            currentPage = 1;
            renderTable();
        }

        function renderTable() {
            const tbody = document.getElementById('proposedTableBody');
            tbody.innerHTML = '';

            if (filteredList.length === 0) {
                tbody.innerHTML = `<tr><td colspan="8" class="text-center py-4 text-muted">Không có nhiệm vụ đặt hàng đề xuất nào phù hợp.</td></tr>`;
                document.getElementById('pageInfo').innerText = '0 / 0 hồ sơ';
                document.getElementById('paginationProposed').innerHTML = '';
                return;
            }

            const start = (currentPage - 1) * ITEMS_PER_PAGE;
            const paginated = filteredList.slice(start, start + ITEMS_PER_PAGE);

            paginated.forEach((item, index) => {
                const tr = document.createElement('tr');
                let statusHtml = '';

                if (item.status === 'pending') {
                    statusHtml = `<span class="badge-status status-pending"><i class="fa-regular fa-clock me-1"></i>Chờ duyệt</span>`;
                } else if (item.status === 'rejected') {
                    statusHtml = `<span class="badge-status status-rejected"><i class="fa-solid fa-xmark me-1"></i>Từ chối</span>`;
                }

                tr.innerHTML = `
                    <td class="text-center fw-bold">${start + index + 1}</td>
                    <td class="fw-bold"><code>${item.code}</code></td>
                    <td class="fw-semibold text-dark">${item.name}</td>
                    <td>
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 fw-normal">
                            ${item.regTypeName}
                        </span>
                    </td>
                    <td><i class="fa-solid fa-user-tie text-secondary me-1"></i>${item.leader}</td>
                    <td><small class="text-muted"><i class="fa-solid fa-building-columns me-1"></i>${item.organization}</small></td>
                    <td class="text-center">${statusHtml}</td>
                    <td class="text-center">
                        <div class="d-inline-flex align-items-center justify-content-center gap-1">
                            <a href="/QLNCKH/chi-tiet-de-tai?id=${item.id}&mode=view" class="btn-action-icon view" title="Xem chi tiết hồ sơ">
                                <i class="fa-regular fa-eye"></i>
                            </a>
                            <a href="/QLNCKH/dang-ky-de-tai-nckh?id=${item.id}&mode=edit" class="btn-action-icon edit" title="Cập nhật đề xuất">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>
                            <a href="javascript:void(0)" onclick="confirmDelete(${item.id}, '${item.name.replace(/'/g, "\\'")}')" class="btn-action-icon delete" title="Hủy hồ sơ đề xuất">
                                <i class="fa-regular fa-trash-can"></i>
                            </a>
                        </div>
                    </td>
                `;
                tbody.appendChild(tr);
            });

            document.getElementById('pageInfo').innerText = `Hiển thị ${paginated.length} / ${filteredList.length} hồ sơ đề xuất`;
            renderPagination(filteredList.length);
        }

        function renderPagination(totalItems) {
            const ul = document.getElementById('paginationProposed');
            ul.innerHTML = '';
            const totalPages = Math.ceil(totalItems / ITEMS_PER_PAGE) || 1;

            const prevLi = document.createElement('li');
            prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
            prevLi.innerHTML = `<a class="page-link" href="javascript:void(0)">&laquo;</a>`;
            if (currentPage > 1) {
                prevLi.addEventListener('click', () => {
                    currentPage--;
                    renderTable();
                });
            }
            ul.appendChild(prevLi);

            for (let i = 1; i <= totalPages; i++) {
                const li = document.createElement('li');
                li.className = `page-item ${i === currentPage ? 'active' : ''}`;
                li.innerHTML = `<a class="page-link" href="javascript:void(0)">${i}</a>`;
                li.addEventListener('click', () => {
                    currentPage = i;
                    renderTable();
                });
                ul.appendChild(li);
            }

            const nextLi = document.createElement('li');
            nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
            nextLi.innerHTML = `<a class="page-link" href="javascript:void(0)">&raquo;</a>`;
            if (currentPage < totalPages) {
                nextLi.addEventListener('click', () => {
                    currentPage++;
                    renderTable();
                });
            }
            ul.appendChild(nextLi);
        }

        function promptDeleteProject(id, title) {
            confirmDelete(id, title);
        }

        function confirmDelete(id, title) {
            document.getElementById('deleteProposedId').value = id;
            document.getElementById('deleteProposedTitleDisplay').innerText = title;
            const modalEl = document.getElementById('deleteConfirmModal');
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        }

        async function handleConfirmDelete() {
            const id = document.getElementById('deleteProposedId').value;
            if (!id) return;

            try {
                const res = await fetchWithAuth(`${API_URL}?detect=project_delete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        project_id: id
                    })
                });
                const result = await res.json();
                if (res.ok && result.status === 'success') {
                    alert(result.message || "Đã hủy hồ sơ đề xuất!");
                }
            } catch (e) {
                console.error("Lỗi xóa đề xuất:", e);
            }

            proposedList = proposedList.filter(item => String(item.id) !== String(id));
            const modalEl = document.getElementById('deleteConfirmModal');
            const instance = bootstrap.Modal.getInstance(modalEl);
            if (instance) instance.hide();

            applyFilter();
        }
    </script>
</body>

</html>