<!-- TOPBAR DÙNG CHUNG PHÂN HỆ R3 -->
<style>
    /* CSS CÔ LẬP TRONG PHẠM VI TOPBAR (Scope: #r3TopbarHeader) */
    #r3TopbarHeader.top-header {
        background: #ffffff !important;
        border-bottom: 1px solid #e2e8f0 !important;
        padding: 12px 24px 12px 45px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        position: relative !important;
        z-index: 100 !important;
        box-sizing: border-box !important;
        width: 100% !important;
        min-height: 60px !important;
    }

    #r3TopbarHeader .page-title {
        font-size: 1.05rem !important;
        font-weight: 700 !important;
        color: #0f172a !important;
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
        margin: 0 !important;
    }

    #r3TopbarHeader .topbar-right {
        display: flex !important;
        align-items: center !important;
        gap: 16px !important;
    }

    #r3TopbarHeader .user-profile {
        display: flex !important;
        align-items: center !important;
        gap: 10px !important;
        padding-left: 12px !important;
        border-left: 1px solid #e2e8f0 !important;
        cursor: pointer !important;
        user-select: none !important;
    }

    #r3TopbarHeader .avatar {
        width: 36px !important;
        height: 36px !important;
        background: #2563eb !important;
        color: #ffffff !important;
        border-radius: 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-weight: 700 !important;
        font-size: 0.9rem !important;
        overflow: hidden !important;
        flex-shrink: 0 !important;
    }

    #r3TopbarHeader .avatar img {
        width: 100% !important;
        height: 100% !important;
        border-radius: 50% !important;
        object-fit: cover !important;
    }

    #r3TopbarHeader .dropdown-menu {
        z-index: 1055 !important;
    }

    #r3TopbarHeader #topbarUserName {
        font-size: 0.85rem !important;
        font-weight: 700 !important;
        color: #0f172a !important;
        max-width: 180px !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        line-height: 1.2 !important;
    }

    #r3TopbarHeader #topbarUserUnit {
        font-size: 0.72rem !important;
        color: #64748b !important;
        max-width: 180px !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        line-height: 1.2 !important;
    }
</style>

<header class="top-header" id="r3TopbarHeader">
    <div class="page-title">
        <i class="<?= $pageIcon ?? 'fa-solid fa-list-check' ?>" style="color: #2563eb;"></i> 
        <span><?= $pageTitle ?? 'QUẢN LÝ ĐỀ TÀI NGHIÊN CỨU' ?></span>
    </div>
    <div class="topbar-right">
        <!-- Nút Trợ giúp & Hướng dẫn: Mở modal #helpModal trong r3_modal_topbar.php -->
        <button class="btn btn-outline-secondary btn-sm rounded-circle p-2" type="button" data-bs-toggle="modal" data-bs-target="#helpModal" id="btnHelpGuide" title="Trợ giúp & Hướng dẫn">
            <i class="fa-solid fa-circle-question fs-5"></i>
        </button>

        <!-- Chuông thông báo -->
        <div class="dropdown">
            <button class="btn btn-light position-relative rounded-circle p-2" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="btnNotification">
                <i class="fa-solid fa-bell text-secondary fs-5"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notificationCount" style="display: none;">0</span>
            </button>
            <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 p-0" style="width: 340px; max-height: 420px; overflow-y: auto;">
                <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold">
                        <i class="fa-solid fa-bell me-2 text-primary"></i> Thông báo
                    </h6>
                    <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none fs-7 text-primary fw-semibold" onclick="window.markAllAsRead()">
                        Đánh dấu đã đọc
                    </button>
                </div>
                <div id="notificationList" class="list-group list-group-flush">
                    <div class="p-3 text-center text-muted fs-7">
                        <i class="fa-solid fa-spinner fa-spin me-1"></i> Đang tải thông báo...
                    </div>
                </div>
            </div>
        </div>

        <!-- Khối Thông tin cá nhân: Mở modal #userProfileModal trong r3_modal_topbar.php -->
        <div class="user-profile" title="Nhấn để cập nhật hồ sơ cá nhân" onclick="window.openProfileModal()">
            <div class="avatar" id="topbarAvatarContainer">
                <span id="topbarAvatarText">U</span>
                <img id="topbarAvatarImg" src="" style="display: none;">
            </div>
            <div>
                <div id="topbarUserName">
                    <script>
                        (function() {
                            try {
                                const cached = JSON.parse(sessionStorage.getItem('cached_current_user_profile') || '{}');
                                document.write(cached.name || 'Người dùng');
                            } catch (e) {
                                document.write('Người dùng');
                            }
                        })();
                    </script>
                </div>
                <div id="topbarUserUnit">
                    <script>
                        (function() {
                            try {
                                const cached = JSON.parse(sessionStorage.getItem('cached_current_user_profile') || '{}');
                                document.write(cached.unit_name || '');
                            } catch (e) {}
                        })();
                    </script>
                </div>
            </div>
            <i class="fa-solid fa-pen-to-square ms-1" style="font-size: 0.8rem; color: #2563eb;" title="Chỉnh sửa"></i>
        </div>
    </div>
