<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <base href="/QLNCKH/View/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thùng Rác Nhiệm Vụ - NCKH</title>
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

        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: var(--dark-color);
            color: #fff;
            transition: all 0.3s;
            z-index: 1000;
        }

        #sidebar .nav-link {
            color: #94a3b8;
            padding: 12px 20px;
            margin: 4px 10px;
            border-radius: 8px;
        }

        #sidebar .nav-link:hover,
        #sidebar .nav-link.active {
            color: #fff;
            background-color: var(--primary-color);
        }

        #main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
        }

        .btn-action-sm {
            padding: 4px 8px;
            font-size: 0.78rem;
            font-weight: 600;
            border-radius: 5px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
    </style>
</head>

<body>
    <!-- Sidebar Menu -->
    <?php include '../Model/sidebar.php'; ?>

    <!-- Main Content -->
    <div id="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="m-0 text-secondary fw-bold">
                    <i class="fa-solid fa-trash-arrow-up text-danger me-2"></i>Thùng Rác Nhiệm Vụ
                </h4>
                <small class="text-muted">Các nhiệm vụ bị xóa sẽ được lưu trữ tại đây trong vòng 30 ngày trước khi bị xóa vĩnh viễn.</small>
            </div>
        </div>

        <!-- KHU VỰC HIỂN THỊ THÔNG BÁO -->
        <div id="alertContainer"></div>

        <!-- BẢNG DANH SÁCH NHIỆM VỤ TRONG THÙNG RÁC -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-secondary">
                    <i class="fa-solid fa-box-archive me-2"></i>Danh Sách Nhiệm Vụ Đã Xóa Mềm
                </h6>
                <span class="badge bg-light text-secondary border px-3 py-2" id="trashCountBadge">Đang nạp...</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0" id="tableTrashProjects" style="font-size: 0.875rem;">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;" class="text-center">STT</th>
                                <th style="width: 125px;">Mã Nhiệm vụ</th>
                                <th>Tên Nhiệm vụ</th>
                                <th style="width: 180px;">Tên Chủ nhiệm</th>
                                <th style="width: 200px;">Tài khoản nhận nhiệm vụ</th>
                                <th style="width: 150px;" class="text-center">Thời gian xóa</th>
                                <th style="width: 190px;" class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fa-solid fa-spinner fa-spin me-2"></i>Đang tải dữ liệu thùng rác...
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
                        <i class="fa-solid fa-triangle-exclamation text-warning fa-3x"></i>
                    </div>
                    <h5 class="fw-bold mb-2" id="confirmTitle">Xác nhận</h5>
                    <p class="text-muted small mb-4" id="confirmMessage">Bạn có chắc chắn muốn thực hiện hành động này?</p>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-secondary btn-sm px-3 fw-semibold" data-bs-dismiss="modal">Hủy</button>
                        <button type="button" class="btn btn-primary btn-sm px-3 fw-semibold" id="btnConfirmExecute">Xác nhận</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="../asset/script/bootstrap5.js"></script>
    <script src="../asset/script/sidebar.js"></script>
    <script>
        const API_URL = '../index_api.php';
        let confirmModal = null;
        let pendingConfirmCallback = null;

        document.addEventListener("DOMContentLoaded", function() {
            confirmModal = new bootstrap.Modal(document.getElementById('modalConfirmAction'));

            document.getElementById('btnConfirmExecute').addEventListener('click', function() {
                if (typeof pendingConfirmCallback === 'function') {
                    pendingConfirmCallback();
                }
                if (confirmModal) confirmModal.hide();
            });

            loadTrashProjects();
        });

        async function loadTrashProjects() {
            const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
            try {
                const response = await fetch(`${API_URL}?detect=project_trash_get`, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });
                const result = await response.json();

                if (response.ok && result.status === 'success') {
                    renderTrashTable(result.data || []);
                } else {
                    renderTrashTable([]);
                }
            } catch (err) {
                console.error("Lỗi tải thùng rác:", err);
                renderTrashTable([]);
            }
        }

        function renderTrashTable(projects) {
            const tbody = document.querySelector("#tableTrashProjects tbody");
            const badge = document.getElementById("trashCountBadge");
            tbody.innerHTML = "";

            if (!projects || projects.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted py-4">Thùng rác hiện đang trống.</td></tr>`;
                badge.innerText = "0 nhiệm vụ";
                return;
            }

            badge.innerText = `${projects.length} nhiệm vụ`;

            projects.forEach((p, index) => {
                const tr = document.createElement("tr");

                // 1. Format hiển thị Chủ nhiệm
                const leaderDisplay = `
                    <div class="fw-semibold text-dark">
                        <i class="fa-solid fa-user-tie text-secondary me-1"></i>${escapeHtml(p.leader_name || 'Chưa cập nhật')}
                    </div>
                `;

                // 2. Format hiển thị Tài khoản tạo
                let creatorDisplay = '<span class="text-muted fst-italic">Hệ thống tạo</span>';
                if (p.creator_name || p.creator_username) {
                    const roleBadge = p.creator_type ? `<span class="badge bg-light text-secondary border ms-1" style="font-size:0.7rem;">${escapeHtml(p.creator_type)}</span>` : '';
                    creatorDisplay = `
                        <div class="fw-semibold text-dark">
                            <i class="fa-solid fa-user-pen text-primary me-1"></i>${escapeHtml(p.creator_name || p.creator_username)}
                            ${roleBadge}
                        </div>
                        <small class="text-muted" style="font-size:0.75rem;">Username: <code>${escapeHtml(p.creator_username || '---')}</code></small>
                    `;
                }

                tr.innerHTML = `
                    <td class="fw-bold text-center">${index + 1}</td>
                    <td><span class="fw-bold text-primary"><code>${escapeHtml(p.project_code || ('NV' + p.project_id))}</code></span></td>
                    <td><div class="fw-bold text-dark">${escapeHtml(p.project_name)}</div></td>
                    <td>${leaderDisplay}</td>
                    <td>${creatorDisplay}</td>
                    <td class="text-center"><small class="text-danger fw-semibold"><i class="fa-regular fa-clock me-1"></i>${p.deleted_at || '---'}</small></td>
                    <td class="text-center">
                        <button class="btn btn-action-sm btn-outline-success me-1" onclick="restoreProject(${p.project_id}, '${escapeHtml(p.project_name)}')" title="Khôi phục lại nhiệm vụ">
                            <i class="fa-solid fa-rotate-left"></i> Khôi phục
                        </button>
                        <button class="btn btn-action-sm btn-outline-danger" onclick="forceDeleteProject(${p.project_id}, '${escapeHtml(p.project_name)}')" title="Xóa vĩnh viễn">
                            <i class="fa-solid fa-trash-can"></i> Xóa hẳn
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        function restoreProject(projectId, projectName) {
            document.getElementById('confirmTitle').innerText = "Xác nhận khôi phục";
            document.getElementById('confirmMessage').innerText = `Bạn có chắc chắn muốn khôi phục nhiệm vụ "${projectName}" về danh sách quản lý ban đầu?`;

            const btnExec = document.getElementById('btnConfirmExecute');
            btnExec.className = "btn btn-success btn-sm px-3 fw-semibold";
            btnExec.innerText = "Khôi phục";

            pendingConfirmCallback = async function() {
                const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
                try {
                    const response = await fetch(`${API_URL}?detect=project_restore`, {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            project_id: projectId
                        })
                    });
                    const result = await response.json();

                    if (response.ok && result.status === 'success') {
                        showUINotification(result.message || "Khôi phục nhiệm vụ thành công!", "success");
                        loadTrashProjects();
                    } else {
                        showUINotification("Lỗi: " + result.message, "danger");
                    }
                } catch (err) {
                    console.error("Lỗi khôi phục:", err);
                    showUINotification("Không thể kết nối đến máy chủ.", "danger");
                }
            };

            confirmModal.show();
        }

        function forceDeleteProject(projectId, projectName) {
            document.getElementById('confirmTitle').innerText = "Cảnh báo xóa vĩnh viễn";
            document.getElementById('confirmMessage').innerText = `Hành động này sẽ xóa vĩnh viễn nhiệm vụ "${projectName}" cùng toàn bộ file đính kèm liên quan!`;

            const btnExec = document.getElementById('btnConfirmExecute');
            btnExec.className = "btn btn-danger btn-sm px-3 fw-semibold";
            btnExec.innerText = "Xóa vĩnh viễn";

            pendingConfirmCallback = async function() {
                const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
                try {
                    const response = await fetch(`${API_URL}?detect=project_force_delete`, {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            project_id: projectId
                        })
                    });
                    const result = await response.json();

                    if (response.ok && result.status === 'success') {
                        showUINotification(result.message || "Đã xóa vĩnh viễn thành công!", "success");
                        loadTrashProjects();
                    } else {
                        showUINotification("Lỗi: " + result.message, "danger");
                    }
                } catch (err) {
                    console.error("Lỗi xóa vĩnh viễn:", err);
                    showUINotification("Không thể kết nối đến máy chủ.", "danger");
                }
            };

            confirmModal.show();
        }

        function showUINotification(message, type = "success") {
            const container = document.getElementById('alertContainer');
            if (!container) return;
            const alertId = 'alert_' + Date.now();
            container.innerHTML = `
                <div id="${alertId}" class="alert alert-${type} alert-dismissible fade show shadow-sm mb-4" role="alert">
                    <i class="fa-solid ${type === 'success' ? 'fa-circle-check' : 'fa-triangle-exclamation'} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            setTimeout(() => {
                const alertEl = document.getElementById(alertId);
                if (alertEl) bootstrap.Alert.getOrCreateInstance(alertEl).close();
            }, 4000);
        }

        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
        }
    </script>
</body>

</html>