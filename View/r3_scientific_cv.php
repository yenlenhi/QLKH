<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <base href="/QLNCKH/View/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lý Lịch Khoa Học (BM-04) - Giảng Viên</title>
    <script src="../asset/script/auth_check.js"></script>
    <link rel="stylesheet" href="../asset/css/all.min.css">
    <link href="../asset/css/bootstrap5.css" rel="stylesheet">
    <link rel="stylesheet" href="../asset/css/style.css">

    <style>
        :root {
            --sidebar-width: 250px;
            --primary-blue: #0284c7;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #f1f5f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #1e293b;
            overflow-x: hidden;
        }

        .main-wrapper {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            box-sizing: border-box;
            transition: margin-left 0.25s ease, width 0.25s ease;
        }

        .top-header {
            padding-left: 24px !important;
            padding-right: 24px !important;
            padding-top: 12px !important;
            padding-bottom: 12px !important;
            background: #ffffff !important;
            border-bottom: 1px solid #e2e8f0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            min-height: 64px !important;
            box-sizing: border-box !important;
        }

        .topbar-right {
            display: flex !important;
            align-items: center !important;
            gap: 18px !important;
        }

        .user-profile {
            display: flex !important;
            align-items: center !important;
            gap: 10px !important;
            padding-left: 14px !important;
            border-left: 1px solid #e2e8f0 !important;
            cursor: pointer !important;
        }

        .avatar {
            width: 36px !important;
            height: 36px !important;
            background: #2563eb !important;
            color: white !important;
            border-radius: 50% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-weight: bold !important;
            font-size: 0.9rem !important;
            overflow: hidden !important;
            flex-shrink: 0 !important;
        }

        .cv-card {
            background: #ffffff;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
            overflow: hidden;
            width: 100% !important;
            box-sizing: border-box !important;
        }

        .nav-tabs-custom {
            display: grid !important;
            grid-template-columns: repeat(4, 1fr) !important;
            width: 100% !important;
            background: #ffffff;
            border-bottom: 2px solid #e2e8f0;
            padding: 0 !important;
            margin: 0 !important;
            box-sizing: border-box !important;
        }

        .nav-tabs-custom .nav-link {
            border: none;
            padding: 16px 12px !important;
            font-size: 0.92rem;
            font-weight: 600;
            color: #64748b;
            border-bottom: 3px solid transparent;
            border-radius: 0;
            cursor: pointer;
            transition: all 0.2s ease;
            background: transparent;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px;
            text-align: center;
            white-space: nowrap;
            width: 100% !important;
            box-sizing: border-box !important;
        }

        .nav-tabs-custom .nav-link:hover {
            color: #0284c7;
            background-color: #f8fafc;
        }

        .nav-tabs-custom .nav-link.active {
            color: #0284c7;
            border-bottom-color: #0284c7;
            background-color: #f0f9ff;
            font-weight: 700;
        }

        @media (max-width: 992px) {
            .nav-tabs-custom {
                display: flex !important;
                overflow-x: auto;
            }

            .nav-tabs-custom .nav-link {
                flex: 1 0 auto;
                padding: 14px 18px !important;
            }
        }

        .section-header-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #0369a1;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding-bottom: 8px;
            border-bottom: 1px dashed #cbd5e1;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .form-label-custom {
            font-size: 0.825rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 4px;
        }

        .form-label-custom span.req {
            color: #ef4444;
        }

        .dynamic-table th {
            font-size: 0.8rem;
            font-weight: 700;
            color: #1e293b;
            background-color: #f8fafc;
            border-bottom: 1px solid #cbd5e1;
            text-align: center;
            vertical-align: middle;
        }

        .dynamic-table td {
            font-size: 0.85rem;
            vertical-align: middle;
        }

        .invalid-feedback {
            font-size: 0.78rem;
            font-weight: 500;
        }

        /* =========================================================
           KHUNG XEM TRƯỚC VÀ TRANG IN A4 (FIX LỖI NỀN TỐI VÀ TRÀN TRANG)
           ========================================================= */
        /* =========================================================
           KHUNG XEM TRƯỚC VÀ TRANG IN A4 (KHẮC PHỤC TRIỆT ĐỂ LỖI NỀN TỐI)
           ========================================================= */
        .a4-preview-wrapper {
            background-color: #525659;
            padding: 30px 15px;
            display: block;
            /* Đổi từ flex sang block để thanh cuộn dọc cuộn tự nhiên mà không bóp méo nền con */
            overflow-y: auto;
            max-height: 85vh;
            border-radius: 8px;
        }

        /* TỜ GIẤY A4: Tự động kéo dài 100%, liền mạch nền trắng, không bị đứt đoạn */
        .a4-page-sheet {
            background-color: #ffffff !important;
            background: #ffffff !important;
            width: 100%;
            max-width: 820px;
            min-height: 1123px;
            height: auto !important;
            margin: 0 auto;
            /* Căn giữa màn hình */
            padding: 50px 60px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.45);
            font-family: "Times New Roman", Times, serif;
            font-size: 13pt;
            line-height: 1.35;
            color: #000000 !important;
            box-sizing: border-box;
            position: relative;
            display: block;
        }

        /* Tất cả các phần tử con bên trong trang giấy đều thừa kế nền trắng */
        .a4-page-sheet * {
            background-color: transparent;
        }

        /* BẢNG CHUẨN BM-04: ÉP BUỘC NỀN TRẮNG ĐỤC CHO TẤT CẢ Ô VÀ DÒNG */
        table.tbl-bm04 {
            width: 100% !important;
            border-collapse: collapse !important;
            margin-top: 6pt !important;
            margin-bottom: 12pt !important;
            background-color: #ffffff !important;
            page-break-inside: auto !important;
        }

        table.tbl-bm04 thead {
            background-color: #f2f2f2 !important;
        }

        table.tbl-bm04 th {
            border: 1px solid #000000 !important;
            padding: 5pt 6pt !important;
            font-size: 11pt !important;
            line-height: 1.25 !important;
            color: #000000 !important;
            text-align: center !important;
            font-weight: bold !important;
            background-color: #f2f2f2 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        table.tbl-bm04 td {
            border: 1px solid #000000 !important;
            padding: 5pt 6pt !important;
            font-size: 11pt !important;
            line-height: 1.25 !important;
            color: #000000 !important;
            background-color: #ffffff !important;
        }

        table.tbl-bm04 tr {
            background-color: #ffffff !important;
            page-break-inside: avoid !important;
        }

        /* =========================================================
           CSS KHI BẤM NÚT IN HOẶC XUẤT PDF TRÊN TRÌNH DUYỆT
           ========================================================= */
        @media print {
            @page {
                size: A4 portrait;
                margin: 15mm 15mm 15mm 20mm;
            }

            html,
            body {
                background: #ffffff !important;
                background-color: #ffffff !important;
                color: #000000 !important;
                font-family: "Times New Roman", Times, serif !important;
                font-size: 13pt !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                height: auto !important;
            }

            body * {
                visibility: hidden;
            }

            /* Chỉ hiển thị vùng bản in A4 */
            #printableA4Area,
            #printableA4Area *,
            #adminPrintableArea,
            #adminPrintableArea * {
                visibility: visible;
            }

            #printableA4Area,
            #adminPrintableArea {
                position: absolute !important;
                left: 0 !important;
                top: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
                min-height: auto !important;
                height: auto !important;
                padding: 0 !important;
                margin: 0 !important;
                box-shadow: none !important;
                border: none !important;
                background: #ffffff !important;
                background-color: #ffffff !important;
            }

            .sidebar,
            .top-header,
            .nav-tabs-custom,
            .btn-action-area,
            .cv-card,
            .btn,
            .toast-container {
                display: none !important;
            }

            .main-wrapper {
                margin: 0 !important;
                width: 100% !important;
                padding: 0 !important;
            }

            .a4-preview-wrapper,
            .a4-preview-modal-wrapper {
                background: #ffffff !important;
                background-color: #ffffff !important;
                padding: 0 !important;
                max-height: none !important;
                overflow: visible !important;
            }

            .sign-section {
                page-break-inside: avoid !important;
            }
        }
    </style>
</head>