</header>

<script>
    (function() {
        const API_URL = '/QLNCKH/index_api.php';
        const CACHE_PROFILE_KEY = 'cached_current_user_profile';
        const CACHE_UNREAD_KEY = 'cached_unread_notifications_count';
        let sseReconnectTimer = null;

        if (typeof window.fetchWithAuth !== 'function') {
            window.fetchWithAuth = async function(url, options = {}) {
                const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
                options.headers = options.headers || {};
                if (token) {
                    if (options.headers instanceof Headers) {
                        options.headers.set('Authorization', `Bearer ${token}`);
                    } else {
                        options.headers['Authorization'] = `Bearer ${token}`;
                    }
                }
                return fetch(url, options);
            };
        }

        const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
        if (!token) return;

        let currentUserId = 0;
        let tokenUsername = '';
        try {
            const base64Url = token.split('.')[1];
            const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
            const payload = JSON.parse(decodeURIComponent(atob(base64).split('').map(c => '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2)).join('')));
            currentUserId = parseInt(payload.user_id || payload.id || 0);
            tokenUsername = payload.username || '';
        } catch (e) {}

        function escapeHtml(str) {
            if (!str) return '';
            return String(str).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
        }

        function resolveAvatarPath(rawPath) {
            if (!rawPath) return '';
            if (rawPath.startsWith('http://') || rawPath.startsWith('https://')) return rawPath;
            if (rawPath.startsWith('/QLNCKH/')) return rawPath;
            const cleanPath = rawPath.replace(/^(\.\.\/)+/, '').replace(/^\.\//, '').replace(/^asset\//, '');
            return '/QLNCKH/asset/' + cleanPath;
        }

        function updateProfileUI(name, avatar, unitName = '') {
            const nameEl = document.getElementById('topbarUserName');
            const unitEl = document.getElementById('topbarUserUnit');
            const topbarImg = document.getElementById('topbarAvatarImg');
            const topbarText = document.getElementById('topbarAvatarText');

            if (nameEl && name) nameEl.textContent = name;
            if (unitEl) unitEl.textContent = unitName || '';

            if (topbarImg && topbarText) {
                const resolvedAvatar = resolveAvatarPath(avatar);
                if (resolvedAvatar && resolvedAvatar.trim() !== '') {
                    topbarImg.src = resolvedAvatar;
                    topbarImg.style.display = 'block';
                    topbarText.style.display = 'none';
                } else {
                    topbarImg.style.display = 'none';
                    topbarText.textContent = name ? name.trim().charAt(0).toUpperCase() : 'U';
                    topbarText.style.display = 'inline-block';
                }
            }
        }

        function updateNotificationBadgeUI(count) {
            const countEl = document.getElementById('notificationCount');
            if (!countEl) return;
            const unread = parseInt(count) || 0;
            if (unread > 0) {
                countEl.textContent = unread > 99 ? '99+' : unread;
                countEl.style.display = 'inline-block';
            } else {
                countEl.style.display = 'none';
            }
        }

        const cachedProfile = sessionStorage.getItem(CACHE_PROFILE_KEY);
        if (cachedProfile) {
            try {
                const parsed = JSON.parse(cachedProfile);
                updateProfileUI(parsed.name, parsed.avatar, parsed.unit_name);
            } catch (e) {}
        } else if (tokenUsername) {
            updateProfileUI(tokenUsername, '', '');
        }

        const cachedUnread = sessionStorage.getItem(CACHE_UNREAD_KEY);
        if (cachedUnread !== null) {
            updateNotificationBadgeUI(cachedUnread);
        }

        async function syncUserProfile() {
            if (!currentUserId) return;
            try {
                let res = await fetchWithAuth(`${API_URL}?detect=get_user&user_id=${currentUserId}`).then(r => r.json());
                if (!res || res.status !== 'success' || !res.data) {
                    res = await fetchWithAuth(`${API_URL}?detect=get_profile&user_id=${currentUserId}`).then(r => r.json());
                }

                if (res && res.status === 'success' && res.data) {
                    const u = res.data;
                    const freshUser = {
                        name: u.name || tokenUsername,
                        avatar: u.avatar || "",
                        unit_id: u.unit_id || "",
                        unit_name: u.unit_name || (u.unit_code ? `Khoa / Đơn vị ${u.unit_code}` : '')
                    };
                    sessionStorage.setItem(CACHE_PROFILE_KEY, JSON.stringify(freshUser));
                    updateProfileUI(freshUser.name, freshUser.avatar, freshUser.unit_name);
                }
            } catch (err) {}
        }

        async function loadNotifications() {
            const listEl = document.getElementById('notificationList');
            try {
                const response = await fetchWithAuth(`${API_URL}?detect=get_notifications`);
                const data = await response.json();

                if (response.ok && data.status === 'success') {
                    const unread = data.unread_count || 0;
                    sessionStorage.setItem(CACHE_UNREAD_KEY, unread);
                    updateNotificationBadgeUI(unread);

                    if (!listEl) return;

                    const notis = data.notifications || [];
                    if (notis.length === 0) {
                        listEl.innerHTML = `<div class="p-4 text-center text-muted fs-7">Không có thông báo nào.</div>`;
                        return;
                    }

                    listEl.innerHTML = notis.map(item => {
                        const isUnread = parseInt(item.is_read) === 0;
                        const bgStyle = isUnread ? 'bg-primary-subtle fw-semibold' : 'bg-white';
                        const unreadDot = isUnread ? '<span class="badge bg-danger p-1 rounded-circle me-1 d-inline-block" style="width: 7px; height: 7px;"> </span>' : '';

                        return `
                            <a href="javascript:void(0)" 
                               onclick="window.handleNotificationClick(${item.id}, '${item.project_id || ''}')"
                               class="list-group-item list-group-item-action p-3 border-bottom ${bgStyle}">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong class="fs-7 text-dark text-truncate me-2">${unreadDot}${escapeHtml(item.title)}</strong>
                                    <small class="text-muted flex-shrink-0" style="font-size:0.7rem;">${item.created_at}</small>
                                </div>
                                <p class="mb-0 fs-7 text-secondary fw-normal">${escapeHtml(item.content)}</p>
                            </a>
                        `;
                    }).join('');
                }
            } catch (err) {
                console.error("Lỗi tải thông báo:", err);
            }
        }

        window.handleNotificationClick = async function(notiId, projectId) {
            try {
                await fetchWithAuth(`${API_URL}?detect=read_notification`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: notiId })
                });
                loadNotifications();
                if (projectId && projectId !== '0') {
                    window.location.href = `/QLNCKH/chi-tiet-de-tai?id=${encodeURIComponent(projectId)}`;
                }
            } catch (err) {}
        };

        window.markAllAsRead = async function() {
            try {
                const response = await fetchWithAuth(`${API_URL}?detect=mark_notifications_read`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({})
                });
                const result = await response.json();
                if (response.ok && result.status === 'success') {
                    sessionStorage.setItem(CACHE_UNREAD_KEY, 0);
                    updateNotificationBadgeUI(0);
                    loadNotifications();
                }
            } catch (err) {}
        };

        function initRealtimeSSE() {
            if (window.topbarEventSource) {
                window.topbarEventSource.close();
                window.topbarEventSource = null;
            }

            const sseUrl = `${API_URL}?detect=stream_notifications&token=${encodeURIComponent(token)}`;
            window.topbarEventSource = new EventSource(sseUrl);

            window.topbarEventSource.onmessage = function(event) {
                try {
                    const data = JSON.parse(event.data);
                    if (typeof data.unread_count !== 'undefined') {
                        sessionStorage.setItem(CACHE_UNREAD_KEY, data.unread_count);
                        updateNotificationBadgeUI(data.unread_count);
                        loadNotifications();
                    }
                } catch (e) {}
            };

            window.topbarEventSource.onerror = function() {
                if (window.topbarEventSource) {
                    window.topbarEventSource.close();
                    window.topbarEventSource = null;
                }
                if (!sseReconnectTimer) {
                    sseReconnectTimer = setTimeout(() => {
                        sseReconnectTimer = null;
                        if (document.visibilityState === 'visible') {
                            initRealtimeSSE();
                        }
                    }, 10000);
                }
            };
        }

        window.addEventListener('beforeunload', function() {
            if (window.topbarEventSource) {
                window.topbarEventSource.close();
                window.topbarEventSource = null;
            }
            if (sseReconnectTimer) {
                clearTimeout(sseReconnectTimer);
            }
        });

        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                if (window.topbarEventSource) {
                    window.topbarEventSource.close();
                    window.topbarEventSource = null;
                }
            } else {
                initRealtimeSSE();
            }
        });

        // Mở Modal Hồ sơ cá nhân (kết nối với modal trong r3_modal_topbar.php)
        window.openProfileModal = function() {
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
                    previewText.textContent = (editNameInput ? editNameInput.value : 'U').charAt(0).toUpperCase();
                }
            }

            const modalEl = document.getElementById('userProfileModal');
            if (modalEl) bootstrap.Modal.getOrCreateInstance(modalEl).show();
        };

        window.previewAvatarImage = function(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewImg = document.getElementById('profileAvatarPreview');
                    const previewText = document.getElementById('profileAvatarText');
                    if (previewImg && previewText) {
                        previewImg.src = e.target.result;
                        previewImg.style.display = 'block';
                        previewText.style.display = 'none';
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        };

        window.handleUpdateProfile = async function(event) {
            event.preventDefault();
            const newName = document.getElementById('editUserName').value.trim();
            const newUnitId = document.getElementById('editUserUnitId') ? document.getElementById('editUserUnitId').value : '';
            const newPassword = document.getElementById('editUserPassword') ? document.getElementById('editUserPassword').value.trim() : '';
            const avatarFileInput = document.getElementById('editUserAvatar');
            const btnSave = document.getElementById('btnSaveProfile');

            if (!newName) return;

            const formData = new FormData();
            formData.append('user_id', currentUserId);
            formData.append('name', newName);
            if (newUnitId !== '') {
                formData.append('unit_id', newUnitId);
            }
            if (newPassword) {
                formData.append('password', newPassword);
            }
            if (avatarFileInput && avatarFileInput.files.length > 0) {
                formData.append('avatar', avatarFileInput.files[0]);
            }

            try {
                if (btnSave) {
                    btnSave.disabled = true;
                    btnSave.innerHTML = `<span class="spinner-border spinner-border-sm me-1"></span> Đang lưu...`;
                }

                let response = await fetchWithAuth(`${API_URL}?detect=update_user_info`, {
                    method: 'POST',
                    body: formData
                });
                let result = await response.json();

                if (!response.ok || result.status !== 'success') {
                    response = await fetchWithAuth(`${API_URL}?detect=update_profile`, {
                        method: 'POST',
                        body: formData
                    });
                    result = await response.json();
                }

                if (response.ok && result.status === 'success') {
                    const selectEl = document.getElementById('editUserUnitId');
                    const unitName = (selectEl && selectEl.selectedIndex > 0) ? selectEl.options[selectEl.selectedIndex].text.replace(/^\[.*?\]\s*/, '') : '';

                    let cachedUser = {};
                    try {
                        cachedUser = JSON.parse(sessionStorage.getItem(CACHE_PROFILE_KEY) || '{}');
                    } catch (e) {}

                    const updatedAvatar = result.avatarUrl ?
                        result.avatarUrl :
                        (avatarFileInput.files[0] ? document.getElementById('profileAvatarPreview').src : (cachedUser.avatar || ''));

                    const freshData = {
                        name: newName,
                        avatar: updatedAvatar,
                        unit_id: newUnitId || '',
                        unit_name: unitName
                    };

                    sessionStorage.setItem(CACHE_PROFILE_KEY, JSON.stringify(freshData));
                    updateProfileUI(newName, updatedAvatar, unitName);

                    const modalEl = document.getElementById('userProfileModal');
                    if (modalEl) bootstrap.Modal.getInstance(modalEl).hide();

                    alert(result.message || "Cập nhật hồ sơ cá nhân thành công!");
                } else {
                    alert("Lỗi: " + (result.message || "Không thể cập nhật hồ sơ!"));
                }
            } catch (err) {
                console.error("Lỗi cập nhật profile:", err);
                alert("Lỗi kết nối máy chủ!");
            } finally {
                if (btnSave) {
                    btnSave.disabled = false;
                    btnSave.innerHTML = `<i class="fa-solid fa-floppy-disk me-1"></i> Lưu thông tin`;
                }
            }
        };

        window.addEventListener('load', function() {
            setTimeout(() => {
                syncUserProfile();
                loadNotifications();
                initRealtimeSSE();
            }, 500);
        });
    })();
</script>