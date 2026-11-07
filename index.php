<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Hệ thống Quản lý Nghiên cứu Khoa học (QLNCKH)</title>

    <!-- PURE CSS STYLES (KHÔNG DÙNG CDN/FRAMEWORK) -->
    <style>
        :root {
            --primary-navy: #1e3a8a;
            --primary-blue: #2563eb;
            --primary-hover: #1d4ed8;
            --bg-main: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --focus-ring: rgba(37, 99, 235, 0.2);
            --error-color: #ef4444;
            --error-bg: #fef2f2;
            --success-color: #10b981;
            --success-bg: #ecfdf5;
            --radius: 12px;
            --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        body {
            background-color: var(--bg-main);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 24px 24px;
        }

        .login-card {
            background-color: var(--card-bg);
            width: 100%;
            max-width: 420px;
            padding: 40px 32px;
            border-radius: var(--radius);
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
            border: 1px solid var(--border-color);
        }

        .header-section {
            text-align: center;
            margin-bottom: 28px;
        }

        .logo-wrapper {
            width: 64px;
            height: 64px;
            background: none;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.25);
        }

        .university-name {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--primary-navy);
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .system-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text-main);
        }

        .alert-box {
            display: none;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 0.8125rem;
            margin-bottom: 20px;
            align-items: center;
            gap: 10px;
            line-height: 1.4;
        }

        .alert-box.show {
            display: flex;
        }

        .alert-box.error {
            background-color: var(--error-bg);
            color: var(--error-color);
            border: 1px solid #fca5a5;
        }

        .alert-box.success {
            background-color: var(--success-bg);
            color: var(--success-color);
            border: 1px solid #6ee7b7;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 6px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 12px;
            color: var(--text-muted);
            pointer-events: none;
            display: flex;
            align-items: center;
        }

        .form-input {
            width: 100%;
            padding: 10px 14px 10px 40px;
            font-size: 0.875rem;
            color: var(--text-main);
            background-color: #f8fafc;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            outline: none;
            transition: var(--transition);
        }

        .form-input:focus {
            background-color: #ffffff;
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px var(--focus-ring);
        }

        .form-input.has-error {
            border-color: var(--error-color);
            background-color: #fff5f5;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            transition: var(--transition);
        }

        .password-toggle:hover {
            color: var(--text-main);
        }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.8125rem;
            margin-bottom: 24px;
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            background-color: var(--primary-navy);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .submit-btn:hover {
            background-color: #1a3276;
            box-shadow: 0 4px 8px rgba(30, 58, 138, 0.2);
        }

        .submit-btn:active {
            transform: scale(0.99);
        }

        .submit-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .spinner {
            display: none;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #ffffff;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .footer-text {
            text-align: center;
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 28px;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 28px 20px;
            }

            .university-name {
                font-size: 0.6875rem;
            }

            .system-title {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>

    <div class="login-card">

        <!-- 1. LOGO TRƯỜNG & TÊN TRƯỜNG -->
        <div class="header-section">
            <div class="logo-wrapper">
                <img id="schoolLogo" src="asset/img/logoT04.png" alt="Logo Trường" style="max-width: 100%; height: auto; object-fit: contain;">
            </div>
            <div class="university-name" id="universityName">Trường Đại học An ninh nhân dân</div>
            <h1 class="system-title" id="systemTitleDisplay">Hệ thống Quản lý nhiệm vụ khoa học công nghệ và đổi mới sáng tạo</h1>
        </div>

        <!-- NOTIFICATION BOX -->
        <div id="alertBox" class="alert-box">
            <span id="alertMessage"></span>
        </div>

        <!-- 2. FORM ĐĂNG NHẬP -->
        <form id="loginForm" onsubmit="handleFormSubmit(event)" novalidate>

            <!-- Username Input -->
            <div class="form-group">
                <label for="username" class="form-label">Tên đăng nhập</label>
                <div class="input-wrapper">
                    <span class="input-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                    </span>
                    <input type="text" id="username" name="username" class="form-input" placeholder="Ví dụ: CB202600 hoặc GV201200" autocomplete="username">
                </div>
            </div>

            <!-- Password Input -->
            <div class="form-group">
                <label for="password" class="form-label">Mật khẩu</label>
                <div class="input-wrapper">
                    <span class="input-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                        </svg>
                    </span>
                    <input type="password" id="password" name="password" placeholder="Nhập mật khẩu..." autocomplete="current-password" autocorrect="off" autocapitalize="none" spellcheck="false" lang="en" inputmode="verbatim" class="form-input">
                    <button type="button" class="password-toggle" id="togglePasswordBtn" onclick="togglePassword()" title="Ẩn/Hiện mật khẩu">
                        <svg id="eyeIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="form-options"></div>

            <!-- Submit Button -->
            <button type="submit" id="submitBtn" class="submit-btn">
                <span id="spinner" class="spinner"></span>
                <span id="btnText">Đăng nhập</span>
            </button>

        </form>

        <!-- Footer -->
        <div class="footer-text">
            © 2026 Bản quyền thuộc <span id="copyrightSchoolName">Trường Đại học An ninh nhân dân</span>
        </div>

    </div>

    <!-- PURE JAVASCRIPT LOGIC -->
    <script>
        const API_URL = "index_api.php";
        const CACHE_SETTING_KEY = "cached_public_system_setting";

        // Cập nhật giao diện Tên trường và Logo
        function applySystemSettingUI(setting) {
            if (!setting) return;
            const uniNameEl = document.getElementById('universityName');
            const copyrightEl = document.getElementById('copyrightSchoolName');
            const logoEl = document.getElementById('schoolLogo');

            if (setting.university_name) {
                if (uniNameEl) uniNameEl.textContent = setting.university_name;
                if (copyrightEl) copyrightEl.textContent = setting.university_name;
            }
            if (setting.logo_path && logoEl) {
                logoEl.src = setting.logo_path;
            }
            if (setting.system_title) {
                const sysTitleEl = document.getElementById('systemTitleDisplay');
                if (sysTitleEl) sysTitleEl.textContent = setting.system_title;
            }
        }

        // 1. Tự chạy ngay tức thì: Đọc từ localStorage để hiển thị không bị giật (FOUC)
        (function initSystemSetting() {
            const cached = localStorage.getItem(CACHE_SETTING_KEY);
            if (cached) {
                try {
                    applySystemSettingUI(JSON.parse(cached));
                } catch (e) {}
            }

            // Gọi API đồng bộ cấu hình mới nhất từ server
            fetch(`${API_URL}?detect=system_setting_get`)
                .then(res => res.json())
                .then(result => {
                    if (result.status === 'success' && result.data) {
                        localStorage.setItem(CACHE_SETTING_KEY, JSON.stringify(result.data));
                        applySystemSettingUI(result.data);
                    }
                })
                .catch(err => console.error("Lỗi tải thông tin trường:", err));
        })();

        // Toggle Show/Hide Password
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 19c-7 0-10-7-10-7a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 5c7 0 10 7 10 7a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                    <line x1="1" y1="1" x2="23" y2="23"/>
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/>
                    <circle cx="12" cy="12" r="3"/>
                `;
            }
        }

        // Display Notification Alert Box
        function showAlert(type, message) {
            const alertBox = document.getElementById('alertBox');
            const alertMessage = document.getElementById('alertMessage');

            alertBox.className = `alert-box show ${type}`;
            alertMessage.innerText = message;
        }

        function hideAlert() {
            const alertBox = document.getElementById('alertBox');
            alertBox.className = 'alert-box';
        }

        // Set Loading State on Button
        function setLoadingState(isLoading) {
            const submitBtn = document.getElementById('submitBtn');
            const spinner = document.getElementById('spinner');
            const btnText = document.getElementById('btnText');

            if (isLoading) {
                submitBtn.disabled = true;
                spinner.style.display = 'inline-block';
                btnText.innerText = 'Đang xử lý...';
            } else {
                submitBtn.disabled = false;
                spinner.style.display = 'none';
                btnText.innerText = 'Đăng nhập';
            }
        }

        // Form Submit & Login
        async function handleFormSubmit(event) {
            event.preventDefault();

            const usernameInput = document.getElementById('username');
            const passwordInput = document.getElementById('password');
            const username = usernameInput.value.trim();
            const password = passwordInput.value.trim();

            usernameInput.classList.remove('has-error');
            passwordInput.classList.remove('has-error');
            hideAlert();

            if (!username && !password) {
                usernameInput.classList.add('has-error');
                passwordInput.classList.add('has-error');
                showAlert('error', 'Vui lòng nhập Tên đăng nhập và Mật khẩu!');
                return;
            }

            if (!username) {
                usernameInput.classList.add('has-error');
                showAlert('error', 'Vui lòng nhập Tên đăng nhập!');
                return;
            }

            if (!password) {
                passwordInput.classList.add('has-error');
                showAlert('error', 'Vui lòng nhập Mật khẩu!');
                return;
            }

            setLoadingState(true);

            try {
                const response = await fetch(API_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        detect: 'login',
                        username,
                        password
                    })
                });

                const result = await response.json();

                if (response.ok && result.status === 'success') {
                    showAlert('success', result.message || 'Đăng nhập thành công! Đang chuyển hướng...');

                    // Lưu JWT Token và User Info vào SessionStorage
                    if (result.token) {
                        sessionStorage.setItem('access_token', result.token);
                    }

                    const userId = (result.data && result.data.user_id) ? result.data.user_id : username;
                    const usernameVal = (result.data && result.data.username) ? result.data.username : username;

                    sessionStorage.setItem('user_id', userId);
                    sessionStorage.setItem('username', usernameVal);

                    setTimeout(() => {
                        window.location.href = (result.data && result.data.redirect) ? result.data.redirect : 'trang-chu';
                    }, 500);

                } else {
                    showAlert('error', result.message || 'Tên đăng nhập hoặc mật khẩu không chính xác!');
                }
            } catch (error) {
                console.error('REST API Error:', error);
                showAlert('error', 'Không thể kết nối tới máy chủ API!');
            } finally {
                setLoadingState(false);
            }
        }
    </script>
</body>

</html>