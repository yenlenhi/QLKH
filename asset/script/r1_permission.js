const API_URL = "/QLNCKH/index_api.php";

let toastInstance = null;
let confirmModalInstance = null;
let pendingConfirmCallback = null;

let orgUnitsCache = [];

// =========================================================================
// 1. QUẢN LÝ DANH MỤC KHOA / PHÒNG / BAN (TBL_ORG_UNIT)
// =========================================================================

// Tải danh sách đơn vị từ API org_unit_get
async function loadOrgUnits() {
    try {
        const res = await fetchWithAuth(`${API_URL}?detect=org_unit_get`, {
            method: 'GET'
        }).then(r => r.json());

        if (res.status === 'success' && Array.isArray(res.data)) {
            orgUnitsCache = res.data;
            renderOrgUnitOptions();
        }
    } catch (err) {
        console.error("Lỗi nạp danh sách Khoa:", err);
    }
}

// Render các thẻ option vào select box #updateUnitId
function renderOrgUnitOptions(selectedUnitId = '') {
    const selectEl = document.getElementById('updateUnitId');
    if (!selectEl) return;

    let html = '<option value="">-- Chưa phân công / Trực thuộc trường --</option>';
    orgUnitsCache.forEach(unit => {
        const isSelected = String(unit.unit_id) === String(selectedUnitId) ? 'selected' : '';
        html += `<option value="${unit.unit_id}" ${isSelected}>[${unit.unit_code}] ${unit.unit_name}</option>`;
    });
    selectEl.innerHTML = html;

    // Gán lại giá trị trực tiếp để đảm bảo chọn đúng
    if (selectedUnitId !== undefined && selectedUnitId !== null && selectedUnitId !== '') {
        selectEl.value = String(selectedUnitId);
    } else {
        selectEl.value = '';
    }
}

// Lưu cập nhật thông tin tài khoản (Họ tên, Khoa, Mật khẩu mới)
async function saveUserUpdate() {
    const userId = document.getElementById('updateUserId').value;
    const name = document.getElementById('updateName').value.trim();
    const unitId = document.getElementById('updateUnitId').value;
    const password = document.getElementById('updatePassword').value.trim();

    if (!name) {
        showNotification("Vui lòng nhập Họ và Tên!", "warning");
        return;
    }

    const payload = {
        detect: 'update_user_info',
        user_id: parseInt(userId),
        name: name,
        unit_id: unitId ? parseInt(unitId) : null,
        password: password ? password : null
    };

    try {
        const res = await fetchWithAuth(API_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        }).then(r => r.json());

        if (res.status === 'success') {
            showNotification(res.message || "Cập nhật thông tin tài khoản thành công!", "success");
            const modalEl = document.getElementById('modalCapNhat');
            const modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) modalInstance.hide();

            // Tải lại danh sách tài khoản
            loadUserList();
        } else {
            showNotification(res.message || "Cập nhật thất bại!", "danger");
        }
    } catch (err) {
        console.error("Lỗi cập nhật user:", err);
        showNotification("Lỗi kết nối máy chủ!", "danger");
    }
}

// =========================================================================
// 2. CÁC HÀM TIỆN ÍCH THÔNG BÁO & DIALOG XÁC NHẬN
// =========================================================================

// Hàm hiển thị thông báo giao diện đẹp thay thế alert()
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
    } else if (type === "warning") {
        toastEl.classList.add('bg-warning', 'text-dark');
        toastIcon.className = 'fa-solid fa-triangle-exclamation me-2 fs-5';
    } else {
        toastEl.classList.add('bg-primary');
        toastIcon.className = 'fa-solid fa-circle-info me-2 fs-5';
    }

    toastMsg.textContent = message;
    if (!toastInstance) {
        toastInstance = new bootstrap.Toast(toastEl, { delay: 3500 });
    }
    toastInstance.show();
}

