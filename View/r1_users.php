<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <!-- THÊM DÒNG NÀY ĐỂ ĐỊNH VỊ GỐC CHO ĐƯỜNG DẪN TƯƠNG ĐỐI -->
    <base href="/QLNCKH/View/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Người dùng - Admin</title>
    <script src="../asset/script/auth_check.js"></script>
    <link href="../asset/css/bootstrap5.css" rel="stylesheet">
    <link rel="stylesheet" href="../asset/css/all.min.css">
    <link rel="stylesheet" href="../asset/css/style_admin.css">

    <style>
        /* =========================================================
           KHUNG XEM TRƯỚC VÀ TRANG IN A4 (KHẮC PHỤC TRIỆT ĐỂ LỖI NỀN TỐI)
           ========================================================= */
        .a4-preview-wrapper,
        .a4-preview-modal-wrapper {
            background-color: #525659 !important;
            padding: 30px 15px !important;
            display: block !important;
            overflow-y: auto !important;
            max-height: 82vh !important;
            border-radius: 6px;
        }

        .a4-page-sheet {
            background-color: #ffffff !important;
            background: #ffffff !important;
            width: 100% !important;
            max-width: 820px !important;
            min-height: 1123px !important;
            height: auto !important;
            margin: 0 auto 30px auto !important;
            padding: 50px 60px !important;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.45) !important;
            font-family: "Times New Roman", Times, serif !important;
            font-size: 13pt !important;
            line-height: 1.35 !important;
            color: #000000 !important;
            box-sizing: border-box !important;
            position: relative !important;
            display: block !important;
        }

        table.tbl-bm04 {
            width: 100% !important;
            border-collapse: collapse !important;
            margin-top: 6pt !important;
            margin-bottom: 12pt !important;
            background-color: #ffffff !important;
            background: #ffffff !important;
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
            background: #ffffff !important;
        }

        table.tbl-bm04 tr {
            background-color: #ffffff !important;
            background: #ffffff !important;
            page-break-inside: avoid !important;
        }

        @media print {
            @page {
                size: A4 portrait;
                margin: 15mm 15mm 15mm 20mm;
            }

            body {
                background: #ffffff !important;
                color: #000000 !important;
                font-family: "Times New Roman", Times, serif !important;
            }

            /* Ẩn các thành phần giao diện thừa khi gọi print trực tiếp */
            #sidebar,
            #main-content,
            .modal-backdrop,
            .modal-header,
            .modal-footer {
                display: none !important;
            }
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
                <h3 class="fw-bold m-0 text-primary"><i class="fa-solid fa-users-gear me-2"></i>Quản lý Người dùng</h3>
                <small class="text-muted">Thống kê và tổng hợp số lượng đề tài NCKH theo từng người dùng trong hệ thống.</small>
            </div>
            <a href="../r1_register_account.php" class="btn btn-success btn-sm"><i class="fa-solid fa-user-plus me-1"></i> Thêm tài khoản mới</a>
        </div>

        <!-- Bộ lọc -->
        <div class="card shadow-sm border-0 rounded-3 mb-4">
            <div class="card-body p-3">
                <div class="row g-2">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                            <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm theo Username hoặc Tên người dùng...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-primary w-100" onclick="fetchUsers()"><i class="fa-solid fa-filter me-1"></i> Lọc dữ liệu</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bảng Dữ liệu -->
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center border">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="py-3" style="width: 50px;">STT</th>
                                <th scope="col" class="py-3 text-start">Username</th>
                                <th scope="col" class="py-3 text-start">Tên người dùng</th>
                                <th scope="col" class="py-3">Đã đăng ký</th>
                                <th scope="col" class="py-3">Đang thực hiện</th>
                                <th scope="col" class="py-3">Đã nghiệm thu</th>
                                <th scope="col" class="py-3">Đã thanh toán</th>
                                <th scope="col" class="py-3">Trạng Thái TK</th>
                                <th scope="col" class="py-3" style="width: 180px;">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody id="userTableBody">
                            <tr>
                                <td colspan="9" class="py-4 text-muted"><i class="fa-solid fa-spinner fa-spin me-2"></i>Đang tải dữ liệu người dùng...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL XEM CHI TIẾT NHÀ KHOA HỌC (LÝ LỊCH BM-04 READ-ONLY) -->
    <div class="modal fade" id="modalViewScientist" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white py-2 px-3">
                    <h5 class="modal-title fs-6 fw-bold">
                        <i class="fa-solid fa-id-card-clip me-2"></i>Lý Lịch Khoa Học (Mẫu BM-04) - <span id="modalScientistName" class="text-warning">Đang tải...</span>
                    </h5>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-light btn-sm fw-bold text-primary shadow-sm" onclick="printScientistCV()">
                            <i class="fa-solid fa-print me-1"></i> In / Xuất PDF (A4)
                        </button>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                </div>
                <div class="modal-body p-0">
                    <div class="a4-preview-modal-wrapper">
                        <div class="a4-page-sheet" id="adminPrintableArea">
                            <div style="text-align: right; font-weight: bold; font-size: 11pt; margin-bottom: 2pt;">BM-04</div>
                            <div style="text-align: center; font-style: italic; font-size: 11pt; margin-bottom: 6pt;">
                                Ban hành kèm theo Thông tư số 22/2026/TT-BCA ngày 26/02/2026 của Bộ trưởng Bộ Công an
                            </div>
                            <div style="text-align: center; margin-bottom: 14pt;">
                                <div style="font-weight: bold; font-size: 12pt;">CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div>
                                <div style="font-weight: bold; font-size: 13pt;">Độc lập - Tự do - Hạnh phúc</div>
                                <div style="font-size: 11pt; margin-top: -3px; font-weight: bold;">-----------------------</div>
                            </div>
                            <div style="text-align: center; margin-bottom: 12pt;">
                                <div style="font-weight: bold; font-size: 15pt; margin-bottom: 4pt;">LÝ LỊCH KHOA HỌC</div>
                                <div style="font-weight: bold; font-size: 12pt; margin-bottom: 4pt;">Cá nhân đăng ký thực hiện nhiệm vụ khoa học, công nghệ và đổi mới sáng tạo</div>
                                <div style="font-style: italic; font-weight: bold; font-size: 12pt;">“....................................................................................................................”</div>
                            </div>
                            <table style="width: auto; margin: 12pt auto 16pt auto; border-collapse: collapse; border: none;">
                                <tr>
                                    <td style="border: none; padding: 3pt 15pt 3pt 0; font-size: 13pt; text-align: left; vertical-align: middle;">Đăng ký chức danh Chủ nhiệm nhiệm vụ</td>
                                    <td style="border: none; padding: 3pt 0; font-size: 14pt; text-align: center; vertical-align: middle; width: 30px;"><span id="vwRoleCN">☐</span></td>
                                </tr>
                                <tr>
                                    <td style="border: none; padding: 3pt 15pt 3pt 0; font-size: 13pt; text-align: left; vertical-align: middle;">Đăng ký chức danh Thư ký nhiệm vụ</td>
                                    <td style="border: none; padding: 3pt 0; font-size: 14pt; text-align: center; vertical-align: middle;"><span id="vwRoleTK">☐</span></td>
                                </tr>
                                <tr>
                                    <td style="border: none; padding: 3pt 15pt 3pt 0; font-size: 13pt; text-align: left; vertical-align: middle;">Đăng ký chức danh khác trong Ban Chủ nhiệm nhiệm vụ</td>
                                    <td style="border: none; padding: 3pt 0; font-size: 14pt; text-align: center; vertical-align: middle;"><span id="vwRoleKhac">☐</span></td>
                                </tr>
                            </table>
                            <div style="line-height: 1.5; font-size: 13pt;">
                                <div style="margin-bottom: 3pt;">1. Họ và tên: <strong id="vwFullName">....................................................</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Số định danh cá nhân: <span id="vwIdCard">.......................................</span></div>
                                <div style="margin-bottom: 3pt;">2. Ngày/tháng/năm sinh: <span id="vwBirthDate">....../....../............</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Giới tính: <span id="vwGender">.........................................................</span></div>
                                <div style="margin-bottom: 3pt;">3. Học hàm (nếu có): <span id="vwRank">.................................</span> &nbsp;&nbsp;&nbsp;&nbsp; Năm được bổ nhiệm học hàm: <span id="vwRankYear">............</span></div>
                                <div style="margin-bottom: 3pt; margin-left: 15pt;">Học vị (nếu có): <strong id="vwDegree">.................................</strong> &nbsp;&nbsp;&nbsp;&nbsp; Năm được công nhận học vị: <span id="vwDegreeYear">............</span></div>
                                <div style="margin-bottom: 3pt;">4. Chức danh nghề nghiệp (nếu có): <span id="vwJobTitle">..........................</span> &nbsp;&nbsp;&nbsp;&nbsp; Chức vụ (nếu có): <span id="vwPosition">............................</span></div>
                                <div style="margin-left: 20pt; margin-bottom: 2pt;">- Nhân tài về khoa học, công nghệ và đổi mới sáng tạo <span id="vwTalent1" style="float: right; margin-right: 35pt;">☐</span></div>
                                <div style="margin-left: 20pt; margin-bottom: 2pt;">- Nhà khoa học trẻ tài năng, kỹ sư trẻ tài năng <span id="vwTalent2" style="float: right; margin-right: 35pt;">☐</span></div>
                                <div style="margin-left: 20pt; margin-bottom: 4pt;">- Không thuộc hai trường hợp trên <span id="vwTalent3" style="float: right; margin-right: 35pt;">☒</span></div>
                                <div style="margin-bottom: 3pt;">5. Địa chỉ nhà riêng: <span id="vwHomeAddress">......................................................................................................................</span></div>
                                <div style="margin-bottom: 3pt;">6. Điện thoại liên hệ: <span id="vwPhone">........................................</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; E-mail: <span id="vwEmail">...............................................................</span></div>
                                <div style="margin-bottom: 3pt;">7. Đơn vị công tác (nếu có):</div>
                                <div style="margin-left: 15pt; margin-bottom: 2pt;">Tên đơn vị: <span id="vwOrg">.............................................................................................................................</span></div>
                                <div style="margin-left: 15pt; margin-bottom: 2pt;">Địa chỉ đơn vị: <span id="vwWorkAddress">.........................................................................................................................</span></div>
                                <div style="margin-left: 15pt; margin-bottom: 3pt;">Thủ trưởng đơn vị: <span id="vwOrgHeadName">....................................</span> &nbsp;&nbsp;&nbsp;&nbsp; Điện thoại liên hệ: <span id="vwOrgHeadPhone">.................................................</span></div>
                            </div>
                            <div style="font-weight: bold; margin-top: 10pt; margin-bottom: 3pt; font-size: 13pt;">8. Quá trình đào tạo</div>
                            <table class="tbl-bm04" id="vwTableEdu">
                                <thead>
                                    <tr>
                                        <th style="width: 22%;">Bậc đào tạo</th>
                                        <th style="width: 33%;">Nơi đào tạo</th>
                                        <th style="width: 25%;">Chuyên môn</th>
                                        <th style="width: 20%;">Năm cấp bằng<br>tốt nghiệp</th>
                                    </tr>
                                </thead>
                                <tbody><tr><td colspan="4" style="text-align: center; font-style: italic;">Chưa có thông tin</td></tr></tbody>
                            </table>
                            <div style="font-weight: bold; margin-top: 10pt; margin-bottom: 3pt; font-size: 13pt;">9. Quá trình công tác</div>
                            <table class="tbl-bm04" id="vwTableWork">
                                <thead>
                                    <tr>
                                        <th style="width: 24%;">Thời gian<br><span style="font-weight: normal; font-size: 10.5pt;">(Từ năm ... đến năm...)</span></th>
                                        <th style="width: 26%;">Vị trí công tác</th>
                                        <th style="width: 26%;">Đơn vị công tác</th>
                                        <th style="width: 24%;">Địa chỉ đơn vị</th>
                                    </tr>
                                </thead>
                                <tbody><tr><td colspan="4" style="text-align: center; font-style: italic;">Chưa có thông tin</td></tr></tbody>
                            </table>
                            <div style="font-weight: bold; margin-top: 10pt; margin-bottom: 3pt; font-size: 13pt;">10. Các công trình khoa học đã công bố chủ yếu (liệt kê tối đa 05 công trình tiêu biểu - nếu có)</div>
                            <table class="tbl-bm04" id="vwTableArticles">
                                <thead>
                                    <tr>
                                        <th style="width: 6%;">TT</th>
                                        <th style="width: 42%;">Tên công trình<br><span style="font-weight: normal; font-size: 10.5pt;">(bài báo, công trình...)</span></th>
                                        <th style="width: 22%;">Là tác giả hoặc<br>đồng tác giả</th>
                                        <th style="width: 20%;">Nơi công bố<br><span style="font-weight: normal; font-size: 10.5pt;">(tên tạp chí, NXB)</span></th>
                                        <th style="width: 10%;">Năm<br>công bố</th>
                                    </tr>
                                </thead>
                                <tbody><tr><td colspan="5" style="text-align: center; font-style: italic;">Chưa có thông tin</td></tr></tbody>
                            </table>
                            <div style="font-weight: bold; margin-top: 10pt; margin-bottom: 3pt; font-size: 13pt;">11. Các văn bằng bảo hộ quyền sở hữu trí tuệ (liên quan đến nhiệm vụ KH, CN&ĐMST - nếu có):</div>
                            <table class="tbl-bm04" id="vwTablePatents">
                                <thead>
                                    <tr>
                                        <th style="width: 8%;">TT</th>
                                        <th style="width: 72%;">Tên và nội dung văn bằng</th>
                                        <th style="width: 20%;">Năm cấp văn bằng</th>
                                    </tr>
                                </thead>
                                <tbody><tr><td colspan="3" style="text-align: center; font-style: italic;">Chưa có thông tin</td></tr></tbody>
                            </table>
                            <div style="font-weight: bold; margin-top: 10pt; margin-bottom: 3pt; font-size: 13pt;">12. Số công trình được áp dụng trong thực tiễn (liên quan đến nhiệm vụ KH,CN&ĐMST đăng ký - nếu có)</div>
                            <table class="tbl-bm04" id="vwTableAppliedWorks">
                                <thead>
                                    <tr>
                                        <th style="width: 8%;">TT</th>
                                        <th style="width: 42%;">Tên công trình</th>
                                        <th style="width: 30%;">Hình thức, quy mô,<br>địa chỉ áp dụng</th>
                                        <th style="width: 20%;">Thời gian<br><span style="font-weight: normal; font-size: 10.5pt;">(bắt đầu - kết thúc)</span></th>
                                    </tr>
                                </thead>
                                <tbody><tr><td colspan="4" style="text-align: center; font-style: italic;">Chưa có thông tin</td></tr></tbody>
                            </table>
                            <div style="font-weight: bold; margin-top: 10pt; margin-bottom: 3pt; font-size: 13pt;">13. Các nhiệm vụ KH, CN&ĐMST đã và đang chủ trì hoặc tham gia:</div>
                            <table class="tbl-bm04" id="vwTableProjects">
                                <thead>
                                    <tr>
                                        <th style="width: 40%;">Tên nhiệm vụ<br><span style="font-weight: normal; font-size: 10.5pt;">(Chủ trì / tham gia)</span></th>
                                        <th style="width: 20%;">Thời gian<br><span style="font-weight: normal; font-size: 10.5pt;">(bắt đầu - kết thúc)</span></th>
                                        <th style="width: 20%;">Thuộc Chương trình<br><span style="font-weight: normal; font-size: 10.5pt;">(nếu có)</span></th>
                                        <th style="width: 20%;">Tình trạng nhiệm vụ<br><span style="font-weight: normal; font-size: 10.5pt;">(đã/chưa nghiệm thu)</span></th>
                                    </tr>
                                </thead>
                                <tbody><tr><td colspan="4" style="text-align: center; font-style: italic;">Chưa có thông tin</td></tr></tbody>
                            </table>
                            <div style="font-weight: bold; margin-top: 10pt; margin-bottom: 3pt; font-size: 13pt;">14. Giải thưởng (về KH,CN&ĐMST, chất lượng sản phẩm,... liên quan đến nhiệm vụ KH, CN&ĐMST - nếu có)</div>
                            <table class="tbl-bm04" id="vwTableAwards">
                                <thead>
                                    <tr>
                                        <th style="width: 8%;">TT</th>
                                        <th style="width: 72%;">Hình thức và nội dung giải thưởng</th>
                                        <th style="width: 20%;">Năm tặng thưởng</th>
                                    </tr>
                                </thead>
                                <tbody><tr><td colspan="3" style="text-align: center; font-style: italic;">Chưa có thông tin</td></tr></tbody>
                            </table>
                            <div style="font-weight: bold; margin-top: 10pt; margin-bottom: 3pt; font-size: 13pt;">15. Thành tựu hoạt động khoa học và công nghệ và sản xuất kinh doanh khác (nếu có):</div>
                            <div id="vwOtherAchievements" style="font-style: italic; min-height: 20pt; margin-bottom: 10pt; line-height: 1.4;">......................................................................................................................................................................................</div>
                            <div style="font-weight: bold; margin-top: 10pt; margin-bottom: 3pt; font-size: 13pt;">16. Cam kết</div>
                            <div style="line-height: 1.45; font-size: 13pt;">
                                Tôi cam kết:<br>
                                - Các thông tin trên là trung thực, chính xác;<br>
                                - Có đủ thời gian, năng lực và điều kiện để chủ trì, điều hành/tham gia thực hiện nhiệm vụ;<br>
                                - Thực hiện nhiệm vụ đúng quy định của pháp luật, chịu trách nhiệm về kết quả và sử dụng kinh phí theo hợp đồng được ký với cơ quan quản lý nhiệm vụ.
                            </div>
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
                                        <div id="vwSignName" style="font-weight: bold; font-size: 12pt;">................................................</div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-2 bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary btn-sm fw-bold" onclick="printScientistCV()">
                        <i class="fa-solid fa-print me-1"></i> In / Xuất PDF (A4)
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL CẬP NHẬT -->
    <div class="modal fade" id="modalCapNhat" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-user-pen me-2"></i>Cập Nhật Thông Tin Tài Khoản</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-3">
                    <div id="updateAlertMessage" class="alert d-none mb-3 py-2 small" role="alert"></div>

                    <form id="formCapNhatTaiKhoan">
                        <input type="hidden" id="updateUserId">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Username</label>
                            <input type="text" class="form-control bg-light" id="displayUsername" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Họ và Tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="updateName" required placeholder="Nhập Họ và Tên...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Khoa / Phòng / Ban</label>
                            <select class="form-select" id="updateUnitId" name="unit_id">
                                <option value="">-- Chưa phân công / Trực thuộc trường --</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Mật khẩu mới</label>
                            <input type="password" class="form-control" id="updatePassword" placeholder="Để trống nếu không muốn đổi mật khẩu">
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-warning btn-sm px-4 fw-bold" id="btnSaveUpdate" onclick="saveUserUpdate()"><i class="fa-solid fa-check me-1"></i>Cập Nhật</button>
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
                        <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Hủy</button>
                        <button type="button" class="btn btn-danger btn-sm px-3" id="btnConfirmExecute">Xác nhận</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TOAST THÔNG BÁO NỔI -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1090;">
        <div id="liveToast" class="toast align-items-center text-white border-0 shadow" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center">
                    <i id="toastIcon" class="fa-solid fa-circle-check me-2 fs-5"></i>
                    <span id="toastMessage">Thông báo</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script src="../asset/script/bootstrap5.js"></script>
    <script src="../asset/script/sidebar.js"></script>
    <script>
        const API_URL = '../index_api.php';
        const API_GET_USERS = `${API_URL}?detect=get_users`;
        const API_GET_STATS = `${API_URL}?detect=user_project_stats`;
        const API_UPDATE_INFO = `${API_URL}?detect=update_user_info`;
        const API_UPDATE_STATUS = `${API_URL}?detect=update_user_status`;
        const API_DELETE_USER = `${API_URL}?detect=delete_user`;

        let allUsersData = [];
        let userStatsData = {};
        let orgUnitsCache = [];
        let confirmModal = null;
        let updateModalInstance = null;
        let scientistModalInstance = null;
        let toastInstance = null;
        let pendingConfirmCallback = null;

        function showNotification(message, type = "success") {
            const toastEl = document.getElementById('liveToast');
            const toastMsg = document.getElementById('toastMessage');
            const toastIcon = document.getElementById('toastIcon');
            if (!toastEl) return;

            toastEl.className = 'toast align-items-center text-white border-0 shadow';
            if (type === "success") {
                toastEl.classList.add('bg-success');
                toastIcon.className = 'fa-solid fa-circle-check me-2 fs-5';
            } else if (type === "danger" || type === "error") {
                toastEl.classList.add('bg-danger');
                toastIcon.className = 'fa-solid fa-circle-xmark me-2 fs-5';
            } else {
                toastEl.classList.add('bg-warning', 'text-dark');
                toastIcon.className = 'fa-solid fa-triangle-exclamation me-2 fs-5';
            }

            toastMsg.textContent = message;
            if (!toastInstance) {
                toastInstance = new bootstrap.Toast(toastEl, { delay: 3500 });
            }
            toastInstance.show();
        }

        function showUpdateModalAlert(message, type = 'success') {
            const alertEl = document.getElementById('updateAlertMessage');
            if (!alertEl) return;
            alertEl.className = `alert alert-${type} mb-3 py-2 small`;
            alertEl.innerText = message;
            alertEl.classList.remove('d-none');
        }

        async function loadOrgUnits() {
            try {
                const res = await fetchWithAuth(`${API_URL}?detect=org_unit_get`, { method: "GET" }).then(r => r.json());
                if (res.status === 'success' && Array.isArray(res.data)) {
                    orgUnitsCache = res.data;
                    renderOrgUnitOptions();
                }
            } catch (err) {
                console.error("Lỗi nạp danh sách Khoa:", err);
            }
        }

        function renderOrgUnitOptions(selectedUnitId = '') {
            const selectEl = document.getElementById('updateUnitId');
            if (!selectEl) return;

            let html = '<option value="">-- Chưa phân công / Trực thuộc trường --</option>';
            orgUnitsCache.forEach(unit => {
                const isSelected = String(unit.unit_id) === String(selectedUnitId) ? 'selected' : '';
                html += `<option value="${unit.unit_id}" ${isSelected}>[${unit.unit_code}] ${unit.unit_name}</option>`;
            });
            selectEl.innerHTML = html;

            if (selectedUnitId !== undefined && selectedUnitId !== null && selectedUnitId !== '') {
                selectEl.value = String(selectedUnitId);
            } else {
                selectEl.value = '';
            }
        }

        async function fetchUsers() {
            const tbody = document.getElementById('userTableBody');
            if (!tbody) return;

            try {
                const [userRes, statsRes] = await Promise.all([
                    fetchWithAuth(API_GET_USERS, {
                        method: "GET",
                        headers: { "Accept": "application/json" }
                    }),
                    fetchWithAuth(API_GET_STATS, {
                        method: "GET",
                        headers: { "Accept": "application/json" }
                    })
                ]);

                const userResult = await userRes.json();
                const statsResult = await statsRes.json();

                if (userRes.ok && userResult.status === 'success') {
                    allUsersData = userResult.data || [];
                } else {
                    tbody.innerHTML = `<tr><td colspan="9" class="py-4 text-danger">Lỗi tải danh sách người dùng.</td></tr>`;
                    return;
                }

                if (statsRes.ok && statsResult.status === 'success') {
                    userStatsData = statsResult.data || {};
                }

                renderTable();
            } catch (err) {
                console.error('Lỗi kết nối:', err);
                tbody.innerHTML = `<tr><td colspan="9" class="py-4 text-danger">Không thể kết nối đến máy chủ API.</td></tr>`;
            }
        }

        function renderTable() {
            const tbody = document.getElementById('userTableBody');
            const searchInput = document.getElementById('searchInput');
            const searchVal = searchInput ? searchInput.value.trim().toLowerCase() : '';

            let filtered = allUsersData.filter(user => parseInt(user.can_grant_permission) === 0);

            if (searchVal) {
                filtered = filtered.filter(u =>
                    (u.username && u.username.toLowerCase().includes(searchVal)) ||
                    (u.name && u.name.toLowerCase().includes(searchVal))
                );
            }

            if (filtered.length === 0) {
                tbody.innerHTML = `<tr><td colspan="9" class="py-4 text-muted">Không tìm thấy người dùng phù hợp.</td></tr>`;
                return;
            }

            tbody.innerHTML = filtered.map((user, index) => {
                const isStatusActive = parseInt(user.status) === 1;
                const stats = userStatsData[user.user_id] || {
                    pending: 0,
                    running: 0,
                    awaiting_payment: 0,
                    completed: 0
                };

                return `
                <tr data-user-id="${user.user_id}" data-status="${user.status}">
                    <td class="fw-bold">${index + 1}</td>
                    <td class="text-start fw-bold username-val">${user.username}</td>
                    <td class="text-start name-val">
                        <div>${user.name || '---'}</div>
                        ${user.unit_name ? `<small class="text-muted"><i class="fa-solid fa-building-columns me-1"></i>${user.unit_name}</small>` : ''}
                    </td>
                    
                    <td><span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 fw-bold">${stats.pending}</span></td>
                    <td><span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 fw-bold">${stats.running}</span></td>
                    <td><span class="badge bg-info bg-opacity-10 text-info px-2 py-1 fw-bold">${stats.awaiting_payment}</span></td>
                    <td><span class="badge bg-success bg-opacity-10 text-success px-2 py-1 fw-bold">${stats.completed}</span></td>
                    
                    <td>
                        <span class="badge ${isStatusActive ? 'bg-success' : 'bg-danger'} status-badge">
                            ${isStatusActive ? 'Hoạt động' : 'Bị khóa'}
                        </span>
                    </td>

                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-info" 
                                onclick="viewScientistDetail(${user.user_id}, '${escapeHtml(user.name || user.username)}')" 
                                title="Xem chi tiết Lý lịch khoa học (BM-04)">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <button type="button" class="btn btn-outline-warning" 
                                data-bs-toggle="modal" data-bs-target="#modalCapNhat" 
                                data-id="${user.user_id}" 
                                data-user="${user.username}" 
                                data-name="${user.name || ''}" 
                                data-unit-id="${user.unit_id || ''}" 
                                title="Cập nhật thông tin">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button type="button" class="btn ${isStatusActive ? 'btn-outline-danger' : 'btn-outline-success'}" 
                                onclick="toggleAccountStatus(this)" 
                                title="${isStatusActive ? 'Vô hiệu hóa tài khoản' : 'Kích hoạt tài khoản'}">
                                <i class="fa-solid ${isStatusActive ? 'fa-user-slash' : 'fa-user-check'}"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger" 
                                onclick="deleteUser(this)" 
                                title="Xóa tài khoản">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </td>
                </tr>`;
            }).join('');
        }

        function escapeHtml(text) {
            if (!text) return '';
            return text.replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        async function viewScientistDetail(userId, scientistName) {
            document.getElementById('modalScientistName').innerText = scientistName;

            if (!scientistModalInstance) {
                scientistModalInstance = new bootstrap.Modal(document.getElementById('modalViewScientist'));
            }
            scientistModalInstance.show();

            try {
                const res = await fetchWithAuth(`${API_URL}?detect=cv_get&user_id=${userId}`, {
                    method: 'GET'
                }).then(r => r.json());

                if (res.status === 'success' && res.data) {
                    const d = res.data;

                    const regRole = d.reg_role || 'chu_nhiem';
                    document.getElementById('vwRoleCN').innerText = regRole === 'chu_nhiem' ? '☒' : '☐';
                    document.getElementById('vwRoleTK').innerText = regRole === 'thu_ky' ? '☒' : '☐';
                    document.getElementById('vwRoleKhac').innerText = regRole === 'khac' ? '☒' : '☐';

                    const talentVal = String(d.talent_category || '3');
                    document.getElementById('vwTalent1').innerText = talentVal === '1' ? '☒' : '☐';
                    document.getElementById('vwTalent2').innerText = talentVal === '2' ? '☒' : '☐';
                    document.getElementById('vwTalent3').innerText = talentVal === '3' ? '☒' : '☐';

                    document.getElementById('vwFullName').innerText = d.name || '....................................................';
                    document.getElementById('vwIdCard').innerText = d.id_card_number || '.......................................';
                    document.getElementById('vwBirthDate').innerText = d.birth_date ? d.birth_date.split('-').reverse().join('/') : '....../....../............';
                    document.getElementById('vwGender').innerText = d.gender || '.........................................................';

                    document.getElementById('vwRank').innerText = d.academic_rank || '.................................';
                    document.getElementById('vwRankYear').innerText = d.academic_rank_year || '............';
                    document.getElementById('vwDegree').innerText = d.academic_degree || '.................................';
                    document.getElementById('vwDegreeYear').innerText = d.academic_degree_year || '............';

                    document.getElementById('vwJobTitle').innerText = d.job_title || '..........................';
                    document.getElementById('vwPosition').innerText = d.position || '............................';
                    document.getElementById('vwHomeAddress').innerText = d.home_address || '......................................................................................................................';

                    document.getElementById('vwPhone').innerText = d.phone_number || '........................................';
                    document.getElementById('vwEmail').innerText = d.email_work || '...............................................................';

                    const schoolName = d.university_name || 'Trường Đại học An ninh nhân dân';
                    const unitName = d.unit_name || (d.unit_code ? `Đơn vị ${d.unit_code}` : '');
                    document.getElementById('vwOrg').innerText = unitName ? `${schoolName} (${unitName})` : schoolName;
                    document.getElementById('vwWorkAddress').innerText = d.work_address || '.........................................................................................................................';
                    document.getElementById('vwOrgHeadName').innerText = d.org_head_name || '....................................';
                    document.getElementById('vwOrgHeadPhone').innerText = d.org_head_phone || '.................................................';

                    document.getElementById('vwOtherAchievements').innerText = d.other_achievements || '......................................................................................................................................................................................';
                    document.getElementById('vwSignName').innerText = d.name || '................................................';

                    renderModalTable('vwTableEdu', d.education_history, (item) => `
                        <tr>
                            <td>${escapeHtml(item.degree || '')}</td>
                            <td>${escapeHtml(item.school || '')}</td>
                            <td>${escapeHtml(item.major || '')}</td>
                            <td class="text-center">${escapeHtml(String(item.year || ''))}</td>
                        </tr>
                    `, 4);

                    renderModalTable('vwTableWork', d.work_history, (item) => `
                        <tr>
                            <td class="text-center">${escapeHtml(item.time || '')}</td>
                            <td>${escapeHtml(item.position || '')}</td>
                            <td>${escapeHtml(item.org || '')}</td>
                            <td>${escapeHtml(item.address || '')}</td>
                        </tr>
                    `, 4);

                    renderModalTable('vwTableArticles', d.published_articles, (item, idx) => `
                        <tr>
                            <td class="text-center">${idx + 1}</td>
                            <td>${escapeHtml(item.title || '')}</td>
                            <td>${escapeHtml(item.role || '')}</td>
                            <td>${escapeHtml(item.journal || '')}</td>
                            <td class="text-center">${escapeHtml(String(item.year || ''))}</td>
                        </tr>
                    `, 5);

                    renderModalTable('vwTablePatents', d.patents, (item, idx) => `
                        <tr>
                            <td class="text-center">${idx + 1}</td>
                            <td>${escapeHtml(item.title || '')}</td>
                            <td class="text-center">${escapeHtml(String(item.year || ''))}</td>
                        </tr>
                    `, 3);

                    renderModalTable('vwTableAppliedWorks', d.applied_works, (item, idx) => `
                        <tr>
                            <td class="text-center">${idx + 1}</td>
                            <td>${escapeHtml(item.title || '')}</td>
                            <td>${escapeHtml(item.scope || '')}</td>
                            <td class="text-center">${escapeHtml(item.time || '')}</td>
                        </tr>
                    `, 4);

                    renderModalTable('vwTableProjects', d.projects_participated, (item) => `
                        <tr>
                            <td>${escapeHtml(item.name || '')}</td>
                            <td class="text-center">${escapeHtml(item.time || '')}</td>
                            <td>${escapeHtml(item.program || '')}</td>
                            <td class="text-center">${escapeHtml(item.status || '')}</td>
                        </tr>
                    `, 4);

                    renderModalTable('vwTableAwards', d.awards, (item, idx) => `
                        <tr>
                            <td class="text-center">${idx + 1}</td>
                            <td>${escapeHtml(item.title || '')}</td>
                            <td class="text-center">${escapeHtml(String(item.year || ''))}</td>
                        </tr>
                    `, 3);

                } else {
                    showNotification("Không tìm thấy dữ liệu lý lịch khoa học của người dùng này.", "warning");
                }
            } catch (err) {
                console.error("Lỗi nạp lý lịch:", err);
                showNotification("Lỗi kết nối máy chủ khi nạp hồ sơ khoa học!", "danger");
            }
        }

        function renderModalTable(tableId, rawData, rowBuilder, colSpan) {
            const tbody = document.querySelector(`#${tableId} tbody`);
            if (!tbody) return;

            let list = [];
            if (rawData) {
                try {
                    list = typeof rawData === 'string' ? JSON.parse(rawData) : rawData;
                } catch (e) {
                    list = [];
                }
            }

            if (Array.isArray(list) && list.length > 0) {
                tbody.innerHTML = list.map((item, idx) => rowBuilder(item, idx)).join('');
            } else {
                tbody.innerHTML = `<tr><td colspan="${colSpan}" class="text-center text-muted fst-italic">Chưa có thông tin</td></tr>`;
            }
        }

        // =========================================================================
        // HÀM IN / XUẤT PDF A4 ĐƯỢC CẢI TIẾN BẰNG IFRAME (CHỐNG LỖI TRẮNG TRANG MODAL)
        // =========================================================================
        function printScientistCV() {
            const printContent = document.getElementById('adminPrintableArea');
            if (!printContent) return;

            // Xóa iframe cũ nếu tồn tại
            const oldIframe = document.getElementById('print_cv_iframe');
            if (oldIframe) oldIframe.remove();

            // Tạo một iframe ẩn độc lập để thực hiện lệnh in
            const iframe = document.createElement('iframe');
            iframe.id = 'print_cv_iframe';
            iframe.style.position = 'fixed';
            iframe.style.right = '0';
            iframe.style.bottom = '0';
            iframe.style.width = '0';
            iframe.style.height = '0';
            iframe.style.border = '0';
            document.body.appendChild(iframe);

            const doc = iframe.contentWindow.document;

            doc.open();
            doc.write(`
                <!DOCTYPE html>
                <html lang="vi">
                <head>
                    <meta charset="UTF-8">
                    <title>Lý Lịch Khoa Học - BM04</title>
                    <style>
                        @page {
                            size: A4 portrait;
                            margin: 15mm 15mm 15mm 20mm;
                        }
                        * {
                            box-sizing: border-box;
                        }
                        body {
                            margin: 0;
                            padding: 0;
                            background-color: #ffffff;
                            font-family: "Times New Roman", Times, serif;
                            font-size: 13pt;
                            line-height: 1.35;
                            color: #000000;
                            -webkit-print-color-adjust: exact;
                            print-color-adjust: exact;
                        }
                        table.tbl-bm04 {
                            width: 100% !important;
                            border-collapse: collapse !important;
                            margin-top: 6pt !important;
                            margin-bottom: 12pt !important;
                        }
                        table.tbl-bm04 th {
                            border: 1px solid #000000 !important;
                            padding: 5pt 6pt !important;
                            font-size: 11pt !important;
                            line-height: 1.25 !important;
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
                        }
                        table.tbl-bm04 tr {
                            page-break-inside: avoid !important;
                        }
                        .sign-section {
                            page-break-inside: avoid !important;
                        }
                    </style>
                </head>
                <body>
                    ${printContent.innerHTML}
                </body>
                </html>
            `);
            doc.close();

            // Đợi CSS nạp hoàn tất rồi kích hoạt lệnh in
            setTimeout(() => {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            }, 300);
        }

        // CẬP NHẬT USER KÈM UNIT_ID
        async function saveUserUpdate() {
            const userId = document.getElementById('updateUserId').value;
            const newName = document.getElementById('updateName').value.trim();
            const unitId = document.getElementById('updateUnitId').value;
            const newPassword = document.getElementById('updatePassword').value.trim();
            const btnSave = document.getElementById('btnSaveUpdate');

            if (!newName) {
                showUpdateModalAlert('Vui lòng không để trống Họ và Tên!', 'warning');
                return;
            }

            try {
                btnSave.disabled = true;
                btnSave.innerHTML = `<i class="fa-solid fa-spinner fa-spin me-1"></i> Đang lưu...`;

                const response = await fetchWithAuth(API_UPDATE_INFO, {
                    method: "POST",
                    headers: {
                        "Accept": "application/json",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        user_id: parseInt(userId),
                        name: newName,
                        unit_id: unitId ? parseInt(unitId) : null,
                        password: newPassword ? newPassword : null
                    })
                });

                const res = await response.json();

                if (response.ok && res.status === "success") {
                    showUpdateModalAlert(res.message || "Cập nhật thành công!", "success");
                    showNotification(res.message || "Cập nhật thông tin thành công!", "success");

                    setTimeout(() => {
                        const modalElement = document.getElementById('modalCapNhat');
                        if (modalElement) {
                            if (!updateModalInstance) updateModalInstance = bootstrap.Modal.getInstance(modalElement);
                            if (updateModalInstance) updateModalInstance.hide();
                        }
                        fetchUsers();
                    }, 800);
                } else {
                    showUpdateModalAlert("Lỗi: " + (res.message || "Không thể cập nhật"), "danger");
                }
            } catch (err) {
                console.error("Lỗi cập nhật:", err);
                showUpdateModalAlert("Lỗi kết nối máy chủ!", "danger");
            } finally {
                btnSave.disabled = false;
                btnSave.innerHTML = `<i class="fa-solid fa-check me-1"></i>Cập Nhật`;
            }
        }

        function toggleAccountStatus(btn) {
            const tr = btn.closest('tr');
            const userId = tr.getAttribute('data-user-id');
            const currentStatus = parseInt(tr.getAttribute('data-status'));
            const newStatus = currentStatus === 1 ? 0 : 1;
            const actionText = newStatus === 0 ? 'vô hiệu hóa' : 'kích hoạt';

            document.getElementById('confirmTitle').innerText = "Xác nhận trạng thái tài khoản";
            document.getElementById('confirmMessage').innerText = `Bạn có chắc chắn muốn ${actionText} tài khoản này không?`;

            pendingConfirmCallback = async function() {
                try {
                    const response = await fetchWithAuth(API_UPDATE_STATUS, {
                        method: "POST",
                        headers: {
                            "Accept": "application/json",
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            user_id: userId,
                            status: newStatus
                        })
                    });
                    const res = await response.json();
                    if (response.ok && res.status === "success") {
                        showNotification(res.message || "Đã thay đổi trạng thái thành công!", "success");
                        fetchUsers();
                    } else {
                        showNotification("Lỗi: " + (res.message || "Không thể đổi trạng thái"), "danger");
                    }
                } catch (err) {
                    console.error("Lỗi trạng thái:", err);
                    showNotification("Lỗi kết nối máy chủ.", "danger");
                }
            };

            confirmModal.show();
        }

        function deleteUser(btn) {
            const tr = btn.closest('tr');
            const userId = tr.getAttribute('data-user-id');
            const username = tr.querySelector('.username-val').textContent.trim();

            document.getElementById('confirmTitle').innerText = "Xác nhận xóa tài khoản";
            document.getElementById('confirmMessage').innerText = `Hành động này không thể hoàn tác! Bạn có chắc chắn muốn XÓA vĩnh viễn tài khoản "${username}" không?`;

            pendingConfirmCallback = async function() {
                try {
                    const response = await fetchWithAuth(API_DELETE_USER, {
                        method: "POST",
                        headers: {
                            "Accept": "application/json",
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            user_id: userId
                        })
                    });
                    const res = await response.json();
                    if (response.ok && res.status === "success") {
                        showNotification(res.message || "Đã xóa tài khoản thành công!", "success");
                        fetchUsers();
                    } else {
                        showNotification("Lỗi: " + (res.message || "Không thể xóa"), "danger");
                    }
                } catch (err) {
                    console.error("Lỗi xóa:", err);
                    showNotification("Lỗi kết nối máy chủ.", "danger");
                }
            };

            confirmModal.show();
        }

        document.addEventListener('DOMContentLoaded', async () => {
            await loadOrgUnits();
            fetchUsers();
            confirmModal = new bootstrap.Modal(document.getElementById('modalConfirmAction'));

            document.getElementById('btnConfirmExecute').addEventListener('click', function() {
                if (typeof pendingConfirmCallback === 'function') {
                    pendingConfirmCallback();
                }
                if (confirmModal) confirmModal.hide();
            });

            const modalCapNhat = document.getElementById('modalCapNhat');
            if (modalCapNhat) {
                modalCapNhat.addEventListener('show.bs.modal', async (e) => {
                    const alertEl = document.getElementById('updateAlertMessage');
                    if (alertEl) alertEl.classList.add('d-none');

                    const btn = e.relatedTarget;
                    const unitId = btn.getAttribute('data-unit-id') || '';

                    document.getElementById('updateUserId').value = btn.getAttribute('data-id');
                    document.getElementById('displayUsername').value = btn.getAttribute('data-user');
                    document.getElementById('updateName').value = btn.getAttribute('data-name');
                    document.getElementById('updatePassword').value = '';

                    if (orgUnitsCache.length === 0) {
                        await loadOrgUnits();
                    }
                    renderOrgUnitOptions(unitId);
                });
            }

            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('keypress', e => {
                    if (e.key === 'Enter') renderTable();
                });
                let timeout = null;
                searchInput.addEventListener('input', () => {
                    clearTimeout(timeout);
                    timeout = setTimeout(renderTable, 300);
                });
            }
        });
    </script>
</body>

</html>