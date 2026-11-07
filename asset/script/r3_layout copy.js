let currentProfile = {
    userId: '',
    name: '',
    avatar: ''
};

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainWrapper = document.getElementById('mainWrapper');
    const toggleBtn = document.getElementById('toggleBtn');

    if (sidebar && mainWrapper) {
        sidebar.classList.toggle('collapsed');
        mainWrapper.classList.toggle('expanded');

        if (toggleBtn) {
            const icon = toggleBtn.querySelector('i');
            if (icon) {
                icon.className = sidebar.classList.contains('collapsed') ?
                    'fa-solid fa-chevron-right' :
                    'fa-solid fa-chevron-left';
            }
        }
    }
}

// 1. Tải thông tin Profile (Đã bổ sung tự động truyền user_id vào Query String)
async function loadUserProfile() {
    try {
        // Lấy user_id từ biến currentProfile HOẶC gọi hàm getUserIdFromToken() trong auth_check.js
        const userId = currentProfile.userId || (typeof getUserIdFromToken === 'function' ? getUserIdFromToken() : null);

        let apiUrl = '../Controller/api/get_profile.php';
        if (userId) {
            apiUrl += `?user_id=${encodeURIComponent(userId)}`;
        }

        const response = await fetchWithAuth(apiUrl, {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        });

        const result = await response.json();

        if (response.ok && result.status === 'success') {
            const user = result.data;
            currentProfile.userId = user.user_id;
            currentProfile.name = user.name;
            currentProfile.avatar = user.avatar;

            const nameEl = document.getElementById('topbarUserName');
            if (nameEl) nameEl.innerText = user.name || 'N/A';

            const topbarImg = document.getElementById('topbarAvatarImg');
            const topbarText = document.getElementById('topbarAvatarText');

            if (user.avatar && user.avatar.trim() !== '') {
                if (topbarImg) {
                    topbarImg.src = user.avatar;
                    topbarImg.style.display = 'block';
                }
                if (topbarText) topbarText.style.display = 'none';
            } else {
                if (topbarImg) topbarImg.style.display = 'none';
                if (topbarText) {
                    const firstLetter = user.name ? user.name.trim().charAt(0).toUpperCase() : 'U';
                    topbarText.innerText = firstLetter;
                    topbarText.style.display = 'inline-block';
                }
            }

            const editNameInput = document.getElementById('editUserName');
            if (editNameInput) editNameInput.value = user.name;
        } else {
            console.warn("Lỗi tải profile:", result.message);
        }
    } catch (err) {
        console.error("Lỗi kết nối API get_profile:", err);
    }
}

function openProfileModal() {
    const editNameInput = document.getElementById('editUserName');
    const editAvatarInput = document.getElementById('editUserAvatar');

    const topbarEl = document.getElementById('topbarUserName');
    if (editNameInput) {
        editNameInput.value = currentProfile.name || (topbarEl ? topbarEl.innerText : '') || '';
    }
    if (editAvatarInput) editAvatarInput.value = '';

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
            const topbarEl = document.getElementById('topbarUserName');
            const currentName = currentProfile.name || (topbarEl ? topbarEl.innerText : '') || 'U';
            previewText.innerText = currentName.trim().charAt(0).toUpperCase();
        }
    }

    const modalEl = document.getElementById('userProfileModal');
    if (modalEl) {
        const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
        modalInstance.show();
    }
}

function previewAvatarImage(input) {
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
}

