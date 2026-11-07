<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <base href="/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lý Lịch Khoa Học - Giảng Viên</title>
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

        .a4-preview-wrapper {
            background-color: #525659;
            padding: 30px 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow-y: auto;
            max-height: 85vh;
            border-radius: 8px;
        }

        .a4-page-sheet {
            background: #ffffff;
            width: 100%;
            max-width: 820px;
            min-height: 1100px;
            padding: 60px 70px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.35);
            font-family: "Times New Roman", Times, serif;
            font-size: 13.5pt;
            line-height: 1.45;
            color: #000000;
            box-sizing: border-box;
        }

        .a4-page-sheet table {
            width: 100% !important;
            border-collapse: collapse !important;
            margin: 10pt 0 !important;
        }

        .a4-page-sheet th,
        .a4-page-sheet td {
            border: 1px solid #000000 !important;
            padding: 6px 8px !important;
            font-size: 12pt !important;
        }

        .a4-page-sheet th {
            text-align: center;
            font-weight: bold;
        }

        .avatar-upload-box {
            width: 130px;
            height: 165px;
            border: 2px dashed #cbd5e1;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: #f8fafc;
            position: relative;
        }

        .avatar-upload-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .invalid-feedback {
            font-size: 0.78rem;
            font-weight: 500;
        }

        @media print {
            body * {
                visibility: hidden;
            }
            #printableA4Area, #printableA4Area * {
                visibility: visible;
            }
            #printableA4Area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 0;
                box-shadow: none;
            }
            .sidebar, .top-header, .nav-tabs-custom, .btn-action-area {
                display: none !important;
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
        $pageTitle = "LÝ LỊCH KHOA HỌC CHUYÊN GIA";
        $pageIcon = "fa-solid fa-id-card-clip";
        include '../Model/r3_topbar.php';
        ?>

        <main class="container-fluid px-4 py-3">
            <div id="toastFloatingArea"></div>

            <!-- TAB NAVIGATION DÀN ĐỀU 100% BỀ NGANG -->
            <div class="cv-card mb-3">
                <nav class="nav nav-tabs-custom" id="cvTabsNav">
                    <button type="button" class="nav-link active" data-tab="tab1">
                        <i class="fa-solid fa-user-circle"></i> 1. Thông tin cá nhân
                    </button>
                    <button type="button" class="nav-link" data-tab="tab2">
                        <i class="fa-solid fa-graduation-cap"></i> 2. Quá trình đào tạo & công tác
                    </button>
                    <button type="button" class="nav-link" data-tab="tab3">
                        <i class="fa-solid fa-award"></i> 3. Kinh nghiệm & thành tích nghiên cứu
                    </button>
                    <button type="button" class="nav-link" data-tab="tab4" onclick="syncPrintPreview()">
                        <i class="fa-solid fa-print"></i> 4. Xem và in (BM-06)
                    </button>
                </nav>
            </div>

            <form id="formScientificCV" onsubmit="handleCVSave(event)" novalidate>
                <input type="hidden" id="cvId" name="cv_id" value="">

                <!-- TAB 1: THÔNG TIN CÁ NHÂN -->
                <div class="tab-pane-content" id="tab1Content">
                    <div class="cv-card p-4">
                        <div class="section-header-title">
                            <span><i class="fa-solid fa-address-card me-2"></i>I. Thông Tin Cá Nhân</span>
                            <span class="badge bg-light text-muted border fw-normal" style="font-size: 0.75rem;">Thông tin định danh</span>
                        </div>

                        <div class="row g-4">
                            <!-- Ảnh đại diện 3x4 -->
                            <div class="col-md-auto text-center">
                                <div class="avatar-upload-box mb-2" id="avatarPreviewContainer">
                                    <i class="fa-solid fa-user fa-3x text-secondary" id="defaultAvatarIcon"></i>
                                    <img src="" id="imgUserAvatar" class="d-none" alt="Ảnh 3x4">
                                </div>
                                <label class="btn btn-outline-secondary btn-sm" style="font-size: 0.75rem; cursor: pointer;">
                                    <i class="fa-solid fa-camera me-1"></i> Tải ảnh 3x4
                                    <input type="file" id="inputAvatar" accept="image/*" class="d-none" onchange="previewAvatar(this)">
                                </label>
                            </div>

                            <!-- Form thông tin -->
                            <div class="col-md">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Họ và tên <span class="req">*</span></label>
                                        <input type="text" class="form-control form-control-sm" id="cvFullName" name="full_name" required placeholder="Họ và tên...">
                                        <div class="invalid-feedback" id="err_cvFullName">Vui lòng nhập họ và tên.</div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label-custom">Ngày sinh <span class="req">*</span></label>
                                        <input type="date" class="form-control form-control-sm" id="cvBirthDate" name="birth_date" required>
                                        <div class="invalid-feedback" id="err_cvBirthDate">Người khai phải đủ từ 18 tuổi trở lên.</div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label-custom">Giới tính <span class="req">*</span></label>
                                        <select class="form-select form-select-sm" id="cvGender" name="gender" required>
                                            <option value="Nam">Nam</option>
                                            <option value="Nữ">Nữ</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label-custom">Số CCCD/CMND <span class="req">*</span></label>
                                        <input type="text" class="form-control form-control-sm" id="cvIdCard" name="id_card_number" required placeholder="12 số (CCCD) hoặc 9 số (CMND)" maxlength="12" inputmode="numeric">
                                        <div class="invalid-feedback" id="err_cvIdCard">Bắt buộc đúng 12 chữ số (CCCD) hoặc 9 chữ số (CMND).</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label-custom">Số điện thoại liên hệ <span class="req">*</span></label>
                                        <input type="text" class="form-control form-control-sm" id="cvPhone" name="phone_number" required placeholder="10 số, bắt đầu bằng 03, 05, 07, 08, 09" maxlength="11" inputmode="tel">
                                        <div class="invalid-feedback" id="err_cvPhone">Số điện thoại không hợp lệ (Phải gồm 10 chữ số chuẩn VN).</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label-custom">Email công vụ / Email chính <span class="req">*</span></label>
                                        <input type="email" class="form-control form-control-sm" id="cvEmail" name="email_work" required placeholder="email@annd.edu.vn">
                                        <div class="invalid-feedback" id="err_cvEmail">Email không đúng định dạng chuẩn (vd: name@domain.com).</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label-custom">Học hàm</label>
                                        <select class="form-select form-select-sm" id="cvAcademicRank" name="academic_rank">
                                            <option value="">-- Không --</option>
                                            <option value="Giáo sư">Giáo sư</option>
                                            <option value="Phó Giáo sư">Phó Giáo sư</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Học vị cao nhất <span class="req">*</span></label>
                                        <select class="form-select form-select-sm" id="cvAcademicDegree" name="academic_degree" required>
                                            <option value="Cử nhân / Kỹ sư">Cử nhân / Kỹ sư</option>
                                            <option value="Thạc sĩ">Thạc sĩ</option>
                                            <option value="Tiến sĩ">Tiến sĩ</option>
                                            <option value="Tiến sĩ khoa học">Tiến sĩ khoa học</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label-custom">Cơ quan / Đơn vị công tác</label>
                                        <input type="text" class="form-control form-control-sm bg-light" id="cvOrgName" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Khoa / Phòng / Đơn vị trực thuộc</label>
                                        <input type="text" class="form-control form-control-sm bg-light" id="cvDepartment" readonly>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label-custom">Lĩnh vực nghiên cứu chuyên sâu</label>
                                        <textarea class="form-control form-control-sm" id="cvResearchFields" name="research_fields" rows="2" placeholder="Ví dụ: Khoa học an ninh, An toàn mạng, Trí tuệ nhân tạo, Quản lý nhà nước..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 2: QUÁ TRÌNH ĐÀO TẠO & CÔNG TÁC -->
                <div class="tab-pane-content d-none" id="tab2Content">
                    <div class="cv-card p-4">
                        <div class="section-header-title">
                            <span><i class="fa-solid fa-graduation-cap me-2"></i>II. Quá Trình Đào Tạo</span>
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
                                        <th>Nơi đào tạo (Trường/Viện)</th>
                                        <th>Chuyên ngành</th>
                                        <th style="width: 120px;">Năm tốt nghiệp</th>
                                        <th style="width: 50px;">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="cv-card p-4">
                        <div class="section-header-title">
                            <span><i class="fa-solid fa-briefcase me-2"></i>III. Quá Trình Công Tác & Giảng Dạy</span>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addWorkRow()">
                                <i class="fa-solid fa-plus me-1"></i> Thêm quá trình
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered dynamic-table mb-0" id="tableWork">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">TT</th>
                                        <th style="width: 160px;">Thời gian (Từ - Đến)</th>
                                        <th>Đơn vị công tác</th>
                                        <th>Chức danh / Vị trí đảm nhiệm</th>
                                        <th style="width: 50px;">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- TAB 3: KINH NGHIỆM & THÀNH TÍCH NGHIÊN CỨU -->
                <div class="tab-pane-content d-none" id="tab3Content">
                    <div class="cv-card p-4">
                        <div class="section-header-title">
                            <span><i class="fa-solid fa-newspaper me-2"></i>1. Các Công Trình Khoa Học Đã Công Bố (Bài báo, Giáo trình, Sách)</span>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addArticleRow()">
                                <i class="fa-solid fa-plus me-1"></i> Thêm công trình
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered dynamic-table mb-0" id="tableArticles">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">TT</th>
                                        <th>Tên bài báo / công trình / giáo trình</th>
                                        <th style="width: 130px;">Năm công bố</th>
                                        <th>Tên tạp chí / Nhà xuất bản</th>
                                        <th style="width: 150px;">Chỉ số ISSN/ISBN</th>
                                        <th style="width: 50px;">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="cv-card p-4">
                        <div class="section-header-title">
                            <span><i class="fa-solid fa-folder-tree me-2"></i>2. Các Đề Tài / Nhiệm Vụ KH&CN Đã & Đang Chủ Nhiệm / Tham Gia</span>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addProjectRow()">
                                <i class="fa-solid fa-plus me-1"></i> Thêm đề tài
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered dynamic-table mb-0" id="tableProjects">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">TT</th>
                                        <th>Tên đề tài / nhiệm vụ KHCN</th>
                                        <th style="width: 140px;">Mã số & Cấp quản lý</th>
                                        <th style="width: 150px;">Thời gian thực hiện</th>
                                        <th style="width: 150px;">Vai trò tham gia</th>
                                        <th style="width: 120px;">Kết quả</th>
                                        <th style="width: 50px;">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- TAB 4: XEM TRƯỚC VÀ IN (BM-06) -->
                <div class="tab-pane-content d-none" id="tab4Content">
                    <div class="cv-card p-3 mb-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-bold mb-1 text-primary"><i class="fa-solid fa-file-invoice me-2"></i>Bản In Lý Lịch Khoa Học (Mẫu BM-06)</h6>
                            <small class="text-muted">Định dạng chuẩn trang in A4 sẵn sàng ký duyệt và xuất file PDF</small>
                        </div>
                        <button type="button" class="btn btn-primary px-4 fw-bold shadow-sm" onclick="window.print()">
                            <i class="fa-solid fa-print me-2"></i> In / Xuất PDF (A4)
                        </button>
                    </div>

                    <div class="a4-preview-wrapper">
                        <div class="a4-page-sheet" id="printableA4Area">
                            <div class="text-end fw-bold" style="font-size: 11pt;">Mẫu BM-06</div>
                            <div class="text-center my-4">
                                <h4 class="fw-bold mb-1" style="font-size: 16pt;">LÝ LỊCH KHOA HỌC</h4>
                                <div class="fst-italic" style="font-size: 11pt;">(Ban hành kèm theo quy định quản lý nhiệm vụ KHCN&ĐMST)</div>
                            </div>

                            <h5 class="fw-bold mt-4 mb-2" style="font-size: 13.5pt;">I. THÔNG TIN CHUNG</h5>
                            <div>1. Họ và tên: <strong id="pvFullName">---</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Giới tính: <span id="pvGender">---</span></div>
                            <div>2. Ngày, tháng, năm sinh: <span id="pvBirthDate">---</span></div>
                            <div>3. Số CCCD: <span id="pvIdCard">---</span></div>
                            <div>4. Học hàm: <span id="pvRank">---</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Học vị cao nhất: <strong id="pvDegree">---</strong></div>
                            <div>5. Đơn vị công tác: <span id="pvOrg">---</span></div>
                            <div>6. Khoa/Phòng/Bộ môn: <span id="pvDept">---</span></div>
                            <div>7. Điện thoại: <span id="pvPhone">---</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Email: <span id="pvEmail">---</span></div>
                            <div>8. Lĩnh vực nghiên cứu chuyên sâu: <span id="pvResearchFields">---</span></div>

                            <h5 class="fw-bold mt-4 mb-2" style="font-size: 13.5pt;">II. QUÁ TRÌNH ĐÀO TẠO</h5>
                            <table id="pvTableEdu">
                                <thead>
                                    <tr>
                                        <th style="width: 40px;">TT</th>
                                        <th>Bậc đào tạo</th>
                                        <th>Cơ sở đào tạo</th>
                                        <th>Chuyên ngành</th>
                                        <th style="width: 100px;">Năm tốt nghiệp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td colspan="5" class="text-center text-muted fst-italic">Chưa có thông tin</td></tr>
                                </tbody>
                            </table>

                            <h5 class="fw-bold mt-4 mb-2" style="font-size: 13.5pt;">III. CÁC CÔNG TRÌNH KHOA HỌC ĐÃ CÔNG BỐ</h5>
                            <table id="pvTableArticles">
                                <thead>
                                    <tr>
                                        <th style="width: 40px;">TT</th>
                                        <th>Tên công trình / bài báo</th>
                                        <th style="width: 80px;">Năm</th>
                                        <th>Tạp chí / NXB</th>
                                        <th style="width: 110px;">ISSN/ISBN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td colspan="5" class="text-center text-muted fst-italic">Chưa có thông tin</td></tr>
                                </tbody>
                            </table>

                            <h5 class="fw-bold mt-4 mb-2" style="font-size: 13.5pt;">IV. ĐỀ TÀI / NHIỆM VỤ ĐÃ THAM GIA</h5>
                            <table id="pvTableProjects">
                                <thead>
                                    <tr>
                                        <th style="width: 40px;">TT</th>
                                        <th>Tên đề tài / nhiệm vụ</th>
                                        <th style="width: 120px;">Cấp quản lý</th>
                                        <th style="width: 110px;">Thời gian</th>
                                        <th style="width: 110px;">Vai trò</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td colspan="5" class="text-center text-muted fst-italic">Chưa có thông tin</td></tr>
                                </tbody>
                            </table>

                            <div class="row mt-5">
                                <div class="col-6 text-center">
                                    <div class="fw-bold">XÁC NHẬN CỦA ĐƠN VỊ CHỦ TRÌ</div>
                                    <div class="fst-italic" style="font-size: 10.5pt;">(Ký, ghi rõ họ tên và đóng dấu)</div>
                                </div>
                                <div class="col-6 text-center">
                                    <div class="fst-italic">Ngày ..... tháng ..... năm 2026</div>
                                    <div class="fw-bold">NGƯỜI KHAI LÝ LỊCH</div>
                                    <div class="fst-italic" style="font-size: 10.5pt;">(Ký và ghi rõ họ tên)</div>
                                    <div style="height: 70px;"></div>
                                    <div class="fw-bold" id="pvSignName">---</div>
                                </div>
                            </div>
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
        const API_URL = '../index_api.php';
        let currentSchoolName = 'Học viện An ninh nhân dân';
        let currentUnitName = 'Chưa phân công đơn vị';

        // XỬ LÝ CHUYỂN TAB TỰ DO
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
            const toastObj = new bootstrap.Toast(el, { delay: 3500 });
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

        // ==========================================
        // CÁC HÀM VALIDATE LOGIC
        // ==========================================
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
            if (age > 100) {
                setFieldInvalid(input, 'Năm sinh không hợp lệ.');
                return false;
            }
            setFieldValid(input);
            return true;
        }

        function validateIdCard(val) {
            const input = document.getElementById('cvIdCard');
            const clean = val.trim();
            // Chuẩn 9 số (CMND) hoặc 12 số (CCCD/Căn cước)
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
            // Đầu số Việt Nam 10 chữ số bắt đầu bằng 03, 05, 07, 08, 09 hoặc +84
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
                setFieldInvalid(input, 'Địa chỉ email không đúng định dạng (vd: giangvien@annd.edu.vn).');
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

        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('imgUserAvatar');
                    const defIcon = document.getElementById('defaultAvatarIcon');
                    img.src = e.target.result;
                    img.classList.remove('d-none');
                    defIcon.classList.add('d-none');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function addEducationRow(data = {}) {
            const tbody = document.querySelector('#tableEducation tbody');
            const idx = tbody.children.length + 1;
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="text-center fw-bold text-muted">${idx}</td>
                <td><input type="text" class="form-control form-control-sm edu-degree" value="${data.degree || ''}" placeholder="Cử nhân, Thạc sĩ, Tiến sĩ..."></td>
                <td><input type="text" class="form-control form-control-sm edu-school" value="${data.school || ''}" placeholder="Tên trường / Viện..."></td>
                <td><input type="text" class="form-control form-control-sm edu-major" value="${data.major || ''}" placeholder="Chuyên ngành đào tạo..."></td>
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
                <td><input type="text" class="form-control form-control-sm work-org" value="${data.org || ''}" placeholder="Cơ quan, đơn vị công tác..."></td>
                <td><input type="text" class="form-control form-control-sm work-position" value="${data.position || ''}" placeholder="Giảng viên, Trưởng bộ môn..."></td>
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
                <td><textarea class="form-control form-control-sm art-title" rows="1" placeholder="Tên bài báo / công trình...">${data.title || ''}</textarea></td>
                <td><input type="number" class="form-control form-control-sm art-year text-center" value="${data.year || ''}" placeholder="YYYY" min="1950" max="2099"></td>
                <td><input type="text" class="form-control form-control-sm art-journal" value="${data.journal || ''}" placeholder="Tạp chí / NXB..."></td>
                <td><input type="text" class="form-control form-control-sm art-issn" value="${data.issn || ''}" placeholder="ISSN/ISBN..."></td>
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
                <td><textarea class="form-control form-control-sm proj-name" rows="1" placeholder="Tên đề tài...">${data.name || ''}</textarea></td>
                <td><input type="text" class="form-control form-control-sm proj-code" value="${data.code || ''}" placeholder="Mã số / Cấp..."></td>
                <td><input type="text" class="form-control form-control-sm proj-duration" value="${data.duration || ''}" placeholder="VD: 12 tháng (2025)"></td>
                <td><input type="text" class="form-control form-control-sm proj-role" value="${data.role || ''}" placeholder="Chủ nhiệm / Tham gia"></td>
                <td><input type="text" class="form-control form-control-sm proj-result" value="${data.result || ''}" placeholder="Đạt / Xuất sắc..."></td>
                <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()"><i class="fa-solid fa-trash-can"></i></button></td>
            `;
            tbody.appendChild(tr);
        }

        function syncPrintPreview() {
            document.getElementById('pvFullName').innerText = document.getElementById('cvFullName').value || '---';
            document.getElementById('pvGender').innerText = document.getElementById('cvGender').value || '---';
            document.getElementById('pvBirthDate').innerText = document.getElementById('cvBirthDate').value ? document.getElementById('cvBirthDate').value.split('-').reverse().join('/') : '---';
            document.getElementById('pvIdCard').innerText = document.getElementById('cvIdCard').value || '---';
            document.getElementById('pvRank').innerText = document.getElementById('cvAcademicRank').value || 'Không';
            document.getElementById('pvDegree').innerText = document.getElementById('cvAcademicDegree').value || '---';
            document.getElementById('pvOrg').innerText = currentSchoolName;
            document.getElementById('pvDept').innerText = currentUnitName;
            document.getElementById('pvPhone').innerText = document.getElementById('cvPhone').value || '---';
            document.getElementById('pvEmail').innerText = document.getElementById('cvEmail').value || '---';
            document.getElementById('pvResearchFields').innerText = document.getElementById('cvResearchFields').value || '---';
            document.getElementById('pvSignName').innerText = document.getElementById('cvFullName').value || '---';

            const eduRows = document.querySelectorAll('#tableEducation tbody tr');
            const pvEduTbody = document.querySelector('#pvTableEdu tbody');
            if (eduRows.length > 0) {
                pvEduTbody.innerHTML = '';
                eduRows.forEach((r, i) => {
                    const deg = r.querySelector('.edu-degree')?.value || '';
                    const sch = r.querySelector('.edu-school')?.value || '';
                    const maj = r.querySelector('.edu-major')?.value || '';
                    const yr = r.querySelector('.edu-year')?.value || '';
                    pvEduTbody.innerHTML += `
                        <tr>
                            <td class="text-center">${i + 1}</td>
                            <td>${deg}</td>
                            <td>${sch}</td>
                            <td>${maj}</td>
                            <td class="text-center">${yr}</td>
                        </tr>
                    `;
                });
            }

            const artRows = document.querySelectorAll('#tableArticles tbody tr');
            const pvArtTbody = document.querySelector('#pvTableArticles tbody');
            if (artRows.length > 0) {
                pvArtTbody.innerHTML = '';
                artRows.forEach((r, i) => {
                    const ttl = r.querySelector('.art-title')?.value || '';
                    const yr = r.querySelector('.art-year')?.value || '';
                    const jnl = r.querySelector('.art-journal')?.value || '';
                    const issn = r.querySelector('.art-issn')?.value || '';
                    pvArtTbody.innerHTML += `
                        <tr>
                            <td class="text-center">${i + 1}</td>
                            <td>${ttl}</td>
                            <td class="text-center">${yr}</td>
                            <td>${jnl}</td>
                            <td>${issn}</td>
                        </tr>
                    `;
                });
            }

            const projRows = document.querySelectorAll('#tableProjects tbody tr');
            const pvProjTbody = document.querySelector('#pvTableProjects tbody');
            if (projRows.length > 0) {
                pvProjTbody.innerHTML = '';
                projRows.forEach((r, i) => {
                    const nm = r.querySelector('.proj-name')?.value || '';
                    const cd = r.querySelector('.proj-code')?.value || '';
                    const dur = r.querySelector('.proj-duration')?.value || '';
                    const rl = r.querySelector('.proj-role')?.value || '';
                    pvProjTbody.innerHTML += `
                        <tr>
                            <td class="text-center">${i + 1}</td>
                            <td>${nm}</td>
                            <td>${cd}</td>
                            <td>${dur}</td>
                            <td>${rl}</td>
                        </tr>
                    `;
                });
            }
        }

        // TẢI DỮ LIỆU
        document.addEventListener("DOMContentLoaded", async function() {
            const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
            if (!token) return;

            try {
                const res = await fetch(`${API_URL}?detect=cv_get`, {
                    headers: { 'Authorization': `Bearer ${token}` }
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
                    document.getElementById('cvAcademicDegree').value = d.academic_degree || 'Cử nhân / Kỹ sư';
                    document.getElementById('cvResearchFields').value = d.research_fields || '';

                    currentSchoolName = d.university_name || 'Học viện An ninh nhân dân';
                    document.getElementById('cvOrgName').value = currentSchoolName;
                    document.getElementById('pvOrg').innerText = currentSchoolName;

                    currentUnitName = d.unit_name || (d.unit_code ? `Đơn vị ${d.unit_code}` : 'Chưa phân công đơn vị');
                    document.getElementById('cvDepartment').value = currentUnitName;
                    document.getElementById('pvDept').innerText = currentUnitName;

                    if (d.education_history) {
                        try {
                            const edus = JSON.parse(d.education_history);
                            if (edus.length > 0) edus.forEach(e => addEducationRow(e));
                            else addEducationRow();
                        } catch(e){ addEducationRow(); }
                    } else { addEducationRow(); }

                    if (d.work_history) {
                        try {
                            const works = JSON.parse(d.work_history);
                            if (works.length > 0) works.forEach(w => addWorkRow(w));
                            else addWorkRow();
                        } catch(e){ addWorkRow(); }
                    } else { addWorkRow(); }

                    if (d.published_articles) {
                        try {
                            const arts = JSON.parse(d.published_articles);
                            if (arts.length > 0) arts.forEach(a => addArticleRow(a));
                            else addArticleRow();
                        } catch(e){ addArticleRow(); }
                    } else { addArticleRow(); }

                    if (d.projects_participated) {
                        try {
                            const projs = JSON.parse(d.projects_participated);
                            if (projs.length > 0) projs.forEach(p => addProjectRow(p));
                            else addProjectRow();
                        } catch(e){ addProjectRow(); }
                    } else { addProjectRow(); }
                } else {
                    addEducationRow();
                    addWorkRow();
                    addArticleRow();
                    addProjectRow();
                }
            } catch (err) {
                console.error("Lỗi nạp lý lịch khoa học:", err);
            }
        });

        // XỬ LÝ LƯU CV VỚI VALIDATION TRƯỚC KHI GỬI
        async function handleCVSave(event) {
            event.preventDefault();
            const submitBtn = document.getElementById('btnSaveCV');
            const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');

            // 1. Kiểm tra họ tên
            const fullNameEl = document.getElementById('cvFullName');
            if (!fullNameEl.value.trim()) {
                setFieldInvalid(fullNameEl, 'Vui lòng nhập họ và tên.');
                switchTab('tab1');
                fullNameEl.focus();
                return;
            } else {
                setFieldValid(fullNameEl);
            }

            // 2. Kiểm tra tuổi
            const birthDateEl = document.getElementById('cvBirthDate');
            if (!validateAge(birthDateEl.value)) {
                switchTab('tab1');
                birthDateEl.focus();
                showFloatingToast("Vui lòng kiểm tra lại ngày sinh (Phải từ đủ 18 tuổi).", "danger");
                return;
            }

            // 3. Kiểm tra CCCD
            const idCardEl = document.getElementById('cvIdCard');
            if (!validateIdCard(idCardEl.value)) {
                switchTab('tab1');
                idCardEl.focus();
                showFloatingToast("Số CCCD/CMND không hợp lệ.", "danger");
                return;
            }

            // 4. Kiểm tra SĐT
            const phoneEl = document.getElementById('cvPhone');
            if (!validatePhone(phoneEl.value)) {
                switchTab('tab1');
                phoneEl.focus();
                showFloatingToast("Số điện thoại không hợp lệ.", "danger");
                return;
            }

            // 5. Kiểm tra Email
            const emailEl = document.getElementById('cvEmail');
            if (!validateEmail(emailEl.value)) {
                switchTab('tab1');
                emailEl.focus();
                showFloatingToast("Địa chỉ email không hợp lệ.", "danger");
                return;
            }

            // Gom dữ liệu bảng Quá trình đào tạo
            const eduList = [];
            document.querySelectorAll('#tableEducation tbody tr').forEach(r => {
                const degree = r.querySelector('.edu-degree')?.value.trim();
                const school = r.querySelector('.edu-school')?.value.trim();
                const major = r.querySelector('.edu-major')?.value.trim();
                const year = r.querySelector('.edu-year')?.value.trim();
                if (degree || school) eduList.push({ degree, school, major, year });
            });

            // Gom dữ liệu Quá trình công tác
            const workList = [];
            document.querySelectorAll('#tableWork tbody tr').forEach(r => {
                const time = r.querySelector('.work-time')?.value.trim();
                const org = r.querySelector('.work-org')?.value.trim();
                const position = r.querySelector('.work-position')?.value.trim();
                if (org || position) workList.push({ time, org, position });
            });

            // Gom bài báo khoa học
            const artList = [];
            document.querySelectorAll('#tableArticles tbody tr').forEach(r => {
                const title = r.querySelector('.art-title')?.value.trim();
                const year = r.querySelector('.art-year')?.value.trim();
                const journal = r.querySelector('.art-journal')?.value.trim();
                const issn = r.querySelector('.art-issn')?.value.trim();
                if (title) artList.push({ title, year, journal, issn });
            });

            // Gom đề tài tham gia
            const projList = [];
            document.querySelectorAll('#tableProjects tbody tr').forEach(r => {
                const name = r.querySelector('.proj-name')?.value.trim();
                const code = r.querySelector('.proj-code')?.value.trim();
                const duration = r.querySelector('.proj-duration')?.value.trim();
                const role = r.querySelector('.proj-role')?.value.trim();
                const result = r.querySelector('.proj-result')?.value.trim();
                if (name) projList.push({ name, code, duration, role, result });
            });

            const payload = {
                cv_id: document.getElementById('cvId').value,
                full_name: fullNameEl.value.trim(),
                birth_date: birthDateEl.value,
                gender: document.getElementById('cvGender').value,
                id_card_number: idCardEl.value.trim(),
                phone_number: phoneEl.value.trim(),
                email_work: emailEl.value.trim(),
                academic_rank: document.getElementById('cvAcademicRank').value,
                academic_degree: document.getElementById('cvAcademicDegree').value,
                research_fields: document.getElementById('cvResearchFields').value.trim(),
                education_history: JSON.stringify(eduList),
                work_history: JSON.stringify(workList),
                published_articles: JSON.stringify(artList),
                projects_participated: JSON.stringify(projList)
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
                    showFloatingToast(result.message || "Đã lưu lý lịch khoa học thành công!", "success");
                    if (result.cv_id) document.getElementById('cvId').value = result.cv_id;
                } else {
                    showFloatingToast(result.message || "Không thể lưu dữ liệu!", "danger");
                }
            } catch (err) {
                console.error("Lỗi lưu CV:", err);
                showFloatingToast("Lỗi kết nối máy chủ!", "danger");
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = `<i class="fa-solid fa-floppy-disk me-2"></i> Lưu Lý Lịch Khoa Học`;
            }
        }
    </script>
</body>

</html>