
<div class="d-flex justify-content-between align-items-center bg-white p-3 rounded-3 shadow-sm mb-4">
    <div class="d-flex align-items-center gap-3">
        <!-- <a href="r1_overview.php" class="btn btn-outline-secondary btn-sm" id="btnBackOverview">
            <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
        </a> -->
        <h4 class="m-0 text-secondary fw-bold" id="topbarTitle">Chi Tiết Đề Tài Nghiên Cứu</h4>
    </div>
    <div class="d-flex align-items-center gap-3">
        <!-- <span class="badge bg-primary fs-6" id="userRoleBadge">
            <i class="fa-solid fa-user-shield me-1"></i> <span id="userTypeText">Admin (GV)</span>
        </span> -->
        <div class="text-end">
            <span class="fw-bold text-dark d-block" id="userNameLabel">
                <i class="fa-solid fa-circle-user text-secondary me-1"></i> Xin chào, đang tải...
            </span>
            <small class="text-muted" id="userAccountLabel">Tài khoản: ---</small>
        </div>
    </div>
</div>

<!-- Script chuẩn hóa giúp Topbar luôn load được tên user -->
<script>
    // Định nghĩa hàm global để trang chính hoặc topbar đều có thể gọi chủ động
    window.loadTopbarUserInfo = async function() {
        const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
        if (!token) return;

        try {
            // 1. Giải mã token trước để hiển thị tức thì tên/username nếu có sẵn trong payload
            const base64Url = token.split('.')[1];
            const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
            const jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
                return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
            }).join(''));

            const payloadData = JSON.parse(jsonPayload);
            const currentUserId = payloadData.user_id || payloadData.id || 0;

            // Hiển thị tạm thời từ token để người dùng không thấy chữ "đang tải" quá lâu
            if (payloadData.name) {
                const nameLabel = document.getElementById('userNameLabel');
                const accountLabel = document.getElementById('userAccountLabel');
                if (nameLabel) nameLabel.innerHTML = `<i class="fa-solid fa-circle-user text-secondary me-1"></i> Xin chào, ${escapeHtml(payloadData.name)}`;
                if (accountLabel && payloadData.username) accountLabel.innerText = `Tài khoản: ${escapeHtml(payloadData.username)}`;
            }

            // 2. Gọi API get_users để lấy dữ liệu chính xác nhất từ CSDL
            const response = await fetch('/QLNCKH/index_api.php?detect=get_users', {
                method: 'GET',
                headers: { 'Authorization': `Bearer ${token}` }
            });
            const result = await response.json();

            if (response.ok && result.status === 'success' && Array.isArray(result.data)) {
                const currentUser = result.data.find(u => parseInt(u.user_id) === parseInt(currentUserId));

                if (currentUser) {
                    const name = currentUser.name || "Người dùng";
                    const username = currentUser.username || "unknow";
                    
                    const nameLabel = document.getElementById('userNameLabel');
                    const accountLabel = document.getElementById('userAccountLabel');

                    if (nameLabel) {
                        nameLabel.innerHTML = `<i class="fa-solid fa-circle-user text-secondary me-1"></i> Xin chào, ${escapeHtml(name)}`;
                    }
                    if (accountLabel) {
                        accountLabel.innerText = `Tài khoản: ${escapeHtml(username)}`;
                    }
                }
            }
        } catch (err) {
            console.error("Lỗi tải thông tin user cho topbar:", err);
        }
    };

    // Tự động kích hoạt ngay khi DOM sẵn sàng
    if (document.readyState === 'loading') {
        document.addEventListener("DOMContentLoaded", window.loadTopbarUserInfo);
    } else {
        window.loadTopbarUserInfo();
    }

    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
    }
</script>
