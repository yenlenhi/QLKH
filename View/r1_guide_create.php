<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <!-- THÊM DÒNG NÀY ĐỂ ĐỊNH VỊ GỐC CHO ĐƯỜNG DẪN TƯƠNG ĐỐI -->
    <base href="/QLNCKH/View/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Hướng dẫn - Admin</title>
    <script src="../asset/script/auth_check.js"></script>
    <link href="../asset/css/bootstrap5.css" rel="stylesheet">
    <link rel="stylesheet" href="../asset/css/all.min.css">
    <link rel="stylesheet" href="../asset/css/style_admin.css">
</head>

<body>

    <!-- Sidebar Menu -->
    <?php include '../Model/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div id="main-content" class="p-4">
        <!-- <?php include '../Model/r1_topbar.php'; ?> -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold m-0 text-primary">
                    <i class="fa-solid fa-book-bookmark me-2"></i>Thêm Hướng dẫn Mới
                </h3>
                <small class="text-muted">Soạn thảo bài hướng dẫn dùng chung cho hệ thống.</small>
            </div>

            <!-- Navigation Buttons -->
            <div class="btn-group">
                <a href="r1_guide_create.php" class="btn btn-primary fw-bold active">
                    <i class="fa-solid fa-pen-to-square me-2"></i>Thêm Hướng dẫn Mới
                </a>
                <a href="r1_guide_list.php" class="btn btn-outline-primary fw-bold">
                    <i class="fa-solid fa-list-check me-2"></i>Danh sách Hướng dẫn
                </a>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white py-3">
                <h5 class="card-title fw-bold m-0 text-dark">
                    <i class="fa-solid fa-plus-circle text-primary me-2"></i>Soạn thảo Bài Hướng dẫn Mới
                </h5>
            </div>
            <div class="card-body p-4">
                <form id="formCreateGuide" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="guideTitle" class="form-label fw-bold">Tiêu đề Hướng dẫn <span class="text-danger">*</span></label>
                        <input type="text" class="form-control validate-text" id="guideTitle" name="title" placeholder="Nhập tiêu đề (không nhập ký tự đặc biệt)..." required>
                    </div>
                    <div class="mb-3">
                        <label for="guideContent" class="form-label fw-bold">Nội dung Hướng dẫn <span class="text-danger">*</span></label>
                        <textarea class="form-control validate-text" id="guideContent" name="content" rows="6" placeholder="Nhập nội dung chi tiết..." required></textarea>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="guideHotline" class="form-label fw-bold"><i class="fa-solid fa-headset text-danger me-1"></i> Hotline hỗ trợ</label>
                            <input type="text" class="form-control validate-number" id="guideHotline" name="hotline" placeholder="Chỉ nhập số (VD: 19001234)" maxlength="15">
                        </div>
                        <div class="col-md-6">
                            <label for="guidePhone" class="form-label fw-bold"><i class="fa-solid fa-phone text-success me-1"></i> Số điện thoại liên hệ</label>
                            <input type="text" class="form-control validate-number" id="guidePhone" name="phone" placeholder="Chỉ nhập số (VD: 0912345678)" maxlength="11">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="guideAttachments" class="form-label fw-bold">
                            <i class="fa-solid fa-paperclip text-primary me-1"></i> Tài liệu đính kèm (Cho phép chọn nhiều file)
                        </label>
                        <input class="form-control" type="file" id="guideAttachments" name="attachments[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.zip,.rar">
                    </div>
                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <button type="reset" class="btn btn-light border px-4"><i class="fa-solid fa-rotate-left me-1"></i> Nhập lại</button>
                        <button type="submit" class="btn btn-primary px-4"><i class="fa-solid fa-paper-plane me-2"></i> Xuất bản Hướng dẫn</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="../asset/script/bootstrap5.js"></script>
    <script src="../asset/script/sidebar.js"></script>
    <script>
        // function toggleSidebar() {
        //     var sidebar = document.getElementById('sidebar');
        //     var mainContent = document.getElementById('main-content');
        //     if (sidebar && mainContent) {
        //         sidebar.classList.toggle('collapsed');
        //         mainContent.classList.toggle('expanded');
        //     }
        // }
        const API_URL = "../index_api.php";

        document.addEventListener('DOMContentLoaded', function() {
            // Validation: Chỉ cho phép nhập số
            document.querySelectorAll('.validate-number').forEach(function(element) {
                element.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            });

            // Validation: Lọc ký tự đặc biệt nguy hại
            document.querySelectorAll('.validate-text').forEach(function(element) {
                element.addEventListener('input', function() {
                    this.value = this.value.replace(/[^a-zA-Z0-9àáảãạâầấẩẫậăằắẳẵặèéẻẽẹêềếểễệđìíỉĩịòóỏõọôồốổỗộơờớởỡợùúủũụưừứửữựỳýỷỹỵÀÁẢÃẠÂẦẤẨẪẬĂẰẮẲẴẶÈÉẺẼẸÊỀẾỂỄỆĐÌÍỈĨỊÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢÙÚỦŨỤƯỪỨỬỮỰỲÝỶỸỴ\s.,\-?!()\/]/g, '');
                });
            });

            // Gửi dữ liệu Thêm Hướng dẫn
            document.getElementById('formCreateGuide').addEventListener('submit', async function(e) {
                e.preventDefault();
                var formData = new FormData(this);

                try {
                    var response = await fetchWithAuth(`${API_URL}?detect=create_guides`, {
                        method: 'POST',
                        body: formData
                    });
                    var result = await response.json();

                    if (result.status === 'success') {
                        alert(result.message);
                        window.location.href = 'r1_guide_list.php';
                    } else {
                        alert('Lỗi: ' + result.message);
                    }
                } catch (err) {
                    alert('Lỗi kết nối đến server.');
                }
            });
        });
    </script>
</body>

</html>