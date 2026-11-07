// asset/js/auth_check.js
// Hàm toàn cục trích xuất user_id từ JWT Token lưu trong sessionStorage
function getUserIdFromToken() {
    const token = sessionStorage.getItem('access_token');
    if (!token) return null;
    try {
        const payloadBase64 = token.split('.')[1];
        if (!payloadBase64) return null;
        const decodedPayload = JSON.parse(atob(payloadBase64));
        return decodedPayload.user_id || decodedPayload.id || decodedPayload.sub || null;
    } catch (e) {
        console.error("Lỗi khi giải mã Token:", e);
        return null;
    }
}
(function checkAuth() {
    // 1. Lấy Token từ sessionStorage (không dùng localStorage)
    const token = sessionStorage.getItem('access_token');

    // Nếu không có Token (do chưa đăng nhập hoặc vừa đóng tab/trình duyệt mở lại)
    if (!token) {
        clearSessionAndRedirect();
        return;
    }

    // 2. Giải mã payload của JWT để kiểm tra thời hạn hết hạn (exp)
    try {
        const payloadBase64 = token.split('.')[1];
        const decodedPayload = JSON.parse(atob(payloadBase64));
        const currentTime = Math.floor(Date.now() / 1000);

        if (decodedPayload.exp && decodedPayload.exp < currentTime) {
            alert('Phiên làm việc đã hết hạn. Vui lòng đăng nhập lại!');
            clearSessionAndRedirect();
        }
    } catch (e) {
        // Token bị hỏng hoặc không đúng định dạng JWT
        clearSessionAndRedirect();
    }
})();

// Hàm xóa dữ liệu phiên và chuyển về trang đăng nhập
function clearSessionAndRedirect() {
    sessionStorage.clear();
    localStorage.clear(); // Xóa phòng trường hợp còn sót dữ liệu cũ

    // Chuyển hướng về trang đăng nhập (sửa lại đường dẫn tương đối tùy theo vị trí file HTML)
    window.location.href = '../index.php';
}

// Hàm hỗ trợ gửi Fetch Request có đính kèm JWT Token tự động cho các API
// Hàm hỗ trợ gửi Fetch Request có đính kèm JWT Token tự động cho các API
async function fetchWithAuth(url, options = {}) {
    const token = sessionStorage.getItem('access_token');

    // 1. Khởi tạo headers mặc định với Token
    const headers = {
        'Authorization': `Bearer ${token}`,
        ...options.headers
    };

    const method = (options.method || 'GET').toUpperCase();

    // 2. Chỉ tự động thêm Content-Type: application/json đối với POST/PUT/PATCH khi body KHÔNG PHẢI FormData
    if (['POST', 'PUT', 'PATCH'].includes(method)) {
        if (!(options.body instanceof FormData) && !headers['Content-Type']) {
            headers['Content-Type'] = 'application/json';
        }
        // Nếu là FormData thì xóa Content-Type để trình duyệt tự tạo boundary
        if (options.body instanceof FormData) {
            delete headers['Content-Type'];
        }
    } else {
        // Đối với GET hoặc DELETE, không gửi Content-Type để tránh lỗi Server
        delete headers['Content-Type'];
    }

    options.headers = headers;

    const response = await fetch(url, options);

    // Xử lý khi Token hết hạn hoặc không hợp lệ
    if (response.status === 401) {
        alert('Xác thực thất bại hoặc phiên làm việc đã hết hạn.');
        clearSessionAndRedirect();
    }

    return response;
}