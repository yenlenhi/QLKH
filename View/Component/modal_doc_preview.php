<!-- MODAL XEM TRƯỚC TÀI LIỆU DÙNG CHUNG TOÀN HỆ THỐNG -->
<div class="modal fade" id="globalDocPreviewModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content bg-dark border-0">
            <!-- Header Modal -->
            <div class="modal-header bg-dark text-white border-bottom border-secondary py-2 px-3">
                <div class="d-flex align-items-center gap-2 text-truncate me-3">
                    <i class="fa-solid fa-file-lines text-primary fs-5"></i>
                    <span class="fw-bold fs-6 text-truncate" id="globalPreviewTitle">Xem tài liệu</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="#" id="globalBtnOpenTab" target="_blank" class="btn btn-sm btn-outline-light">
                        <i class="fa-solid fa-up-right-from-square me-1"></i> Mở tab mới
                    </a>
                    <a href="#" id="globalBtnDownload" download class="btn btn-sm btn-primary">
                        <i class="fa-solid fa-download me-1"></i> Tải file
                    </a>
                    <button type="button" class="btn btn-sm btn-secondary ms-2" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark me-1"></i> Đóng
                    </button>
                </div>
            </div>

            <!-- Body Modal: Chiếm 100% chiều cao và độ rộng màn hình -->
            <div class="modal-body p-0 position-relative" style="background-color: #525659; overflow: hidden;">
                <div id="globalPreviewLoading" class="position-absolute top-50 start-50 translate-middle text-center text-white d-none">
                    <div class="spinner-border text-primary" role="status"></div>
                    <div class="mt-2 small">Đang nạp dữ liệu văn bản...</div>
                </div>
                <div id="globalPreviewContent" style="width: 100%; height: 100%; overflow-y: auto;"></div>
            </div>
        </div>
    </div>
</div>

<style>
/* CSS Tờ giấy Word A4 bên trong Modal */
.modal-word-sheet {
    background: #ffffff;
    width: 100%;
    max-width: 860px;
    min-height: 1100px;
    margin: 30px auto;
    padding: 60px 80px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
    font-family: "Times New Roman", Times, serif;
    font-size: 14pt;
    line-height: 1.5;
    color: #000;
    text-align: justify;
    box-sizing: border-box;
}
.modal-word-sheet table {
    width: 100% !important;
    border-collapse: collapse !important;
    margin: 14pt 0 !important;
}
.modal-word-sheet th, .modal-word-sheet td {
    border: 1px solid #000 !important;
    padding: 6px 10px !important;
}
</style>

<script>
async function openGlobalDocPreview(fileUrl, fileName) {
    const modalEl = document.getElementById('globalDocPreviewModal');
    const modalTitle = document.getElementById('globalPreviewTitle');
    const btnDownload = document.getElementById('globalBtnDownload');
    const btnOpenTab = document.getElementById('globalBtnOpenTab');
    const previewContent = document.getElementById('globalPreviewContent');
    const loadingEl = document.getElementById('globalPreviewLoading');

    modalTitle.innerText = fileName;
    btnDownload.href = fileUrl;
    btnDownload.setAttribute('download', fileName);
    btnOpenTab.href = fileUrl;

    previewContent.innerHTML = '';
    loadingEl.classList.remove('d-none');

    const ext = fileName.split('.').pop().toLowerCase();
    const modalObj = new bootstrap.Modal(modalEl);
    modalObj.show();

    if (ext === 'pdf' || ['jpg', 'jpeg', 'png', 'txt'].includes(ext)) {
        loadingEl.classList.add('d-none');
        previewContent.innerHTML = `<iframe src="${fileUrl}#view=FitH" width="100%" height="100%" style="border:none; display:block;"></iframe>`;
    } else if (ext === 'docx') {
        try {
            const response = await fetch(fileUrl);
            const arrayBuffer = await response.arrayBuffer();
            const res = await mammoth.convertToHtml({ arrayBuffer: arrayBuffer });
            loadingEl.classList.add('d-none');
            previewContent.innerHTML = `
                <div class="modal-word-sheet">
                    ${res.value || '<p class="text-center text-muted">Văn bản không có nội dung.</p>'}
                </div>
            `;
        } catch (err) {
            loadingEl.classList.add('d-none');
            previewContent.innerHTML = `
                <div class="text-center p-5 text-white">
                    <i class="fa-solid fa-triangle-exclamation text-warning fa-3x mb-3"></i>
                    <h5>Không thể xem trực tiếp văn bản Word này</h5>
                    <p class="text-muted small">Vui lòng bấm nút "Tải file" ở thanh công cụ phía trên để xem bằng Microsoft Word.</p>
                </div>
            `;
        }
    } else {
        loadingEl.classList.add('d-none');
        previewContent.innerHTML = `
            <div class="text-center p-5 text-white">
                <i class="fa-solid fa-file-arrow-down fa-3x text-secondary mb-3"></i>
                <h5>Định dạng .${ext.toUpperCase()} không hỗ trợ đọc trực tiếp</h5>
                <p class="text-muted small">Vui lòng tải file về thiết bị.</p>
            </div>
        `;
    }
}
</script>