<body>
    <!-- SIDEBAR R3 -->
    <?php include '../Model/r3_sidebar.php'; ?>

    <div class="main-wrapper" id="mainWrapper">
        <!-- TOPBAR -->
        <?php
        $pageTitle = "LÝ LỊCH KHOA HỌC (BM-04)";
        $pageIcon = "fa-solid fa-id-card-clip";
        include '../Model/r3_topbar.php';
        ?>

        <main class="container-fluid px-4 py-3">
            <div id="toastFloatingArea"></div>

            <!-- TAB NAVIGATION DÀN ĐỀU 100% BỀ NGANG -->
            <div class="cv-card mb-3">
                <nav class="nav nav-tabs-custom" id="cvTabsNav">
                    <button type="button" class="nav-link active" data-tab="tab1">
                        <i class="fa-solid fa-user-circle"></i> 1. Thông tin chung (Mục 1 - 7)
                    </button>
                    <button type="button" class="nav-link" data-tab="tab2">
                        <i class="fa-solid fa-graduation-cap"></i> 2. Đào tạo & Công tác (Mục 8 - 9)
                    </button>
                    <button type="button" class="nav-link" data-tab="tab3">
                        <i class="fa-solid fa-award"></i> 3. Thành tích & Giải thưởng (Mục 10 - 15)
                    </button>
                    <button type="button" class="nav-link" data-tab="tab4" onclick="syncPrintPreview()">
                        <i class="fa-solid fa-print"></i> 4. Xem trước & Xuất PDF
                    </button>
                </nav>
            </div>

            <form id="formScientificCV" onsubmit="handleCVSave(event)" novalidate>
                <input type="hidden" id="cvId" name="cv_id" value="">

                <!-- ============================================================== -->
                <!-- TAB 1: THÔNG TIN CHUNG (MỤC 1 - 7 CỦA BM-04)                   -->
                <!-- ============================================================== -->
                <div class="tab-pane-content" id="tab1Content">
                    <div class="cv-card p-4">
                        <div class="section-header-title">
                            <span><i class="fa-solid fa-address-card me-2"></i>I. Đăng Ký Chức Danh & Thông Tin Cá Nhân</span>
                            <span class="badge bg-light text-muted border fw-normal" style="font-size: 0.75rem;">Mục 1 đến Mục 7 (BM-04)</span>
                        </div>

                        <!-- KHỐI CHỌN CHỨC DANH ĐĂNG KÝ THỰC HIỆN NHIỆM VỤ -->
                        <div class="p-3 bg-light rounded-3 border mb-4">
                            <label class="form-label-custom mb-2">Đăng ký chức danh thực hiện nhiệm vụ: <span class="req">*</span></label>
                            <div class="d-flex flex-wrap gap-4">
                                <div class="form-check">
                                    <label class="form-check-label fw-semibold" for="roleCN">
                                        Đăng ký chức danh Chủ nhiệm nhiệm vụ
                                    </label>
                                    <input class="form-check-input" type="radio" name="reg_role" id="roleCN" value="chu_nhiem" checked>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label fw-semibold" for="roleTK">
                                        Đăng ký chức danh Thư ký nhiệm vụ
                                    </label>
                                    <input class="form-check-input" type="radio" name="reg_role" id="roleTK" value="thu_ky">
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label fw-semibold" for="roleKhac">
                                        Đăng ký chức danh khác trong Ban Chủ nhiệm nhiệm vụ
                                    </label>
                                    <input class="form-check-input" type="radio" name="reg_role" id="roleKhac" value="khac">
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <!-- 1. Họ và tên & Số định danh -->
                            <div class="col-md-6">
                                <label class="form-label-custom">1. Họ và tên <span class="req">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="cvFullName" name="full_name" required placeholder="Họ và tên...">
                                <div class="invalid-feedback" id="err_cvFullName">Vui lòng nhập họ và tên.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Số định danh cá nhân (CCCD/CMND) <span class="req">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="cvIdCard" name="id_card_number" required placeholder="12 chữ số CCCD hoặc 9 chữ số CMND" maxlength="12" inputmode="numeric">
                                <div class="invalid-feedback" id="err_cvIdCard">Số CCCD phải gồm đúng 12 chữ số (hoặc 9 chữ số CMND cũ).</div>
                            </div>

                            <!-- 2. Ngày sinh & Giới tính -->
                            <div class="col-md-6">
                                <label class="form-label-custom">2. Ngày/tháng/năm sinh <span class="req">*</span></label>
                                <input type="date" class="form-control form-control-sm" id="cvBirthDate" name="birth_date" required>
                                <div class="invalid-feedback" id="err_cvBirthDate">Người khai phải đủ từ 18 tuổi trở lên.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Giới tính <span class="req">*</span></label>
                                <select class="form-select form-select-sm" id="cvGender" name="gender" required>
                                    <option value="Nam">Nam</option>
                                    <option value="Nữ">Nữ</option>
                                </select>
                            </div>

                            <!-- 3. Học hàm & Học vị -->
                            <div class="col-md-3">
                                <label class="form-label-custom">3. Học hàm (nếu có)</label>
                                <select class="form-select form-select-sm" id="cvAcademicRank" name="academic_rank">
                                    <option value="">-- Không --</option>
                                    <option value="Giáo sư">Giáo sư</option>
                                    <option value="Phó Giáo sư">Phó Giáo sư</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label-custom">Năm được bổ nhiệm học hàm</label>
                                <input type="number" class="form-control form-control-sm text-center" id="cvAcademicRankYear" name="academic_rank_year" placeholder="YYYY" min="1950" max="2099">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label-custom">Học vị (nếu có) <span class="req">*</span></label>
                                <select class="form-select form-select-sm" id="cvAcademicDegree" name="academic_degree" required>
                                    <option value="Cử nhân">Cử nhân</option>
                                    <option value="Kỹ sư">Kỹ sư</option>
                                    <option value="Thạc sĩ">Thạc sĩ</option>
                                    <option value="Tiến sĩ">Tiến sĩ</option>
                                    <option value="Tiến sĩ khoa học">Tiến sĩ khoa học</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label-custom">Năm được công nhận học vị</label>
                                <input type="number" class="form-control form-control-sm text-center" id="cvAcademicDegreeYear" name="academic_degree_year" placeholder="YYYY" min="1950" max="2099">
                            </div>

                            <!-- 4. Chức danh nghề nghiệp & Phân loại nhân tài -->
                            <div class="col-md-6">
                                <label class="form-label-custom">4. Chức danh nghề nghiệp (nếu có)</label>
                                <input type="text" class="form-control form-control-sm" id="cvJobTitle" name="job_title" placeholder="VD: Giảng viên cao cấp, Nghiên cứu viên chính...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Chức vụ (nếu có)</label>
                                <input type="text" class="form-control form-control-sm" id="cvPosition" name="position" placeholder="VD: Trưởng bộ môn, Phó Trưởng phòng...">
                            </div>
                            <div class="col-12">
                                <label class="form-label-custom text-secondary mb-1">Đối tượng ưu tiên (BM-04):</label>
                                <div class="d-flex flex-wrap gap-4 p-2 bg-light rounded border">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="talent_category" id="talent1" value="1">
                                        <label class="form-check-label" for="talent1">Nhân tài về khoa học, công nghệ và đổi mới sáng tạo</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="talent_category" id="talent2" value="2">
                                        <label class="form-check-label" for="talent2">Nhà khoa học trẻ tài năng, kỹ sư trẻ tài năng</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="talent_category" id="talent3" value="3" checked>
                                        <label class="form-check-label" for="talent3">Không thuộc hai trường hợp trên</label>
                                    </div>
                                </div>
                            </div>

                            <!-- 5. Địa chỉ nhà riêng -->
                            <div class="col-12">
                                <label class="form-label-custom">5. Địa chỉ nhà riêng</label>
                                <input type="text" class="form-control form-control-sm" id="cvHomeAddress" name="home_address" placeholder="Nhập địa chỉ nơi cư trú...">
                            </div>

                            <!-- 6. Điện thoại & Email -->
                            <div class="col-md-6">
                                <label class="form-label-custom">6. Điện thoại liên hệ <span class="req">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="cvPhone" name="phone_number" required placeholder="0912345678" maxlength="11" inputmode="tel">
                                <div class="invalid-feedback" id="err_cvPhone">Số điện thoại không hợp lệ (Phải là 10 chữ số chuẩn VN).</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">E-mail liên hệ <span class="req">*</span></label>
                                <input type="email" class="form-control form-control-sm" id="cvEmail" name="email_work" required placeholder="email@annd.edu.vn">
                                <div class="invalid-feedback" id="err_cvEmail">Email không đúng định dạng chuẩn.</div>
                            </div>

                            <!-- 7. Đơn vị công tác -->
                            <div class="col-md-6">
                                <label class="form-label-custom">7. Đơn vị công tác (Tự động theo hệ thống)</label>
                                <input type="text" class="form-control form-control-sm bg-light" id="cvOrgName" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Khoa / Phòng / Đơn vị trực thuộc</label>
                                <input type="text" class="form-control form-control-sm bg-light" id="cvDepartment" readonly>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label-custom">Địa chỉ đơn vị</label>
                                <input type="text" class="form-control form-control-sm" id="cvWorkAddress" name="work_address" placeholder="Địa chỉ trụ sở cơ quan...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Thủ trưởng đơn vị</label>
                                <input type="text" class="form-control form-control-sm" id="cvOrgHeadName" name="org_head_name" placeholder="Họ và tên Thủ trưởng đơn vị...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Điện thoại liên hệ Thủ trưởng đơn vị</label>
                                <input type="text" class="form-control form-control-sm" id="cvOrgHeadPhone" name="org_head_phone" placeholder="Số điện thoại Thủ trưởng...">
                            </div>

                            <!-- Lĩnh vực nghiên cứu -->
                            <div class="col-12">
                                <label class="form-label-custom">Lĩnh vực nghiên cứu chuyên sâu</label>
                                <textarea class="form-control form-control-sm" id="cvResearchFields" name="research_fields" rows="2" placeholder="Ví dụ: Khoa học an ninh, An toàn thông tin, Trí tuệ nhân tạo, Quản lý nhà nước về ANTT..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============================================================== -->
                <!-- TAB 2: QUÁ TRÌNH ĐÀO TẠO & CÔNG TÁC (MỤC 8 & 9)                -->
                <!-- ============================================================== -->
                <div class="tab-pane-content d-none" id="tab2Content">
                    <!-- 8. Quá trình đào tạo -->
                    <div class="cv-card p-4">
                        <div class="section-header-title">
                            <span><i class="fa-solid fa-graduation-cap me-2"></i>8. Quá Trình Đào Tạo</span>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addEducationRow()">
                                <i class="fa-solid fa-plus me-1"></i> Thêm bậc đào tạo
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered dynamic-table mb-0" id="tableEducation">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">TT</th>
                                        <th>Bậc đào tạo</th>
                                        <th>Nơi đào tạo</th>
                                        <th>Chuyên môn</th>
                                        <th style="width: 150px;">Năm cấp bằng tốt nghiệp</th>
                                        <th style="width: 50px;">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <!-- 9. Quá trình công tác -->
                    <div class="cv-card p-4">
                        <div class="section-header-title">
                            <span><i class="fa-solid fa-briefcase me-2"></i>9. Quá Trình Công Tác</span>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addWorkRow()">
                                <i class="fa-solid fa-plus me-1"></i> Thêm quá trình công tác
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered dynamic-table mb-0" id="tableWork">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">TT</th>
                                        <th style="width: 170px;">Thời gian (Từ năm... đến năm...)</th>
                                        <th>Vị trí công tác</th>
                                        <th>Đơn vị công tác</th>
                                        <th>Địa chỉ đơn vị</th>
                                        <th style="width: 50px;">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ============================================================== -->
                <!-- TAB 3: THÀNH TÍCH, CÔNG TRÌNH & GIẢI THƯỞNG (MỤC 10 - 15)       -->
                <!-- ============================================================== -->
                <div class="tab-pane-content d-none" id="tab3Content">
                    <!-- 10. Các công trình khoa học đã công bố chủ yếu -->
                    <div class="cv-card p-4">
                        <div class="section-header-title">
                            <span><i class="fa-solid fa-newspaper me-2"></i>10. Các Công Trình Khoa Học Đã Công Bố Chủ Yếu (Tối đa 05 công trình)</span>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addArticleRow()">
                                <i class="fa-solid fa-plus me-1"></i> Thêm công trình
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered dynamic-table mb-0" id="tableArticles">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">TT</th>
                                        <th>Tên công trình (bài báo, công trình...)</th>
                                        <th style="width: 160px;">Là tác giả / đồng tác giả</th>
                                        <th>Nơi công bố (tên tạp chí, NXB)</th>
                                        <th style="width: 120px;">Năm công bố</th>
                                        <th style="width: 50px;">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <!-- 11. Các văn bằng bảo hộ quyền SHTT -->
                    <div class="cv-card p-4">
                        <div class="section-header-title">
                            <span><i class="fa-solid fa-certificate me-2"></i>11. Các Văn Bằng Bảo Hộ Quyền Sở Hữu Trí Tuệ (nếu có)</span>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addPatentRow()">
                                <i class="fa-solid fa-plus me-1"></i> Thêm văn bằng
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered dynamic-table mb-0" id="tablePatents">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">TT</th>
                                        <th>Tên và nội dung văn bằng</th>
                                        <th style="width: 150px;">Năm cấp văn bằng</th>
                                        <th style="width: 50px;">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <!-- 12. Số công trình được áp dụng trong thực tiễn -->
                    <div class="cv-card p-4">
                        <div class="section-header-title">
                            <span><i class="fa-solid fa-industry me-2"></i>12. Số Công Trình Được Áp Dụng Trong Thực Tiễn (nếu có)</span>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addAppliedWorkRow()">
                                <i class="fa-solid fa-plus me-1"></i> Thêm công trình áp dụng
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered dynamic-table mb-0" id="tableAppliedWorks">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">TT</th>
                                        <th>Tên công trình</th>
                                        <th>Hình thức, quy mô, địa chỉ áp dụng</th>
                                        <th style="width: 170px;">Thời gian (bắt đầu - kết thúc)</th>
                                        <th style="width: 50px;">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <!-- 13. Các nhiệm vụ KH, CN&ĐMST đã và đang chủ trì hoặc tham gia -->
                    <div class="cv-card p-4">
                        <div class="section-header-title">
                            <span><i class="fa-solid fa-folder-tree me-2"></i>13. Các Nhiệm Vụ KH, CN&ĐMST Đã & Đang Chủ Trì Hoặc Tham Gia</span>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addProjectRow()">
                                <i class="fa-solid fa-plus me-1"></i> Thêm nhiệm vụ
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered dynamic-table mb-0" id="tableProjects">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">TT</th>
                                        <th>Tên nhiệm vụ (Chủ trì / tham gia)</th>
                                        <th style="width: 170px;">Thời gian (bắt đầu - kết thúc)</th>
                                        <th>Thuộc Chương trình (nếu có)</th>
                                        <th style="width: 160px;">Tình trạng nhiệm vụ</th>
                                        <th style="width: 50px;">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <!-- 14. Giải thưởng -->
                    <div class="cv-card p-4">
                        <div class="section-header-title">
                            <span><i class="fa-solid fa-trophy me-2"></i>14. Giải Thưởng Về KH,CN&ĐMST (nếu có)</span>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addAwardRow()">
                                <i class="fa-solid fa-plus me-1"></i> Thêm giải thưởng
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered dynamic-table mb-0" id="tableAwards">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">TT</th>
                                        <th>Hình thức và nội dung giải thưởng</th>
                                        <th style="width: 150px;">Năm tặng thưởng</th>
                                        <th style="width: 50px;">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <!-- 15. Thành tựu khác -->
                    <div class="cv-card p-4">
                        <div class="section-header-title">
                            <span><i class="fa-solid fa-star me-2"></i>15. Thành Tựu Hoạt Động KH&CN Và Sản Xuất Kinh Doanh Khác (nếu có)</span>
                        </div>
                        <textarea class="form-control form-control-sm" id="cvOtherAchievements" name="other_achievements" rows="3" placeholder="Nhập các thành tựu khoa học, sản xuất kinh doanh khác..."></textarea>
                    </div>
                </div>

                <!-- ============================================================== -->
                <!-- TAB 4: XEM TRƯỚC VÀ XUẤT BM-04 CHUẨN IN VÀ FILE PDF           -->
                <!-- ============================================================== -->
                <div class="tab-pane-content d-none" id="tab4Content">
                    <div class="cv-card p-3 mb-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-bold mb-1 text-primary"><i class="fa-solid fa-file-pdf me-2"></i>Bản In & Xuất Lý Lịch Khoa Học (Mẫu BM-04)</h6>
                            <small class="text-muted">Định dạng chuẩn trang in A4 sẵn sàng ký duyệt và xuất file PDF</small>
                        </div>
                        <!-- KHỐI CHỌN ĐỀ TÀI PENDING -->
                        <div class="d-flex align-items-center gap-2 flex-grow-1 justify-content-end" style="max-width: 600px;">
                            <label for="selectPendingProject" class="fw-semibold text-nowrap small text-dark mb-0">
                                <i class="fa-solid fa-book-bookmark text-primary me-1"></i> Chọn nhiệm vụ:
                            </label>
                            <select id="selectPendingProject" class="form-select form-select-sm" style="max-width: 380px;" onchange="onSelectPendingProjectChange(this)">
                                <option value="">-- Để trống dòng chấm (..................) --</option>
                            </select>
                            <button type="button" class="btn btn-primary px-3 fw-bold shadow-sm btn-sm text-nowrap" onclick="exportBM04PDF()">
                                <i class="fa-solid fa-print me-1"></i> In / Xuất PDF (BM-04)
                            </button>
                        </div>
                    </div>

                    <div class="a4-preview-wrapper">
                        <div class="a4-page-sheet" id="printableA4Area">
                            <!-- GÓC PHẢI TRÊN CÙNG: MẪU BM-04 -->
                            <div style="text-align: right; font-weight: bold; font-size: 11pt; margin-bottom: 2pt;">
                                BM-04
                            </div>

                            <!-- DÒNG THÔNG TƯ BAN HÀNH CĂN GIỮA -->
                            <div style="text-align: center; font-style: italic; font-size: 11pt; margin-bottom: 6pt;">
                                Ban hành kèm theo Thông tư số 22/2026/TT-BCA ngày 26/02/2026 của Bộ trưởng Bộ Công an
                            </div>

                            <!-- TIÊU NGỮ QUỐC GIA CĂN GIỮA -->
                            <div style="text-align: center; margin-bottom: 14pt;">
                                <div style="font-weight: bold; font-size: 12pt;">CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div>
                                <div style="font-weight: bold; font-size: 13pt;">Độc lập - Tự do - Hạnh phúc</div>
                                <div style="font-size: 11pt; margin-top: -3px; font-weight: bold;">-----------------------</div>
                            </div>

                            <!-- TIÊU ĐỀ LÝ LỊCH KHOA HỌC -->
                            <div style="text-align: center; margin-bottom: 12pt;">
                                <div style="font-weight: bold; font-size: 15pt; margin-bottom: 4pt;">LÝ LỊCH KHOA HỌC</div>
                                <div style="font-weight: bold; font-size: 12pt; margin-bottom: 4pt;">
                                    Cá nhân đăng ký thực hiện nhiệm vụ khoa học, công nghệ và đổi mới sáng tạo
                                </div>
                                <div style="font-style: italic; font-weight: bold; font-size: 12pt;">
                                    “<span id="pvProjectNameText">....................................................................................................................</span>”
                                </div>
                            </div>

                            <!-- 3 KHỐI CHỨC DANH ĐĂNG KÝ (CĂN THẲNG CỘT BÊN PHẢI CHUẨN BM-04) -->
                            <table style="width: auto; margin: 12pt auto 16pt auto; border-collapse: collapse; border: none;">
                                <tr>
                                    <td style="border: none; padding: 3pt 15pt 3pt 0; font-size: 13pt; text-align: left; vertical-align: middle;">
                                        Đăng ký chức danh Chủ nhiệm nhiệm vụ
                                    </td>
                                    <td style="border: none; padding: 3pt 0; font-size: 14pt; text-align: center; vertical-align: middle; width: 30px;">
                                        <span id="pvRoleCN">☐</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border: none; padding: 3pt 15pt 3pt 0; font-size: 13pt; text-align: left; vertical-align: middle;">
                                        Đăng ký chức danh Thư ký nhiệm vụ
                                    </td>
                                    <td style="border: none; padding: 3pt 0; font-size: 14pt; text-align: center; vertical-align: middle;">
                                        <span id="pvRoleTK">☐</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border: none; padding: 3pt 15pt 3pt 0; font-size: 13pt; text-align: left; vertical-align: middle;">
                                        Đăng ký chức danh khác trong Ban Chủ nhiệm nhiệm vụ
                                    </td>
                                    <td style="border: none; padding: 3pt 0; font-size: 14pt; text-align: center; vertical-align: middle;">
                                        <span id="pvRoleKhac">☐</span>
                                    </td>
                                </tr>
                            </table>

                            <!-- MỤC 1 ĐẾN 7 -->
                            <div style="line-height: 1.5; font-size: 13pt;">
                                <div style="margin-bottom: 3pt;">1. Họ và tên: <strong id="pvFullName">....................................................</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Số định danh cá nhân: <span id="pvIdCard">.......................................</span></div>
                                <div style="margin-bottom: 3pt;">2. Ngày/tháng/năm sinh: <span id="pvBirthDate">....../....../............</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Giới tính: <span id="pvGender">.........................................................</span></div>
                                <div style="margin-bottom: 3pt;">3. Học hàm (nếu có): <span id="pvRank">.................................</span> &nbsp;&nbsp;&nbsp;&nbsp; Năm được bổ nhiệm học hàm: <span id="pvRankYear">............</span></div>
                                <div style="margin-bottom: 3pt; margin-left: 15pt;">Học vị (nếu có): <strong id="pvDegree">.................................</strong> &nbsp;&nbsp;&nbsp;&nbsp; Năm được công nhận học vị: <span id="pvDegreeYear">............</span></div>

                                <div style="margin-bottom: 3pt;">4. Chức danh nghề nghiệp (nếu có): <span id="pvJobTitle">..........................</span> &nbsp;&nbsp;&nbsp;&nbsp; Chức vụ (nếu có): <span id="pvPosition">............................</span></div>
                                <div style="margin-left: 20pt; margin-bottom: 2pt;">- Nhân tài về khoa học, công nghệ và đổi mới sáng tạo <span id="pvTalent1" style="float: right; margin-right: 35pt;">☐</span></div>
                                <div style="margin-left: 20pt; margin-bottom: 2pt;">- Nhà khoa học trẻ tài năng, kỹ sư trẻ tài năng <span id="pvTalent2" style="float: right; margin-right: 35pt;">☐</span></div>
                                <div style="margin-left: 20pt; margin-bottom: 4pt;">- Không thuộc hai trường hợp trên <span id="pvTalent3" style="float: right; margin-right: 35pt;">☒</span></div>

                                <div style="margin-bottom: 3pt;">5. Địa chỉ nhà riêng: <span id="pvHomeAddress">......................................................................................................................</span></div>
                                <div style="margin-bottom: 3pt;">6. Điện thoại liên hệ: <span id="pvPhone">........................................</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; E-mail: <span id="pvEmail">...............................................................</span></div>
                                <div style="margin-bottom: 3pt;">7. Đơn vị công tác (nếu có):</div>
                                <div style="margin-left: 15pt; margin-bottom: 2pt;">Tên đơn vị: <span id="pvOrg">.............................................................................................................................</span></div>
                                <div style="margin-left: 15pt; margin-bottom: 2pt;">Địa chỉ đơn vị: <span id="pvWorkAddress">.........................................................................................................................</span></div>
                                <div style="margin-left: 15pt; margin-bottom: 3pt;">Thủ trưởng đơn vị: <span id="pvOrgHeadName">....................................</span> &nbsp;&nbsp;&nbsp;&nbsp; Điện thoại liên hệ: <span id="pvOrgHeadPhone">.................................................</span></div>
                            </div>

                            <!-- 8. QUÁ TRÌNH ĐÀO TẠO -->
                            <div style="font-weight: bold; margin-top: 10pt; margin-bottom: 3pt; font-size: 13pt;">8. Quá trình đào tạo</div>
                            <table class="tbl-bm04" id="pvTableEdu">
                                <thead>
                                    <tr>
                                        <th style="width: 22%;">Bậc đào tạo</th>
                                        <th style="width: 33%;">Nơi đào tạo</th>
                                        <th style="width: 25%;">Chuyên môn</th>
                                        <th style="width: 20%;">Năm cấp bằng<br>tốt nghiệp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="4" style="text-align: center; font-style: italic;">Chưa có thông tin</td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- 9. QUÁ TRÌNH CÔNG TÁC -->
                            <div style="font-weight: bold; margin-top: 10pt; margin-bottom: 3pt; font-size: 13pt;">9. Quá trình công tác</div>
                            <table class="tbl-bm04" id="pvTableWork">
                                <thead>
                                    <tr>
                                        <th style="width: 24%;">Thời gian<br><span style="font-weight: normal; font-size: 10.5pt;">(Từ năm ... đến năm...)</span></th>
                                        <th style="width: 26%;">Vị trí công tác</th>
                                        <th style="width: 26%;">Đơn vị công tác</th>
                                        <th style="width: 24%;">Địa chỉ đơn vị</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="4" style="text-align: center; font-style: italic;">Chưa có thông tin</td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- 10. CÔNG TRÌNH CÔNG BỐ -->
                            <div style="font-weight: bold; margin-top: 10pt; margin-bottom: 3pt; font-size: 13pt;">10. Các công trình khoa học đã công bố chủ yếu (liệt kê tối đa 05 công trình tiêu biểu - nếu có)</div>
                            <table class="tbl-bm04" id="pvTableArticles">
                                <thead>
                                    <tr>
                                        <th style="width: 6%;">TT</th>
                                        <th style="width: 42%;">Tên công trình<br><span style="font-weight: normal; font-size: 10.5pt;">(bài báo, công trình...)</span></th>
                                        <th style="width: 22%;">Là tác giả hoặc<br>đồng tác giả</th>
                                        <th style="width: 20%;">Nơi công bố<br><span style="font-weight: normal; font-size: 10.5pt;">(tên tạp chí, NXB)</span></th>
                                        <th style="width: 10%;">Năm<br>công bố</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="5" style="text-align: center; font-style: italic;">Chưa có thông tin</td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- 11. VĂN BẰNG BẢO HỘ SHTT -->
                            <div style="font-weight: bold; margin-top: 10pt; margin-bottom: 3pt; font-size: 13pt;">11. Các văn bằng bảo hộ quyền sở hữu trí tuệ (liên quan đến nhiệm vụ KH, CN&ĐMST - nếu có):</div>
                            <table class="tbl-bm04" id="pvTablePatents">
                                <thead>
                                    <tr>
                                        <th style="width: 8%;">TT</th>
                                        <th style="width: 72%;">Tên và nội dung văn bằng</th>
                                        <th style="width: 20%;">Năm cấp văn bằng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="3" style="text-align: center; font-style: italic;">Chưa có thông tin</td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- 12. CÔNG TRÌNH ÁP DỤNG THỰC TIỄN -->
                            <div style="font-weight: bold; margin-top: 10pt; margin-bottom: 3pt; font-size: 13pt;">12. Số công trình được áp dụng trong thực tiễn (liên quan đến nhiệm vụ KH,CN&ĐMST đăng ký - nếu có)</div>
                            <table class="tbl-bm04" id="pvTableAppliedWorks">
                                <thead>
                                    <tr>
                                        <th style="width: 8%;">TT</th>
                                        <th style="width: 42%;">Tên công trình</th>
                                        <th style="width: 30%;">Hình thức, quy mô,<br>địa chỉ áp dụng</th>
                                        <th style="width: 20%;">Thời gian<br><span style="font-weight: normal; font-size: 10.5pt;">(bắt đầu - kết thúc)</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="4" style="text-align: center; font-style: italic;">Chưa có thông tin</td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- 13. NHIỆM VỤ KHCN -->
                            <div style="font-weight: bold; margin-top: 10pt; margin-bottom: 3pt; font-size: 13pt;">13. Các nhiệm vụ KH, CN&ĐMST đã và đang chủ trì hoặc tham gia:</div>
                            <table class="tbl-bm04" id="pvTableProjects">
                                <thead>
                                    <tr>
                                        <th style="width: 40%;">Tên nhiệm vụ<br><span style="font-weight: normal; font-size: 10.5pt;">(Chủ trì / tham gia)</span></th>
                                        <th style="width: 20%;">Thời gian<br><span style="font-weight: normal; font-size: 10.5pt;">(bắt đầu - kết thúc)</span></th>
                                        <th style="width: 20%;">Thuộc Chương trình<br><span style="font-weight: normal; font-size: 10.5pt;">(nếu có)</span></th>
                                        <th style="width: 20%;">Tình trạng nhiệm vụ<br><span style="font-weight: normal; font-size: 10.5pt;">(đã/chưa nghiệm thu)</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="4" style="text-align: center; font-style: italic;">Chưa có thông tin</td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- 14. GIẢI THƯỞNG -->
                            <div style="font-weight: bold; margin-top: 10pt; margin-bottom: 3pt; font-size: 13pt;">14. Giải thưởng (về KH,CN&ĐMST, chất lượng sản phẩm,... liên quan đến nhiệm vụ KH, CN&ĐMST - nếu có)</div>
                            <table class="tbl-bm04" id="pvTableAwards">
                                <thead>
                                    <tr>
                                        <th style="width: 8%;">TT</th>
                                        <th style="width: 72%;">Hình thức và nội dung giải thưởng</th>
                                        <th style="width: 20%;">Năm tặng thưởng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="3" style="text-align: center; font-style: italic;">Chưa có thông tin</td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- 15. THÀNH TỰU KHÁC -->
                            <div style="font-weight: bold; margin-top: 10pt; margin-bottom: 3pt; font-size: 13pt;">15. Thành tựu hoạt động khoa học và công nghệ và sản xuất kinh doanh khác (nếu có):</div>
                            <div id="pvOtherAchievements" style="font-style: italic; min-height: 20pt; margin-bottom: 10pt; line-height: 1.4;">......................................................................................................................................................................................</div>

                            <!-- 16. CAM KẾT -->
                            <div style="font-weight: bold; margin-top: 10pt; margin-bottom: 3pt; font-size: 13pt;">16. Cam kết</div>
                            <div style="line-height: 1.45; font-size: 13pt;">
                                Tôi cam kết:<br>
                                - Các thông tin trên là trung thực, chính xác;<br>
                                - Có đủ thời gian, năng lực và điều kiện để chủ trì, điều hành/tham gia thực hiện nhiệm vụ;<br>
                                - Thực hiện nhiệm vụ đúng quy định của pháp luật, chịu trách nhiệm về kết quả và sử dụng kinh phí theo hợp đồng được ký với cơ quan quản lý nhiệm vụ.
                            </div>

                            <!-- KHU VỰC CHỮ KÝ 2 BÊN -->
                            <table class="sign-section" style="width: 100%; border: none; margin-top: 25pt; border-collapse: collapse;">
                                <tr>
                                    <td style="width: 50%; text-align: center; vertical-align: top; border: none; padding: 0 10pt;">
                                        <div style="font-weight: bold; font-size: 12pt;">ĐẠI DIỆN<br>ĐƠN VỊ CÔNG TÁC XÁC NHẬN</div>
                                        <div style="font-style: italic; font-size: 10.5pt; margin-top: 4pt;">Đơn vị đồng ý và bố trí thời gian cần thiết để ........................ thực hiện nhiệm vụ khoa học, công nghệ và đổi mới sáng tạo</div>
                                        <div style="font-style: italic; font-size: 11pt;">(Ký, ghi rõ họ tên; đóng dấu)</div>
                                        <div style="height: 70pt;"></div>
                                    </td>
                                    <td style="width: 50%; text-align: center; vertical-align: top; border: none; padding: 0 10pt;">
                                        <div style="font-style: italic; font-size: 12pt;">............, ngày ....... tháng ....... năm ......</div>
                                        <div style="font-weight: bold; font-size: 12pt; margin-top: 4pt;">CÁ NHÂN<br>ĐĂNG KÝ THỰC HIỆN NHIỆM VỤ</div>
                                        <div style="font-style: italic; font-size: 11pt;">(Ký, ghi rõ họ tên)</div>
                                        <div style="height: 70pt;"></div>
                                        <div id="pvSignName" style="font-weight: bold; font-size: 12pt;">................................................</div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- NÚT LƯU THÔNG TIN Ở CUỐI TRANG -->
                <div class="d-flex justify-content-end gap-3 mt-3 mb-5 btn-action-area">
                    <button type="button" class="btn btn-outline-secondary px-4 fw-semibold" onclick="window.history.back()">
                        <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
                    </button>
                    <button type="submit" class="btn btn-primary px-5 fw-bold shadow" id="btnSaveCV">
                        <i class="fa-solid fa-floppy-disk me-2"></i> Lưu Lý Lịch Khoa Học
                    </button>
                </div>
            </form>
        </main>
    </div>

    <!-- MODAL TOPBAR -->
    <?php include '../Model/r3_modal_topbar.php'; ?>

    <script src="../asset/script/bootstrap5.js"></script>
    <script src="../asset/script/sidebar.js"></script>

    <script>
        const API_URL = '/QLNCKH/index_api.php';
        let currentSchoolName = 'Học viện An ninh nhân dân';
        let currentUnitName = 'Chưa phân công đơn vị';

        // CHUYỂN TAB TỰ DO
        document.querySelectorAll('#cvTabsNav .nav-link').forEach(btn => {
            btn.addEventListener('click', function() {
                switchTab(this.getAttribute('data-tab'));
            });
        });

        function switchTab(targetTab) {
            document.querySelectorAll('#cvTabsNav .nav-link').forEach(b => {
                b.classList.toggle('active', b.getAttribute('data-tab') === targetTab);
            });
            document.querySelectorAll('.tab-pane-content').forEach(p => p.classList.add('d-none'));
            const targetContent = document.getElementById(targetTab + 'Content');
            if (targetContent) {
                targetContent.classList.remove('d-none');
            }
        }

        function showFloatingToast(message, type = 'success') {
            const container = document.getElementById('toastFloatingArea');
            const alertId = 'toast_' + Date.now();
            const bgClass = type === 'success' ? 'bg-success' : 'bg-danger';
            const iconClass = type === 'success' ? 'fa-circle-check' : 'fa-triangle-exclamation';

            const toastHtml = `
                <div id="${alertId}" class="toast align-items-center text-white ${bgClass} border-0 shadow-lg position-fixed top-0 end-0 m-3" style="z-index: 9999;" role="alert">
                    <div class="d-flex">
                        <div class="toast-body d-flex align-items-center gap-2">
                            <i class="fa-solid ${iconClass} fs-5"></i>
                            <div>${message}</div>
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', toastHtml);
            const el = document.getElementById(alertId);
            const toastObj = new bootstrap.Toast(el, {
                delay: 3500
            });
            toastObj.show();
            el.addEventListener('hidden.bs.toast', () => el.remove());
        }

        // TỰ ĐỘNG LỌC CHỈ CHO NHẬP SỐ TRÊN CCCD & SĐT
        document.getElementById('cvIdCard').addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.classList.contains('is-invalid')) validateIdCard(this.value);
        });

        document.getElementById('cvPhone').addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9+]/g, '');
            if (this.classList.contains('is-invalid')) validatePhone(this.value);
        });

        document.getElementById('cvEmail').addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) validateEmail(this.value);
        });

        document.getElementById('cvBirthDate').addEventListener('change', function() {
            if (this.classList.contains('is-invalid')) validateAge(this.value);
        });

        // VALIDATION LOGIC
        function validateAge(dobStr) {
            const input = document.getElementById('cvBirthDate');
            if (!dobStr) {
                setFieldInvalid(input, 'Vui lòng chọn ngày tháng năm sinh.');
                return false;
            }
            const dob = new Date(dobStr);
            const today = new Date();

            let age = today.getFullYear() - dob.getFullYear();
            const monthDiff = today.getMonth() - dob.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                age--;
            }

            if (dob > today || age < 18) {
                setFieldInvalid(input, `Người khai phải đủ từ 18 tuổi trở lên (Hiện tại: ${age < 0 ? 0 : age} tuổi).`);
                return false;
            }
            setFieldValid(input);
            return true;
        }

        function validateIdCard(val) {
            const input = document.getElementById('cvIdCard');
            const clean = val.trim();
            const regex = /^(?:\d{9}|\d{12})$/;
            if (!regex.test(clean)) {
                setFieldInvalid(input, 'Số CCCD/CMND phải gồm đúng 12 chữ số (CCCD) hoặc 9 chữ số (CMND cũ).');
                return false;
            }
            setFieldValid(input);
            return true;
        }

        function validatePhone(val) {
            const input = document.getElementById('cvPhone');
            const clean = val.trim();
            const regex = /^(0|\+84)(3|5|7|8|9)[0-9]{8}$/;
            if (!regex.test(clean)) {
                setFieldInvalid(input, 'Số điện thoại không hợp lệ (Phải là 10 chữ số, ví dụ: 0912345678).');
                return false;
            }
            setFieldValid(input);
            return true;
        }

        function validateEmail(val) {
            const input = document.getElementById('cvEmail');
            const clean = val.trim();
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!regex.test(clean)) {
                setFieldInvalid(input, 'Địa chỉ email không đúng định dạng.');
                return false;
            }
            setFieldValid(input);
            return true;
        }

        function setFieldInvalid(el, msg) {
            el.classList.add('is-invalid');
            const errEl = document.getElementById('err_' + el.id);
            if (errEl) errEl.innerText = msg;
        }

        function setFieldValid(el) {
            el.classList.remove('is-invalid');
        }

        // ==========================================
        // CÁC HÀM THÊM / XÓA DÒNG BẢNG ĐỘNG
        // ==========================================
        function addEducationRow(data = {}) {
            const tbody = document.querySelector('#tableEducation tbody');
            const idx = tbody.children.length + 1;
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="text-center fw-bold text-muted">${idx}</td>
                <td><input type="text" class="form-control form-control-sm edu-degree" value="${data.degree || ''}" placeholder="Cử nhân, Thạc sĩ, Tiến sĩ..."></td>
                <td><input type="text" class="form-control form-control-sm edu-school" value="${data.school || ''}" placeholder="Tên trường / Viện..."></td>
                <td><input type="text" class="form-control form-control-sm edu-major" value="${data.major || ''}" placeholder="Chuyên môn..."></td>
                <td><input type="number" class="form-control form-control-sm edu-year text-center" value="${data.year || ''}" placeholder="YYYY" min="1950" max="2099"></td>
                <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()"><i class="fa-solid fa-trash-can"></i></button></td>
            `;
            tbody.appendChild(tr);
        }

        function addWorkRow(data = {}) {
            const tbody = document.querySelector('#tableWork tbody');
            const idx = tbody.children.length + 1;
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="text-center fw-bold text-muted">${idx}</td>
                <td><input type="text" class="form-control form-control-sm work-time" value="${data.time || ''}" placeholder="VD: 2018 - 2023"></td>
                <td><input type="text" class="form-control form-control-sm work-position" value="${data.position || ''}" placeholder="Vị trí công tác..."></td>
                <td><input type="text" class="form-control form-control-sm work-org" value="${data.org || ''}" placeholder="Đơn vị công tác..."></td>
                <td><input type="text" class="form-control form-control-sm work-address" value="${data.address || ''}" placeholder="Địa chỉ đơn vị..."></td>
                <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()"><i class="fa-solid fa-trash-can"></i></button></td>
            `;
            tbody.appendChild(tr);
        }

        function addArticleRow(data = {}) {
            const tbody = document.querySelector('#tableArticles tbody');
            const idx = tbody.children.length + 1;
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="text-center fw-bold text-muted">${idx}</td>
                <td><textarea class="form-control form-control-sm art-title" rows="1" placeholder="Tên công trình...">${data.title || ''}</textarea></td>
                <td><input type="text" class="form-control form-control-sm art-role" value="${data.role || ''}" placeholder="Tác giả chính / đồng tác giả"></td>
                <td><input type="text" class="form-control form-control-sm art-journal" value="${data.journal || ''}" placeholder="Tạp chí, NXB..."></td>
                <td><input type="number" class="form-control form-control-sm art-year text-center" value="${data.year || ''}" placeholder="YYYY" min="1950" max="2099"></td>
                <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()"><i class="fa-solid fa-trash-can"></i></button></td>
            `;
            tbody.appendChild(tr);
        }

        function addPatentRow(data = {}) {
            const tbody = document.querySelector('#tablePatents tbody');
            const idx = tbody.children.length + 1;
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="text-center fw-bold text-muted">${idx}</td>
                <td><input type="text" class="form-control form-control-sm pat-title" value="${data.title || ''}" placeholder="Tên và nội dung văn bằng bảo hộ SHTT..."></td>
                <td><input type="number" class="form-control form-control-sm pat-year text-center" value="${data.year || ''}" placeholder="YYYY" min="1950" max="2099"></td>
                <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()"><i class="fa-solid fa-trash-can"></i></button></td>
            `;
            tbody.appendChild(tr);
        }

        function addAppliedWorkRow(data = {}) {
            const tbody = document.querySelector('#tableAppliedWorks tbody');
            const idx = tbody.children.length + 1;
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="text-center fw-bold text-muted">${idx}</td>
                <td><input type="text" class="form-control form-control-sm app-title" value="${data.title || ''}" placeholder="Tên công trình..."></td>
                <td><input type="text" class="form-control form-control-sm app-scope" value="${data.scope || ''}" placeholder="Hình thức, quy mô, địa chỉ áp dụng..."></td>
                <td><input type="text" class="form-control form-control-sm app-time" value="${data.time || ''}" placeholder="Bắt đầu - kết thúc..."></td>
                <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()"><i class="fa-solid fa-trash-can"></i></button></td>
            `;
            tbody.appendChild(tr);
        }

        function addProjectRow(data = {}) {
            const tbody = document.querySelector('#tableProjects tbody');
            const idx = tbody.children.length + 1;
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="text-center fw-bold text-muted">${idx}</td>
                <td><textarea class="form-control form-control-sm proj-name" rows="1" placeholder="Tên nhiệm vụ (Chủ trì/tham gia)...">${data.name || ''}</textarea></td>
                <td><input type="text" class="form-control form-control-sm proj-time" value="${data.time || ''}" placeholder="Bắt đầu - kết thúc"></td>
                <td><input type="text" class="form-control form-control-sm proj-program" value="${data.program || ''}" placeholder="Thuộc Chương trình (nếu có)"></td>
                <td><input type="text" class="form-control form-control-sm proj-status" value="${data.status || ''}" placeholder="Đã/chưa nghiệm thu"></td>
                <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()"><i class="fa-solid fa-trash-can"></i></button></td>
            `;
            tbody.appendChild(tr);
        }

        function addAwardRow(data = {}) {
            const tbody = document.querySelector('#tableAwards tbody');
            const idx = tbody.children.length + 1;
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="text-center fw-bold text-muted">${idx}</td>
                <td><input type="text" class="form-control form-control-sm awd-title" value="${data.title || ''}" placeholder="Hình thức và nội dung giải thưởng..."></td>
                <td><input type="number" class="form-control form-control-sm awd-year text-center" value="${data.year || ''}" placeholder="YYYY" min="1950" max="2099"></td>
                <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()"><i class="fa-solid fa-trash-can"></i></button></td>
            `;
            tbody.appendChild(tr);
        }

        // ĐỒNG BỘ DỮ LIỆU SANG BẢN IN TRANG A4 (TAB 4)
        function syncPrintPreview() {
            // Đồng bộ tên nhiệm vụ đã chọn
            const selectProj = document.getElementById('selectPendingProject');
            const pvName = document.getElementById('pvProjectNameText');
            if (selectProj && pvName) {
                const chosenVal = selectProj.value.trim();
                pvName.textContent = chosenVal ? chosenVal : '....................................................................................................................';
            }
            // Checkbox vai trò
            const roleVal = document.querySelector('input[name="reg_role"]:checked')?.value || 'chu_nhiem';
            document.getElementById('pvRoleCN').innerText = roleVal === 'chu_nhiem' ? '☒' : '☐';
            document.getElementById('pvRoleTK').innerText = roleVal === 'thu_ky' ? '☒' : '☐';
            document.getElementById('pvRoleKhac').innerText = roleVal === 'khac' ? '☒' : '☐';

            // Checkbox nhân tài
            const talentVal = document.querySelector('input[name="talent_category"]:checked')?.value || '3';
            document.getElementById('pvTalent1').innerText = talentVal === '1' ? '☒' : '☐';
            document.getElementById('pvTalent2').innerText = talentVal === '2' ? '☒' : '☐';
            document.getElementById('pvTalent3').innerText = talentVal === '3' ? '☒' : '☐';

            document.getElementById('pvFullName').innerText = document.getElementById('cvFullName').value || '....................................................';
            document.getElementById('pvIdCard').innerText = document.getElementById('cvIdCard').value || '.......................................';
            document.getElementById('pvBirthDate').innerText = document.getElementById('cvBirthDate').value ? document.getElementById('cvBirthDate').value.split('-').reverse().join('/') : '....../....../............';
            document.getElementById('pvGender').innerText = document.getElementById('cvGender').value || '.........................................................';

            document.getElementById('pvRank').innerText = document.getElementById('cvAcademicRank').value || '.................................';
            document.getElementById('pvRankYear').innerText = document.getElementById('cvAcademicRankYear').value || '............';
            document.getElementById('pvDegree').innerText = document.getElementById('cvAcademicDegree').value || '.................................';
            document.getElementById('pvDegreeYear').innerText = document.getElementById('cvAcademicDegreeYear').value || '............';

            document.getElementById('pvJobTitle').innerText = document.getElementById('cvJobTitle').value || '..........................';
            document.getElementById('pvPosition').innerText = document.getElementById('cvPosition').value || '............................';
            document.getElementById('pvHomeAddress').innerText = document.getElementById('cvHomeAddress').value || '......................................................................................................................';

            document.getElementById('pvPhone').innerText = document.getElementById('cvPhone').value || '........................................';
            document.getElementById('pvEmail').innerText = document.getElementById('cvEmail').value || '...............................................................';

            document.getElementById('pvOrg').innerText = `${currentSchoolName} (${currentUnitName})`;
            document.getElementById('pvWorkAddress').innerText = document.getElementById('cvWorkAddress').value || '.........................................................................................................................';
            document.getElementById('pvOrgHeadName').innerText = document.getElementById('cvOrgHeadName').value || '....................................';
            document.getElementById('pvOrgHeadPhone').innerText = document.getElementById('cvOrgHeadPhone').value || '.................................................';

            document.getElementById('pvOtherAchievements').innerText = document.getElementById('cvOtherAchievements').value || '......................................................................................................................................................................................';
            document.getElementById('pvSignName').innerText = document.getElementById('cvFullName').value || '................................................';

            // Đồng bộ Bảng 8
            const eduRows = document.querySelectorAll('#tableEducation tbody tr');
            const pvEduTbody = document.querySelector('#pvTableEdu tbody');
            if (eduRows.length > 0) {
                pvEduTbody.innerHTML = '';
                eduRows.forEach(r => {
                    pvEduTbody.innerHTML += `
                        <tr>
                            <td>${r.querySelector('.edu-degree')?.value || ''}</td>
                            <td>${r.querySelector('.edu-school')?.value || ''}</td>
                            <td>${r.querySelector('.edu-major')?.value || ''}</td>
                            <td class="text-center">${r.querySelector('.edu-year')?.value || ''}</td>
                        </tr>
                    `;
                });
            }

            // Đồng bộ Bảng 9
            const workRows = document.querySelectorAll('#tableWork tbody tr');
            const pvWorkTbody = document.querySelector('#pvTableWork tbody');
            if (workRows.length > 0) {
                pvWorkTbody.innerHTML = '';
                workRows.forEach(r => {
                    pvWorkTbody.innerHTML += `
                        <tr>
                            <td class="text-center">${r.querySelector('.work-time')?.value || ''}</td>
                            <td>${r.querySelector('.work-position')?.value || ''}</td>
                            <td>${r.querySelector('.work-org')?.value || ''}</td>
                            <td>${r.querySelector('.work-address')?.value || ''}</td>
                        </tr>
                    `;
                });
            }

            // Đồng bộ Bảng 10
            const artRows = document.querySelectorAll('#tableArticles tbody tr');
            const pvArtTbody = document.querySelector('#pvTableArticles tbody');
            if (artRows.length > 0) {
                pvArtTbody.innerHTML = '';
                artRows.forEach((r, i) => {
                    pvArtTbody.innerHTML += `
                        <tr>
                            <td class="text-center">${i + 1}</td>
                            <td>${r.querySelector('.art-title')?.value || ''}</td>
                            <td>${r.querySelector('.art-role')?.value || ''}</td>
                            <td>${r.querySelector('.art-journal')?.value || ''}</td>
                            <td class="text-center">${r.querySelector('.art-year')?.value || ''}</td>
                        </tr>
                    `;
                });
            }

            // Đồng bộ Bảng 11
            const patRows = document.querySelectorAll('#tablePatents tbody tr');
            const pvPatTbody = document.querySelector('#pvTablePatents tbody');
            if (patRows.length > 0) {
                pvPatTbody.innerHTML = '';
                patRows.forEach((r, i) => {
                    pvPatTbody.innerHTML += `
                        <tr>
                            <td class="text-center">${i + 1}</td>
                            <td>${r.querySelector('.pat-title')?.value || ''}</td>
                            <td class="text-center">${r.querySelector('.pat-year')?.value || ''}</td>
                        </tr>
                    `;
                });
            }

            // Đồng bộ Bảng 12
            const appRows = document.querySelectorAll('#tableAppliedWorks tbody tr');
            const pvAppTbody = document.querySelector('#pvTableAppliedWorks tbody');
            if (appRows.length > 0) {
                pvAppTbody.innerHTML = '';
                appRows.forEach((r, i) => {
                    pvAppTbody.innerHTML += `
                        <tr>
                            <td class="text-center">${i + 1}</td>
                            <td>${r.querySelector('.app-title')?.value || ''}</td>
                            <td>${r.querySelector('.app-scope')?.value || ''}</td>
                            <td class="text-center">${r.querySelector('.app-time')?.value || ''}</td>
                        </tr>
                    `;
                });
            }

            // Đồng bộ Bảng 13
            const projRows = document.querySelectorAll('#tableProjects tbody tr');
            const pvProjTbody = document.querySelector('#pvTableProjects tbody');
            if (projRows.length > 0) {
                pvProjTbody.innerHTML = '';
                projRows.forEach(r => {
                    pvProjTbody.innerHTML += `
                        <tr>
                            <td>${r.querySelector('.proj-name')?.value || ''}</td>
                            <td class="text-center">${r.querySelector('.proj-time')?.value || ''}</td>
                            <td>${r.querySelector('.proj-program')?.value || ''}</td>
                            <td class="text-center">${r.querySelector('.proj-status')?.value || ''}</td>
                        </tr>
                    `;
                });
            }

            // Đồng bộ Bảng 14
            const awdRows = document.querySelectorAll('#tableAwards tbody tr');
            const pvAwdTbody = document.querySelector('#pvTableAwards tbody');
            if (awdRows.length > 0) {
                pvAwdTbody.innerHTML = '';
                awdRows.forEach((r, i) => {
                    pvAwdTbody.innerHTML += `
                        <tr>
                            <td class="text-center">${i + 1}</td>
                            <td>${r.querySelector('.awd-title')?.value || ''}</td>
                            <td class="text-center">${r.querySelector('.awd-year')?.value || ''}</td>
                        </tr>
                    `;
                });
            }
        }

        // HÀM IN / XUẤT PDF CHUẨN A4 BẰNG BỘ IN NATIVE CỦA TRÌNH DUYỆT
        function exportBM04PDF() {
            syncPrintPreview();
            setTimeout(() => {
                window.print();
            }, 150);
        }

        // TẢI DỮ LIỆU QUA REST API
        document.addEventListener("DOMContentLoaded", async function() {
            loadUserPendingProjects();
            const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
            if (!token) return;

            try {
                const res = await fetch(`${API_URL}?detect=cv_get`, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                }).then(r => r.json());

                if (res.status === 'success' && res.data) {
                    const d = res.data;
                    document.getElementById('cvId').value = d.cv_id || '';
                    document.getElementById('cvFullName').value = d.name || '';
                    document.getElementById('cvBirthDate').value = d.birth_date || '';
                    document.getElementById('cvGender').value = d.gender || 'Nam';
                    document.getElementById('cvIdCard').value = d.id_card_number || '';
                    document.getElementById('cvPhone').value = d.phone_number || '';
                    document.getElementById('cvEmail').value = d.email_work || '';

                    document.getElementById('cvAcademicRank').value = d.academic_rank || '';
                    document.getElementById('cvAcademicRankYear').value = d.academic_rank_year || '';
                    document.getElementById('cvAcademicDegree').value = d.academic_degree || 'Cử nhân';
                    document.getElementById('cvAcademicDegreeYear').value = d.academic_degree_year || '';

                    document.getElementById('cvJobTitle').value = d.job_title || '';
                    document.getElementById('cvPosition').value = d.position || '';
                    document.getElementById('cvHomeAddress').value = d.home_address || '';
                    document.getElementById('cvWorkAddress').value = d.work_address || '';
                    document.getElementById('cvOrgHeadName').value = d.org_head_name || '';
                    document.getElementById('cvOrgHeadPhone').value = d.org_head_phone || '';
                    document.getElementById('cvResearchFields').value = d.research_fields || '';
                    document.getElementById('cvOtherAchievements').value = d.other_achievements || '';

                    // Vai trò đăng ký
                    const regRole = d.reg_role || 'chu_nhiem';
                    const roleRadio = document.querySelector(`input[name="reg_role"][value="${regRole}"]`);
                    if (roleRadio) roleRadio.checked = true;

                    // Phân loại nhân tài
                    const talentVal = String(d.talent_category || '3');
                    const talentRadio = document.querySelector(`input[name="talent_category"][value="${talentVal}"]`);
                    if (talentRadio) talentRadio.checked = true;

                    // Tên trường và đơn vị
                    currentSchoolName = d.university_name || 'Học viện An ninh nhân dân';
                    document.getElementById('cvOrgName').value = currentSchoolName;
                    currentUnitName = d.unit_name || (d.unit_code ? `Đơn vị ${d.unit_code}` : 'Chưa phân công đơn vị');
                    document.getElementById('cvDepartment').value = currentUnitName;

                    // Parse Bảng 8
                    if (d.education_history) {
                        try {
                            const edus = JSON.parse(d.education_history);
                            if (edus.length > 0) edus.forEach(e => addEducationRow(e));
                            else addEducationRow();
                        } catch (e) {
                            addEducationRow();
                        }
                    } else {
                        addEducationRow();
                    }

                    // Parse Bảng 9
                    if (d.work_history) {
                        try {
                            const works = JSON.parse(d.work_history);
                            if (works.length > 0) works.forEach(w => addWorkRow(w));
                            else addWorkRow();
                        } catch (e) {
                            addWorkRow();
                        }
                    } else {
                        addWorkRow();
                    }

                    // Parse Bảng 10
                    if (d.published_articles) {
                        try {
                            const arts = JSON.parse(d.published_articles);
                            if (arts.length > 0) arts.forEach(a => addArticleRow(a));
                            else addArticleRow();
                        } catch (e) {
                            addArticleRow();
                        }
                    } else {
                        addArticleRow();
                    }

                    // Parse Bảng 11
                    if (d.patents) {
                        try {
                            const pats = JSON.parse(d.patents);
                            if (pats.length > 0) pats.forEach(p => addPatentRow(p));
                            else addPatentRow();
                        } catch (e) {
                            addPatentRow();
                        }
                    } else {
                        addPatentRow();
                    }

                    // Parse Bảng 12
                    if (d.applied_works) {
                        try {
                            const apps = JSON.parse(d.applied_works);
                            if (apps.length > 0) apps.forEach(a => addAppliedWorkRow(a));
                            else addAppliedWorkRow();
                        } catch (e) {
                            addAppliedWorkRow();
                        }
                    } else {
                        addAppliedWorkRow();
                    }

                    // Parse Bảng 13
                    if (d.projects_participated) {
                        try {
                            const projs = JSON.parse(d.projects_participated);
                            if (projs.length > 0) projs.forEach(p => addProjectRow(p));
                            else addProjectRow();
                        } catch (e) {
                            addProjectRow();
                        }
                    } else {
                        addProjectRow();
                    }

                    // Parse Bảng 14
                    if (d.awards) {
                        try {
                            const awds = JSON.parse(d.awards);
                            if (awds.length > 0) awds.forEach(a => addAwardRow(a));
                            else addAwardRow();
                        } catch (e) {
                            addAwardRow();
                        }
                    } else {
                        addAwardRow();
                    }

                } else {
                    addEducationRow();
                    addWorkRow();
                    addArticleRow();
                    addPatentRow();
                    addAppliedWorkRow();
                    addProjectRow();
                    addAwardRow();
                }
            } catch (err) {
                console.error("Lỗi nạp lý lịch khoa học:", err);
            }
        });

        // XỬ LÝ LƯU CV VỚI VALIDATION
        async function handleCVSave(event) {
            event.preventDefault();
            const submitBtn = document.getElementById('btnSaveCV');
            const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');

            // 1. Họ tên
            const fullNameEl = document.getElementById('cvFullName');
            if (!fullNameEl.value.trim()) {
                setFieldInvalid(fullNameEl, 'Vui lòng nhập họ và tên.');
                switchTab('tab1');
                fullNameEl.focus();
                return;
            } else {
                setFieldValid(fullNameEl);
            }

            // 2. Tuổi
            const birthDateEl = document.getElementById('cvBirthDate');
            if (!validateAge(birthDateEl.value)) {
                switchTab('tab1');
                birthDateEl.focus();
                showFloatingToast("Vui lòng kiểm tra lại ngày sinh (Phải từ đủ 18 tuổi trở lên).", "danger");
                return;
            }

            // 3. CCCD
            const idCardEl = document.getElementById('cvIdCard');
            if (!validateIdCard(idCardEl.value)) {
                switchTab('tab1');
                idCardEl.focus();
                showFloatingToast("Số CCCD/CMND không hợp lệ.", "danger");
                return;
            }

            // 4. SĐT
            const phoneEl = document.getElementById('cvPhone');
            if (!validatePhone(phoneEl.value)) {
                switchTab('tab1');
                phoneEl.focus();
                showFloatingToast("Số điện thoại không hợp lệ.", "danger");
                return;
            }

            // 5. Email
            const emailEl = document.getElementById('cvEmail');
            if (!validateEmail(emailEl.value)) {
                switchTab('tab1');
                emailEl.focus();
                showFloatingToast("Địa chỉ email không hợp lệ.", "danger");
                return;
            }

            // Gom dữ liệu Bảng 8
            const eduList = [];
            document.querySelectorAll('#tableEducation tbody tr').forEach(r => {
                const degree = r.querySelector('.edu-degree')?.value.trim();
                const school = r.querySelector('.edu-school')?.value.trim();
                const major = r.querySelector('.edu-major')?.value.trim();
                const year = r.querySelector('.edu-year')?.value.trim();
                if (degree || school) eduList.push({
                    degree,
                    school,
                    major,
                    year
                });
            });

            // Gom dữ liệu Bảng 9
            const workList = [];
            document.querySelectorAll('#tableWork tbody tr').forEach(r => {
                const time = r.querySelector('.work-time')?.value.trim();
                const position = r.querySelector('.work-position')?.value.trim();
                const org = r.querySelector('.work-org')?.value.trim();
                const address = r.querySelector('.work-address')?.value.trim();
                if (org || position) workList.push({
                    time,
                    position,
                    org,
                    address
                });
            });

            // Gom Bảng 10 (tối đa 5 bài báo)
            const artList = [];
            document.querySelectorAll('#tableArticles tbody tr').forEach((r, idx) => {
                if (idx < 5) {
                    const title = r.querySelector('.art-title')?.value.trim();
                    const role = r.querySelector('.art-role')?.value.trim();
                    const journal = r.querySelector('.art-journal')?.value.trim();
                    const year = r.querySelector('.art-year')?.value.trim();
                    if (title) artList.push({
                        title,
                        role,
                        journal,
                        year
                    });
                }
            });

            // Gom Bảng 11 (Văn bằng SHTT)
            const patentList = [];
            document.querySelectorAll('#tablePatents tbody tr').forEach(r => {
                const title = r.querySelector('.pat-title')?.value.trim();
                const year = r.querySelector('.pat-year')?.value.trim();
                if (title) patentList.push({
                    title,
                    year
                });
            });

            // Gom Bảng 12 (Công trình áp dụng)
            const appliedList = [];
            document.querySelectorAll('#tableAppliedWorks tbody tr').forEach(r => {
                const title = r.querySelector('.app-title')?.value.trim();
                const scope = r.querySelector('.app-scope')?.value.trim();
                const time = r.querySelector('.app-time')?.value.trim();
                if (title) appliedList.push({
                    title,
                    scope,
                    time
                });
            });

            // Gom Bảng 13 (Nhiệm vụ KHCN)
            const projList = [];
            document.querySelectorAll('#tableProjects tbody tr').forEach(r => {
                const name = r.querySelector('.proj-name')?.value.trim();
                const time = r.querySelector('.proj-time')?.value.trim();
                const program = r.querySelector('.proj-program')?.value.trim();
                const status = r.querySelector('.proj-status')?.value.trim();
                if (name) projList.push({
                    name,
                    time,
                    program,
                    status
                });
            });

            // Gom Bảng 14 (Giải thưởng)
            const awardList = [];
            document.querySelectorAll('#tableAwards tbody tr').forEach(r => {
                const title = r.querySelector('.awd-title')?.value.trim();
                const year = r.querySelector('.awd-year')?.value.trim();
                if (title) awardList.push({
                    title,
                    year
                });
            });

            const payload = {
                cv_id: document.getElementById('cvId').value,
                reg_role: document.querySelector('input[name="reg_role"]:checked')?.value || 'chu_nhiem',
                full_name: fullNameEl.value.trim(),
                id_card_number: idCardEl.value.trim(),
                birth_date: birthDateEl.value,
                gender: document.getElementById('cvGender').value,
                academic_rank: document.getElementById('cvAcademicRank').value,
                academic_rank_year: document.getElementById('cvAcademicRankYear').value.trim(),
                academic_degree: document.getElementById('cvAcademicDegree').value,
                academic_degree_year: document.getElementById('cvAcademicDegreeYear').value.trim(),
                job_title: document.getElementById('cvJobTitle').value.trim(),
                position: document.getElementById('cvPosition').value.trim(),
                talent_category: document.querySelector('input[name="talent_category"]:checked')?.value || '3',
                home_address: document.getElementById('cvHomeAddress').value.trim(),
                phone_number: phoneEl.value.trim(),
                email_work: emailEl.value.trim(),
                work_address: document.getElementById('cvWorkAddress').value.trim(),
                org_head_name: document.getElementById('cvOrgHeadName').value.trim(),
                org_head_phone: document.getElementById('cvOrgHeadPhone').value.trim(),
                research_fields: document.getElementById('cvResearchFields').value.trim(),
                education_history: JSON.stringify(eduList),
                work_history: JSON.stringify(workList),
                published_articles: JSON.stringify(artList),
                patents: JSON.stringify(patentList),
                applied_works: JSON.stringify(appliedList),
                projects_participated: JSON.stringify(projList),
                awards: JSON.stringify(awardList),
                other_achievements: document.getElementById('cvOtherAchievements').value.trim()
            };

            submitBtn.disabled = true;
            submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> Đang lưu hồ sơ...`;

            try {
                const res = await fetch(`${API_URL}?detect=cv_save`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });
                const result = await res.json();

                if (res.ok && result.status === 'success') {
                    showFloatingToast(result.message || "Đã lưu lý lịch khoa học (BM-04) thành công!", "success");
                    if (result.cv_id) document.getElementById('cvId').value = result.cv_id;
                } else {
                    showFloatingToast("Lỗi: " + (result.message || "Không thể lưu dữ liệu!"), "danger");
                }
            } catch (err) {
                console.error("Lỗi lưu CV:", err);
                showFloatingToast("Lỗi kết nối máy chủ!", "danger");
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = `<i class="fa-solid fa-floppy-disk me-2"></i> Lưu Lý Lịch Khoa Học`;
            }
        }
        async function loadUserPendingProjects() {
            const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
            const userId = sessionStorage.getItem('user_id') || '';
            const selectEl = document.getElementById('selectPendingProject');
            if (!selectEl) return;

            try {
                // Gọi thẳng api project_get đã có sẵn trong hệ thống
                const url = `${API_URL}?detect=project_get${userId ? `&user_id=${userId}` : ''}`;
                const res = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                }).then(r => r.json());

                if (res.status === 'success' && Array.isArray(res.data)) {
                    // Lọc các đề tài đang ở trạng thái pending hoặc cho_duyet
                    const pendingProjects = res.data.filter(item => {
                        const st = (item.status || '').toLowerCase().trim();
                        return st === 'pending' || st === 'cho_duyet' || st === 'cho-duyet' || st === '';
                    });

                    selectEl.innerHTML = '<option value="">-- Để trống dòng chấm (..................) --</option>';

                    if (pendingProjects.length === 0) {
                        const optNone = document.createElement('option');
                        optNone.disabled = true;
                        optNone.textContent = '(Không có nhiệm vụ nào đang chờ duyệt)';
                        selectEl.appendChild(optNone);
                    } else {
                        pendingProjects.forEach(item => {
                            const opt = document.createElement('option');
                            opt.value = item.project_name;
                            opt.textContent = `[${item.project_code || 'Chưa có mã'}] ${item.project_name}`;
                            selectEl.appendChild(opt);
                        });
                    }
                } else {
                    console.warn("Không lấy được danh sách đề tài từ project_get:", res);
                }
            } catch (err) {
                console.error("Lỗi kết nối API project_get:", err);
            }
        }
        // Xử lý khi user chọn đề tài từ dropdown
        function onSelectPendingProjectChange(select) {
            const pvName = document.getElementById('pvProjectNameText');
            if (!pvName) return;
            const val = select.value.trim();
            if (val) {
                pvName.textContent = val;
            } else {
                pvName.textContent = '....................................................................................................................';
            }
        }
    </script>
</body>

</html>
