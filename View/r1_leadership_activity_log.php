<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <base href="/QLNCKH/View/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NCKH PORTAL - Lịch Sử Hoạt Động Hệ Thống</title>
    <script src="../asset/script/auth_check.js"></script>
    <link href="../asset/css/bootstrap5.css" rel="stylesheet">
    <link rel="stylesheet" href="../asset/css/all.min.css">
    <link rel="stylesheet" href="../asset/css/style_admin.css">
</head>

<body>

    <!-- SIDEBAR QUẢN TRỊ CHUNG -->
    <?php include '../Model/sidebar.php'; ?>

    <div id="main-content">
        
        <!-- TOPBAR -->
        <?php 
        $pageTitle = "LỊCH SỬ HOẠT ĐỘNG HỆ THỐNG";
        include '../Model/r1_topbar.php'; 
        ?>

        <div id="adminTabContent">
            <div class="card shadow-sm border-0 mx-4 my-3">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fa-solid fa-clock-rotate-left me-2"></i> Nhật Ký Thao Tác Toàn Hệ Thống (CB & LD)
                    </h6>
                    <button class="btn btn-outline-primary btn-sm fw-semibold" onclick="fetchSystemLogs()">
                        <i class="fa-solid fa-rotate me-1"></i> Làm mới dữ liệu
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-3 px-3">Thời gian</th>
                                    <th class="py-3">Người thực hiện</th>
                                    <th class="py-3">Hành động</th>
                                    <th class="py-3">Đối tượng tác động</th>
                                    <th class="py-3">Nội dung chi tiết</th>
                                    <th class="py-3">Địa chỉ IP</th>
                                </tr>
                            </thead>
                            <tbody id="systemLogsTableBody">
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="fa-solid fa-spinner fa-spin me-2"></i> Đang tải dữ liệu lịch sử hệ thống...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS & Sidebar JS -->
    <script src="../asset/script/bootstrap5.js"></script>
    <script src="../asset/script/sidebar.js"></script>

    <script>
        const API_URL = '/QLNCKH/index_api.php';

        document.addEventListener("DOMContentLoaded", function() {
            fetchSystemLogs();
        });

        async function fetchSystemLogs() {
            const tbody = document.getElementById('systemLogsTableBody');
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        <i class="fa-solid fa-spinner fa-spin me-2"></i> Đang tải dữ liệu lịch sử hệ thống...
                    </td>
                </tr>
            `;

            try {
                // Gọi API lấy lịch sử hoạt động
                const response = await fetchWithAuth(`${API_URL}?detect=get_activity_logs`, {
                    method: 'GET'
                });
                const result = await response.json();

                if (response.ok && result.status === 'success') {
                    const logs = result.data || [];
                    if (logs.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-muted">Chưa có nhật ký hoạt động nào trong hệ thống.</td></tr>`;
                        return;
                    }

                    tbody.innerHTML = logs.map(l => {
                        let badgeColor = 'bg-secondary';
                        if (l.action_type === 'CREATE') badgeColor = 'bg-success';
                        else if (l.action_type === 'UPDATE') badgeColor = 'bg-primary';
                        else if (l.action_type === 'DELETE' || l.action_type === 'REJECT') badgeColor = 'bg-danger';
                        else if (l.action_type === 'APPROVE' || l.action_type === 'PAYMENT') badgeColor = 'bg-info text-dark';

                        return `
                            <tr>
                                <td class="text-nowrap text-muted small px-3">${l.created_at}</td>
                                <td>
                                    <div class="fw-bold text-dark">${escapeHtml(l.cb_name || 'Người dùng')}</div>
                                    <small class="text-muted">(@${escapeHtml(l.cb_username || '---')})</small>
                                </td>
                                <td><span class="badge ${badgeColor}">${escapeHtml(l.action_type)}</span></td>
                                <td class="fw-semibold text-secondary">${escapeHtml(l.target_object)}</td>
                                <td>${escapeHtml(l.description)}</td>
                                <td class="small text-muted">${escapeHtml(l.ip_address || '---')}</td>
                            </tr>
                        `;
                    }).join('');
                } else {
                    tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-danger">Không thể tải dữ liệu lịch sử hoặc không đủ quyền truy cập.</td></tr>`;
                }
            } catch (err) {
                console.error("Lỗi:", err);
                tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-danger">Lỗi kết nối máy chủ!</td></tr>`;
            }
        }

        function escapeHtml(str) {
            if (!str) return '';
            return String(str).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
        }
    </script>
</body>

</html>
