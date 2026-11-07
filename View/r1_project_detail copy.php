<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <base href="/QLNCKH/View/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Nhiệm Vụ NCKH - Admin Dashboard</title>
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

        /* Nút quay lại danh sách */
        .btn-back-nav {
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
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-back-nav:hover {
            background-color: #f1f5f9;
            color: #0f172a;
            border-color: #94a3b8;
        }

        /* Danh sách file đính kèm */
        .file-list-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .file-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background-color: #ffffff;
            transition: all 0.2s ease;
        }

        .file-item:hover {
            background-color: #f8fafc;
            border-color: #93c5fd;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
            transform: translateY(-1px);
        }

        /* Tối ưu typography & Layout thông tin nhiệm vụ */
        .info-grid {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .info-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            padding: 8px 12px;
            background: #ffffff;
            border: 1px solid #f1f5f9;
            border-radius: 8px;
            font-size: 0.875rem;
            transition: background 0.15s ease;
        }

        .info-row:hover {
            background: #f8fafc;
        }

        .info-label {
            min-width: 140px;
            color: #64748b;
            font-weight: 600;
            font-size: 0.815rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .info-val {
            flex: 1;
            text-align: right;
            word-break: break-word;
            color: #1e293b;
        }

        .info-val-left {
            flex: 1;
            text-align: left;
            margin-left: 12px;
            word-break: break-word;
        }

        .custom-badge {
            font-size: 0.785rem;
            font-weight: 500;
            padding: 4px 8px;
            border-radius: 6px;
            display: inline-block;
        }

        /* ==============================================================
           CSS MODAL TOÀN MÀN HÌNH CHỐNG LỆCH KHUNG & MÔ PHỎNG TRANG WORD A4
           ============================================================== */
        #docPreviewModal {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            max-width: 100vw !important;
            max-height: 100vh !important;
            margin: 0 !important;
            padding: 0 !important;
            z-index: 99999 !important;
            pointer-events: auto !important;
            transform: none !important;
            background-color: rgba(0, 0, 0, 0.7) !important;
        }

        #docPreviewModal .modal-dialog {
            width: 100vw !important;
            height: 100vh !important;
            max-width: 100vw !important;
            max-height: 100vh !important;
            margin: 0 !important;
            padding: 0 !important;
            transform: none !important;
            display: block !important;
        }

        #docPreviewModal .modal-content {
            width: 100vw !important;
            height: 100vh !important;
            border-radius: 0 !important;
            display: flex !important;
            flex-direction: column !important;
        }

        #docPreviewModal .modal-body {
            flex: 1 1 auto !important;
            height: calc(100vh - 54px) !important;
            width: 100% !important;
            overflow: hidden !important;
            background-color: #525659 !important;
            padding: 0 !important;
            margin: 0 !important;
            position: relative !important;
        }

        #docPreviewModal iframe {
            width: 100% !important;
            height: 100% !important;
            border: none !important;
            display: block !important;
        }

        /* TỜ GIẤY IN A4 CHO XEM WORD */
        .modal-word-sheet {
            background: #ffffff;
            width: 100%;
            max-width: 860px;
            min-height: 1100px;
            margin: 25px auto;
            padding: 60px 80px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.4);
            font-family: "Times New Roman", Times, serif;
            font-size: 14pt;
            line-height: 1.5;
            color: #000000;
            text-align: justify;
            box-sizing: border-box;
        }

        .modal-word-sheet p {
            margin-bottom: 6pt;
            line-height: 1.5;
        }

        .modal-word-sheet h1,
        .modal-word-sheet h2,
        .modal-word-sheet h3 {
            font-family: "Times New Roman", Times, serif;
            font-weight: bold;
            color: #000;
            text-align: center;
            margin-top: 14pt;
            margin-bottom: 6pt;
        }

        .modal-word-sheet table {
            width: 100% !important;
            border-collapse: collapse !important;
            margin: 14pt 0 !important;
            font-size: 13pt !important;
        }

        .modal-word-sheet th,
        .modal-word-sheet td {
            border: 1px solid #000000 !important;
            padding: 6px 10px !important;
            vertical-align: middle !important;
        }

        .modal-word-sheet th {
            font-weight: bold;
            text-align: center;
            background-color: #f8fafc;
        }

        .modal-word-sheet img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 12px auto;
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

        <!-- NÚT QUAY LẠI DANH SÁCH THÔNG MINH (GIỮ NGUYÊN BỘ LỌC ASSIGNED) -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <button type="button" class="btn-back-nav" onclick="handleGoBack()">
                <i class="fa-solid fa-arrow-left"></i> Quay lại danh sách
            </button>
        </div>

        <!-- KHU VỰC HIỂN THỊ THÔNG BÁO TRỰC QUAN TRÊN GIAO DIỆN -->
        <div id="alertContainer"></div>

        <div class="row g-4">
            <!-- CỘT TRÁI: THÔNG TIN CHI TIẾT & CÁC NÚT THAO TÁC -->
            <div class="col-lg-5">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold text-primary fs-6 d-flex align-items-center">
                            <i class="fa-solid fa-file-lines me-2 text-primary"></i>Thông Tin Nhiệm Vụ
                        </h6>
                        <span class="badge bg-warning text-dark px-3 py-2 fw-semibold shadow-sm" id="statusBadge">Chờ duyệt</span>
                    </div>

                    <div class="card-body p-3">
                        <!-- KHỐI 1: TÊN VÀ MÃ NHIỆM VỤ TIÊU ĐIỂM -->
                        <div class="p-3 bg-light rounded-3 border border-slate-200 mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge bg-white text-secondary border px-2 py-1 small fw-bold">
                                    <i class="fa-solid fa-hashtag me-1"></i><span id="lblCode">---</span>
                                </span>
                                <span class="badge bg-light text-muted border px-2 py-1" id="lblLevel">---</span>
                            </div>
                            <h6 class="fw-bold text-dark mb-0 lh-base" id="lblTitle" style="font-size: 0.975rem;">
                                Đang nạp dữ liệu nhiệm vụ...
                            </h6>
                        </div>

                        <!-- KHỐI 2: DANH MỤC THÔNG SỐ NGHIỆP VỤ -->
                        <div class="info-grid mb-3">
                            <div class="info-row">
                                <div class="info-label">
                                    <i class="fa-solid fa-shapes text-secondary"></i> Hình thức:
                                </div>
                                <div class="info-val">
                                    <span class="custom-badge bg-info bg-opacity-10 text-primary border border-info border-opacity-25" id="lblRegType">---</span>
                                </div>
                            </div>

                            <div class="info-row align-items-center">
                                <div class="info-label">
                                    <i class="fa-solid fa-layer-group text-secondary"></i> Loại nhiệm vụ:
                                </div>
                                <div class="info-val-left text-end">
                                    <span class="custom-badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 lh-sm text-start" id="lblSubSystem">---</span>
                                </div>
                            </div>

                            <div class="info-row">
                                <div class="info-label">
                                    <i class="fa-solid fa-user-shield text-secondary"></i> Người tiếp nhận:
                                </div>
                                <div class="info-val" id="lblActionUser">
                                    <span class="text-danger fst-italic">Chưa phân công</span>
                                </div>
                            </div>

                            <div class="info-row">
                                <div class="info-label">
                                    <i class="fa-solid fa-coins text-secondary"></i> Kinh phí dự toán:
                                </div>
                                <div class="info-val fw-bold text-success" id="lblBudget">0 VNĐ</div>
                            </div>

                            <div class="info-row">
                                <div class="info-label">
                                    <i class="fa-solid fa-user-tie text-secondary"></i> Chủ nhiệm:
                                </div>
                                <div class="info-val fw-semibold text-dark" id="lblLeader">Đang tải...</div>
                            </div>

                            <div class="info-row flex-column align-items-stretch">
                                <div class="info-label mb-1">
                                    <i class="fa-solid fa-users text-secondary"></i> Thành viên phối hợp:
                                </div>
                                <div class="bg-light p-2 rounded border border-slate-100 mt-1">
                                    <ul class="list-unstyled mb-0 small text-secondary" id="lblMembers">
                                        <li>Chưa có thành viên</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- NÚT CHUYỂN SANG TRANG QUẢN LÝ CHI PHÍ NHIỆM VỤ -->
                        <div class="d-grid mb-3">
                            <button type="button" class="btn btn-outline-primary fw-semibold btn-sm py-2 shadow-sm" onclick="openProjectFinance()">
                                <i class="fa-solid fa-file-invoice-dollar me-2"></i> Quản Lý Chi Phí & Thanh Toán
                            </button>
                        </div>

                        <hr class="my-3 text-slate-200">

                        <!-- KHU VỰC THAO TÁC NGHIỆP VỤ -->
                        <div class="p-3 bg-light rounded-3 border">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold text-secondary m-0 small text-uppercase" style="letter-spacing: 0.5px;">
                                    <i class="fa-solid fa-sliders me-1"></i> Thao Tác Nghiệp Vụ
                                </h6>
                                <!-- NÚT GỬI THÔNG BÁO: CHỈ HIỂN THỊ KHI CÓ PROJECT_USER_ACTION_ID -->
                                <button type="button" class="btn btn-sm btn-outline-primary fw-semibold" id="btnOpenNotifyModal" style="display: none;" onclick="openNotifyModal()" title="Gửi thông báo trực tiếp đến tài khoản đảm nhiệm nhiệm vụ này">
                                    <i class="fa-solid fa-bell me-1"></i> Gửi thông báo
                                </button>
                            </div>

                            <div class="d-grid gap-2">
                                <!-- NÚT GIAO NHIỆM VỤ (CHỈ DÀNH CHO NHIỆM VỤ TÀI TRỢ GIAO TRỰC TIẾP) -->
                                <button type="button" class="btn btn-warning text-dark fw-bold btn-sm py-2 shadow-sm" id="btnAssignTask" style="display: none;" onclick="openAssignTaskModal()">
                                    <i class="fa-solid fa-user-plus me-1"></i> Giao Nhiệm Vụ
                                </button>

                                <!-- NÚT XÉT DUYỆT (CHỈ HIỂN THỊ KHI ĐÃ CÓ PROJECT_USER_ACTION_ID) -->
                                <button type="button" class="btn btn-success action-btn btn-sm py-2 shadow-sm fw-semibold" id="btnApprove" style="display: none;" onclick="confirmAction('approve')">
                                    <i class="fa-solid fa-check-circle me-1"></i> Xét Duyệt Nhiệm Vụ (Kích hoạt tiến độ)
                                </button>
                                <button type="button" class="btn btn-info text-white action-btn btn-sm py-2 shadow-sm fw-semibold" id="btnNghiemThu" style="display:none;" onclick="confirmAction('awaiting_acceptance')">
                                    <i class="fa-solid fa-award me-1"></i> Chuyển Chờ Nghiệm Thu
                                </button>
                                <button type="button" class="btn btn-primary action-btn btn-sm py-2 shadow-sm fw-semibold" id="btnThanhToan" style="display:none;" onclick="confirmAction('awaiting_payment')">
                                    <i class="fa-solid fa-money-check-dollar me-1"></i> Xác Nhận Chờ Thanh Toán
                                </button>
                                <button type="button" class="btn btn-success action-btn btn-sm py-2 shadow-sm fw-semibold" id="btnComplete" style="display:none;" onclick="confirmAction('completed')">
                                    <i class="fa-solid fa-flag-checkered me-1"></i> Xác Nhận Đã Hoàn Thành
                                </button>
                                <button type="button" class="btn btn-outline-danger action-btn btn-sm py-2" id="btnReject" onclick="confirmAction('reject')">
                                    <i class="fa-solid fa-xmark-circle me-1"></i> Từ Chối Nhiệm Vụ
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CỘT PHẢI: QUẢN LÝ DANH SÁCH FILE ĐÍNH KÈM -->
            <div class="col-lg-7">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold text-primary">
                            <i class="fa-solid fa-folder-open me-2"></i>Danh Sách File Hồ Sơ Đính Kèm (<span id="fileCount">0</span>)
                        </h6>
                        <span class="badge bg-light text-muted border fw-normal" style="font-size: 0.785rem;">
                            Bấm "Xem tài liệu" để đọc toàn màn hình
                        </span>
                    </div>
                    <div class="card-body p-3">
                        <div class="file-list-container" id="fileListContainer">
                            <div class="text-center text-muted py-4 small">Đang tải file...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL POPUP XEM TÀI LIỆU TOÀN MÀN HÌNH CHỐNG LỖI LỆCH LAYOUT -->
    <div class="modal fade" id="docPreviewModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content bg-dark border-0">
                <div class="modal-header bg-dark text-white border-bottom border-secondary py-2 px-3">
                    <div class="d-flex align-items-center gap-2 text-truncate me-3">
                        <i class="fa-solid fa-file-lines text-primary fs-5"></i>
                        <span class="fw-bold fs-6 text-truncate" id="modalPreviewTitle">Xem tài liệu</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-shrink-0">
                        <a href="#" id="modalBtnOpenNewTab" target="_blank" class="btn btn-sm btn-outline-light">
                            <i class="fa-solid fa-up-right-from-square me-1"></i> Mở tab mới
                        </a>
                        <a href="#" id="modalBtnDownload" download class="btn btn-sm btn-primary">
                            <i class="fa-solid fa-download me-1"></i> Tải về máy
                        </a>
                        <button type="button" class="btn btn-sm btn-secondary ms-2" data-bs-dismiss="modal">
                            <i class="fa-solid fa-xmark me-1"></i> Đóng
                        </button>
                    </div>
                </div>
                <div class="modal-body">
                    <div id="modalPreviewLoading" class="position-absolute top-50 start-50 translate-middle text-center text-white d-none">
                        <div class="spinner-border text-light" role="status"></div>
                        <div class="mt-2 small">Đang đọc tài liệu...</div>
                    </div>
                    <div id="modalPreviewContainer" style="width: 100%; height: 100%; overflow-y: auto;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL GIAO NHIỆM VỤ CHO GIẢNG VIÊN (CHO NHIỆM VỤ TÀI TRỢ GIAO TRỰC TIẾP) -->
    <div class="modal fade" id="assignTaskModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-warning text-dark py-2">
                    <h6 class="modal-title fw-bold">
                        <i class="fa-solid fa-user-plus me-2"></i> Giao Nhiệm Vụ Cho Giảng Viên Phụ Trách
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="small text-secondary mb-3">
                        Nhiệm vụ này thuộc dạng <strong>Nhiệm vụ tài trợ giao trực tiếp</strong>. Vui lòng chọn Giảng viên trong hệ thống để bàn giao phụ trách.
                    </p>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Nhiệm vụ:</label>
                        <input type="text" id="assignProjectTitle" class="form-control form-control-sm bg-light" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Chọn Giảng viên tiếp nhận <span class="text-danger">*</span></label>
                        <select class="form-select form-select-sm" id="selectTeacherAssign">
                            <option value="" disabled selected>-- Đang tải danh sách Giảng viên --</option>
                        </select>
                    </div>
                    <div id="assignAlertMsg" class="alert d-none py-2 small mb-0"></div>
                </div>
                <div class="modal-footer bg-light py-2 justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="button" class="btn btn-success btn-sm px-4 fw-semibold" id="btnConfirmAssign" onclick="handleAssignTeacher()">
                        <i class="fa-solid fa-check me-1"></i> Đồng ý giao
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL XÁC NHẬN THAO TÁC DUYỆT / TỪ CHỐI -->
    <div class="modal fade" id="actionConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold text-primary" id="confirmModalTitle">Xác nhận thao tác</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <p id="confirmModalMessage" class="mb-0 text-secondary fs-6">Bạn có chắc chắn muốn thực hiện hành động này?</p>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="button" class="btn btn-primary btn-sm px-4" id="btnExecuteAction">Xác nhận</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL GỬI THÔNG BÁO CHO TÀI KHOẢN ĐẢM NHIỆM NHIỆM VỤ -->
    <div class="modal fade" id="notifyUserModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white py-2">
                    <h6 class="modal-title fw-bold">
                        <i class="fa-solid fa-paper-plane me-1"></i> Gửi Thông Báo Đến Người Đảm Nhiệm
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="mb-2">
                        <label class="form-label small fw-semibold text-secondary">Nhiệm vụ liên quan:</label>
                        <input type="text" id="notifyProjectTitle" class="form-control form-control-sm bg-light" readonly>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold text-secondary">Tiêu đề thông báo <span class="text-danger">*</span></label>
                        <input type="text" id="notifyTitleInput" class="form-control form-control-sm" placeholder="VD: Nhắc nhở nộp báo cáo tiến độ...">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold text-secondary">Nội dung chi tiết <span class="text-danger">*</span></label>
                        <textarea id="notifyContentInput" class="form-control form-control-sm" rows="3" placeholder="Nhập nội dung cần gửi đến tài khoản phụ trách nhiệm vụ..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2 justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="button" class="btn btn-primary btn-sm px-3 fw-semibold" id="btnSendCustomNotice" onclick="handleSendCustomNotice()">
                        <i class="fa-solid fa-paper-plane me-1"></i> Gửi thông báo
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="../asset/script/bootstrap5.js"></script>
    <script src="../asset/script/mammoth.js"></script>
    <script src="../asset/script/sidebar.js"></script>
    <script>
        const API_URL = '../index_api.php';
        let projectFiles = [];
        let currentProjectId = 0;
        let currentProjectCode = '';
        let currentProjectTitle = '';
        let currentStatus = 'pending';
        let currentActionUserId = null;
        let currentRegisterTypeId = null;
        let pendingActionType = '';
        let hasConfirmCheckPermission = false;
        let hasManageFeePermission = false;

        // ==============================================================
        // HÀM QUAY LẠI TỐI ƯU BẢO LƯU BỘ LỌC ASSIGNED
        // ==============================================================
        function handleGoBack() {
            const urlParams = new URLSearchParams(window.location.search);
            const assignedParam = urlParams.get('assigned'); // Đọc tham số assigned trên URL hiện tại nếu có

            // 1. Ưu tiên: Quay lại URL trước đó nếu có referrer từ trang nội bộ
            if (document.referrer && document.referrer.includes(window.location.host)) {
                // Nếu trang referrer chính là trang quan-ly-nhiem-vu thì quay lại trực tiếp
                window.location.href = document.referrer;
                return;
            }

            // 2. Lịch sử duyệt trang
            if (window.history.length > 1) {
                window.history.back();
                return;
            }

            // 3. Dự phòng trường hợp mở tab mới hoặc F5: chuyển đúng trang theo assigned
            if (assignedParam !== null && assignedParam !== '') {
                window.location.href = `quan-ly-nhiem-vu?assigned=${encodeURIComponent(assignedParam)}`;
            } else {
                const savedAssigned = sessionStorage.getItem('last_assigned_filter');
                if (savedAssigned !== null && savedAssigned !== '') {
                    window.location.href = `quan-ly-nhiem-vu?assigned=${encodeURIComponent(savedAssigned)}`;
                } else {
                    window.location.href = 'quan-ly-nhiem-vu';
                }
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            currentProjectId = urlParams.get('id');

            // Tự động ghi nhớ bộ lọc assigned nếu được truyền qua URL
            const assignedFromUrl = urlParams.get('assigned');
            if (assignedFromUrl !== null && assignedFromUrl !== '') {
                sessionStorage.setItem('last_assigned_filter', assignedFromUrl);
            }

            if (!currentProjectId) {
                showUINotification("Không tìm thấy mã nhiệm vụ!", "danger");
                setTimeout(() => {
                    handleGoBack();
                }, 1500);
                return;
            }

            checkUserPermissions(currentProjectId);
        });

        function openProjectFinance() {
            if (!hasManageFeePermission) {
                showUINotification("Bạn không có quyền truy cập Quản lý Chi Phí & Thanh Toán (Thiếu quyền MANAGE_FEE).", "danger");
                return;
            }
            if (!currentProjectId) return;
            const url = `r1_project_detail_finance.php?id=${encodeURIComponent(currentProjectId)}&code=${encodeURIComponent(currentProjectCode)}&title=${encodeURIComponent(currentProjectTitle)}`;
            window.location.assign(url);
        }

        async function checkUserPermissions(projectId) {
            const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
            if (!token) {
                showUINotification("Phiên đăng nhập hết hạn.", "danger");
                return;
            }

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
                    const permissions = rawData.map(item => {
                        if (typeof item === 'string') return item.trim().toUpperCase();
                        if (typeof item === 'object' && item !== null) {
                            const val = item.permission_code || item.code || item.name || Object.values(item)[0];
                            return typeof val === 'string' ? val.trim().toUpperCase() : '';
                        }
                        return '';
                    });

                    hasConfirmCheckPermission = permissions.includes("CONFIRM_CHECK");
                    hasManageFeePermission = permissions.includes("MANAGE_FEE");
                } else {
                    hasConfirmCheckPermission = false;
                }
            } catch (err) {
                console.error("Lỗi gọi API kiểm tra quyền:", err);
                hasConfirmCheckPermission = false;
            }

            loadProjectDetail(projectId);
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
                if (alertEl) {
                    let alertInstance = bootstrap.Alert.getOrCreateInstance(alertEl);
                    alertInstance.close();
                }
            }, 4000);
        }

        async function loadProjectDetail(id) {
            const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
            try {
                const response = await fetch(`${API_URL}?detect=project_get&project_id=${id}`, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });
                const result = await response.json();

                if (response.ok && result.status === 'success') {
                    const data = result.data;
                    currentStatus = data.status || 'pending';
                    currentProjectCode = data.project_code || ('NV' + data.project_id);
                    currentProjectTitle = data.project_name || '';
                    currentActionUserId = data.project_user_action_id ? parseInt(data.project_user_action_id) : null;
                    currentRegisterTypeId = parseInt(data.register_type_id || 0);

                    // Hiển thị thông tin
                    document.getElementById('lblCode').innerText = currentProjectCode;
                    document.getElementById('lblTitle').innerText = currentProjectTitle;
                    document.getElementById('lblRegType').innerText = data.register_type_name || 'Nhiệm vụ đăng ký';
                    document.getElementById('lblLevel').innerText = data.level_name || '---';
                    document.getElementById('lblSubSystem').innerText = data.sub_system_name || data.system_name || '---';
                    document.getElementById('lblBudget').innerText = Number(data.estimated_budget || 0).toLocaleString('vi-VN') + ' VNĐ';

                    // Cán bộ đảm nhiệm
                    const lblActionUser = document.getElementById('lblActionUser');
                    if (data.action_user_name) {
                        lblActionUser.innerHTML = `<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1"><i class="fa-solid fa-user-check me-1"></i>${escapeHtml(data.action_user_name)}</span>`;
                    } else {
                        lblActionUser.innerHTML = `<span class="text-danger fst-italic">Chưa có cán bộ tiếp nhận</span>`;
                    }

                    // Nút gửi thông báo
                    const btnOpenNotify = document.getElementById('btnOpenNotifyModal');
                    if (currentActionUserId && currentActionUserId > 0) {
                        btnOpenNotify.style.display = 'inline-block';
                    } else {
                        btnOpenNotify.style.display = 'none';
                    }

                    // Nút giao nhiệm vụ
                    const btnAssignTask = document.getElementById('btnAssignTask');
                    const isDirectAssignment = (currentRegisterTypeId === 4) ||
                        (data.register_type_code === 'tai_tro_giao_truc_tiep') ||
                        (data.register_type_name && data.register_type_name.toLowerCase().includes('giao trực tiếp'));

                    if (isDirectAssignment && hasConfirmCheckPermission) {
                        btnAssignTask.style.display = 'block';
                        if (currentActionUserId && currentActionUserId > 0) {
                            btnAssignTask.innerHTML = `<i class="fa-solid fa-user-gear me-2"></i> Đổi Giảng Viên Đảm Nhiệm`;
                            btnAssignTask.className = "btn btn-outline-warning text-dark fw-bold";
                        } else {
                            btnAssignTask.innerHTML = `<i class="fa-solid fa-user-plus me-2"></i> Giao Nhiệm Vụ`;
                            btnAssignTask.className = "btn btn-warning text-dark fw-bold";
                        }
                    } else {
                        btnAssignTask.style.display = 'none';
                    }

                    // Thành viên
                    if (data.members && data.members.length > 0) {
                        let leaderName = "Chưa phân công";
                        let otherMembersHtml = "";

                        data.members.forEach(m => {
                            const platoonInfo = m.platoon ? ` (Đơn vị: ${m.platoon})` : '';
                            if (parseInt(m.is_leader) === 1) {
                                leaderName = m.full_name + platoonInfo;
                            } else {
                                otherMembersHtml += `<li>• ${m.full_name}${platoonInfo}</li>`;
                            }
                        });

                        document.getElementById('lblLeader').innerText = leaderName;
                        document.getElementById('lblMembers').innerHTML = otherMembersHtml || '<li>Không có thành viên khác</li>';
                    } else {
                        document.getElementById('lblLeader').innerText = "Chưa cập nhật";
                        document.getElementById('lblMembers').innerHTML = '<li>Không có thành viên</li>';
                    }

                    // Files đính kèm
                    if (data.files && data.files.length > 0) {
                        projectFiles = data.files.map(f => ({
                            id: f.file_id,
                            name: f.file_name,
                            size: formatBytes(f.file_size),
                            date: f.created_at ? f.created_at.split(' ')[0] : '',
                            url: resolveFilePath(f.file_path)
                        }));
                        renderFileList();
                    } else {
                        document.getElementById('fileCount').innerText = '0';
                        document.getElementById('fileListContainer').innerHTML = `<div class="text-center text-muted py-4 small">Không có file đính kèm nào.</div>`;
                    }

                    applyStatusRules(currentStatus);
                }
            } catch (err) {
                console.error("Lỗi tải chi tiết nhiệm vụ:", err);
            }
        }

        // RENDER DANH SÁCH FILE VỚI NÚT XEM TÀI LIỆU
        function renderFileList() {
            const container = document.getElementById('fileListContainer');
            document.getElementById('fileCount').innerText = projectFiles.length;
            container.innerHTML = "";

            container.innerHTML = projectFiles.map((file, idx) => {
                const ext = file.name.split('.').pop().toLowerCase();
                const iconClass = (ext === 'doc' || ext === 'docx') 
                    ? 'fa-file-word text-primary' 
                    : (ext === 'pdf' ? 'fa-file-pdf text-danger' : 'fa-file-lines text-secondary');

                return `
                    <div class="file-item">
                        <div class="d-flex align-items-center gap-3 text-truncate me-3">
                            <i class="fa-solid ${iconClass} fs-3"></i>
                            <div class="text-truncate">
                                <div class="fw-bold text-dark small text-truncate" title="${escapeHtml(file.name)}">${escapeHtml(file.name)}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">${file.size} • ${file.date}</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                            <button type="button" class="btn btn-sm btn-outline-primary fw-semibold" style="font-size: 0.785rem; padding: 5px 12px;" onclick="openPreviewModal(${idx})">
                                <i class="fa-solid fa-eye me-1"></i> Xem tài liệu
                            </button>
                            <a href="${file.url}" download="${escapeHtml(file.name)}" class="btn btn-sm btn-light border text-secondary" style="font-size: 0.785rem; padding: 5px 10px;" title="Tải file về máy">
                                <i class="fa-solid fa-download"></i>
                            </a>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // HÀM MỞ POPUP PREVIEW TOÀN MÀN HÌNH TƯƠNG TỰ R3
        async function openPreviewModal(index) {
            const file = projectFiles[index];
            if (!file) return;

            const modalTitle = document.getElementById('modalPreviewTitle');
            const btnOpenNewTab = document.getElementById('modalBtnOpenNewTab');
            const btnDownload = document.getElementById('modalBtnDownload');
            const previewContainer = document.getElementById('modalPreviewContainer');
            const loadingEl = document.getElementById('modalPreviewLoading');

            modalTitle.innerText = file.name;
            btnOpenNewTab.href = file.url;
            btnDownload.href = file.url;
            btnDownload.setAttribute('download', file.name);

            previewContainer.innerHTML = '';
            loadingEl.classList.remove('d-none');

            const modalEl = document.getElementById('docPreviewModal');

            // Đưa modal ra ngoài body để không bị ảnh hưởng bởi layout sidebar của admin
            if (modalEl.parentElement !== document.body) {
                document.body.appendChild(modalEl);
            }

            const modalObj = bootstrap.Modal.getOrCreateInstance(modalEl);
            modalObj.show();

            const ext = file.name.split('.').pop().toLowerCase();

            if (ext === 'pdf') {
                loadingEl.classList.add('d-none');
                previewContainer.innerHTML = `<iframe src="${file.url}#view=FitH&pagemode=thumbs"></iframe>`;
            } else if (['jpg', 'jpeg', 'png', 'gif', 'txt'].includes(ext)) {
                loadingEl.classList.add('d-none');
                previewContainer.innerHTML = `
                    <div class="d-flex justify-content-center align-items-center p-4" style="min-height: 100%;">
                        <img src="${file.url}" style="max-width: 95%; max-height: 90vh; border-radius: 6px; box-shadow: 0 4px 16px rgba(0,0,0,0.3);">
                    </div>
                `;
            } else if (ext === 'docx') {
                try {
                    const response = await fetch(file.url);
                    const arrayBuffer = await response.arrayBuffer();

                    const options = {
                        styleMap: [
                            "p[style-name='Heading 1'] => h1:fresh",
                            "p[style-name='Heading 2'] => h2:fresh",
                            "table => table.table-word"
                        ]
                    };

                    const res = await mammoth.convertToHtml({ arrayBuffer: arrayBuffer }, options);
                    loadingEl.classList.add('d-none');

                    previewContainer.innerHTML = `
                        <div class="modal-word-sheet">
                            ${res.value || '<p class="text-center text-muted">Văn bản không có nội dung hiển thị.</p>'}
                        </div>
                    `;
                } catch (e) {
                    console.error("Lỗi đọc file Word:", e);
                    loadingEl.classList.add('d-none');
                    previewContainer.innerHTML = `
                        <div class="text-center p-5 text-white">
                            <i class="fa-solid fa-triangle-exclamation text-warning fa-3x mb-3"></i>
                            <h5>Không thể đọc trực tiếp tài liệu Word này</h5>
                            <p class="text-muted small">Vui lòng bấm nút "Tải về máy" ở góc trên bên phải để xem bằng Microsoft Word.</p>
                        </div>
                    `;
                }
            } else {
                loadingEl.classList.add('d-none');
                previewContainer.innerHTML = `
                    <div class="text-center p-5 text-white">
                        <i class="fa-solid fa-file-arrow-down fa-3x text-secondary mb-3"></i>
                        <h5>Định dạng .${ext.toUpperCase()} không hỗ trợ xem trực tiếp</h5>
                        <p class="text-muted small">Vui lòng tải tệp tin về thiết bị để mở.</p>
                    </div>
                `;
            }
        }

        async function openAssignTaskModal() {
            document.getElementById('assignProjectTitle').value = `[${currentProjectCode}] ${currentProjectTitle}`;
            const selectEl = document.getElementById('selectTeacherAssign');
            const alertMsg = document.getElementById('assignAlertMsg');
            alertMsg.className = "alert d-none py-2 small mb-0";

            selectEl.innerHTML = `<option value="" disabled selected>-- Đang nạp danh sách Giảng viên --</option>`;

            const modal = new bootstrap.Modal(document.getElementById('assignTaskModal'));
            modal.show();

            const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
            try {
                const response = await fetch(`${API_URL}?detect=user_teacher_get`, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });
                const res = await response.json();

                if (response.ok && res.status === 'success' && Array.isArray(res.data)) {
                    selectEl.innerHTML = `<option value="" disabled ${!currentActionUserId ? 'selected' : ''}>-- Chọn Giảng viên nhận nhiệm vụ --</option>`;
                    res.data.forEach(u => {
                        const isSelected = (currentActionUserId && parseInt(u.user_id) === currentActionUserId) ? 'selected' : '';
                        selectEl.innerHTML += `<option value="${u.user_id}" ${isSelected}>${escapeHtml(u.name)} (${escapeHtml(u.username)})</option>`;
                    });
                } else {
                    selectEl.innerHTML = `<option value="" disabled selected>Không tải được danh sách giảng viên</option>`;
                }
            } catch (e) {
                console.error("Lỗi nạp GV:", e);
                selectEl.innerHTML = `<option value="" disabled selected>Lỗi kết nối máy chủ</option>`;
            }
        }

        async function handleAssignTeacher() {
            const selectEl = document.getElementById('selectTeacherAssign');
            const alertMsg = document.getElementById('assignAlertMsg');
            const btnConfirm = document.getElementById('btnConfirmAssign');
            const selectedTeacherId = parseInt(selectEl.value);

            if (!selectedTeacherId || selectedTeacherId <= 0) {
                alertMsg.className = "alert alert-warning py-2 small mb-0";
                alertMsg.innerText = "Vui lòng chọn một Giảng viên từ danh sách!";
                alertMsg.classList.remove('d-none');
                return;
            }

            btnConfirm.disabled = true;
            btnConfirm.innerHTML = `<span class="spinner-border spinner-border-sm me-1"></span> Đang xử lý...`;
            alertMsg.className = "alert d-none py-2 small mb-0";

            const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');

            try {
                const response = await fetch(`${API_URL}?detect=project_update`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        project_id: parseInt(currentProjectId),
                        project_user_action_id: selectedTeacherId
                    })
                });
                const result = await response.json();

                if (response.ok && result.status === 'success') {
                    showUINotification("Đã giao nhiệm vụ thành công cho Giảng viên!", "success");

                    try {
                        await fetch(`${API_URL}?detect=send_notification`, {
                            method: 'POST',
                            headers: {
                                'Authorization': `Bearer ${token}`,
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                project_id: parseInt(currentProjectId),
                                user_id: selectedTeacherId,
                                title: `Phân công nhiệm vụ mới: [${currentProjectCode}]`,
                                content: `Bạn vừa được Ban Quản trị phân công đảm nhiệm nhiệm vụ: "${currentProjectTitle}". Vui lòng tiếp nhận và triển khai thực hiện.`,
                                type: 'INFO'
                            })
                        });
                    } catch (errNoti) {
                        console.warn("Lỗi gửi thông báo cho GV:", errNoti);
                    }

                    const modalEl = document.getElementById('assignTaskModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) modalInstance.hide();

                    loadProjectDetail(currentProjectId);
                } else {
                    alertMsg.className = "alert alert-danger py-2 small mb-0";
                    alertMsg.innerText = result.message || "Không thể cập nhật người đảm nhiệm.";
                    alertMsg.classList.remove('d-none');
                }
            } catch (err) {
                console.error("Lỗi giao nhiệm vụ:", err);
                alertMsg.className = "alert alert-danger py-2 small mb-0";
                alertMsg.innerText = "Lỗi kết nối đến máy chủ. Vui lòng thử lại.";
                alertMsg.classList.remove('d-none');
            } finally {
                btnConfirm.disabled = false;
                btnConfirm.innerHTML = `<i class="fa-solid fa-check me-1"></i> Đồng ý giao`;
            }
        }

        function confirmAction(actionType) {
            if (!hasConfirmCheckPermission) {
                showUINotification("Bạn không có quyền thực hiện thao tác này (Thiếu quyền CONFIRM_CHECK).", "danger");
                return;
            }

            pendingActionType = actionType;
            const titleEl = document.getElementById('confirmModalTitle');
            const msgEl = document.getElementById('confirmModalMessage');
            const btnExec = document.getElementById('btnExecuteAction');

            if (actionType === 'approve') {
                titleEl.innerText = "Xác nhận xét duyệt nhiệm vụ";
                msgEl.innerText = "Hệ thống sẽ chuyển trạng thái nhiệm vụ sang 'Đang tiến hành' (running), kích hoạt mốc thời gian và tự động gửi thông báo đến tài khoản đảm nhiệm. Bạn có muốn tiếp tục?";
                btnExec.className = "btn btn-success btn-sm px-4";
                btnExec.innerText = "Đồng ý duyệt";
            } else if (actionType === 'awaiting_acceptance') {
                titleEl.innerText = "Chuyển chờ nghiệm thu";
                msgEl.innerText = "Xác nhận chuyển nhiệm vụ sang giai đoạn chờ nghiệm thu và gửi thông báo?";
                btnExec.className = "btn btn-info btn-sm px-4 text-white";
                btnExec.innerText = "Xác nhận";
            } else if (actionType === 'awaiting_payment') {
                titleEl.innerText = "Xác nhận chờ thanh toán";
                msgEl.innerText = "Xác nhận chuyển nhiệm vụ sang giai đoạn chờ thanh toán / quyết toán kinh phí?";
                btnExec.className = "btn btn-primary btn-sm px-4";
                btnExec.innerText = "Xác nhận";
            } else if (actionType === 'completed') {
                titleEl.innerText = "Xác nhận hoàn thành";
                msgEl.innerText = "Xác nhận nhiệm vụ đã hoàn tất toàn bộ quy trình?";
                btnExec.className = "btn btn-success btn-sm px-4";
                btnExec.innerText = "Hoàn thành";
            } else if (actionType === 'reject') {
                titleEl.innerText = "Xác nhận từ chối nhiệm vụ";
                msgEl.innerText = "Bạn có chắc chắn muốn từ chối nhiệm vụ này không?";
                btnExec.className = "btn btn-danger btn-sm px-4";
                btnExec.innerText = "Từ chối";
            }

            const modalInstance = new bootstrap.Modal(document.getElementById('actionConfirmModal'));
            modalInstance.show();
        }

        document.getElementById('btnExecuteAction').addEventListener('click', function() {
            if (!hasConfirmCheckPermission) return;

            const modalEl = document.getElementById('actionConfirmModal');
            const modalInstance = bootstrap.Modal.getInstance(modalEl);
            modalInstance.hide();

            if (pendingActionType === 'approve') {
                updateProjectStatus('running', 'Nhiệm vụ của bạn đã được phê duyệt', 'Nhiệm vụ đã được Admin xét duyệt chính thức và bắt đầu tính thời gian thực hiện.');
            } else if (pendingActionType === 'awaiting_acceptance') {
                updateProjectStatus('awaiting_acceptance', 'Nhiệm vụ chuyển sang giai đoạn chờ nghiệm thu', 'Hồ sơ nhiệm vụ của bạn đã được chuyển sang trạng thái Chờ nghiệm thu.');
            } else if (pendingActionType === 'awaiting_payment') {
                updateProjectStatus('awaiting_payment', 'Nhiệm vụ chuyển sang giai đoạn chờ thanh toán', 'Nhiệm vụ đã được xác nhận chờ thanh toán/quyết toán kinh phí.');
            } else if (pendingActionType === 'completed') {
                updateProjectStatus('completed', 'Nhiệm vụ đã hoàn thành', 'Chúc mừng! Nhiệm vụ của bạn đã hoàn tất toàn bộ quy trình.');
            } else if (pendingActionType === 'reject') {
                rejectProject();
            }
        });

        async function updateProjectStatus(newStatus, notiTitle = '', notiContent = '') {
            if (!hasConfirmCheckPermission) return;

            const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
            const formData = new FormData();
            formData.append('project_id', currentProjectId);
            formData.append('status', newStatus);

            try {
                const response = await fetch(`${API_URL}?detect=project_update`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    },
                    body: formData
                });
                const result = await response.json();

                if (response.ok && result.status === 'success') {
                    showUINotification(result.message || "Cập nhật trạng thái thành công!", "success");

                    if (notiTitle && currentActionUserId) {
                        sendNotificationToCreator(notiTitle, `[${currentProjectCode}] ${notiContent}`, 'INFO');
                    }

                    loadProjectDetail(currentProjectId);
                } else {
                    showUINotification("Lỗi: " + result.message, "danger");
                }
            } catch (err) {
                console.error("Lỗi API update:", err);
                showUINotification("Không thể kết nối đến máy chủ.", "danger");
            }
        }

        async function rejectProject() {
            if (!hasConfirmCheckPermission) return;

            const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
            try {
                const response = await fetch(`${API_URL}?detect=project_reject`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        project_id: currentProjectId
                    })
                });
                const result = await response.json();

                if (response.ok && result.status === 'success') {
                    showUINotification(result.message || "Đã từ chối nhiệm vụ thành công!", "success");

                    if (currentActionUserId) {
                        sendNotificationToCreator("Nhiệm vụ của bạn bị từ chối", `Nhiệm vụ [${currentProjectCode}] đã bị Hội đồng / Ban Giám hiệu từ chối xét duyệt.`, 'DANGER');
                    }

                    loadProjectDetail(currentProjectId);
                } else {
                    showUINotification("Lỗi: " + result.message, "danger");
                }
            } catch (err) {
                console.error("Lỗi API reject:", err);
                showUINotification("Không thể kết nối đến máy chủ.", "danger");
            }
        }

        async function sendNotificationToCreator(title, content, notiType = 'INFO') {
            const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
            try {
                const response = await fetch(`${API_URL}?detect=send_notification`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        project_id: currentProjectId,
                        title: title,
                        content: content,
                        type: notiType
                    })
                });

                return await response.json();
            } catch (e) {
                console.error("Lỗi gửi thông báo:", e);
                throw e;
            }
        }

        function openNotifyModal() {
            if (!currentActionUserId) {
                showUINotification("Nhiệm vụ này chưa có tài khoản đảm nhiệm, không thể gửi thông báo!", "warning");
                return;
            }
            document.getElementById('notifyProjectTitle').value = `[${currentProjectCode}] ${currentProjectTitle}`;
            document.getElementById('notifyTitleInput').value = `Thông báo về nhiệm vụ: ${currentProjectCode}`;
            document.getElementById('notifyContentInput').value = '';

            const modal = new bootstrap.Modal(document.getElementById('notifyUserModal'));
            modal.show();
        }

        async function handleSendCustomNotice() {
            const title = document.getElementById('notifyTitleInput').value.trim();
            const content = document.getElementById('notifyContentInput').value.trim();
            const btnSend = document.getElementById('btnSendCustomNotice');

            if (!title || !content) {
                alert("Vui lòng nhập đầy đủ tiêu đề và nội dung!");
                return;
            }

            btnSend.disabled = true;
            btnSend.innerHTML = `<span class="spinner-border spinner-border-sm me-1"></span> Đang gửi...`;

            try {
                const res = await sendNotificationToCreator(title, content, 'INFO');
                if (res && res.status === 'success') {
                    showUINotification(res.message, "success");
                    const modalEl = document.getElementById('notifyUserModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                } else {
                    alert(res.message || "Gửi không thành công!");
                }
            } catch (err) {
                alert("Lỗi kết nối khi gửi thông báo: " + err.message);
            } finally {
                btnSend.disabled = false;
                btnSend.innerHTML = `<i class="fa-solid fa-paper-plane me-1"></i> Gửi thông báo`;
            }
        }

        function applyStatusRules(status) {
            const badge = document.getElementById('statusBadge');
            const btnApprove = document.getElementById('btnApprove');
            const btnNghiemThu = document.getElementById('btnNghiemThu');
            const btnThanhToan = document.getElementById('btnThanhToan');
            const btnComplete = document.getElementById('btnComplete');
            const btnReject = document.getElementById('btnReject');

            btnApprove.style.display = 'none';
            btnNghiemThu.style.display = 'none';
            btnThanhToan.style.display = 'none';
            btnComplete.style.display = 'none';
            btnReject.style.display = 'block';

            switch (status) {
                case 'pending':
                    badge.className = "badge bg-warning text-dark fs-6";
                    badge.innerText = "Chờ duyệt";
                    if (currentActionUserId && currentActionUserId > 0) {
                        btnApprove.style.display = 'block';
                    } else {
                        btnApprove.style.display = 'none';
                    }
                    break;
                case 'running':
                    badge.className = "badge bg-primary fs-6";
                    badge.innerText = "Đang tiến hành";
                    btnNghiemThu.style.display = 'block';
                    break;
                case 'awaiting_acceptance':
                    badge.className = "badge bg-info text-dark fs-6";
                    badge.innerText = "Chờ nghiệm thu";
                    btnThanhToan.style.display = 'block';
                    break;
                case 'awaiting_payment':
                    badge.className = "badge fs-6";
                    badge.style.backgroundColor = "#f3e8ff";
                    badge.style.color = "#6b21a8";
                    badge.innerText = "Chờ thanh toán";
                    btnComplete.style.display = 'block';
                    break;
                case 'completed':
                    badge.className = "badge bg-success fs-6";
                    badge.innerText = "Đã hoàn thành";
                    btnReject.style.display = 'none';
                    break;
                case 'rejected':
                    badge.className = "badge bg-danger fs-6";
                    badge.innerText = "Đã từ chối";
                    btnReject.style.display = 'none';
                    break;
                default:
                    badge.className = "badge bg-secondary fs-6";
                    badge.innerText = status;
                    break;
            }

            if (!hasConfirmCheckPermission) {
                const actionButtons = document.querySelectorAll('.card-body .btn-success, .card-body .btn-info, .card-body .btn-primary, .card-body .btn-outline-danger');
                actionButtons.forEach(btn => {
                    btn.disabled = true;
                    btn.title = "Tài khoản không có quyền phê duyệt (Thiếu quyền CONFIRM_CHECK)";
                    btn.classList.add('opacity-50');
                    btn.style.pointerEvents = 'none';
                });
                showUINotification("Tài khoản của bạn đang ở chế độ chỉ xem (Thiếu quyền duyệt CONFIRM_CHECK).", "warning");
            }
        }

        function formatBytes(bytes) {
            if (!bytes) return '0 KB';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
        }

        function resolveFilePath(rawPath) {
            if (!rawPath) return '';
            if (rawPath.startsWith('../asset/')) return rawPath;
            if (rawPath.startsWith('asset/')) return '../' + rawPath;
            return '../asset/file/project/' + rawPath.split('/').pop();
        }

        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
        }
    </script>
</body>

</html>