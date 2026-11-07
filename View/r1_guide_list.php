<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <!-- THÊM DÒNG NÀY ĐỂ ĐỊNH VỊ GỐC CHO ĐƯỜNG DẪN TƯƠNG ĐỐI -->
    <base href="/QLNCKH/View/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách Hướng dẫn - Admin</title>
    <script src="../asset/script/auth_check.js"></script>
    <link href="../asset/css/bootstrap5.css" rel="stylesheet">
    <link rel="stylesheet" href="../asset/css/all.min.css">
    <link rel="stylesheet" href="../asset/css/style_admin.css">
    <style>
        /* CSS Định dạng văn bản xem trước cho Mammoth.js */

        .mammoth-doc-container {
            width: 100%;
            height: 100%;
            overflow-y: auto;
            background: #ffffff;
            padding: 25px;
            font-family: "Times New Roman", Times, serif;
            line-height: 1.6;
            color: #212529;
            border-radius: 4px;
        }

        .mammoth-doc-container table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 1rem;
        }

        .mammoth-doc-container table,
        .mammoth-doc-container th,
        .mammoth-doc-container td {
            border: 1px solid #dee2e6;
            padding: 8px;
        }

        .mammoth-doc-container img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>

<body>
    <!-- Sidebar Menu -->
    <!-- Nhúng Sidebar dùng chung -->
    <?php include '../Model/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div id="main-content" class="p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold m-0 text-primary">
                    <i class="fa-solid fa-book-bookmark me-2"></i> Danh sách Hướng dẫn
                </h3>
                <small class="text-muted">Quản lý, xem thông tin, chỉnh sửa và xóa bài hướng dẫn.</small>
            </div>
            <div class="btn-group">
                <a href="r1_guide_create.php" class="btn btn-outline-primary fw-bold">
                    <i class="fa-solid fa-pen-to-square me-2"></i> Thêm Hướng dẫn Mới
                </a>
                <a href="r1_guide_list.php" class="btn btn-primary fw-bold active">
                    <i class="fa-solid fa-list-check me-2"></i> Danh sách Hướng dẫn
                </a>
            </div>
        </div>
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title fw-bold m-0 text-dark">
                    <i class="fa-solid fa-list-check me-2 text-primary"></i>Danh sách Hướng dẫn Hệ thống
                </h5>
                <button class="btn btn-sm btn-outline-secondary" onclick="loadGuideList()">
                    <i class="fa-solid fa-arrows-rotate me-1"></i> Làm mới
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;" class="text-center">STT</th>
                                <th style="width: 250px;">Tiêu đề</th>
                                <th>Nội dung tóm tắt</th>
                                <th style="width: 180px;">Liên hệ</th>
                                <th style="width: 110px;" class="text-center">File kèm</th>
                                <th style="width: 130px;" class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="guideTableBody">
                            <!-- Dữ liệu render từ API -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL ĐIỀU CHỈNH CẬP NHẬT -->
    <div class="modal fade" id="editGuideModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="formEditGuide" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold text-primary">
                            <i class="fa-solid fa-pen-to-square me-2"></i>Cập nhật Hướng dẫn
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="editGuideId" name="id">

                        <!-- Chứa ID của các file bị người dùng chọn xóa -->
                        <div id="deletedFilesContainer"></div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text" class="form-control validate-text" id="editGuideTitle" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nội dung <span class="text-danger">*</span></label>
                            <textarea class="form-control validate-text" id="editGuideContent" name="content" rows="6" required></textarea>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Hotline</label>
                                <input type="text" class="form-control validate-number" id="editGuideHotline" name="hotline" maxlength="15">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Số điện thoại</label>
                                <input type="text" class="form-control validate-number" id="editGuidePhone" name="phone" maxlength="11">
                            </div>
                        </div>

                        <!-- Danh sách File Hiện Tại -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fa-solid fa-folder-open text-primary me-1"></i> File đính kèm hiện tại
                            </label>
                            <div id="editFileListContainer" class="list-group border rounded p-2 bg-light">
                                <!-- Render danh sách file cũ bằng JS -->
                            </div>
                        </div>

                        <!-- Bổ sung file mới -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fa-solid fa-cloud-arrow-up text-success me-1"></i> Tải thêm file mới
                            </label>
                            <input class="form-control" type="file" id="editGuideAttachments" name="attachments[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.zip,.rar">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Lưu cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL XEM CHI TIẾT BÀI HƯỚNG DẪN -->
    <div class="modal fade" id="viewGuideModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold text-primary" id="viewTitle">
                        <i class="fa-solid fa-book-open me-2"></i> Chi tiết Bài Hướng dẫn
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Cột trái: Nội dung thông tin -->
                        <div class="col-md-5 border-end">
                            <h5 class="fw-bold text-dark" id="viewGuideTitle">---</h5>
                            <div class="text-muted mb-3 small">
                                <span class="me-3">
                                    <i class="fa-solid fa-headset text-danger me-1"></i> Hotline:
                                    <strong id="viewHotline">-</strong>
                                </span>
                                <span>
                                    <i class="fa-solid fa-phone text-success me-1"></i> SĐT:
                                    <strong id="viewPhone">-</strong>
                                </span>
                            </div>
                            <hr>
                            <h6 class="fw-bold">Nội dung:</h6>
                            <div id="viewGuideContent" class="p-3 bg-light rounded" style="white-space: pre-line; max-height: 250px; overflow-y: auto;">
                                ---
                            </div>
                            <!-- Danh sách File Đính Kèm -->
                            <h6 class="fw-bold mt-4 mb-2">
                                <i class="fa-solid fa-paperclip text-primary me-1"></i> File đính kèm:
                            </h6>
                            <div id="fileListContainer" class="list-group list-group-flush border rounded">
                                <!-- JS render danh sách file -->
                            </div>
                        </div>
                        <!-- Cột phải: Trình xem File (Preview) -->
                        <div class="col-md-7">
                            <h6 class="fw-bold mb-2">
                                <i class="fa-solid fa-eye text-primary me-1"></i> Xem trước tài liệu:
                            </h6>
                            <div id="filePreviewArea" class="border rounded bg-secondary-subtle d-flex justify-content-center align-items-center" style="height: 500px;">
                                <span class="text-muted">Chọn một file bên trái để xem nội dung</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../asset/script/bootstrap5.js"></script>
    <!-- Thư viện đọc file DOCX (Mammoth.js) -->
    <script src="../asset/script/mammoth.js"></script>
    <script src="../asset/script/sidebar.js"></script>
    <script>
        const API_URL = "../index_api.php";

        document.addEventListener('DOMContentLoaded', function() {
            // Validation Input
            document.querySelectorAll('.validate-number').forEach(function(element) {
                element.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            });

            document.querySelectorAll('.validate-text').forEach(function(element) {
                element.addEventListener('input', function() {
                    this.value = this.value.replace(/[^a-zA-Z0-9àáảãạâầấẩẫậăằắẳẵặèéẻẽẹêềếểễệđìíỉĩịòóỏõọôồốổỗộơờớởỡợùúủũụưừứửữựỳýỷỹỵÀÁẢÃẠÂẦẤẨẪẬĂẰẮẲẴẶÈÉẺẼẸÊỀẾỂỄỆĐÌÍỈĨỊÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢÙÚỦŨỤƯỪỨỬỮỰỲÝỶỸỴ\s.,\-?!()\/]/g, '');
                });
            });

            loadGuideList();

            // Gửi dữ liệu Cập nhật Hướng dẫn
            document.getElementById('formEditGuide').addEventListener('submit', async function(e) {
                e.preventDefault();
                var formData = new FormData(this);

                try {
                    var response = await fetchWithAuth(`${API_URL}?detect=update_guides`, {
                        method: 'POST',
                        body: formData
                    });
                    var result = await response.json();

                    if (result.status === 'success') {
                        alert(result.message);
                        bootstrap.Modal.getInstance(document.getElementById('editGuideModal')).hide();
                        loadGuideList();
                    } else {
                        alert('Lỗi: ' + result.message);
                    }
                } catch (err) {
                    alert('Lỗi kết nối đến server.');
                }
            });
        });

        // Gọi API lấy Danh sách
        // Gọi API lấy Danh sách
        async function loadGuideList() {
            try {
                var response = await fetchWithAuth(`${API_URL}?detect=get_guides`);

                // Kiểm tra xem phản hồi có nội dung hay không trước khi parse JSON
                var text = await response.text();
                if (!text) {
                    console.error('Server trả về dữ liệu rỗng.');
                    return;
                }

                var result = JSON.parse(text);

                if (result.status === 'success') {
                    renderGuideTable(result.data);
                } else {
                    console.error('Lỗi từ API:', result.message);
                }
            } catch (err) {
                console.error('Chưa thể lấy danh sách bài viết.', err);
            }
        }

        function renderGuideTable(data) {
            var tbody = document.getElementById('guideTableBody');
            if (!tbody) return;

            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">Chưa có hướng dẫn nào trong hệ thống.</td></tr>';
                return;
            }

            var html = '';
            data.forEach((item, index) => {
                html += `
                    <tr>
                        <td class="text-center fw-bold">${index + 1}</td>
                        <td><span class="fw-bold text-dark">${escapeHtml(item.title)}</span></td>
                        <td><span class="text-muted small text-truncate d-block" style="max-width: 300px;">${escapeHtml(item.content)}</span></td>
                        <td class="small">
                            <div><i class="fa-solid fa-headset text-danger me-1"></i> ${escapeHtml(item.hotline || '-')}</div>
                            <div><i class="fa-solid fa-phone text-success me-1"></i> ${escapeHtml(item.number_phone || '-')}</div>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-info text-dark"><i class="fa-solid fa-paperclip me-1"></i> ${item.files_count || 0} files</span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-info" onclick="openViewModal(${item.id})" title="Xem chi tiết & đọc file">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-outline-primary" onclick="openEditModal(${item.id})" title="Cập nhật">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger" onclick="deleteGuide(${item.id})" title="Xóa">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;
        }

        // Mở Modal Xem chi tiết
        async function openViewModal(id) {
            try {
                var response = await fetchWithAuth(`${API_URL}?detect=get_guides&id=${id}`);
                if (!response.ok) throw new Error(`Mã lỗi HTTP: ${response.status}`);

                var result = await response.json();

                if (result.status === 'success' && result.data) {
                    var data = result.data;

                    document.getElementById('viewGuideTitle').innerText = data.title || '';
                    document.getElementById('viewGuideContent').innerText = data.content || '';
                    document.getElementById('viewHotline').innerText = data.hotline || '-';
                    document.getElementById('viewPhone').innerText = data.number_phone || '-';

                    document.getElementById('filePreviewArea').innerHTML = '<span class="text-muted">Chọn một file bên trái để xem nội dung</span>';

                    var fileContainer = document.getElementById('fileListContainer');
                    fileContainer.innerHTML = '';

                    var files = parseFilesData(data.file_guides);

                    if (!files || files.length === 0) {
                        fileContainer.innerHTML = '<div class="list-group-item text-muted small py-3 text-center">Không có file đính kèm</div>';
                    } else {
                        files.forEach((fileObj) => {
                            var displayName = fileObj.original_name || fileObj.file_path.split('/').pop();
                            var cleanPath = resolveFilePath(fileObj.file_path);
                            var fileExt = cleanPath.split('.').pop().toLowerCase();

                            var itemHtml = `
                                <div class="list-group-item d-flex justify-content-between align-items-center py-2">
                                    <div class="text-truncate me-2" style="max-width: 220px;" title="${escapeHtml(displayName)}">
                                        <i class="fa-solid fa-file-word text-primary me-2"></i>
                                        <small class="fw-bold text-dark">${escapeHtml(displayName)}</small>
                                    </div>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-sm btn-primary" onclick="previewFile('${cleanPath}', '${fileExt}')" title="Xem trước">
                                            <i class="fa-solid fa-eye"></i> Xem
                                        </button>
                                        <a href="${cleanPath}" download="${escapeHtml(displayName)}" class="btn btn-sm btn-outline-secondary" title="Tải về file gốc">
                                            <i class="fa-solid fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            `;
                            fileContainer.insertAdjacentHTML('beforeend', itemHtml);
                        });
                    }

                    new bootstrap.Modal(document.getElementById('viewGuideModal')).show();
                } else {
                    alert('Lỗi từ Server: ' + (result.message || 'Không tìm thấy dữ liệu.'));
                }
            } catch (err) {
                console.error('Chi tiết lỗi openViewModal:', err);
                alert('Không thể tải chi tiết bài viết.\nChi tiết: ' + err.message);
            }
        }

        // Đọc/Xem trước file (Mammoth.js)
        async function previewFile(fileUrl, extension) {
            var previewArea = document.getElementById('filePreviewArea');
            previewArea.innerHTML = '<div class="text-center p-3"><div class="spinner-border text-primary" role="status"></div><p class="mt-2 text-muted">Đang tải tài liệu...</p></div>';

            if (['pdf', 'jpg', 'jpeg', 'png', 'gif', 'txt'].includes(extension)) {
                previewArea.innerHTML = `<iframe src="${fileUrl}" width="100%" height="100%" style="border:none;"></iframe>`;
            } else if (extension === 'docx') {
                try {
                    var response = await fetch(fileUrl);
                    if (!response.ok) throw new Error('Không thể tải file từ máy chủ.');

                    var arrayBuffer = await response.arrayBuffer();
                    previewArea.innerHTML = '<div id="docxContainer" class="mammoth-doc-container"></div>';
                    var container = document.getElementById('docxContainer');

                    var result = await mammoth.convertToHtml({
                        arrayBuffer: arrayBuffer
                    });
                    container.innerHTML = result.value || '<p class="text-muted">File trống hoặc không chứa văn bản hiển thị được.</p>';
                } catch (err) {
                    console.error('Lỗi đọc file Word bằng Mammoth:', err);
                    previewArea.innerHTML = `
                        <div class="text-center p-4 text-danger">
                            <i class="fa-solid fa-circle-exclamation fa-2x mb-2"></i>
                            <p>Không thể đọc file Word này trên trình duyệt.</p>
                            <a href="${fileUrl}" download class="btn btn-outline-primary btn-sm">Tải file về máy</a>
                        </div>
                    `;
                }
            } else if (extension === 'doc') {
                previewArea.innerHTML = `
                    <div class="text-center p-4">
                        <i class="fa-solid fa-file-word fa-3x text-primary mb-3"></i>
                        <h6 class="fw-bold">File Word định dạng cũ (.doc)</h6>
                        <p class="text-muted small">Định dạng .doc không hỗ trợ xem trực tiếp chuẩn xác trong mạng LAN.<br>Vui lòng chuyển sang định dạng <strong>.docx</strong> hoặc tải về máy để xem.</p>
                        <a href="${fileUrl}" download class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-download me-1"></i> Tải file về xem
                        </a>
                    </div>
                `;
            } else {
                previewArea.innerHTML = `
                    <div class="text-center p-4">
                        <i class="fa-solid fa-file-zipper fa-3x text-warning mb-3"></i>
                        <p class="mb-2">Định dạng <strong>.${extension}</strong> không hỗ trợ xem trực tiếp.</p>
                        <a href="${fileUrl}" download class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-download me-1"></i> Tải file về máy
                        </a>
                    </div>
                `;
            }
        }

        // Mở Modal Chỉnh sửa & Liệt kê danh sách file
        async function openEditModal(id) {
            try {
                var response = await fetchWithAuth(`${API_URL}?detect=get_guides&id=${id}`);
                var result = await response.json();

                if (result.status === 'success' && result.data) {
                    var data = result.data;

                    document.getElementById('editGuideId').value = data.id;
                    document.getElementById('editGuideTitle').value = data.title || '';
                    document.getElementById('editGuideContent').value = data.content || '';
                    document.getElementById('editGuideHotline').value = data.hotline || '';
                    document.getElementById('editGuidePhone').value = data.number_phone || '';

                    // Reset input đính kèm file mới
                    document.getElementById('editGuideAttachments').value = '';

                    // Render danh sách file đính kèm hiện tại
                    var editFileList = document.getElementById('editFileListContainer');
                    editFileList.innerHTML = '';

                    var files = parseFilesData(data.file_guides);

                    if (!files || files.length === 0) {
                        editFileList.innerHTML = '<span class="text-muted small text-center d-block py-2">Không có file đính kèm nào.</span>';
                    } else {
                        files.forEach((fileObj, index) => {
                            var filePath = fileObj.file_path || '';
                            var displayName = fileObj.original_name || filePath.split('/').pop();
                            var elementId = 'edit-file-item-' + index;

                            var html = `
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2" id="${elementId}">
                            <span class="text-truncate me-2 small fw-bold" style="max-width: 80%;" title="${escapeHtml(displayName)}">
                                <i class="fa-solid fa-paperclip me-1 text-secondary"></i> ${escapeHtml(displayName)}
                            </span>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteSingleFile(${data.id}, '${escapeHtml(filePath)}', '${elementId}')" title="Xóa file này ngay">
                                <i class="fa-solid fa-trash-can"></i> Xóa
                            </button>
                        </div>
                    `;
                            editFileList.insertAdjacentHTML('beforeend', html);
                        });
                    }

                    new bootstrap.Modal(document.getElementById('editGuideModal')).show();
                }
            } catch (err) {
                console.error('Lỗi mở modal edit:', err);
                alert('Không thể tải chi tiết dữ liệu.');
            }
        }

        // Gọi API riêng để xóa lẻ 1 file ngay lập tức
        async function deleteSingleFile(guideId, filePath, elementId) {
            if (!confirm('Bạn có chắc chắn muốn xóa file đính kèm này không?')) return;

            var formData = new FormData();
            formData.append('guide_id', guideId);
            formData.append('file_path', filePath);

            try {
                var response = await fetchWithAuth(`${API_URL}?detect=delete_single_file_guide`, {
                    method: 'POST',
                    body: formData
                });

                var result = await response.json();

                if (result.status === 'success') {
                    // 1. Xóa phần tử khỏi giao diện Modal
                    var item = document.getElementById(elementId);
                    if (item) item.remove();

                    // 2. Nếu đã xóa hết file thì hiển thị thông báo trống
                    var editFileList = document.getElementById('editFileListContainer');
                    if (editFileList.children.length === 0) {
                        editFileList.innerHTML = '<span class="text-muted small text-center d-block py-2">Không còn file đính kèm nào.</span>';
                    }

                    // 3. Tải lại danh sách bảng ngoài màn hình chính để cập nhật số lượng badge file
                    loadGuideList();
                } else {
                    alert('Lỗi: ' + result.message);
                }
            } catch (err) {
                console.error('Lỗi kết nối API xóa file:', err);
                alert('Không thể kết nối đến server.');
            }
        }

        // Gọi API Xóa Bài viết
        async function deleteGuide(id) {
            if (!confirm('Bạn có chắc chắn muốn xóa bài hướng dẫn này?')) return;

            try {
                var response = await fetchWithAuth(`${API_URL}?detect=delete_guides&id=${id}`, {
                    method: 'DELETE'
                });
                var result = await response.json();

                if (result.status === 'success') {
                    alert(result.message);
                    loadGuideList();
                } else {
                    alert('Lỗi: ' + result.message);
                }
            } catch (err) {
                alert('Lỗi kết nối đến server.');
            }
        }

        // Hàm hỗ trợ parse dữ liệu JSON từ CSDL
        function parseFilesData(rawFiles) {
            if (!rawFiles) return [];
            if (typeof rawFiles === 'string') {
                try {
                    return JSON.parse(rawFiles);
                } catch (e) {
                    return [];
                }
            }
            return rawFiles;
        }

        // Hàm hỗ trợ chuẩn hóa đường dẫn file
        function resolveFilePath(rawPath) {
            if (!rawPath) return '';
            if (rawPath.startsWith('../asset/')) return rawPath;
            if (rawPath.startsWith('asset/')) return '../' + rawPath;
            return '../asset/file/guides/' + rawPath.split('/').pop();
        }

        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
        }
    </script>
</body>

</html>