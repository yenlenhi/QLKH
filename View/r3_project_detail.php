<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <base href="/QLNCKH/View/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NCKH PORTAL - Chi Tiết Đề Tài</title>
    <script src="../asset/script/auth_check.js"></script>
    <link rel="stylesheet" href="../asset/css/all.min.css">
    <link href="../asset/css/bootstrap5.css" rel="stylesheet">
    <link rel="stylesheet" href="../asset/css/style.css">

    <style>
        :root {
            --sidebar-width: 260px;
        }

        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            background-color: #f8fafc;
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
            overflow: hidden;
        }

        .card-panel {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 22px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            height: 100%;
        }

        .card-panel-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.885rem;
        }

        .info-table td {
            padding: 8px 0;
            vertical-align: top;
        }

        .info-table td.label {
            color: #64748b;
            font-weight: 500;
            width: 140px;
        }

        .info-table td.value {
            color: #0f172a;
            font-weight: 600;
            word-break: break-word;
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

        .file-list-container {
            display: flex;
            flex-direction: column;
            gap: 12px;
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

        /* Task Item Checklist tinh tế */
        .task-table th {
            font-size: 0.8rem;
            font-weight: 600;
            color: #475569;
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        .task-table td {
            font-size: 0.84rem;
            padding: 10px 8px;
            border-bottom: 1px solid #f1f5f9;
        }

        .task-row-done {
            background-color: #fafafa;
        }

        .task-row-done .task-title-text {
            text-decoration: line-through;
            color: #94a3b8;
        }

        /* CĂN GIỮA TOÀN BỘ MODAL TRONG HỆ THỐNG */
        /* ĐẶT NỀN MODAL TRONG SUỐT VÀ CĂN GIỮA MÀN HÌNH */
        /* 1. KHUNG MODAL CĂN CHÍNH GIỮA MÀN HÌNH */
        /* 1. KHUNG MODAL VÀ NỀN XÁM TRONG SUỐT */
        .modal {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            z-index: 1060 !important;
            overflow-x: hidden !important;
            overflow-y: auto !important;
            background-color: rgba(30, 41, 59, 0.45) !important;
            /* Xám than trong suốt 45%, nhìn xuyên thấu giao diện sau */
            backdrop-filter: blur(2px) !important;
            /* Mờ nhẹ tạo chiều sâu tinh tế */
        }

        .modal-dialog-centered {
            display: flex !important;
            align-items: center !important;
            min-height: calc(100% - 3.5rem) !important;
            margin: 1.75rem auto !important;
        }

        /* 2. LOẠI BỎ BACKDROP MẶC ĐỊNH CỦA BOOTSTRAP ĐỂ TRÁNH TRẮNG MÀN HÌNH */
        .modal-backdrop {
            display: none !important;
        }

        body.modal-open {
            overflow: auto !important;
            padding-right: 0 !important;
        }

        /* Modal Preview tài liệu */
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
    </style>
</head>

<body>
    <!-- SIDEBAR -->
    <?php include '../Model/r3_sidebar.php'; ?>

    <div class="main-wrapper" id="mainWrapper">
        <!-- TOPBAR -->
        <?php
        $pageTitle = "CHI TIẾT ĐỀ TÀI NGHIÊN CỨU";
        $pageIcon = "fa-solid fa-folder-open";
        include '../Model/r3_topbar.php';
        ?>

        <main class="container-fluid px-4 py-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <button type="button" class="btn-back" onclick="handleGoBack()">
                    <i class="fa-solid fa-arrow-left"></i> Quay lại
                </button>
                <button type="button" class="btn btn-primary btn-sm fw-semibold" id="btnGoToUpdate" onclick="goToUpdatePage()">
                    <i class="fa-solid fa-pen-to-square me-1"></i> Chỉnh sửa / Bổ sung tài liệu
                </button>
            </div>

            <!-- BỐ CỤC 2 CỘT -->
            <div class="row g-4">
                <!-- CỘT TRÁI: THÔNG TIN CHI TIẾT ĐỀ TÀI -->
                <div class="col-lg-5">
                    <div class="card-panel">
                        <div class="card-panel-title">
                            <span><i class="fa-solid fa-circle-info text-primary me-2"></i>Thông Tin Đề Tài</span>
                            <span class="badge bg-warning text-dark" id="statusBadge">Đang tải...</span>
                        </div>

                        <table class="info-table">
                            <tbody>
                                <tr>
                                    <td class="label">Mã đề tài:</td>
                                    <td class="value text-primary" id="lblCode">---</td>
                                </tr>
                                <tr>
                                    <td class="label">Tên đề tài:</td>
                                    <td class="value text-dark" id="lblTitle">Đang tải...</td>
                                </tr>
                                <tr>
                                    <td class="label">Hình thức:</td>
                                    <td class="value"><span class="badge bg-light text-dark border" id="lblRegType">---</span></td>
                                </tr>
                                <tr>
                                    <td class="label">Cấp đề tài:</td>
                                    <td class="value"><span class="badge bg-secondary" id="lblLevel">---</span></td>
                                </tr>
                                <tr>
                                    <td class="label">Phân hệ:</td>
                                    <td class="value" id="lblSubSystem">---</td>
                                </tr>
                                <tr>
                                    <td class="label">Độ mật:</td>
                                    <td class="value" id="lblSecurity">---</td>
                                </tr>
                                <tr>
                                    <td class="label">Dự toán:</td>
                                    <td class="value text-secondary" id="lblBudget">0 VNĐ</td>
                                </tr>
                                <tr>
                                    <td class="label">Được duyệt:</td>
                                    <td class="value text-success" id="lblApprovedBudget">0 VNĐ</td>
                                </tr>
                                <tr>
                                    <td class="label">Đã thanh toán:</td>
                                    <td class="value text-primary" id="lblPaidAmount">0 VNĐ</td>
                                </tr>
                                <tr>
                                    <td class="label">GV phụ trách:</td>
                                    <td class="value" id="lblTeacher">---</td>
                                </tr>
                                <tr>
                                    <td class="label">Chủ nhiệm:</td>
                                    <td class="value" id="lblLeader">---</td>
                                </tr>
                                <tr>
                                    <td class="label">Thành viên:</td>
                                    <td class="value" style="font-weight: normal; color: #475569;">
                                        <ul style="margin: 0; padding-left: 16px;" id="lblMembers">
                                            <li>Đang tải...</li>
                                        </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- CỘT PHẢI: HỒ SƠ ĐÍNH KÈM -->
                <div class="col-lg-7">
                    <div class="card-panel">
                        <div class="card-panel-title">
                            <span>
                                <i class="fa-solid fa-folder-open text-primary me-2"></i>Hồ Sơ Đính Kèm (<span id="fileCount">0</span>)
                            </span>
                            <span class="badge bg-light text-muted border fw-normal" style="font-size: 0.785rem;">
                                Bấm "Xem tài liệu" để đọc
                            </span>
                        </div>

                        <div class="file-list-container" id="fileListContainer">
                            <div class="text-center text-muted py-5 small">Đang tải danh sách tài liệu...</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KHỐI LẬP DANH SÁCH & PHÂN CÔNG NHIỆM VỤ -->
            <div class="card border border-light-subtle rounded-3 shadow-none mt-4" style="background: #ffffff;">
                <div class="card-header bg-white border-bottom border-light-subtle py-3 px-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold text-dark m-0 d-flex align-items-center gap-2" style="font-size: 0.95rem;">
                            <i class="fa-solid fa-list-check text-secondary"></i> Danh Sách Nhiệm Vụ & Phân Công Thành Viên
                        </h6>
                        <span class="text-muted" style="font-size: 0.8rem;">Chủ nhiệm lập khung nhiệm vụ nghiên cứu, phân công cho các thành viên và theo dõi tiến độ</span>
                    </div>
                    <!-- Nút Thêm nhiệm vụ vào danh mục -->
                    <button type="button" class="btn btn-sm btn-outline-dark px-3 fw-semibold" id="btnCreateTaskItem" onclick="openCreateTaskModal()">
                        <i class="fa-solid fa-plus me-1"></i> Thêm nhiệm vụ
                    </button>
                </div>

                <div class="card-body p-4">
                    <!-- TIẾN ĐỘ TỔNG QUAN -->
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="small fw-semibold text-secondary">Tiến độ công việc toàn bộ đề tài:</span>
                        <span class="small fw-bold text-dark" id="taskProgressPercent">0%</span>
                    </div>
                    <div class="progress mb-4" style="height: 6px; background-color: #f1f5f9;">
                        <div class="progress-bar bg-dark" id="taskProgressBar" role="progressbar" style="width: 0%;"></div>
                    </div>

                    <!-- BẢNG NHIỆM VỤ ĐỦ CÁC CỘT: XONG, STT, TÊN NHIỆM VỤ, PHÂN CÔNG, ĐỢT, THỜI HẠN, THAO TÁC -->
                    <div class="table-responsive">
                        <table class="table task-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 45px;" class="text-center">Xong</th>
                                    <th style="width: 50px;" class="text-center">STT</th>
                                    <th>Tên nhiệm vụ / Nội dung nghiên cứu</th>
                                    <th style="width: 250px;">Phân công thực hiện</th>
                                    <th style="width: 100px;" class="text-center">Đợt</th>
                                    <th style="width: 130px;" class="text-center">Thời hạn</th>
                                    <th style="width: 120px;" class="text-center task-action-col">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="projectTaskBody">
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="fa-solid fa-spinner fa-spin me-2"></i> Đang tải dữ liệu công việc...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- MODAL 1: THÊM NHIỆM VỤ MỚI VÀO DANH SÁCH -->
    <div class="modal fade" id="createTaskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-sm rounded-3">
                <div class="modal-header border-bottom py-3 px-4">
                    <h6 class="modal-title fw-bold text-dark" style="font-size: 0.95rem;">
                        <i class="fa-solid fa-list-plus me-1 text-secondary"></i> Thêm nhiệm vụ nghiên cứu mới
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formCreateTask" onsubmit="handleCreateTask(event)">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Tên nhiệm vụ / Nội dung công việc <span class="text-danger">*</span></label>
                            <input type="text" id="taskTitleInput" class="form-control form-control-sm" placeholder="VD: Khảo sát thực tế, Thu thập tài liệu, Viết chuyên đề 1..." required>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-secondary">Thuộc đợt báo cáo</label>
                                <select id="taskPhaseSelect" class="form-select form-select-sm"></select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-secondary">Thời hạn hoàn thành</label>
                                <input type="date" id="taskDueDateInput" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label small fw-semibold text-secondary">Yêu cầu / Sản phẩm đầu ra cần đạt</label>
                            <textarea id="taskDescInput" class="form-control form-control-sm" rows="2" placeholder="Ghi chú chi tiết yêu cầu..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-2 px-4 justify-content-end gap-2 border-top">
                        <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-dark btn-sm px-4 fw-semibold" id="btnSubmitCreateTask">Lưu nhiệm vụ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL 2: GIAO VIỆC (CHỌN NHIỀU THÀNH VIÊN - QUAN HỆ N-N) -->
    <div class="modal fade" id="assignMemberModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-sm rounded-3">
                <div class="modal-header border-bottom py-3 px-4">
                    <h6 class="modal-title fw-bold text-dark" style="font-size: 0.95rem;">
                        <i class="fa-solid fa-users-gear me-1 text-secondary"></i> Phân công thành viên thực hiện
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formAssignMember" onsubmit="handleSaveAssignment(event)">
                    <input type="hidden" id="assignTaskId">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Nhiệm vụ đang phân công:</label>
                            <div class="p-2 bg-light border rounded small fw-bold text-dark" id="displayAssignTaskTitle">---</div>
                        </div>
                        <div>
                            <label class="form-label small fw-semibold text-secondary mb-2">
                                Chọn thành viên đảm nhiệm (cho phép chọn nhiều người):
                            </label>
                            <div id="memberCheckboxList" class="d-flex flex-column gap-2 p-2 border rounded" style="max-height: 220px; overflow-y: auto;">
                                <!-- Nạp danh sách checkbox qua JS -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-2 px-4 justify-content-end gap-2 border-top">
                        <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-dark btn-sm px-4 fw-semibold" id="btnSubmitAssign">Lưu phân công</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL XEM TÀI LIỆU TOÀN MÀN HÌNH -->
    <div class="modal fade" id="docPreviewModal" tabindex="-1" aria-hidden="true">
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

    <!-- MODAL TOPBAR -->
    <?php include '../Model/r3_modal_topbar.php'; ?>

    <script src="../asset/script/bootstrap5.js"></script>
    <script src="../asset/script/sidebar.js"></script>
    <script src="../asset/script/mammoth.js"></script>
    <script>
        const API_URL = "../index_api.php";

        let currentProjectId = 0;
        let projectFiles = [];
        let projectMembers = [];
        let canEditTask = false;
        let currentTaskReportPhases = 2; // Số đợt báo cáo của đề tài
        let loadedTasksList = []; // Danh sách nhiệm vụ đang hiển thị

        function handleGoBack() {
            if (document.referrer && document.referrer.includes(window.location.host)) {
                window.location.href = document.referrer;
            } else if (window.history.length > 1) {
                window.history.back();
            } else {
                window.location.href = "/QLNCKH/nhiem-vu-cua-toi";
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            currentProjectId = urlParams.get('id');

            // Bổ sung: Nếu URL dạng rewrite /QLNCKH/de-tai/12 thì bóc tách trực tiếp từ pathname
            if (!currentProjectId) {
                const pathParts = window.location.pathname.split('/');
                const lastSegment = pathParts[pathParts.length - 1];
                if (!isNaN(lastSegment) && parseInt(lastSegment) > 0) {
                    currentProjectId = lastSegment;
                }
            }

            if (!currentProjectId) {
                alert("Không tìm thấy mã đề tài!");
                handleGoBack();
                return;
            }

            loadProjectDetail(currentProjectId);
            loadProjectTasks(currentProjectId);
        });

        function goToUpdatePage() {
            if (currentProjectId) {
                window.location.href = `/QLNCKH/cap-nhat-de-tai?id=${encodeURIComponent(currentProjectId)}`;
            }
        }

        function escapeHtml(str) {
            if (!str) return '';
            return String(str).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
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

        // Thêm hàm lấy user_id chuẩn từ JWT
        function getCurrentUserIdFromJWT() {
            const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
            if (token) {
                try {
                    const base64Url = token.split('.')[1];
                    const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
                    const jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
                        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
                    }).join(''));
                    const payload = JSON.parse(jsonPayload);
                    return parseInt(payload.user_id || payload.id || 0);
                } catch (e) {
                    console.error("Lỗi giải mã JWT:", e);
                }
            }
            return 0;
        }
        // -------------------------------------------------------------
        // 1. NẠP CHI TIẾT ĐỀ TÀI VÀ XÁC ĐỊNH QUYỀN TỪ project_user_action_id
        // -------------------------------------------------------------
        async function loadProjectDetail(id) {
            const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
            try {
                const response = await fetch(`${API_URL}?detect=project_get&project_id=${encodeURIComponent(id)}`, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });
                const result = await response.json();

                if (response.ok && result.status === 'success' && result.data) {
                    const data = result.data;

                    document.getElementById('lblCode').innerText = data.project_code || ('DT' + data.project_id);
                    document.getElementById('lblTitle').innerText = data.project_name || '---';
                    document.getElementById('lblRegType').innerText = data.register_type_name || '---';
                    document.getElementById('lblLevel').innerText = data.level_name || '---';
                    document.getElementById('lblSubSystem').innerText = data.sub_system_name || '---';
                    document.getElementById('lblSecurity').innerText = data.security_name || '---';
                    document.getElementById('lblTeacher').innerText = data.teacher_in_charge_name || '---';

                    document.getElementById('lblBudget').innerText = Number(data.estimated_budget || 0).toLocaleString('vi-VN') + ' VNĐ';
                    const approvedBudget = data.approved_budget || data.settlement_budget || data.estimated_budget || 0;
                    document.getElementById('lblApprovedBudget').innerText = Number(approvedBudget).toLocaleString('vi-VN') + ' VNĐ';

                    let totalPaid = 0;
                    if (Array.isArray(data.payments) && data.payments.length > 0) {
                        totalPaid = data.payments.reduce((sum, p) => sum + parseFloat(p.paid_amount || 0), 0);
                    } else if (data.total_paid) {
                        totalPaid = parseFloat(data.total_paid);
                    }
                    document.getElementById('lblPaidAmount').innerText = Number(totalPaid).toLocaleString('vi-VN') + ' VNĐ';

                    applyStatusBadge(data.status || 'pending');

                    // KIỂM TRA: CHỈ CẦN TÀI KHOẢN ĐANG SỬ DỤNG LÀ CHỦ NHIỆM (HOẶC ADMIN)
                    const jwtUserId = getCurrentUserIdFromJWT();

                    // Tìm thành viên là chủ nhiệm trong danh sách members
                    let isLeaderUser = false;
                    if (Array.isArray(data.members)) {
                        const leaderMem = data.members.find(m => parseInt(m.is_leader) === 1);
                        if (leaderMem && leaderMem.user_id && parseInt(leaderMem.user_id) === jwtUserId) {
                            isLeaderUser = true;
                        }
                    }

                    // Dự phòng: nếu khớp project_user_action_id cũng là chủ nhiệm
                    const actionUserId = parseInt(data.project_user_action_id || 0);
                    if (jwtUserId > 0 && jwtUserId === actionUserId) {
                        isLeaderUser = true;
                    }

                    const isAdmin = (jwtUserId === 1 || jwtUserId === 7);
                    canEditTask = (isLeaderUser || isAdmin);

                    // Mở nút "Thêm nhiệm vụ" nếu là chủ nhiệm
                    const btnCreate = document.getElementById('btnCreateTaskItem');
                    if (btnCreate) {
                        btnCreate.classList.remove('d-none');
                        btnCreate.disabled = !canEditTask;
                        if (!canEditTask) {
                            btnCreate.setAttribute('title', 'Chỉ Chủ nhiệm đề tài mới có quyền thêm nhiệm vụ');
                        } else {
                            btnCreate.removeAttribute('title');
                        }
                    }

                    // Danh sách thành viên
                    if (Array.isArray(data.members) && data.members.length > 0) {
                        projectMembers = data.members;
                        let leaderName = "Chưa phân công";
                        let otherHtml = "";

                        data.members.forEach(m => {
                            const platoon = m.platoon ? ` (${escapeHtml(m.platoon)})` : '';
                            if (parseInt(m.is_leader) === 1) {
                                leaderName = m.full_name + (m.platoon ? ` (${m.platoon})` : '');
                            } else {
                                otherHtml += `<li>${escapeHtml(m.full_name)}${platoon}</li>`;
                            }
                        });

                        document.getElementById('lblLeader').innerText = leaderName;
                        document.getElementById('lblMembers').innerHTML = otherHtml || '<li>Không có thành viên khác</li>';
                    } else {
                        document.getElementById('lblLeader').innerText = "Chưa cập nhật";
                        document.getElementById('lblMembers').innerHTML = '<li>Không có thành viên</li>';
                    }

                    // Tệp đính kèm
                    if (Array.isArray(data.files) && data.files.length > 0) {
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
                        document.getElementById('fileListContainer').innerHTML = `<div class="text-center text-muted py-5 small">Chưa có tệp đính kèm nào.</div>`;
                    }
                } else {
                    alert(result.message || "Không tìm thấy thông tin đề tài!");
                    handleGoBack();
                }
            } catch (err) {
                console.error("Lỗi tải chi tiết đề tài:", err);
                alert("Lỗi kết nối đến máy chủ!");
            }
        }

        // -------------------------------------------------------------
        // 2. NẠP DANH SÁCH NHIỆM VỤ (QUA index_api.php?detect=project_task_get)
        // -------------------------------------------------------------
        async function loadProjectTasks(id) {
            const tbody = document.getElementById('projectTaskBody');
            const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
            const btnAdd = document.getElementById('btnCreateTaskItem');

            try {
                const res = await fetch(`${API_URL}?detect=project_task_get&project_id=${encodeURIComponent(id)}`, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });
                const result = await res.json();

                if (res.ok && result.status === 'success') {
                    // Nếu API xác nhận can_edit = true hoặc client đã xác thực là chủ nhiệm
                    if (result.can_edit) {
                        canEditTask = true;
                    }

                    loadedTasksList = (result.data && result.data.tasks) ? result.data.tasks : [];
                    const members = (result.data && result.data.members) ? result.data.members : [];

                    if (members.length > 0) {
                        projectMembers = members;

                        // Kiểm tra lại lần nữa từ danh sách members trả về
                        const jwtUserId = getCurrentUserIdFromJWT();
                        const leaderMem = members.find(m => parseInt(m.is_leader) === 1);
                        if ((leaderMem && parseInt(leaderMem.user_id) === jwtUserId) || jwtUserId === 1 || jwtUserId === 7) {
                            canEditTask = true;
                        }
                    }

                    if (result.report_phases) {
                        currentTaskReportPhases = parseInt(result.report_phases);
                    }

                    // ĐẢM BẢO NÚT THÊM NHIỆM VỤ LUÔN SẴN SÀNG CLICK
                    if (btnAdd) {
                        btnAdd.classList.remove('d-none');
                        btnAdd.disabled = false;
                        btnAdd.removeAttribute('title');
                    }

                    renderTasksTable(loadedTasksList);
                } else {
                    if (btnAdd) {
                        btnAdd.classList.remove('d-none');
                        btnAdd.disabled = false;
                    }
                    tbody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-muted small">Chưa có nhiệm vụ nào. Hãy bấm "Thêm nhiệm vụ" để tạo khung công việc.</td></tr>`;
                    updateTaskProgress(0, 0);
                }
            } catch (err) {
                console.error("Lỗi tải tasks:", err);
                tbody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-muted small">Không thể kết nối đến máy chủ.</td></tr>`;
            }
        }

        // -------------------------------------------------------------
        // 3. HIỂN THỊ DANH SÁCH NHIỆM VỤ RA BẢNG CHECKLIST
        // -------------------------------------------------------------
        function renderTasksTable(tasks) {
            const tbody = document.getElementById('projectTaskBody');
            tbody.innerHTML = '';

            if (!tasks || tasks.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-muted small">Chưa có nhiệm vụ cụ thể nào. Hãy bấm "Thêm nhiệm vụ" để lập danh sách.</td></tr>`;
                updateTaskProgress(0, 0);
                return;
            }

            let doneCount = 0;

            tasks.forEach((t, index) => {
                const isDone = (t.status === 'completed');
                if (isDone) doneCount++;

                const tr = document.createElement('tr');
                if (isDone) tr.className = 'task-row-done';

                // Hiển thị danh sách các thành viên được phân công
                let assignedHtml = '';
                if (t.assigned_members && t.assigned_members.length > 0) {
                    assignedHtml = t.assigned_members.map(m => {
                        const isLeader = parseInt(m.is_leader) === 1 ? ' <span class="badge bg-light text-dark border py-0 px-1" style="font-size:0.65rem">CN</span>' : '';
                        return `<div class="text-truncate py-0"><i class="fa-solid fa-user-check text-secondary me-1" style="font-size: 0.75rem;"></i><span class="fw-semibold text-dark">${escapeHtml(m.full_name)}</span>${isLeader}</div>`;
                    }).join('');
                } else {
                    assignedHtml = `<span class="text-muted fst-italic" style="font-size: 0.8rem;">Chưa phân công</span>`;
                }

                tr.innerHTML = `
            <td class="text-center">
                <input class="form-check-input" type="checkbox" ${isDone ? 'checked' : ''} 
                    ${!canEditTask ? 'disabled' : ''} 
                    onchange="handleToggleTask(${t.task_id}, this.checked)" 
                    style="cursor: ${canEditTask ? 'pointer' : 'default'};">
            </td>
            <td class="text-center text-muted fw-semibold">${index + 1}</td>
            <td>
                <div class="task-title-text fw-semibold text-dark">${escapeHtml(t.task_title)}</div>
                ${t.description ? `<small class="text-muted d-block" style="font-size: 0.75rem;">${escapeHtml(t.description)}</small>` : ''}
            </td>
            <td>
                <div class="d-flex flex-column gap-1">${assignedHtml}</div>
            </td>
            <td class="text-center"><span class="badge bg-light text-secondary border">Đợt ${t.phase}</span></td>
            <td class="text-center text-muted" style="font-size: 0.8rem;">${t.due_date ? t.due_date : '---'}</td>
            <td class="text-center task-action-col">
                <div class="d-inline-flex gap-2">
                   <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 fw-semibold" style="font-size: 0.75rem;" 
                        onclick="openAssignMemberModal(${t.task_id})" ${!canEditTask ? 'disabled title="Chỉ Chủ nhiệm đề tài mới có quyền giao việc"' : 'title="Giao / Phân công thành viên"'}>
                        <i class="fa-solid fa-user-plus me-1"></i> Giao việc
                    </button>
                    <button type="button" class="btn btn-sm text-secondary p-0 border-0" onclick="handleDeleteTask(${t.task_id})" ${!canEditTask ? 'disabled' : ''} title="Xóa nhiệm vụ">
                        <i class="fa-regular fa-trash-can"></i>
                    </button>
                </div>
            </td>
        `;
                tbody.appendChild(tr);
            });

            updateTaskProgress(doneCount, tasks.length);
        }

        function updateTaskProgress(completed, total) {
            const pct = total > 0 ? Math.round((completed / total) * 100) : 0;
            document.getElementById('taskProgressPercent').innerText = `${pct}% (${completed}/${total} việc)`;
            document.getElementById('taskProgressBar').style.width = `${pct}%`;
        }
        // -------------------------------------------------------------
        // BƯỚC 1: MỞ MODAL THÊM NHIỆM VỤ (ĐỢT LẤY TỪ REPORT_PHASES CỦA ĐỀ TÀI)
        // -------------------------------------------------------------
        function openCreateTaskModal() {
            const phaseSelect = document.getElementById('taskPhaseSelect');
            phaseSelect.innerHTML = '';

            for (let i = 1; i <= currentTaskReportPhases; i++) {
                phaseSelect.innerHTML += `<option value="${i}">Đợt ${i}</option>`;
            }

            document.getElementById('taskTitleInput').value = '';
            document.getElementById('taskDueDateInput').value = '';
            document.getElementById('taskDescInput').value = '';

            const modalEl = document.getElementById('createTaskModal');
            // Mở trực tiếp modal
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        }
        // -------------------------------------------------------------
        // BƯỚC 2: MỞ MODAL GIAO VIỆC (CHỌN NHIỀU THÀNH VIÊN TRONG ĐỀ TÀI)
        // -------------------------------------------------------------
        function openAssignMemberModal(taskId) {
            const task = loadedTasksList.find(t => parseInt(t.task_id) === parseInt(taskId));
            if (!task) return;

            document.getElementById('assignTaskId').value = task.task_id;
            document.getElementById('displayAssignTaskTitle').innerText = task.task_title;

            const assignedIds = (task.assigned_members || []).map(m => parseInt(m.member_id));
            const container = document.getElementById('memberCheckboxList');
            container.innerHTML = '';

            if (!projectMembers || projectMembers.length === 0) {
                container.innerHTML = `<div class="text-muted small p-2">Không tìm thấy danh sách thành viên đề tài.</div>`;
            } else {
                projectMembers.forEach(m => {
                    const mId = parseInt(m.member_id || m.id);
                    const isChecked = assignedIds.includes(mId) ? 'checked' : '';
                    // Nhãn Chủ nhiệm đẩy trôi dạt về góc phải (ms-auto), không co cụm (flex-shrink-0)
                    const leaderTag = parseInt(m.is_leader) === 1 ?
                        `<span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1 ms-auto flex-shrink-0" style="font-size: 0.68rem; font-weight: 600;">Chủ nhiệm</span>` :
                        '';

                    container.innerHTML += `
                <label class="d-flex align-items-center gap-2 py-2 px-2 border-bottom mb-0 w-100" style="cursor: pointer; transition: background 0.15s ease;">
                    <input class="form-check-input assign-member-cb m-0 flex-shrink-0" type="checkbox" value="${mId}" ${isChecked}>
                    <div class="d-flex flex-column text-truncate me-2" style="min-width: 0;">
                        <span class="small fw-semibold text-dark text-truncate">${escapeHtml(m.full_name)}</span>
                        <small class="text-muted text-truncate" style="font-size: 0.75rem;" title="${escapeHtml(m.platoon || 'Đơn vị')}">
                            (${escapeHtml(m.platoon || 'Đơn vị')})
                        </small>
                    </div>
                    ${leaderTag}
                </label>
            `;
                });
            }

            const modalEl = document.getElementById('assignMemberModal');
            bootstrap.Modal.getOrCreateInstance(modalEl).show();

        }
        async function handleSaveAssignment(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSubmitAssign');
            const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
            const taskId = document.getElementById('assignTaskId').value;

            const selectedMembers = [];
            document.querySelectorAll('.assign-member-cb:checked').forEach(cb => {
                selectedMembers.push(parseInt(cb.value));
            });

            try {
                btn.disabled = true;
                btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1"></span> Đang lưu...`;

                const res = await fetch(`${API_URL}?detect=project_task_assign`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify({
                        task_id: taskId,
                        member_ids: selectedMembers
                    })
                });
                const result = await res.json();

                if (res.ok && result.status === 'success') {
                    bootstrap.Modal.getInstance(document.getElementById('assignMemberModal')).hide();
                    loadProjectTasks(currentProjectId);
                } else {
                    alert(result.message || 'Lỗi khi phân công nhiệm vụ');
                }
            } catch (err) {
                alert('Lỗi kết nối máy chủ');
            } finally {
                btn.disabled = false;
                btn.innerText = 'Lưu phân công';
            }
        }

        // -------------------------------------------------------------
        // 4. MỞ MODAL VÀ GIAO NHIỆM VỤ (QUA index_api.php?detect=project_task_create)
        // -------------------------------------------------------------
        function openAddTaskModal() {
            const select = document.getElementById('taskMemberSelect');
            select.innerHTML = '<option value="">-- Chưa gán (Toàn nhóm) --</option>';

            if (Array.isArray(projectMembers) && projectMembers.length > 0) {
                projectMembers.forEach(m => {
                    const memberId = m.member_id || m.id;
                    const isLeaderText = (parseInt(m.is_leader) === 1) ? ' (Chủ nhiệm)' : '';
                    select.innerHTML += `<option value="${memberId}">${escapeHtml(m.full_name)}${isLeaderText}</option>`;
                });
            }

            document.getElementById('taskTitleInput').value = '';
            document.getElementById('taskDueDateInput').value = '';
            document.getElementById('taskDescInput').value = '';

            bootstrap.Modal.getOrCreateInstance(document.getElementById('addTaskModal')).show();
        }

        async function handleCreateTask(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSubmitCreateTask');
            const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');

            const payload = {
                project_id: currentProjectId,
                task_title: document.getElementById('taskTitleInput').value.trim(),
                phase: document.getElementById('taskPhaseSelect').value,
                due_date: document.getElementById('taskDueDateInput').value,
                description: document.getElementById('taskDescInput').value.trim()
            };

            try {
                btn.disabled = true;
                btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1"></span> Đang lưu...`;

                const res = await fetch(`${API_URL}?detect=project_task_create`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify(payload)
                });
                const result = await res.json();

                if (res.ok && result.status === 'success') {
                    bootstrap.Modal.getInstance(document.getElementById('createTaskModal')).hide();
                    loadProjectTasks(currentProjectId);
                } else {
                    alert(result.message || 'Lỗi khi tạo nhiệm vụ');
                }
            } catch (err) {
                alert('Lỗi kết nối máy chủ');
            } finally {
                btn.disabled = false;
                btn.innerText = 'Lưu nhiệm vụ';
            }
        }

        // -------------------------------------------------------------
        // 5. TÍCH CHỌN HOÀN THÀNH (QUA index_api.php?detect=project_task_toggle_status)
        // -------------------------------------------------------------
        async function handleToggleTask(taskId, isChecked) {
            const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
            try {
                const res = await fetch(`${API_URL}?detect=project_task_toggle_status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify({
                        task_id: taskId,
                        is_completed: isChecked
                    })
                });
                const result = await res.json();
                if (res.ok && result.status === 'success') {
                    loadProjectTasks(currentProjectId);
                } else {
                    alert(result.message || 'Không thể cập nhật tiến độ');
                    loadProjectTasks(currentProjectId);
                }
            } catch (e) {
                alert('Lỗi kết nối máy chủ');
                loadProjectTasks(currentProjectId);
            }
        }

        // -------------------------------------------------------------
        // 6. XÓA NHIỆM VỤ (QUA index_api.php?detect=project_task_delete)
        // -------------------------------------------------------------
        async function handleDeleteTask(taskId) {
            if (!confirm('Bạn có chắc muốn xóa nhiệm vụ này?')) return;
            const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');

            try {
                const res = await fetch(`${API_URL}?detect=project_task_delete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify({
                        task_id: taskId
                    })
                });
                const result = await res.json();
                if (res.ok && result.status === 'success') {
                    loadProjectTasks(currentProjectId);
                } else {
                    alert(result.message || 'Lỗi khi xóa nhiệm vụ');
                }
            } catch (e) {
                alert('Lỗi kết nối máy chủ');
            }
        }

        // -------------------------------------------------------------
        // 7. XEM HỒ SƠ ĐÍNH KÈM (MAMMOTH.JS & PDF)
        // -------------------------------------------------------------
        function renderFileList() {
            const container = document.getElementById('fileListContainer');
            document.getElementById('fileCount').innerText = projectFiles.length;

            container.innerHTML = projectFiles.map((file, idx) => {
                const ext = file.name.split('.').pop().toLowerCase();
                const iconClass = (ext === 'doc' || ext === 'docx') ?
                    'fa-file-word text-primary' :
                    (ext === 'pdf' ? 'fa-file-pdf text-danger' : 'fa-file-lines text-secondary');

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

                    const res = await mammoth.convertToHtml({
                        arrayBuffer: arrayBuffer
                    }, options);
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

        function applyStatusBadge(status) {
            const badge = document.getElementById('statusBadge');
            switch (status) {
                case 'pending':
                    badge.className = "badge bg-warning text-dark";
                    badge.innerText = "Chờ duyệt";
                    break;
                case 'running':
                    badge.className = "badge bg-primary";
                    badge.innerText = "Đang tiến hành";
                    break;
                case 'awaiting_acceptance':
                    badge.className = "badge bg-info text-dark";
                    badge.innerText = "Chờ nghiệm thu";
                    break;
                case 'awaiting_payment':
                    badge.className = "badge bg-secondary";
                    badge.innerText = "Chờ thanh toán";
                    break;
                case 'completed':
                    badge.className = "badge bg-success";
                    badge.innerText = "Đã hoàn thành";
                    break;
                case 'rejected':
                    badge.className = "badge bg-danger";
                    badge.innerText = "Đã từ chối";
                    break;
                default:
                    badge.className = "badge bg-secondary";
                    badge.innerText = status;
                    break;
            }
        }
    </script>
</body>

</html>