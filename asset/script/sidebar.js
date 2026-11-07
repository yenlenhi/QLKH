// asset/script/sidebar.js

// 1. Hàm Bật/Tắt Sidebar
function toggleSidebar() {
    var sidebar = document.getElementById('sidebar');
    var mainContent = document.getElementById('main-content');
    if (sidebar && mainContent) {
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('expanded');
    }
}

// 2. Hàm xử lý Đăng xuất (Dùng Bootstrap Modal, không dùng confirm mặc định)
function handleLogout() {
    let logoutModalEl = document.getElementById('modalConfirmLogout');

    // Tự động khởi tạo Modal xác nhận đăng xuất nếu trang chưa có sẵn
    if (!logoutModalEl) {
        const modalHtml = `
            <div class="modal fade" id="modalConfirmLogout" tabindex="-1" aria-hidden="true" style="z-index: 1095;">
                <div class="modal-dialog modal-dialog-centered modal-sm">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-body text-center p-4">
                            <div class="mb-3 text-warning">
                                <i class="fa-solid fa-arrow-right-from-bracket fa-3x"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Đăng xuất</h5>
                            <p class="text-muted small mb-4">Bạn có chắc chắn muốn đăng xuất khỏi hệ thống không?</p>
                            <div class="d-flex justify-content-center gap-2">
                                <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Hủy</button>
                                <button type="button" class="btn btn-danger btn-sm px-3" id="btnExecuteLogout">Đăng xuất</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        logoutModalEl = document.getElementById('modalConfirmLogout');

        document.getElementById('btnExecuteLogout').addEventListener('click', function() {
            doLogout();
        });
    }

    const modalInstance = new bootstrap.Modal(logoutModalEl);
    modalInstance.show();
}

function doLogout() {
    if (typeof clearSessionAndRedirect === 'function') {
        clearSessionAndRedirect();
    } else {
        sessionStorage.clear();
        localStorage.clear();
        // Lấy đường dẫn gốc của app để redirect về trang đăng nhập
        const pathSegments = window.location.pathname.split('/');
        const appBase = pathSegments.length > 2 ? `/${pathSegments[1]}/` : '/';
        window.location.href = `${appBase}index.php`;
    }
}