// Modal popup thay thế confirm()
function showConfirmDialog(title, message, isDanger, onConfirm) {
    const titleEl = document.getElementById('confirmTitle');
    const msgEl = document.getElementById('confirmMessage');
    const iconEl = document.getElementById('confirmIcon');
    const btnConfirm = document.getElementById('btnConfirmExecute');

    if (titleEl) titleEl.textContent = title;
    if (msgEl) msgEl.textContent = message;

    if (isDanger) {
        if (iconEl) iconEl.innerHTML = `<i class="fa-solid fa-trash-can text-danger fa-3x"></i>`;
        if (btnConfirm) btnConfirm.className = "btn btn-danger btn-sm px-3";
    } else {
        if (iconEl) iconEl.innerHTML = `<i class="fa-solid fa-circle-exclamation text-warning fa-3x"></i>`;
        if (btnConfirm) btnConfirm.className = "btn btn-warning btn-sm px-3 text-dark fw-bold";
    }

    pendingConfirmCallback = onConfirm;
    const modalEl = document.getElementById('modalConfirmAction');
    if (modalEl) {
        if (!confirmModalInstance) {
            confirmModalInstance = new bootstrap.Modal(modalEl);
        }
        confirmModalInstance.show();
    }
}

// =========================================================================
// 3. KHỞI TẠO VÀ BẮT SỰ KIỆN KHI TẢI TRANG
// =========================================================================

document.addEventListener("DOMContentLoaded", async function() {
    // Tải trước danh mục đơn vị Khoa / Phòng
    await loadOrgUnits();

    // Tải danh sách người dùng
    loadUserList();

    const btnExecute = document.getElementById('btnConfirmExecute');
    if (btnExecute) {
        btnExecute.addEventListener('click', function() {
            if (typeof pendingConfirmCallback === 'function') {
                pendingConfirmCallback();
            }
            if (confirmModalInstance) {
                confirmModalInstance.hide();
            }
        });
    }

    // Modal Phân quyền
    const modalPhanQuyen = document.getElementById('modalPhanQuyen');
    if (modalPhanQuyen) {
        modalPhanQuyen.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const userId = button.getAttribute('data-id');
            const username = button.getAttribute('data-user');
            const modalTitleUsername = modalPhanQuyen.querySelector('#modalUsername');

            if (modalTitleUsername) modalTitleUsername.textContent = username;
            document.getElementById('permUserId').value = userId;

            loadPermissionList(userId);

            const btnSave = document.getElementById('btnSavePermission');
            if (btnSave) {
                btnSave.disabled = false;
                btnSave.innerHTML = `<i class="fa-solid fa-floppy-disk me-1"></i>Lưu Thay Đổi`;
            }
        });
    }

    // Modal Cập nhật thông tin tài khoản: Nạp dữ liệu cũ (kèm Khoa cũ)
    const modalCapNhat = document.getElementById('modalCapNhat');
    if (modalCapNhat) {
        modalCapNhat.addEventListener('show.bs.modal', async function(event) {
            const button = event.relatedTarget;
            const userId = button.getAttribute('data-id');
            const username = button.getAttribute('data-user');
            const name = button.getAttribute('data-name');
            const unitId = button.getAttribute('data-unit-id') || '';

            document.getElementById('updateUserId').value = userId;
            document.getElementById('displayUsername').value = username;
            document.getElementById('updateName').value = name || '';
            document.getElementById('updatePassword').value = '';

            // Đảm bảo cache Khoa luôn có trước khi hiển thị select
            if (orgUnitsCache.length === 0) {
                await loadOrgUnits();
            }
            renderOrgUnitOptions(unitId);
        });
    }
});

// =========================================================================
// 4. DANH SÁCH TÀI KHOẢN & RENDER BẢNG
// =========================================================================

function loadUserList() {
    fetchWithAuth(`${API_URL}?detect=get_users`, {
            method: "GET"
        })
        .then(res => res.json())
        .then(res => {
            if (res.status === "success") {
                renderUserTable(res.data);
            } else {
                showNotification("Lỗi tải dữ liệu: " + res.message, "danger");
            }
        })
        .catch(err => {
            console.error("Lỗi kết nối API get_users:", err);
            showNotification("Không thể kết nối đến máy chủ", "danger");
        });
}

