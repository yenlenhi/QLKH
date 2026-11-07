<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <!-- THÊM DÒNG NÀY ĐỂ ĐỊNH VỊ GỐC CHO ĐƯỜNG DẪN TƯƠNG ĐỐI -->
    <base href="/QLNCKH/View/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Kinh Phí & Thanh Toán - Đề Tài NCKH</title>
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
    </style>
</head>

<body>
    <!-- Sidebar Menu -->
    <?php include '../Model/sidebar.php'; ?>

    <!-- Main Content -->
    <div id="main-content">
        <!-- Topbar -->
        <?php include '../Model/r1_topbar_proj_detail.php'; ?>

        <!-- KHU VỰC HIỂN THỊ THÔNG BÁO TRỰC QUAN -->
        <div id="alertContainer"></div>

        <!-- THANH ĐIỀU HƯỚNG QUAY LẠI CHI TIẾT ĐỀ TÀI -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="#" id="btnBackToDetail" class="btn btn-outline-secondary btn-sm mb-2">
                    <i class="fa-solid fa-arrow-left me-1"></i> Quay lại chi tiết đề tài
                </a>
                <h4 class="m-0 text-secondary fw-bold">
                    <i class="fa-solid fa-file-invoice-dollar text-primary me-2"></i>Quản Lý Kinh Phí & Thanh Toán
                </h4>
                <small class="text-muted" id="lblSubProjectInfo">Đang tải thông tin đề tài...</small>
            </div>
            <div>
                <button type="button" class="btn btn-success btn-sm px-3 fw-bold action-btn" id="btnAddPayment" onclick="openPaymentModal()" style="display:none;">
                    <i class="fa-solid fa-plus me-1"></i> Thêm Đợt Thanh Toán
                </button>
            </div>
        </div>

        <!-- 4 THẺ TỔNG QUAN TÀI CHÍNH RÕ RÀNG NGHIỆP VỤ QUYẾT TOÁN -->
        <div class="row g-3 mb-4">
            <!-- Thẻ 1: Dự toán ban đầu -->
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-secondary border-4 h-100">
                    <div class="text-muted small fw-bold text-uppercase">1. Kinh phí dự toán (Trần)</div>
                    <div class="fs-5 fw-bold text-secondary mt-1" id="cardEstimatedBudget">0 VNĐ</div>
                    <small class="text-muted mt-auto">Kinh phí dự trù ban đầu</small>
                </div>
            </div>
            <!-- Thẻ 2: Tổng quyết toán thực tế được duyệt -->
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-primary border-4 h-100 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small fw-bold text-uppercase">2. Tổng quyết toán duyệt</span>
                        <!-- Thu gọn thành Icon bút chì, hover hiển thị title "Chốt số" -->
                        <button type="button" class="btn btn-sm btn-outline-primary p-1 lh-1 rounded-circle" id="btnEditSettlement" onclick="openSettlementModal()" style="display:none; width: 26px; height: 26px;" title="Chốt số">
                            <i class="fa-solid fa-pen" style="font-size: 11px;"></i>
                        </button>
                    </div>
                    <div class="fs-5 fw-bold text-primary text-truncate mt-1" id="cardSettlementBudget">Chưa chốt</div>
                    <small class="text-muted mt-auto pt-2" id="lblBudgetDiff">Chờ nghiệm thu hóa đơn</small>
                </div>
            </div>

            <!-- Thẻ 3: Tổng tiền đã thanh toán -->
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-success border-4 h-100">
                    <div class="text-muted small fw-bold text-uppercase">3. Đã giải ngân / Đã nhận</div>
                    <div class="fs-5 fw-bold text-success mt-1" id="cardTotalPaid">0 VNĐ</div>
                    <small class="text-muted mt-auto">Tổng các đợt đã chi trả</small>
                </div>
            </div>

            <!-- Thẻ 4: Số tiền còn phải thanh toán & Trạng thái tất toán -->
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white p-3 border-start border-warning border-4 h-100" id="cardRemainingBox">
                    <div class="text-muted small fw-bold text-uppercase">4. Còn phải thanh toán</div>
                    <div class="fs-5 fw-bold text-warning-emphasis mt-1" id="cardRemainingBudget">0 VNĐ</div>
                    <small class="fw-semibold mt-auto" id="lblSettlementStatus">Đang tạm tính theo dự toán</small>
                </div>
            </div>
        </div>

        <!-- BẢNG DANH SÁCH CÁC ĐỢT THANH TOÁN -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold text-secondary">
                    <i class="fa-solid fa-list-check me-2"></i>Lịch Sử Các Đợt Giải Ngân & Thanh Toán
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle" id="tablePayments">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 70px;">Đợt</th>
                                <th>Tên đợt / Nội dung chi</th>
                                <th>Số tiền thực nhận (VNĐ)</th>
                                <th>Ngày nhận tiền</th>
                                <th>Trạng thái</th>
                                <th style="width: 130px;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fa-solid fa-spinner fa-spin me-2"></i>Đang tải dữ liệu tài chính...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL THÊM / SỬA ĐỢT THANH TOÁN -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold text-primary" id="paymentModalTitle">Thêm đợt thanh toán</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3">
                    <form id="paymentForm">
                        <input type="hidden" id="paymentId" value="">

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Đợt thanh toán</label>
                            <input type="text" class="form-control form-control-sm" id="paymentPhase" placeholder="Ví dụ: Đợt 1, Đợt tạm ứng, Đợt quyết toán..." required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-secondary">Số tiền thực nhận (VNĐ)</label>
                                <!-- Đã thêm class money-format để tự động có dấu phẩy -->
                                <input type="text" class="form-control form-control-sm money-format" id="paidAmount" placeholder="0" required autocomplete="off">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-secondary">Ngày nhận tiền</label>
                                <input type="date" class="form-control form-control-sm" id="paymentDate" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Trạng thái giải ngân</label>
                            <select class="form-select form-select-sm" id="paymentStatus">
                                <option value="chua_quyet_toan">Chưa đủ thủ tục</option>
                                <option value="da_quyet_toan">Đã xong thủ tục</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Hồ sơ / Giấy tờ, chứng từ thanh toán (.pdf, .jpg, .png, .docx, .zip)</label>
                            <input type="file" class="form-control form-control-sm" id="paymentFile" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip,.rar">
                            <div id="existingFileBox" class="mt-1 small" style="display:none;"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Ghi chú (Tùy chọn)</label>
                            <textarea class="form-control form-control-sm" id="paymentNote" rows="2" placeholder="Số chứng từ, nội dung chi..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="button" class="btn btn-primary btn-sm px-4" onclick="savePaymentData()">Lưu thông tin</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL CHỐT TỔNG QUYẾT TOÁN THỰC TẾ -->
    <div class="modal fade" id="settlementModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold text-primary"><i class="fa-solid fa-calculator me-2"></i>Chốt Tổng Quyết Toán Thực Tế</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Nhập tổng số tiền quyết toán được duyệt (VNĐ)</label>
                        <!-- Đã thêm class money-format để tự động có dấu phẩy -->
                        <input type="text" class="form-control money-format" id="inputSettlementBudget" placeholder="VD: 18,500,000" required autocomplete="off">
                        <small class="text-muted">Con số này căn cứ trên hóa đơn, chứng từ thực tế khi nghiệm thu.</small>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="button" class="btn btn-primary btn-sm px-4" onclick="saveSettlementBudget()">Lưu số quyết toán</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="../asset/script/bootstrap5.js"></script>
    <script src="../asset/script/sidebar.js"></script>
    <script>
        const API_URL = '../index_api.php';
        let currentProjectId = 0;
        let projectEstimatedBudget = 0;
        let projectSettlementBudget = null;
        let paymentModalInstance = null;
        let settlementModalInstance = null;
        let hasConfirmCheckPermission = false;
        let hasManageFeePermission = false; // Thêm cờ quyền MANAGE_FEE

        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            currentProjectId = urlParams.get('id');
            const code = urlParams.get('code') || '';
            const title = urlParams.get('title') || '';

            if (!currentProjectId) {
                showUINotification("Không tìm thấy mã đề tài!", "danger");
                setTimeout(() => {
                    window.location.href = "r1_overview.php";
                }, 1500);
                return;
            }

            document.getElementById('btnBackToDetail').href = `r1_project_detail.php?id=${currentProjectId}&code=${encodeURIComponent(code)}&title=${encodeURIComponent(title)}`;
            if (code && title) {
                document.getElementById('lblSubProjectInfo').innerText = `Mã đề tài: ${code} - Tên đề tài: ${title}`;
            }

            paymentModalInstance = new bootstrap.Modal(document.getElementById('paymentModal'));
            settlementModalInstance = new bootstrap.Modal(document.getElementById('settlementModal'));

            checkUserPermissionsAndLoad(currentProjectId);
        });

        // TỰ ĐỘNG THÊM DẤU PHẨY CỨ MỖI 3 SỐ 0 KHI NHẬP TIỀN
        document.addEventListener('input', function(e) {
            if (e.target && e.target.classList.contains('money-format')) {
                let input = e.target;
                let value = input.value.replace(/\D/g, ""); // Chỉ giữ lại số
                if (value !== "") {
                    input.value = Number(value).toLocaleString('en-US'); // Format có dấu phẩy
                } else {
                    input.value = "";
                }
            }
        });

        // Hàm chuyển chuỗi có dấu phẩy về dạng số thực thuần túy để gửi lên server
        function getCleanNumber(formattedString) {
            if (!formattedString) return 0;
            return parseFloat(formattedString.replace(/,/g, '')) || 0;
        }

        async function checkUserPermissionsAndLoad(projectId) {
            const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
            try {
                const response = await fetch(`${API_URL}?detect=user_permission_get`, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });
                const result = await response.json();
                if (response.ok && result.status === 'success') {
                    const rawData = result.data || [];
                    const permissions = rawData.map(item => typeof item === 'string' ? item.trim().toUpperCase() : (item.permission_code || ''));
                    hasConfirmCheckPermission = permissions.includes("CONFIRM_CHECK");
                    hasManageFeePermission = permissions.includes("MANAGE_FEE"); // Lọc quyền MANAGE_FEE
                }
            } catch (err) {
                console.error("Lỗi kiểm tra quyền:", err);
            }

            // BẢO MẬT TUYỆT ĐỐI: Nếu không có quyền MANAGE_FEE, chặn không cho xem và đá về trang trước
            if (!hasManageFeePermission) {
                showUINotification("Truy cập bị từ chối: Tài khoản của bạn không có quyền Quản lý Chi Phí & Thanh Toán (MANAGE_FEE).", "danger");
                setTimeout(() => {
                    window.location.href = `r1_project_detail.php?id=${projectId}`;
                }, 2000);
                return;
            }

            // Nếu có quyền thì hiển thị các nút thao tác tài chính
            document.getElementById('btnAddPayment').style.display = 'block';
            document.getElementById('btnEditSettlement').style.display = 'inline-block';

            loadFinanceData(projectId);
        }
        async function loadFinanceData(projectId) {
            const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
            try {
                const projRes = await fetch(`${API_URL}?detect=project_get&project_id=${projectId}`, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });
                const projResult = await projRes.json();
                if (projRes.ok && projResult.status === 'success') {
                    projectEstimatedBudget = parseFloat(projResult.data.estimated_budget || 0);
                    projectSettlementBudget = projResult.data.settlement_budget !== null && projResult.data.settlement_budget !== undefined ? parseFloat(projResult.data.settlement_budget) : null;
                    document.getElementById('cardEstimatedBudget').innerText = projectEstimatedBudget.toLocaleString('vi-VN') + ' VNĐ';
                }

                const payRes = await fetch(`${API_URL}?detect=project_payment_get&project_id=${projectId}`, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });
                const payResult = await payRes.json();

                if (payRes.ok && payResult.status === 'success') {
                    renderPaymentTable(payResult.data || []);
                } else {
                    renderPaymentTable([]);
                }

            } catch (err) {
                console.error("Lỗi tải dữ liệu tài chính:", err);
                renderPaymentTable([]);
            }
        }

        function renderPaymentTable(payments) {
            const tbody = document.querySelector("#tablePayments tbody");
            tbody.innerHTML = "";

            let totalPaid = 0;

            if (!payments || payments.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="text-center text-muted py-4">Chưa có đợt giải ngân hoặc thanh toán nào được ghi nhận.</td></tr>`;
                updateSummaryCards(0);
                return;
            }

            payments.forEach((p, index) => {
                const paidVal = parseFloat(p.paid_amount || 0);
                totalPaid += paidVal;

                let badgeHtml = `<span class="badge bg-warning text-dark">Chưa đủ thủ tục</span>`;
                if (p.status === 'da_quyet_toan' || p.status === 'paid') {
                    badgeHtml = `<span class="badge bg-success">Đã quyết toán xong</span>`;
                }

                // Kiểm tra hiển thị file chứng từ
                let fileHtml = '';
                if (p.file_path) {
                    const fileHref = '../' + p.file_path.replace(/^\.\.\//, '');
                    fileHtml = `<div class="mt-1">
                        <a href="${fileHref}" target="_blank" class="badge bg-light text-primary border text-decoration-none">
                            <i class="fa-solid fa-paperclip me-1"></i>${escapeHtml(p.file_name || 'Xem chứng từ')}
                        </a>
                    </div>`;
                }

                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td class="fw-bold">#${index + 1}</td>
                    <td>
                        <div class="fw-bold">${escapeHtml(p.payment_phase)}</div>
                        <small class="text-muted">${escapeHtml(p.note || 'Không có ghi chú')}</small>
                        ${fileHtml}
                    </td>
                    <td class="fw-bold text-success">${Number(paidVal).toLocaleString('vi-VN')} VNĐ</td>
                    <td>${p.payment_date || '---'}</td>
                    <td>${badgeHtml}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick='editPayment(${JSON.stringify(p)})' title="Sửa"><i class="fa-solid fa-pen-to-square"></i></button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deletePayment(${p.payment_id})" title="Xóa"><i class="fa-solid fa-trash"></i></button>
                    </td>
                `;
                tbody.appendChild(tr);
            });

            updateSummaryCards(totalPaid);
        }

        function updateSummaryCards(totalPaid) {
            document.getElementById('cardTotalPaid').innerText = totalPaid.toLocaleString('vi-VN') + ' VNĐ';

            const remEl = document.getElementById('cardRemainingBudget');
            const statusEl = document.getElementById('lblSettlementStatus');
            const diffEl = document.getElementById('lblBudgetDiff');
            const settlementEl = document.getElementById('cardSettlementBudget');

            if (projectSettlementBudget !== null && projectSettlementBudget >= 0) {
                settlementEl.innerText = projectSettlementBudget.toLocaleString('vi-VN') + ' VNĐ';

                const savedAmount = projectEstimatedBudget - projectSettlementBudget;
                diffEl.innerText = savedAmount >= 0 ?
                    `Giảm so với dự toán: ${savedAmount.toLocaleString('vi-VN')} VNĐ` :
                    `Vượt dự toán: ${Math.abs(savedAmount).toLocaleString('vi-VN')} VNĐ`;

                const mustPayRemaining = projectSettlementBudget - totalPaid;
                remEl.innerText = mustPayRemaining.toLocaleString('vi-VN') + ' VNĐ';

                if (mustPayRemaining === 0) {
                    remEl.className = "fs-5 fw-bold text-success mt-1";
                    statusEl.innerHTML = `<span class="badge bg-success"><i class="fa-solid fa-check-double me-1"></i>Đã quyết toán & thanh toán đủ 100%</span>`;
                } else if (mustPayRemaining > 0) {
                    remEl.className = "fs-5 fw-bold text-danger mt-1";
                    statusEl.innerHTML = `<span class="text-danger">Cần chi trả thêm để tất toán</span>`;
                } else {
                    remEl.className = "fs-5 fw-bold text-danger mt-1";
                    statusEl.innerHTML = `<span class="text-danger">Cảnh báo: Đã chi vượt mức quyết toán!</span>`;
                }
            } else {
                settlementEl.innerText = "Chưa chốt số liệu";
                diffEl.innerText = "Tạm tính theo dự toán ban đầu";

                const tempRemaining = projectEstimatedBudget - totalPaid;
                remEl.innerText = tempRemaining.toLocaleString('vi-VN') + ' VNĐ';
                remEl.className = "fs-5 fw-bold text-warning-emphasis mt-1";
                statusEl.innerHTML = `<span class="text-muted">Chưa chốt tổng quyết toán cuối</span>`;
            }
        }

        function openPaymentModal() {
            if (!hasManageFeePermission || !hasConfirmCheckPermission) {
                showUINotification("Bạn không có quyền thực hiện thao tác tài chính này.", "danger");
                return;
            }
            document.getElementById('paymentForm').reset();
            document.getElementById('paymentId').value = "";
            document.getElementById('paymentFile').value = "";
            document.getElementById('existingFileBox').style.display = 'none';
            document.getElementById('existingFileBox').innerHTML = '';
            document.getElementById('paymentModalTitle').innerText = "Thêm đợt thanh toán mới";
            paymentModalInstance.show();
        }

        function editPayment(p) {
            if (!hasManageFeePermission || !hasConfirmCheckPermission) {
                showUINotification("Bạn không có quyền chỉnh sửa tài chính.", "danger");
                return;
            }
            document.getElementById('paymentId').value = p.payment_id;
            document.getElementById('paymentPhase').value = p.payment_phase;
            document.getElementById('paidAmount').value = Number(p.paid_amount || 0).toLocaleString('en-US');
            document.getElementById('paymentDate').value = p.payment_date;
            document.getElementById('paymentStatus').value = p.status;
            document.getElementById('paymentNote').value = p.note || '';
            document.getElementById('paymentFile').value = "";

            const fileBox = document.getElementById('existingFileBox');
            if (p.file_path) {
                const fileHref = '../' + p.file_path.replace(/^\.\.\//, '');
                fileBox.innerHTML = `<span class="text-muted">Tệp hiện tại:</span> <a href="${fileHref}" target="_blank" class="fw-bold text-primary">${escapeHtml(p.file_name || 'Xem tệp')}</a> <span class="text-muted">(Chọn tệp mới nếu muốn thay thế)</span>`;
                fileBox.style.display = 'block';
            } else {
                fileBox.style.display = 'none';
                fileBox.innerHTML = '';
            }

            document.getElementById('paymentModalTitle').innerText = "Cập nhật đợt thanh toán";
            paymentModalInstance.show();
        }

        function openSettlementModal() {
            if (!hasManageFeePermission) {
                showUINotification("Bạn không có quyền chốt quyết toán.", "danger");
                return;
            }
            if (!hasConfirmCheckPermission) {
                showUINotification("Bạn không có quyền chốt quyết toán.", "danger");
                return;
            }
            document.getElementById('inputSettlementBudget').value = projectSettlementBudget !== null ? Number(projectSettlementBudget).toLocaleString('en-US') : '';
            settlementModalInstance.show();
        }

        async function saveSettlementBudget() {
            const rawVal = document.getElementById('inputSettlementBudget').value;
            const val = getCleanNumber(rawVal); // Lọc bỏ dấu phẩy thành số chuẩn

            if (val <= 0) {
                showUINotification("Vui lòng nhập số tiền quyết toán hợp lệ.", "warning");
                return;
            }

            const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
            const formData = new FormData();
            formData.append('project_id', currentProjectId);
            formData.append('settlement_budget', val);

            try {
                const response = await fetch(`${API_URL}?detect=project_settlement_save`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    },
                    body: formData
                });
                const result = await response.json();

                if (response.ok && result.status === 'success') {
                    settlementModalInstance.hide();
                    showUINotification(result.message || "Đã chốt tổng quyết toán thành công!", "success");
                    loadFinanceData(currentProjectId);
                } else {
                    showUINotification("Lỗi: " + (result.message || "Không thể lưu"), "danger");
                }
            } catch (err) {
                console.error("Lỗi lưu quyết toán:", err);
                showUINotification("Không thể kết nối đến máy chủ.", "danger");
            }
        }

        async function savePaymentData() {
            const paymentId = document.getElementById('paymentId').value;
            const paymentPhase = document.getElementById('paymentPhase').value.trim();
            const rawPaidAmount = document.getElementById('paidAmount').value;
            const paidAmount = getCleanNumber(rawPaidAmount);
            const paymentDate = document.getElementById('paymentDate').value;
            const status = document.getElementById('paymentStatus').value;
            const note = document.getElementById('paymentNote').value.trim();
            const fileInput = document.getElementById('paymentFile');

            if (!currentProjectId || currentProjectId <= 0) {
                showUINotification("Lỗi: Không xác định được mã đề tài.", "danger");
                return;
            }

            if (!paymentPhase || paidAmount <= 0 || !paymentDate) {
                showUINotification("Vui lòng điền đầy đủ thông tin bắt buộc (Đợt, Số tiền > 0, Ngày nhận).", "warning");
                return;
            }

            const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
            if (!token) {
                showUINotification("Phiên đăng nhập hết hạn, vui lòng đăng nhập lại.", "danger");
                return;
            }

            const formData = new FormData();
            formData.append('project_id', currentProjectId);
            if (paymentId) formData.append('payment_id', paymentId);
            formData.append('payment_phase', paymentPhase);
            formData.append('paid_amount', paidAmount);
            formData.append('payment_date', paymentDate);
            formData.append('status', status);
            formData.append('note', note);

            // Đính kèm tệp nếu có chọn
            if (fileInput.files.length > 0) {
                formData.append('payment_file', fileInput.files[0]);
            }

            try {
                const response = await fetch(`${API_URL}?detect=project_payment_save`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    },
                    body: formData
                });

                const result = await response.json();

                if (response.ok && result.status === 'success') {
                    if (paymentModalInstance) paymentModalInstance.hide();
                    showUINotification(result.message || "Lưu thông tin tài chính thành công!", "success");
                    loadFinanceData(currentProjectId);
                } else {
                    showUINotification("Lỗi: " + (result.message || "Không thể lưu dữ liệu"), "danger");
                }
            } catch (err) {
                console.error("Lỗi kết nối:", err);
                showUINotification("Không thể kết nối đến máy chủ hoặc lỗi phản hồi.", "danger");
            }
        }

        async function deletePayment(paymentId) {
            if (!hasManageFeePermission) {
                showUINotification("Bạn không có quyền xóa dữ liệu tài chính.", "danger");
                return;
            }
            if (!hasConfirmCheckPermission) {
                showUINotification("Bạn không có quyền xóa dữ liệu tài chính.", "danger");
                return;
            }
            if (!confirm("Bạn có chắc chắn muốn xóa đợt thanh toán này không?")) return;

            const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
            try {
                const response = await fetch(`${API_URL}?detect=project_payment_delete`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        payment_id: paymentId
                    })
                });
                const result = await response.json();

                if (response.ok && result.status === 'success') {
                    showUINotification(result.message || "Xóa thành công!", "success");
                    loadFinanceData(currentProjectId);
                } else {
                    showUINotification("Lỗi: " + result.message, "danger");
                }
            } catch (err) {
                console.error("Lỗi xóa tài chính:", err);
                showUINotification("Không thể kết nối đến máy chủ.", "danger");
            }
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