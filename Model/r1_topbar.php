<!-- Topbar Chung -->
<div class="d-flex justify-content-between align-items-center bg-white p-3 rounded-3 shadow-sm mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="r1_overview.php" class="btn btn-outline-secondary btn-sm" id="btnBackOverview" style="display: none;">
            <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
        </a>
        <h4 class="m-0 text-secondary fw-bold" id="topbarTitle">Trang Quản Trị Hệ Thống</h4>
    </div>
    <div class="d-flex align-items-center gap-3">
        <div class="text-end user-profile-clickable" onclick="openProfileModal()" style="cursor: pointer;" title="Nhấn để cập nhật thông tin cá nhân">
            <span class="fw-bold text-dark d-block" id="userNameLabel">
                <i class="fa-solid fa-circle-user text-primary me-1"></i> Xin chào, <span id="topbarRealName">---</span>
                <i class="fa-solid fa-pen-to-square ms-1 text-muted small"></i>
            </span>
            <small class="text-muted" id="userAccountLabel">Tài khoản: <span id="topbarRealUsername">---</span></small>
        </div>
    </div>
</div>

<!-- MODAL CẬP NHẬT THÔNG TIN CÁ NHÂN -->
<div class="modal fade" id="modalUpdateMyProfile" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold text-primary">
                    <i class="fa-solid fa-user-pen me-2"></i>Cập nhật thông tin cá nhân
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <!-- KHUNG THÔNG BÁO TRONG MODAL -->
                <div id="profileAlertMessage" class="alert d-none mb-3 py-2 small" role="alert"></div>

                <form id="formUpdateMyProfile">
                    <input type="hidden" id="myProfileUserId" value="">

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Tên đăng nhập (Mã tài khoản)</label>
                        <input type="text" class="form-control form-control-sm bg-light" id="myProfileUsername" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Họ và Tên hiển thị <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="myProfileName" required placeholder="Nhập họ và tên của bạn...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Mật khẩu mới (Bỏ trống nếu không muốn đổi)</label>
                        <input type="password" class="form-control form-control-sm" id="myProfilePassword" placeholder="Nhập mật khẩu mới (nếu có)">
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Hủy bỏ</button>
                <button type="button" class="btn btn-primary btn-sm px-4 fw-bold" id="btnSaveMyProfile" onclick="submitMyProfileUpdate()">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Lưu thay đổi
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Script cục bộ: Xử lý hiển thị không giật chữ và thông báo giao diện -->
<script>
    let myProfileModalInstance = null;
    let globalCurrentUserId = 0;

    function showProfileNotification(message, type = 'success') {
        const alertEl = document.getElementById('profileAlertMessage');
        if (!alertEl) return;
        alertEl.className = `alert alert-${type} mb-3 py-2 small`;
        alertEl.innerText = message;
        alertEl.classList.remove('d-none');
    }

    function openProfileModal() {
        const modalEl = document.getElementById('modalUpdateMyProfile');
        if (!modalEl) return;

        // Ẩn thông báo cũ nếu có
        const alertEl = document.getElementById('profileAlertMessage');
        if (alertEl) alertEl.classList.add('d-none');

        if (!myProfileModalInstance) {
            myProfileModalInstance = new bootstrap.Modal(modalEl);
        }

        const nameEl = document.getElementById('topbarRealName');
        const accountEl = document.getElementById('topbarRealUsername');

        document.getElementById('myProfileUserId').value = globalCurrentUserId;
        document.getElementById('myProfileUsername').value = accountEl ? accountEl.textContent : '';
        document.getElementById('myProfileName').value = (nameEl && nameEl.textContent !== '---') ? nameEl.textContent : '';
        document.getElementById('myProfilePassword').value = '';

        myProfileModalInstance.show();
    }

    async function submitMyProfileUpdate() {
        const userId = document.getElementById('myProfileUserId').value;
        const name = document.getElementById('myProfileName').value.trim();
        const password = document.getElementById('myProfilePassword').value.trim();
        const btnSave = document.getElementById('btnSaveMyProfile');

        if (!name) {
            showProfileNotification("Vui lòng không để trống họ và tên!", "warning");
            return;
        }

        const API_URL = '/index_api.php';

        try {
            btnSave.disabled = true;
            btnSave.innerHTML = `<i class="fa-solid fa-spinner fa-spin me-1"></i> Đang lưu...`;

            const response = await fetchWithAuth(`${API_URL}?detect=update_user_info`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    user_id: userId,
                    name: name,
                    password: password
                })
            });

            const result = await response.json();

            if (response.ok && result.status === 'success') {
                showProfileNotification(result.message || "Cập nhật thành công!", "success");

                const accountEl = document.getElementById('topbarRealUsername');
                const currentUsername = accountEl ? accountEl.textContent : '';

                const nameEl = document.getElementById('topbarRealName');
                if (nameEl) nameEl.textContent = name;

                const freshData = {
                    name: name,
                    username: currentUsername
                };
                sessionStorage.setItem('cached_current_user_info', JSON.stringify(freshData));

                setTimeout(() => {
                    if (myProfileModalInstance) {
                        myProfileModalInstance.hide();
                    }
                }, 1000);
            } else {
                showProfileNotification("Lỗi: " + (result.message || "Không thể cập nhật thông tin!"), "danger");
            }
        } catch (err) {
            console.error("Lỗi cập nhật profile:", err);
            showProfileNotification("Lỗi kết nối máy chủ!", "danger");
        } finally {
            btnSave.disabled = false;
            btnSave.innerHTML = `<i class="fa-solid fa-floppy-disk me-1"></i> Lưu thay đổi`;
        }
    }

    (function() {
        const CACHE_USER_KEY = 'cached_current_user_info';
        const API_URL = '/index_api.php';

        function updateTopbarUI(name, username) {
            const nameEl = document.getElementById('topbarRealName');
            const accountEl = document.getElementById('topbarRealUsername');
            if (nameEl) nameEl.textContent = name || 'Người dùng';
            if (accountEl) accountEl.textContent = username || '---';
        }

        let cached = sessionStorage.getItem(CACHE_USER_KEY);
        let hasRendered = false;

        if (cached) {
            try {
                const userObj = JSON.parse(cached);
                updateTopbarUI(userObj.name, userObj.username);
                hasRendered = true;
            } catch (e) {}
        }

        const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
        let payloadData = null;

        if (token) {
            try {
                const base64Url = token.split('.')[1];
                const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
                const jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
                    return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
                }).join(''));
                payloadData = JSON.parse(jsonPayload);

                globalCurrentUserId = payloadData.user_id || payloadData.id || 0;

                if (!hasRendered && payloadData) {
                    const tName = payloadData.name || payloadData.full_name || 'Người dùng';
                    const tUsername = payloadData.username || '---';
                    updateTopbarUI(tName, tUsername);
                }
            } catch (err) {}
        }

        async function syncUserInfoFromServer() {
            if (!token || !payloadData) return;
            const currentUserId = payloadData.user_id || payloadData.id || 0;
            globalCurrentUserId = currentUserId;

            try {
                const targetUrl = `${API_URL}?detect=get_users`;
                let response;

                if (typeof fetchWithAuth === 'function') {
                    response = await fetchWithAuth(targetUrl, {
                        method: 'GET'
                    });
                } else {
                    response = await fetch(targetUrl, {
                        method: 'GET',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json'
                        }
                    });
                }

                const result = await response.json();

                if (response.ok && result.status === 'success' && Array.isArray(result.data)) {
                    const currentUser = result.data.find(u => parseInt(u.user_id) === parseInt(currentUserId));
                    if (currentUser) {
                        const freshData = {
                            name: currentUser.name || "Người dùng",
                            username: currentUser.username || "---"
                        };
                        sessionStorage.setItem(CACHE_USER_KEY, JSON.stringify(freshData));
                        updateTopbarUI(freshData.name, freshData.username);
                    }
                }
            } catch (e) {
                console.error("Lỗi đồng bộ topbar:", e);
            }
        }

        if (document.readyState === 'complete') {
            syncUserInfoFromServer();
        } else {
            window.addEventListener('load', syncUserInfoFromServer);
        }
    })();
</script>