function renderUserTable(users) {
    const tbody = document.querySelector("#tab-phanquyen table tbody");
    if (!tbody) return;

    tbody.innerHTML = "";

    const filteredUsers = (users || []).filter(user => parseInt(user.can_grant_permission) === 1);

    if (filteredUsers.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="text-center text-muted py-4">Không có tài khoản nào có quyền quản trị (can_grant_permission = 1)</td></tr>`;
        return;
    }

    filteredUsers.forEach(user => {
                const prefix = user.username.trim().substring(0, 2).toUpperCase();
                let roleBadge = '<span class="badge bg-info text-dark">Giảng Viên</span>';

                if (prefix === 'LD') {
                    roleBadge = '<span class="badge bg-danger">Lãnh đạo</span>';
                } else if (prefix === 'CB') {
                    roleBadge = '<span class="badge bg-secondary">Cán bộ</span>';
                }

                const isStatusActive = parseInt(user.status) === 1;
                const hasPerm = parseInt(user.permission_count) > 0;
                const permStatusHtml = hasPerm ?
                    `<span class="text-success small fw-bold"><i class="fa-solid fa-check-circle me-1"></i>Đã phân quyền</span>` :
                    `<span class="text-secondary small fw-bold"><i class="fa-solid fa-circle-minus me-1"></i>Chưa phân quyền</span>`;

                const tr = document.createElement("tr");
                tr.setAttribute("data-user-id", user.user_id);
                tr.setAttribute("data-status", user.status);

                tr.innerHTML = `
            <td class="fw-bold username-val text-start">${user.username}</td>
            <td class="name-val text-start">
                <div>${user.name || '---'}</div>
                ${user.unit_name ? `<small class="text-muted"><i class="fa-solid fa-building-columns me-1"></i>${user.unit_name}</small>` : ''}
            </td>
            <td>${roleBadge}</td>
            <td class="permission-status-col">${permStatusHtml}</td>
            <td>
                <span class="badge ${isStatusActive ? 'bg-success' : 'bg-danger'} status-badge">
                    ${isStatusActive ? 'Hoạt động' : 'Bị khóa'}
                </span>
            </td>
            <td class="text-center">
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-primary" 
                        data-bs-toggle="modal" data-bs-target="#modalPhanQuyen" 
                        data-id="${user.user_id}" data-user="${user.username}" title="Phân Quyền">
                        <i class="fa-solid fa-sliders"></i>
                    </button>
                    <!-- BỔ SUNG data-unit-id ĐỂ TRUYỀN KHOA CŨ SANG MODAL -->
                    <button type="button" class="btn btn-outline-warning btn-edit-user" 
                        data-bs-toggle="modal" data-bs-target="#modalCapNhat" 
                        data-id="${user.user_id}" 
                        data-user="${user.username}" 
                        data-name="${user.name || ''}" 
                        data-unit-id="${user.unit_id || ''}" 
                        title="Cập nhật thông tin">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                    <button type="button" class="btn ${isStatusActive ? 'btn-outline-danger' : 'btn-outline-success'} btn-toggle-status" 
                        onclick="toggleAccountStatus(this)" title="${isStatusActive ? 'Vô hiệu hóa tài khoản' : 'Kích hoạt tài khoản'}">
                        <i class="fa-solid ${isStatusActive ? 'fa-user-slash' : 'fa-user-check'}"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-delete-user" 
                        onclick="deleteUser(this)" title="Xóa tài khoản">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

// =========================================================================
// 5. PHÂN QUYỀN, ĐỔI TRẠNG THÁI & XÓA TÀI KHOẢN
// =========================================================================

function savePermission() {
    const userId = document.getElementById('permUserId').value;
    const checkboxes = document.querySelectorAll("#formPhanQuyenModal .perm-checkbox:checked");
    const selectedPermissions = Array.from(checkboxes).map(cb => cb.value);

    const btnSave = document.getElementById('btnSavePermission');
    if (btnSave) {
        btnSave.disabled = true;
        btnSave.innerHTML = `<i class="fa-solid fa-spinner fa-spin me-1"></i>Đang lưu...`;
    }

    fetchWithAuth(API_URL, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                detect: "add_role_user",
                user_id: userId,
                permissions: selectedPermissions
            })
        })
        .then(res => res.json())
        .then(res => {
            if (res.status === "success") {
                showNotification(res.message || "Cập nhật quyền thành công!", "success");
                const modalElement = document.getElementById('modalPhanQuyen');
                if (modalElement) {
                    const modalInstance = bootstrap.Modal.getInstance(modalElement);
                    if (modalInstance) modalInstance.hide();
                }
                loadUserList();
            } else {
                showNotification("Lỗi: " + res.message, "danger");
            }
        })
        .catch(err => {
            console.error("Lỗi phân quyền:", err);
            showNotification("Không thể lưu phân quyền", "danger");
        })
        .finally(() => {
            if (btnSave) {
                btnSave.disabled = false;
                btnSave.innerHTML = `<i class="fa-solid fa-floppy-disk me-1"></i>Lưu Thay Đổi`;
            }
        });
}