// 2. Cập nhật Profile (Dùng FormData - Đảm bảo có user_id)
async function handleUpdateProfile(event) {
    event.preventDefault();

    const newName = document.getElementById('editUserName').value.trim();
    const avatarFileInput = document.getElementById('editUserAvatar');
    const btnSave = document.getElementById('btnSaveProfile');

    if (!newName) {
        alert("Vui lòng nhập họ và tên!");
        return;
    }

    const userId = currentProfile.userId || (typeof getUserIdFromToken === 'function' ? getUserIdFromToken() : '');

    const formData = new FormData();
    formData.append('user_id', userId);
    formData.append('name', newName);

    if (avatarFileInput && avatarFileInput.files.length > 0) {
        formData.append('avatar', avatarFileInput.files[0]);
    }

    try {
        if (btnSave) {
            btnSave.disabled = true;
            btnSave.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> Đang lưu...`;
        }

        const response = await fetchWithAuth('../Controller/api/update_profile.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.status === 'success') {
            alert(result.message || "Cập nhật hồ sơ thành công!");

            currentProfile.name = result.name || newName;
            const topbarNameEl = document.getElementById('topbarUserName');
            if (topbarNameEl) topbarNameEl.innerText = currentProfile.name;

            if (result.avatarUrl) {
                currentProfile.avatar = result.avatarUrl;
                const topbarImg = document.getElementById('topbarAvatarImg');
                const topbarText = document.getElementById('topbarAvatarText');

                if (topbarImg) {
                    topbarImg.src = result.avatarUrl;
                    topbarImg.style.display = 'block';
                }
                if (topbarText) topbarText.style.display = 'none';
            }

            const modalEl = document.getElementById('userProfileModal');
            if (modalEl) {
                const modalInstance = bootstrap.Modal.getInstance(modalEl);
                if (modalInstance) modalInstance.hide();
            }
        } else {
            alert("Lỗi: " + (result.message || "Không thể cập nhật!"));
        }
    } catch (err) {
        console.error("Lỗi cập nhật profile:", err);
        alert("Có lỗi kết nối đến máy chủ API!");
    } finally {
        if (btnSave) {
            btnSave.disabled = false;
            btnSave.innerHTML = `<i class="fa-solid fa-floppy-disk"></i> Lưu thông tin`;
        }
    }
}

// 3. Lấy danh sách thông báo
async function loadNotifications() {
    try {
        const response = await fetchWithAuth('../Controller/api/get_notifications.php', {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        });
        const data = await response.json();

        if (response.ok && data.status === 'success') {
            const listEl = document.getElementById('notificationList');
            const countEl = document.getElementById('notificationCount');

            if (countEl) {
                countEl.innerText = data.unreadCount || 0;
                countEl.style.display = data.unreadCount > 0 ? 'inline-block' : 'none';
            }

            if (listEl) {
                if (!data.notifications || data.notifications.length === 0) {
                    listEl.innerHTML = `<div class="p-3 text-center text-muted fs-7">Không có thông báo mới.</div>`;
                    return;
                }

                listEl.innerHTML = data.notifications.map(item => {
                    const isUnread = item.is_read == 0;
                    const bgStyle = isUnread ? 'bg-primary-subtle fw-semibold' : 'bg-white text-muted';
                    const unreadDot = isUnread ? '<span class="badge bg-primary p-1 rounded-circle me-1"> </span>' : '';

                    return `
                        <a href="javascript:void(0)" 
                           onclick="handleNotificationClick(${item.id}, '${item.project_id || ''}')"
                           class="list-group-item list-group-item-action p-3 border-bottom ${bgStyle}">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <strong class="fs-7 text-dark">${unreadDot}${item.title}</strong>
                                <small class="text-muted fs-7" style="font-size:0.75rem;">${item.created_at}</small>
                            </div>
                            <p class="mb-0 fs-7 text-secondary fw-normal">${item.content}</p>
                        </a>
                    `;
                }).join('');
            }
        }
    } catch (err) {
        console.error("Lỗi nạp thông báo:", err);
    }
}

// 4. Đánh dấu đã đọc thông báo
async function handleNotificationClick(notiId, projectId) {
    try {
        await fetchWithAuth('../Controller/api/read_notification.php', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: notiId })
        });

        loadNotifications();

        if (projectId) {
            window.location.href = `r3_project_update.php?id=${projectId}`;
        }
    } catch (err) {
        console.error("Lỗi cập nhật đã đọc:", err);
    }
}

// 5. Đánh dấu tất cả là đã đọc
async function markAllAsRead() {
    try {
        const response = await fetchWithAuth('../Controller/api/mark_notifications_read.php', {
            method: 'POST',
            headers: {
                'Accept': 'application/json'
            }
        });
        const result = await response.json();

        if (response.ok && result.status === 'success') {
            const countEl = document.getElementById('notificationCount');
            if (countEl) {
                countEl.innerText = '0';
                countEl.style.display = 'none';
            }
            loadNotifications();
        }
    } catch (err) {
        console.error("Lỗi markAllAsRead:", err);
    }
}

// 6. Khởi tạo & Lắng nghe sự kiện SSE với Token đồng nhất
document.addEventListener("DOMContentLoaded", function() {
    loadUserProfile();

    if (typeof fetchProjects === 'function') {
        fetchProjects();
    }

    loadNotifications();

    // Trích xuất Token từ sessionStorage
    const token = sessionStorage.getItem('access_token') || '';
    if (!token) {
        console.warn("⚠️ Không tìm thấy access_token trong sessionStorage.");
        return;
    }

    // Kết nối SSE qua Query Parameter cho Backend đọc
    const sseUrl = `../Controller/api/stream_notifications.php?token=${encodeURIComponent(token)}`;
    const eventSource = new EventSource(sseUrl);

    eventSource.onmessage = function(event) {
        try {
            const data = JSON.parse(event.data);
            const countEl = document.getElementById('notificationCount');

            if (countEl && typeof data.unread_count !== 'undefined') {
                countEl.innerText = data.unread_count;
                countEl.style.display = data.unread_count > 0 ? 'inline-block' : 'none';
            }
            loadNotifications();
        } catch (e) {
            console.error("Lỗi giải mã JSON SSE:", e);
        }
    };

    eventSource.onerror = function(err) {
        console.warn("⚠️ Mất kết nối SSE Server, đang tự động kết nối lại...", err);
    };
});