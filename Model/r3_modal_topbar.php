<!-- MODAL CẬP NHẬT HỒ SƠ CÁ NHÂN DÙNG CHUNG -->
<div class="modal fade" id="userProfileModal" tabindex="-1" aria-labelledby="userProfileModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light py-3">
                <h5 class="modal-title fs-6 fw-bold text-dark d-flex align-items-center gap-2" id="userProfileModalLabel">
                    <i class="fa-solid fa-user-pen text-primary"></i> Cập nhật Thông tin Cá Nhân
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="userProfileForm" onsubmit="window.handleUpdateProfile(event)">
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block" style="cursor: pointer;" onclick="document.getElementById('editUserAvatar').click();" title="Nhấn để đổi ảnh đại diện">
                            <div class="rounded-circle overflow-hidden border border-2 border-primary d-flex align-items-center justify-content-center shadow-sm mx-auto" style="width: 100px; height: 100px; background-color: #2563eb;">
                                <span id="profileAvatarText" class="text-white fw-bold fs-2">U</span>
                                <img id="profileAvatarPreview" src="" class="w-100 h-100" style="object-fit: cover; display: none;">
                            </div>
                            <div class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center border border-2 border-white" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                <i class="fa-solid fa-camera"></i>
                            </div>
                        </div>
                        <div class="form-text fs-7 mt-2">Chạm hoặc click vào hình tròn để tải ảnh lên</div>
                    </div>
                    <input type="file" id="editUserAvatar" class="d-none" accept="image/*" onchange="window.previewAvatarImage(this)">
                    
                    <div class="mb-3">
                        <label for="editUserName" class="form-label small fw-semibold text-secondary">
                            Họ và tên <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="editUserName" class="form-control form-control-sm" required placeholder="Nhập họ và tên mới...">
                    </div>

                    <!-- Ô CHỌN KHOA / PHÒNG / BAN (GỌI API ORG_UNIT_GET NHƯ R1_USERS) -->
                    <div class="mb-3">
                        <label for="editUserUnitId" class="form-label small fw-semibold text-secondary">
                            Khoa / Phòng / Ban
                        </label>
                        <select id="editUserUnitId" name="unit_id" class="form-select form-select-sm">
                            <option value="">-- Chưa phân công / Trực thuộc trường --</option>
                        </select>
                    </div>

                    <div class="mb-0">
                        <label for="editUserPassword" class="form-label small fw-semibold text-secondary">
                            Mật khẩu mới
                        </label>
                        <input type="password" id="editUserPassword" class="form-control form-control-sm" placeholder="Để trống nếu không muốn đổi mật khẩu">
                    </div>
                </div>
                <div class="modal-footer bg-light py-2 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm fw-semibold" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-semibold d-flex align-items-center gap-1" id="btnSaveProfile">
                        <i class="fa-solid fa-floppy-disk"></i> Lưu thông tin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL TRỢ GIÚP & TRA CỨU VĂN BẢN HƯỚNG DẪN TỪ CSDL TBL_GUIDES -->