function toggleAccountStatus(btn) {
    const tr = btn.closest('tr');
    const userId = tr.getAttribute('data-user-id');
    const currentStatus = parseInt(tr.getAttribute('data-status'));
    const newStatus = currentStatus === 1 ? 0 : 1;
    const actionText = newStatus === 0 ? 'vô hiệu hóa' : 'kích hoạt';

    showConfirmDialog(
        `Xác nhận ${actionText}`,
        `Bạn có chắc chắn muốn ${actionText} trạng thái tài khoản này không?`,
        newStatus === 0,
        function() {
            fetchWithAuth(API_URL, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({
                        user_id: userId,
                        status: newStatus,
                        detect: "update_user_status"
                    })
                })
                .then(res => res.json())
                .then(res => {
                    if (res.status === "success") {
                        showNotification(res.message || `Đã ${actionText} tài khoản thành công!`, "success");
                        loadUserList();
                    } else {
                        showNotification("Thông báo: " + res.message, "warning");
                    }
                })
                .catch(err => {
                    console.error("Lỗi kết nối API cập nhật trạng thái:", err);
                    showNotification("Lỗi cập nhật trạng thái", "danger");
                });
        }
    );
}

function deleteUser(btn) {
    const tr = btn.closest('tr');
    const userId = tr.getAttribute('data-user-id');
    const username = tr.querySelector('.username-val').textContent.trim();

    showConfirmDialog(
        "Xóa vĩnh viễn tài khoản",
        `Hành động này không thể hoàn tác! Bạn có chắc chắn muốn xóa tài khoản "${username}" không?`,
        true,
        function() {
            fetchWithAuth(API_URL, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({
                        user_id: userId,
                        detect: "delete_user"
                    })
                })
                .then(res => res.json())
                .then(res => {
                    if (res.status === "success") {
                        showNotification(res.message || "Đã xóa tài khoản thành công!", "success");
                        loadUserList();
                    } else {
                        showNotification("Lỗi: " + res.message, "danger");
                    }
                })
                .catch(err => {
                    console.error("Lỗi kết nối API xóa tài khoản:", err);
                    showNotification("Lỗi kết nối khi xóa tài khoản", "danger");
                });
        }
    );
}

function loadPermissionList(userId) {
    const container = document.querySelector("#formPhanQuyenModal .row");
    if (container) {
        container.innerHTML = `<div class="col-12 text-center py-3 text-muted"><i class="fa-solid fa-spinner fa-spin me-2"></i>Đang tải danh sách quyền...</div>`;
    }

    Promise.all([
            fetchWithAuth(`${API_URL}?detect=get_list_permission`, { method: "GET" }).then(res => res.json()),
            fetchWithAuth(`${API_URL}?detect=get_user_role&user_id=${userId}`, { method: "GET" }).then(res => res.json())
        ])
        .then(([allPermRes, userPermRes]) => {
            if (allPermRes.status === "success" && userPermRes.status === "success") {
                const allPermissions = allPermRes.data;
                const assignedPermissionIds = userPermRes.data.map(id => String(id));
                renderPermissionCheckboxes(allPermissions, assignedPermissionIds);
            } else {
                showNotification("Lỗi tải thông tin phân quyền.", "danger");
            }
        })
        .catch(err => {
            console.error("Lỗi kết nối API:", err);
            if (container) container.innerHTML = `<div class="col-12 text-center text-danger">Không thể kết nối đến máy chủ.</div>`;
            showNotification("Không thể tải danh sách quyền từ máy chủ", "danger");
        });
}

function renderPermissionCheckboxes(permissions, assignedIds = []) {
    const container = document.querySelector("#formPhanQuyenModal .row");
    if (!container) return;

    container.innerHTML = "";

    if (!permissions || permissions.length === 0) {
        container.innerHTML = `<div class="col-12 text-center text-muted">Chưa có quyền nào trong hệ thống.</div>`;
        return;
    }

    permissions.forEach(perm => {
        const isChecked = assignedIds.includes(String(perm.id)) ? "checked" : "";
        const col = document.createElement("div");
        col.className = "col-md-6";
        col.innerHTML = `
            <div class="form-check p-3 border rounded">
                <input class="form-check-input perm-checkbox" type="checkbox" value="${perm.id}" id="perm_${perm.id}" ${isChecked}>
                <label class="form-check-label fw-semibold" for="perm_${perm.id}">
                    ${perm.permission_name}
                </label>
            </div>
        `;
        container.appendChild(col);
    });
}