<div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white py-3 px-4">
                <h5 class="modal-title fs-6 fw-bold" id="helpModalLabel">
                    <i class="fa-solid fa-circle-question me-2"></i> Văn Bản Hướng Dẫn & Biểu Mẫu Nghiên Cứu Khoa Học
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="row g-3">
                    <!-- Cột 1: Danh sách bài hướng dẫn -->
                    <div class="col-md-5 border-end">
                        <div class="input-group input-group-sm mb-2">
                            <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                            <input type="text" class="form-control" id="searchGuideTopbarInput" placeholder="Tìm tên văn bản, hướng dẫn..." oninput="window.handleSearchTopbarGuide()">
                        </div>
                        <div id="guideTopbarListGroup" style="max-height: 60vh; overflow-y: auto;">
                            <div class="text-center text-muted py-4"><i class="fa-solid fa-spinner fa-spin me-2"></i>Đang tải dữ liệu hướng dẫn...</div>
                        </div>
                    </div>

                    <!-- Cột 2: Nội dung chi tiết bài hướng dẫn -->
                    <div class="col-md-7">
                        <div class="p-3 bg-light border rounded h-100" id="guideTopbarDetailBox" style="min-height: 60vh; max-height: 60vh; overflow-y: auto;">
                            <div class="text-center text-muted my-5">
                                <i class="fa-solid fa-book-open text-primary fs-1 mb-2"></i>
                                <div>Chọn một mục văn bản hướng dẫn bên trái để đọc nội dung chi tiết.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2 bg-light d-flex justify-content-between align-items-center">
                <div class="small text-secondary">
                    <i class="fa-solid fa-headset me-1 text-primary"></i> Hỗ trợ kỹ thuật: <b>(028) 38123456</b>
                </div>
                <button type="button" class="btn btn-secondary btn-sm px-4 fw-semibold" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<style>
    #helpModal,
    #userProfileModal {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        margin: 0 !important;
        padding: 0 !important;
        z-index: 1060 !important;
        background: rgba(30, 41, 59, 0.45) !important;
        backdrop-filter: blur(3px) !important;
        -webkit-backdrop-filter: blur(3px) !important;
    }

    #helpModal .modal-dialog,
    #userProfileModal .modal-dialog {
        margin-left: auto !important;
        margin-right: auto !important;
    }

    .topbar-guide-item {
        cursor: pointer;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px 12px;
        margin-bottom: 8px;
        transition: all 0.2s;
        background: #ffffff;
    }

    .topbar-guide-item:hover {
        border-color: #3b82f6;
        background-color: #f8fafc;
    }

    .topbar-guide-item.active {
        border-color: #2563eb;
        background-color: #eff6ff;
        border-left: 4px solid #2563eb;
    }

    .file-download-btn {
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .file-download-btn:hover {
        background-color: #eff6ff !important;
        border-color: #2563eb !important;
    }
</style>

<script>
(function() {
    const MODAL_API_URL = '/QLNCKH/index_api.php';
    let topbarGuidesData = [];
    let orgUnitsCache = [];
    let currentMyUnitId = '';

    // Giải mã lấy user_id hiện tại từ JWT
    function getMyUserId() {
        const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
        if (!token) return 0;
        try {
            const base64Url = token.split('.')[1];
            const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
            const payload = JSON.parse(decodeURIComponent(atob(base64).split('').map(c => '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2)).join('')));
            return parseInt(payload.user_id || payload.id || 0);
        } catch (e) {
            return 0;
        }
    }

    // Đảm bảo fetchWithAuth luôn sẵn sàng
    function callApiWithAuth(url, options = {}) {
        if (typeof window.fetchWithAuth === 'function') {
            return window.fetchWithAuth(url, options);
        }
        const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
        options.headers = options.headers || {};
        if (token) {
            options.headers['Authorization'] = `Bearer ${token}`;
        }
        return fetch(url, options);
    }

    function escapeModalHtml(str) {
        if (!str) return '';
        return String(str).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
    }

    // =========================================================================
    // 1. NẠP DANH SÁCH KHOA (org_unit_get) & KHOA HIỆN TẠI CỦA USER (get_users)
    //    GIỐNG HỆT CÁCH LÀM TRONG FILE R1_USERS.PHP
    // =========================================================================
    async function loadOrgUnitsForModal() {
        try {
            // Bước 1: Gọi org_unit_get để lấy toàn bộ danh sách Khoa/Phòng/Ban
            const res = await callApiWithAuth(`${MODAL_API_URL}?detect=org_unit_get`, { method: "GET" }).then(r => r.json());
            if (res && res.status === 'success' && Array.isArray(res.data)) {
                orgUnitsCache = res.data;
            }

            // Bước 2: Tìm unit_id hiện tại của tài khoản đang đăng nhập
            const myId = getMyUserId();
            if (myId > 0) {
                // Thử lấy từ get_users (như r1_users.php) hoặc get_user / cv_get
                let userFound = null;
                try {
                    const uListRes = await callApiWithAuth(`${MODAL_API_URL}?detect=get_users`, { method: "GET" }).then(r => r.json());
                    if (uListRes && uListRes.status === 'success' && Array.isArray(uListRes.data)) {
                        userFound = uListRes.data.find(u => parseInt(u.user_id || u.id) === myId);
                    }
                } catch (e) {}

                if (!userFound) {
                    try {
                        const singleRes = await callApiWithAuth(`${MODAL_API_URL}?detect=get_user&user_id=${myId}`, { method: "GET" }).then(r => r.json());
                        if (singleRes && singleRes.status === 'success' && singleRes.data) {
                            userFound = singleRes.data;
                        }
                    } catch (e) {}
                }

                if (userFound && userFound.unit_id !== undefined && userFound.unit_id !== null) {
                    currentMyUnitId = String(userFound.unit_id);
                    // Lưu luôn vào cache để đồng bộ
                    try {
                        const cached = JSON.parse(sessionStorage.getItem('cached_current_user_profile') || '{}');
                        cached.unit_id = currentMyUnitId;
                        if (userFound.unit_name) cached.unit_name = userFound.unit_name;
                        sessionStorage.setItem('cached_current_user_profile', JSON.stringify(cached));
                    } catch (e) {}
                }
            }

            // Bước 3: Đổ dữ liệu ra thẻ <select id="editUserUnitId">
            renderOrgUnitOptions(currentMyUnitId);
        } catch (err) {
            console.error("Lỗi nạp danh sách Khoa:", err);
        }
    }

    function renderOrgUnitOptions(selectedUnitId = '') {
        const selectEl = document.getElementById('editUserUnitId');
        if (!selectEl) return;

        let targetUnitId = selectedUnitId;
        if (!targetUnitId) {
            try {
                const cached = JSON.parse(sessionStorage.getItem('cached_current_user_profile') || '{}');
                targetUnitId = cached.unit_id || '';
            } catch (e) {}
        }

        let html = '<option value="">-- Chưa phân công / Trực thuộc trường --</option>';
        orgUnitsCache.forEach(unit => {
            const isSelected = String(unit.unit_id) === String(targetUnitId) ? 'selected' : '';
            html += `<option value="${unit.unit_id}" ${isSelected}>[${escapeModalHtml(unit.unit_code)}] ${escapeModalHtml(unit.unit_name)}</option>`;
        });
        selectEl.innerHTML = html;

        if (targetUnitId !== undefined && targetUnitId !== null && targetUnitId !== '') {
            selectEl.value = String(targetUnitId);
        } else {
            selectEl.value = '';
        }
    }

    // Ghi đè / Bổ sung vào hàm mở Modal Profile để luôn nạp và chọn đúng Khoa
    window.openProfileModal = async function() {
        const editNameInput = document.getElementById('editUserName');
        const editAvatarInput = document.getElementById('editUserAvatar');
        const editPasswordInput = document.getElementById('editUserPassword');
        const topbarNameEl = document.getElementById('topbarUserName');

        if (editNameInput && topbarNameEl) editNameInput.value = topbarNameEl.textContent.trim();
        if (editAvatarInput) editAvatarInput.value = '';
        if (editPasswordInput) editPasswordInput.value = '';

        const topbarImg = document.getElementById('topbarAvatarImg');
        const previewImg = document.getElementById('profileAvatarPreview');
        const previewText = document.getElementById('profileAvatarText');

        if (previewImg && previewText) {
            if (topbarImg && topbarImg.style.display !== 'none' && topbarImg.src) {
                previewImg.src = topbarImg.src;
                previewImg.style.display = 'block';
                previewText.style.display = 'none';
            } else {
                previewImg.style.display = 'none';
                previewText.style.display = 'inline-block';
                previewText.textContent = (editNameInput && editNameInput.value ? editNameInput.value : 'U').charAt(0).toUpperCase();
            }
        }

        // Nếu chưa có danh sách Khoa thì gọi API nạp ngay trước khi hiển thị
        if (orgUnitsCache.length === 0) {
            await loadOrgUnitsForModal();
        } else {
            renderOrgUnitOptions(currentMyUnitId);
        }

        const modalEl = document.getElementById('userProfileModal');
        if (modalEl) bootstrap.Modal.getOrCreateInstance(modalEl).show();
    };

    // Hàm lưu cập nhật thông tin cá nhân kèm Khoa (unit_id) như r1_users.php
    window.handleUpdateProfile = async function(event) {
        event.preventDefault();
        const myId = getMyUserId();
        const newName = document.getElementById('editUserName').value.trim();
        const unitId = document.getElementById('editUserUnitId').value;
        const newPassword = document.getElementById('editUserPassword') ? document.getElementById('editUserPassword').value.trim() : '';
        const avatarFileInput = document.getElementById('editUserAvatar');
        const btnSave = document.getElementById('btnSaveProfile');

        if (!newName) return;

        try {
            if (btnSave) {
                btnSave.disabled = true;
                btnSave.innerHTML = `<i class="fa-solid fa-spinner fa-spin me-1"></i> Đang lưu...`;
            }

            // 1. Cập nhật thông tin User & Khoa qua update_user_info (chuẩn JSON như r1_users.php)
            const infoRes = await callApiWithAuth(`${MODAL_API_URL}?detect=update_user_info`, {
                method: "POST",
                headers: {
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    user_id: parseInt(myId),
                    name: newName,
                    unit_id: unitId ? parseInt(unitId) : null,
                    password: newPassword ? newPassword : null
                })
            }).then(r => r.json()).catch(() => null);

            // 2. Cập nhật Avatar & Profile qua update_profile (FormData)
            const formData = new FormData();
            formData.append('user_id', myId);
            formData.append('name', newName);
            if (unitId) formData.append('unit_id', unitId);
            if (newPassword) formData.append('password', newPassword);
            if (avatarFileInput && avatarFileInput.files.length > 0) {
                formData.append('avatar', avatarFileInput.files[0]);
            }

            const profileRes = await callApiWithAuth(`${MODAL_API_URL}?detect=update_profile`, {
                method: 'POST',
                body: formData
            }).then(r => r.json()).catch(() => null);

            const isSuccess = (infoRes && infoRes.status === 'success') || (profileRes && profileRes.status === 'success');

            if (isSuccess) {
                currentMyUnitId = unitId ? String(unitId) : '';
                const matchedUnit = orgUnitsCache.find(u => String(u.unit_id) === currentMyUnitId);
                const unitName = matchedUnit ? matchedUnit.unit_name : '';

                let cachedProfile = {};
                try {
                    cachedProfile = JSON.parse(sessionStorage.getItem('cached_current_user_profile') || '{}');
                } catch (e) {}

                const updatedAvatar = (profileRes && profileRes.avatarUrl) ?
                    profileRes.avatarUrl.replace(/^\.\.\//, '') :
                    (avatarFileInput && avatarFileInput.files[0] ? document.getElementById('profileAvatarPreview').src : (cachedProfile.avatar || ''));

                sessionStorage.setItem('cached_current_user_profile', JSON.stringify({
                    name: newName,
                    avatar: updatedAvatar,
                    unit_id: currentMyUnitId,
                    unit_name: unitName
                }));

                // Cập nhật tên và Khoa trên thanh Topbar
                const topbarNameEl = document.getElementById('topbarUserName');
                if (topbarNameEl) topbarNameEl.textContent = newName;
                const topbarUnitEl = document.getElementById('topbarUserUnit');
                if (topbarUnitEl) topbarUnitEl.textContent = unitName;

                const modalEl = document.getElementById('userProfileModal');
                if (modalEl) bootstrap.Modal.getInstance(modalEl).hide();

                alert((infoRes && infoRes.message) || (profileRes && profileRes.message) || "Cập nhật thông tin thành công!");
            } else {
                const errMsg = (infoRes && infoRes.message) || (profileRes && profileRes.message) || "Không thể cập nhật!";
                alert("Lỗi: " + errMsg);
            }
        } catch (err) {
            console.error("Lỗi cập nhật:", err);
            alert("Lỗi kết nối máy chủ!");
        } finally {
            if (btnSave) {
                btnSave.disabled = false;
                btnSave.innerHTML = `<i class="fa-solid fa-floppy-disk"></i> Lưu thông tin`;
            }
        }
    };

    // =========================================================================
    // 2. GỌI API NẠP HƯỚNG DẪN (API CHUẨN: detect=get_guides)
    // =========================================================================
    async function fetchTopbarGuides() {
        const listGroup = document.getElementById('guideTopbarListGroup');
        try {
            const res = await callApiWithAuth(`${MODAL_API_URL}?detect=get_guides`, {
                method: 'GET',
                headers: { 'Accept': 'application/json' }
            });

            const result = await res.json();
            if (result.status === 'success' && Array.isArray(result.data)) {
                topbarGuidesData = result.data;
                renderTopbarGuideList(topbarGuidesData);
                if (topbarGuidesData.length > 0) {
                    window.fetchTopbarGuideDetail(topbarGuidesData[0].id);
                }
            } else {
                listGroup.innerHTML = `<div class="text-center text-muted py-4 small">Chưa có bài hướng dẫn nào trong hệ thống.</div>`;
            }
        } catch (err) {
            console.error("Lỗi tải hướng dẫn:", err);
            listGroup.innerHTML = `<div class="text-center text-danger py-4 small">Không thể kết nối đến API hướng dẫn.</div>`;
        }
    }

    function formatDateVi(dateStr) {
        if (!dateStr) return 'Hôm nay';
        try {
            const d = new Date(dateStr);
            if (isNaN(d.getTime())) return dateStr;
            const day = String(d.getDate()).padStart(2, '0');
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const year = d.getFullYear();
            const hours = String(d.getHours()).padStart(2, '0');
            const minutes = String(d.getMinutes()).padStart(2, '0');
            return `${day}/${month}/${year} ${hours}:${minutes}`;
        } catch (e) {
            return dateStr;
        }
    }

    function parseFilesData(raw) {
        if (!raw) return [];
        let data = raw;
        if (typeof data === 'string') {
            try {
                data = JSON.parse(data);
                if (typeof data === 'string') {
                    data = JSON.parse(data);
                }
            } catch (e) {
                return [data];
            }
        }
        if (!Array.isArray(data)) {
            data = [data];
        }
        return data;
    }

    function renderTopbarGuideList(list) {
        const listGroup = document.getElementById('guideTopbarListGroup');
        if (!listGroup) return;

        if (list.length === 0) {
            listGroup.innerHTML = `<div class="text-center text-muted py-4 small">Không tìm thấy tài liệu phù hợp.</div>`;
            return;
        }

        listGroup.innerHTML = list.map(item => {
            let fileCount = item.files_count !== undefined ? item.files_count : 0;
            if (fileCount === 0 && item.file_guides) {
                const parsed = parseFilesData(item.file_guides);
                fileCount = parsed.length;
            }

            const displayDate = item.created_date || item.created_at_formatted || formatDateVi(item.created_at || item.published_at);

            return `
            <div class="topbar-guide-item" id="topbar-guide-${item.id}" onclick="window.fetchTopbarGuideDetail(${item.id})">
                <div class="fw-semibold text-primary small mb-1">${escapeModalHtml(item.title || item.guide_title || 'Văn bản hướng dẫn')}</div>
                <div class="d-flex justify-content-between align-items-center text-muted" style="font-size: 0.72rem;">
                    <span><i class="fa-regular fa-clock me-1"></i>${displayDate}</span>
                    <span><i class="fa-solid fa-paperclip me-1"></i>${fileCount} tệp đính kèm</span>
                </div>
            </div>
            `;
        }).join('');
    }

    window.handleSearchTopbarGuide = function() {
        const keyword = document.getElementById('searchGuideTopbarInput').value.trim().toLowerCase();
        const filtered = topbarGuidesData.filter(g =>
            ((g.title || g.guide_title || '').toLowerCase().includes(keyword)) ||
            ((g.content || g.guide_content || '').toLowerCase().includes(keyword))
        );
        renderTopbarGuideList(filtered);
    };

    window.fetchTopbarGuideDetail = async function(id) {
        document.querySelectorAll('.topbar-guide-item').forEach(el => el.classList.remove('active'));
        const activeItem = document.getElementById(`topbar-guide-${id}`);
        if (activeItem) activeItem.classList.add('active');

        const detailBox = document.getElementById('guideTopbarDetailBox');
        detailBox.innerHTML = `<div class="text-center text-muted my-5"><i class="fa-solid fa-spinner fa-spin me-2"></i>Đang tải nội dung...</div>`;

        try {
            const res = await callApiWithAuth(`${MODAL_API_URL}?detect=get_guides&id=${id}`, {
                method: 'GET',
                headers: { 'Accept': 'application/json' }
            });

            const result = await res.json();
            if (result.status === 'success' && result.data) {
                const d = result.data;
                const files = parseFilesData(d.file_guides);
                const publishDate = d.created_at_formatted || formatDateVi(d.created_at || d.published_at);
                
                let filesHtml = '';
                if (files.length > 0) {
                    const links = files.map((f, idx) => {
                        let filePath = '';
                        let fileName = '';

                        if (typeof f === 'string') {
                            filePath = f;
                            fileName = f.split('/').pop().split('\\').pop();
                        } else if (typeof f === 'object' && f !== null) {
                            filePath = f.path || f.file_url || f.url || f.file || '';
                            fileName = f.name || f.file_name || f.original_name || f.filename || (filePath ? filePath.split('/').pop().split('\\').pop() : `Tài liệu đính kèm ${idx + 1}`);
                        }

                        if (!fileName || fileName.trim() === '') {
                            fileName = `Tài liệu hướng dẫn đính kèm ${idx + 1}`;
                        }

                        let downloadUrl = filePath;
                        if (downloadUrl && !downloadUrl.startsWith('http') && !downloadUrl.startsWith('/QLNCKH/')) {
                            downloadUrl = '/QLNCKH/' + downloadUrl.replace(/^\.\.\//, '').replace(/^\.\//, '');
                        }

                        return `
                            <a href="${downloadUrl}" download="${escapeModalHtml(fileName)}" class="file-download-btn btn btn-sm btn-outline-primary text-start d-flex align-items-center justify-content-between p-2">
                                <span class="text-truncate me-2">
                                    <i class="fa-solid fa-file-arrow-down me-2 text-primary"></i>
                                    <span class="fw-medium text-dark">${escapeModalHtml(fileName)}</span>
                                </span>
                                <i class="fa-solid fa-download flex-shrink-0 text-primary"></i>
                            </a>
                        `;
                    }).join('');

                    filesHtml = `
                        <div class="p-3 bg-white rounded border">
                            <div class="fw-bold small text-secondary mb-2"><i class="fa-solid fa-paperclip me-1"></i>Tài liệu đính kèm tải về:</div>
                            <div class="d-flex flex-column gap-2">${links}</div>
                        </div>
                    `;
                }

                detailBox.innerHTML = `
                    <div class="mb-3 border-bottom pb-2">
                        <h5 class="fw-bold text-primary mb-1">${escapeModalHtml(d.title || d.guide_title || '')}</h5>
                        <small class="text-muted"><i class="fa-regular fa-calendar-check me-1"></i>Ngày đăng: <span class="fw-semibold text-dark">${publishDate}</span></small>
                    </div>
                    <div class="guide-body text-dark mb-4 bg-white p-3 rounded border" style="line-height: 1.6; font-size: 0.95rem;">
                        ${d.content || d.guide_content || '<em class="text-muted">Chưa có nội dung văn bản.</em>'}
                    </div>
                    ${filesHtml}
                `;
            } else {
                detailBox.innerHTML = `<div class="text-danger text-center my-5">Không tìm thấy thông tin văn bản này.</div>`;
            }
        } catch (err) {
            console.error("Lỗi lấy chi tiết hướng dẫn:", err);
            detailBox.innerHTML = `<div class="text-danger text-center my-5">Lỗi kết nối khi nạp nội dung bài hướng dẫn.</div>`;
        }
    };

    // Khởi chạy ngay khi DOM sẵn sàng (Giống hệt r1_users.php)
    document.addEventListener("DOMContentLoaded", function() {
        const modals = ['helpModal', 'userProfileModal'];
        modals.forEach(id => {
            const el = document.getElementById(id);
            if (el && el.parentElement !== document.body) {
                document.body.appendChild(el);
            }
        });

        // 1. Nạp ngay danh sách Khoa từ org_unit_get và unit_id của user từ get_users
        loadOrgUnitsForModal();

        // 2. Khi mở modal hướng dẫn thì nạp danh sách hướng dẫn
        const helpModalEl = document.getElementById('helpModal');
        if (helpModalEl) {
            helpModalEl.addEventListener('show.bs.modal', function() {
                fetchTopbarGuides();
            });
        }

        // 3. Khi mở modal profile thì render lại option Khoa đã chọn
        const userProfileModalEl = document.getElementById('userProfileModal');
        if (userProfileModalEl) {
            userProfileModalEl.addEventListener('show.bs.modal', function() {
                renderOrgUnitOptions(currentMyUnitId);
            });
        }
    });
})();
</script>