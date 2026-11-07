<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$currentUri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$currentSlug = basename($currentUri);

// Logo trường từ hệ thống (nếu có) hoặc dùng logo mặc định
$schoolLogo = "../asset/img/logoT04.png";
// if (!file_exists(__DIR__ . '/../' . $schoolLogo)) {
//     $schoolLogo = "../asset/img/logo.png";
// }
?>

<!-- SCRIPT CHỐNG GIẬT TRẠNG THÁI SIDEBAR KHI LOAD TRANG -->
<script>
    (function() {
        const isCollapsed = localStorage.getItem('r3_sidebar_collapsed_state') === 'true';
        if (isCollapsed) {
            document.documentElement.classList.add('r3-sidebar-is-collapsed');
        }
    })();
</script>

<style>
    :root {
        --r3-sidebar-full-width: 250px;
        --r3-sidebar-mini-width: 70px;
        --r3-active-color: #f97316;
    }

    /* Đảm bảo vùng nội dung chính co giãn tương ứng */
    .main-wrapper {
        margin-left: var(--r3-sidebar-full-width) !important;
        width: calc(100% - var(--r3-sidebar-full-width)) !important;
        transition: margin-left 0.25s cubic-bezier(0.4, 0, 0.2, 1), width 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
        box-sizing: border-box !important;
    }

    html.r3-sidebar-is-collapsed .main-wrapper,
    .main-wrapper.is-collapsed {
        margin-left: var(--r3-sidebar-mini-width) !important;
        width: calc(100% - var(--r3-sidebar-mini-width)) !important;
    }

    /* Khung Sidebar chính Tông Xanh Ngọc Lục Bảo (Emerald & Pine) */
    .sidebar {
        width: var(--r3-sidebar-full-width) !important;
        height: 100vh !important;
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        z-index: 1050 !important;
        box-sizing: border-box !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: space-between !important;
        background: linear-gradient(180deg, #063c2c 0%, #0d5f46 45%, #053b2c 100%) !important;
        color: #ffffff !important;
        transition: width 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
        overflow-x: visible !important;
        overflow-y: auto !important;
        transform: none !important;
        box-shadow: 3px 0 16px rgba(4, 47, 34, 0.35) !important;
    }

    /* Họa tiết chìm Khoa học Công nghệ & Đổi mới sáng tạo NỔI BẬT */
    .sidebar::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: url("../asset/img/bg_r3_sidebar.svg");
        background-repeat: repeat-y;
        background-position: center 20px;
        background-size: 290px 290px;
        opacity: 0.16;
        /* Nổi bật sắc nét hơn */
        pointer-events: none;
        z-index: 0;
    }

    /* Trạng thái thu gọn (Mini 70px) */
    .sidebar.is-collapsed,
    html.r3-sidebar-is-collapsed .sidebar {
        width: var(--r3-sidebar-mini-width) !important;
        overflow: visible !important;
    }

    /* Khối Header chứa Logo & Nút Toggle */
    .sidebar-header-box {
        position: relative;
        z-index: 1;
        padding: 12px 14px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        min-height: 64px !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        background: rgba(0, 0, 0, 0.22);
        overflow: hidden;
        transition: padding 0.25s ease;
    }

    .sidebar-brand-container {
        display: flex;
        align-items: center;
        gap: 10px;
        overflow: hidden;
        white-space: nowrap;
    }

    /* Khung chứa Logo trường */
    .sidebar-logo-box {
        width: 36px;
        height: 36px;
        background: #ffffff;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3px;
        flex-shrink: 0;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.4);
    }

    .sidebar-logo-box img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .sidebar-brand-text {
        font-weight: 700 !important;
        font-size: 0.95rem !important;
        letter-spacing: 0.5px !important;
        white-space: nowrap !important;
        color: #ffffff !important;
        max-width: 150px;
        opacity: 1;
        transform: translateX(0);
        transition: max-width 0.25s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.2s ease, transform 0.25s ease !important;
    }

    .btn-hamburger-toggle {
        background: transparent !important;
        border: none !important;
        color: #fef08a !important;
        cursor: pointer !important;
        width: 38px !important;
        height: 38px !important;
        border-radius: 8px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 1.25rem !important;
        transition: background 0.2s ease !important;
        outline: none !important;
        flex-shrink: 0;
    }

    .btn-hamburger-toggle:hover {
        background: rgba(255, 255, 255, 0.18) !important;
        color: #ffffff !important;
    }

    /* Danh sách Menu */
    .r3-menu-list {
        position: relative;
        z-index: 1;
        list-style: none !important;
        padding: 10px 10px !important;
        margin: 0 !important;
    }

    .r3-nav-item {
        margin-bottom: 5px !important;
        position: relative;
    }

    .r3-nav-link {
        padding: 10px 14px !important;
        cursor: pointer !important;
        color: #d1fae5 !important;
        display: flex !important;
        align-items: center !important;
        font-weight: 500 !important;
        font-size: 0.9rem !important;
        border-radius: 8px !important;
        text-decoration: none !important;
        transition: all 0.2s ease !important;
        white-space: nowrap !important;
        background: transparent;
    }

    .r3-nav-link:hover {
        background: rgba(255, 255, 255, 0.14) !important;
        color: #ffffff !important;
    }

    .r3-nav-link.parent-active {
        background: rgba(255, 255, 255, 0.12) !important;
        color: #ffffff !important;
        font-weight: 600 !important;
    }

    /* Tab Trang chủ Active (Màu cam ấm nổi bật) */
    .r3-nav-link.active {
        background: var(--r3-active-color) !important;
        color: #ffffff !important;
        font-weight: 600 !important;
        box-shadow: 0 4px 10px rgba(249, 115, 22, 0.35) !important;
    }

    .r3-nav-icon {
        width: 24px !important;
        min-width: 24px !important;
        font-size: 1.15rem !important;
        text-align: center !important;
        margin-right: 12px !important;
        flex-shrink: 0 !important;
        transition: margin 0.25s ease;
    }

    .r3-nav-text {
        white-space: nowrap !important;
        transition: opacity 0.2s ease !important;
    }

    .r3-arrow-icon {
        font-size: 0.75rem;
        transition: transform 0.25s ease;
        margin-left: auto;
    }

    /* Submenu thả dọc khi mở rộng */
    .sidebar:not(.is-collapsed) .r3-submenu {
        list-style: none !important;
        padding-left: 18px !important;
        margin: 4px 0 6px 16px !important;
        border-left: 2px solid rgba(110, 231, 183, 0.35);
        display: none;
    }

    .sidebar:not(.is-collapsed) .r3-sub-item {
        padding: 7px 12px !important;
        font-size: 0.85rem !important;
        color: #a7f3d0 !important;
        display: block !important;
        border-radius: 6px !important;
        text-decoration: none !important;
        transition: all 0.2s ease !important;
        white-space: nowrap !important;
        margin-bottom: 2px;
    }

    .sidebar:not(.is-collapsed) .r3-sub-item:hover {
        color: #ffffff !important;
        background: rgba(255, 255, 255, 0.15) !important;
    }

    .sidebar:not(.is-collapsed) .r3-sub-item.active {
        color: #ffffff !important;
        font-weight: 600 !important;
        background: var(--r3-active-color) !important;
        box-shadow: 0 3px 8px rgba(249, 115, 22, 0.3) !important;
    }

    .r3-flyout-header {
        display: none;
    }

    /* Khi thu nhỏ Mini 70px */
    .sidebar.is-collapsed .sidebar-header-box,
    html.r3-sidebar-is-collapsed .sidebar .sidebar-header-box {
        padding: 14px 0 !important;
        justify-content: center !important;
    }

    .sidebar.is-collapsed .sidebar-brand-text,
    html.r3-sidebar-is-collapsed .sidebar .sidebar-brand-text {
        max-width: 0 !important;
        opacity: 0 !important;
        transform: translateX(-15px) !important;
        pointer-events: none !important;
    }

    .sidebar.is-collapsed .r3-nav-text,
    .sidebar.is-collapsed .r3-arrow-icon,
    .sidebar.is-collapsed .btn-logout-text,
    html.r3-sidebar-is-collapsed .sidebar .r3-nav-text,
    html.r3-sidebar-is-collapsed .sidebar .r3-arrow-icon,
    html.r3-sidebar-is-collapsed .sidebar .btn-logout-text {
        display: none !important;
    }

    .sidebar.is-collapsed .r3-nav-link,
    html.r3-sidebar-is-collapsed .sidebar .r3-nav-link {
        padding: 12px 0 !important;
        justify-content: center !important;
    }

    .sidebar.is-collapsed .r3-nav-link .r3-nav-icon,
    html.r3-sidebar-is-collapsed .sidebar .r3-nav-link .r3-nav-icon {
        margin-right: 0 !important;
    }

    /* Menu con bay sang bên phải dạng Flyout */
    .sidebar.is-collapsed .r3-submenu,
    html.r3-sidebar-is-collapsed .sidebar .r3-submenu {
        position: absolute !important;
        top: 0 !important;
        left: 70px !important;
        width: 220px !important;
        background: #ffffff !important;
        border-radius: 8px !important;
        border: 1px solid #e2e8f0 !important;
        padding: 6px !important;
        margin: 0 !important;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2) !important;
        z-index: 1060 !important;
        list-style: none !important;
        display: none;
    }

    .sidebar.is-collapsed .r3-flyout-header,
    html.r3-sidebar-is-collapsed .sidebar .r3-flyout-header {
        display: block !important;
        padding: 8px 12px;
        font-size: 0.8rem;
        font-weight: 700;
        color: #065f46;
        border-bottom: 1px solid #f1f5f9;
        margin-bottom: 4px;
        white-space: nowrap;
    }

    .sidebar.is-collapsed .r3-sub-item,
    html.r3-sidebar-is-collapsed .sidebar .r3-sub-item {
        color: #334155 !important;
        padding: 8px 12px !important;
        font-size: 0.85rem !important;
        border-radius: 6px !important;
        display: flex !important;
        align-items: center;
        text-decoration: none !important;
        margin-bottom: 2px !important;
        white-space: nowrap !important;
        transition: all 0.15s ease !important;
    }

    .sidebar.is-collapsed .r3-sub-item:hover,
    html.r3-sidebar-is-collapsed .sidebar .r3-sub-item:hover {
        background-color: #ecfdf5 !important;
        color: #047857 !important;
    }

    .sidebar.is-collapsed .r3-sub-item.active,
    html.r3-sidebar-is-collapsed .sidebar .r3-sub-item.active {
        background-color: #fff7ed !important;
        color: #ea580c !important;
        font-weight: 600 !important;
    }

    .btn-logout-sidebar {
        position: relative;
        z-index: 1;
        padding: 12px !important;
        border-top: 1px solid rgba(255, 255, 255, 0.12) !important;
        background: rgba(0, 0, 0, 0.15) !important;
        margin-top: auto !important;
    }
</style>

<!-- SIDEBAR GIAO DIỆN XANH NGỌC LỤC BẢO MỚI -->
<aside class="sidebar" id="r3CustomSidebar">
    <div>
        <!-- KHỐI HEADER VỚI LOGO TRƯỜNG & NÚT TOGGLE -->
        <div class="sidebar-header-box">
            <div class="sidebar-brand-container">
                <div class="sidebar-logo-box">
                    <img src="<?= htmlspecialchars($schoolLogo) ?>" alt="Logo">
                </div>
                <div class="sidebar-brand-text">
                    <div style="font-size: 0.88rem; font-weight: 700; color: #a7f3d0; letter-spacing: 0.5px;">CỔNG NCKH</div>
                    <small style="font-size: 0.68rem; color: #d1fae5;">Giảng Viên & Nghiên Cứu</small>
                </div>
            </div>

            <button type="button" class="btn-hamburger-toggle" id="btnSidebarHamburger" title="Đóng / Mở menu">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>

        <ul class="r3-menu-list">
            <!-- 1. TRANG CHỦ -->
            <li class="r3-nav-item">
                <a href="../trang-chu" class="r3-nav-link <?= in_array($currentPage, ['r3_home.php']) || in_array($currentSlug, ['trang-chu', 'dashboard']) ? 'active' : '' ?>" title="Trang chủ">
                    <i class="fa-solid fa-house r3-nav-icon"></i>
                    <span class="r3-nav-text">Trang chủ</span>
                </a>
            </li>

            <!-- 2. NHIỆM VỤ NCKH -->
            <?php
            $isTaskActive = in_array($currentPage, ['r3_my_projects.php', 'r3_proposed_projects.php', 'r3_project_register.php']) || in_array($currentSlug, ['nhiem-vu-cua-toi', 'nhiem-vu-de-xuat-thuc-hien', 'dang-ky-de-tai-nckh']);
            ?>
            <li class="r3-nav-item">
                <div class="r3-nav-link <?= $isTaskActive ? 'parent-active' : '' ?>" id="btnToggleTaskMenu" style="cursor: pointer;" title="Nhiệm vụ NCKH">
                    <i class="fa-solid fa-book-bookmark r3-nav-icon"></i>
                    <span class="r3-nav-text flex-grow-1">Nhiệm vụ NCKH</span>
                    <i class="fa-solid fa-chevron-down r3-arrow-icon" id="iconArrowTask" style="transform: <?= $isTaskActive ? 'rotate(180deg)' : 'rotate(0deg)' ?>;"></i>
                </div>
                <ul class="r3-submenu" id="menuTaskChildren" style="<?= $isTaskActive ? 'display: block;' : '' ?>">
                    <li class="r3-flyout-header">Nhiệm vụ NCKH</li>
                    <li>
                        <a href="../nhiem-vu-cua-toi" class="r3-sub-item <?= in_array($currentPage, ['r3_my_projects.php']) || in_array($currentSlug, ['nhiem-vu-cua-toi']) ? 'active' : '' ?>">
                            <i class="fa-solid fa-circle-dot me-2" style="font-size: 0.65rem;"></i>
                            <span>Nhiệm vụ của tôi</span>
                        </a>
                    </li>
                    <li>
                        <a href="../nhiem-vu-de-xuat-thuc-hien" class="r3-sub-item <?= in_array($currentPage, ['r3_proposed_projects.php', 'r3_project_register.php']) || in_array($currentSlug, ['nhiem-vu-de-xuat-thuc-hien', 'dang-ky-de-tai-nckh']) ? 'active' : '' ?>">
                            <i class="fa-solid fa-circle-dot me-2" style="font-size: 0.65rem;"></i>
                            <span>Nhiệm vụ đề xuất thực hiện</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- 3. LÝ LỊCH KHOA HỌC (DÀNH CHO GIẢNG VIÊN) -->
            <!-- LÝ LỊCH KHOA HỌC (DÀNH CHO GIẢNG VIÊN) -->
            <li class="r3-nav-item">
                <a href="../ly-lich-khoa-hoc" class="r3-nav-link <?= in_array($currentPage, ['r3_scientific_cv.php']) || in_array($currentSlug, ['ly-lich-khoa-hoc']) ? 'active' : '' ?>" title="Lý lịch khoa học">
                    <i class="fa-solid fa-id-card-clip r3-nav-icon"></i>
                    <span class="r3-nav-text">Lý lịch khoa học</span>
                </a>
            </li>

            <!-- 4. BIỂU MẪU HƯỚNG DẪN -->
            <li class="r3-nav-item">
                <a href="../bieu-mau-huong-dan" class="r3-nav-link <?= in_array($currentPage, ['r1_form_list.php']) || $currentSlug === 'bieu-mau-huong-dan' ? 'active' : '' ?>" title="Biểu mẫu & Hướng dẫn">
                    <i class="fa-solid fa-circle-question r3-nav-icon"></i>
                    <span class="r3-nav-text">Biểu mẫu & Hướng dẫn</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- NÚT ĐĂNG XUẤT -->
    <div class="btn-logout-sidebar">
        <button type="button" onclick="handleLogout()" class="btn btn-outline-light w-100 btn-sm d-flex align-items-center justify-content-center gap-2 py-2" title="Đăng xuất" style="border-color: rgba(255, 255, 255, 0.35);">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span class="btn-logout-text fw-bold">Đăng xuất</span>
        </button>
    </div>
</aside>

<!-- SCRIPT HOẠT ĐỘNG CỤC BỘ KHÉP KÍN -->
<script>
    (function() {
        const STORAGE_KEY = 'r3_sidebar_collapsed_state';

        function applySidebarState(isCollapsed) {
            const sidebar = document.getElementById('r3CustomSidebar');
            const mainWrapper = document.getElementById('mainWrapper') || document.querySelector('.main-wrapper');
            const subMenu = document.getElementById('menuTaskChildren');

            if (sidebar) {
                if (isCollapsed) {
                    sidebar.classList.add('is-collapsed');
                    document.documentElement.classList.add('r3-sidebar-is-collapsed');
                } else {
                    sidebar.classList.remove('is-collapsed');
                    document.documentElement.classList.remove('r3-sidebar-is-collapsed');
                }
            }

            if (mainWrapper) {
                if (isCollapsed) {
                    mainWrapper.classList.add('is-collapsed');
                } else {
                    mainWrapper.classList.remove('is-collapsed');
                }
            }

            if (subMenu && isCollapsed) {
                subMenu.style.display = 'none';
            }
        }

        const savedState = localStorage.getItem(STORAGE_KEY) === 'true';
        applySidebarState(savedState);

        function initEvents() {
            const toggleBtn = document.getElementById('btnSidebarHamburger');
            const taskToggle = document.getElementById('btnToggleTaskMenu');
            const subMenu = document.getElementById('menuTaskChildren');
            const arrowIcon = document.getElementById('iconArrowTask');
            const sidebar = document.getElementById('r3CustomSidebar');

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const willCollapse = !sidebar.classList.contains('is-collapsed');
                    applySidebarState(willCollapse);
                    localStorage.setItem(STORAGE_KEY, willCollapse ? 'true' : 'false');
                });
            }

            if (taskToggle && subMenu) {
                taskToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const isMini = sidebar.classList.contains('is-collapsed');
                    const isClosed = window.getComputedStyle(subMenu).display === 'none';

                    if (isClosed) {
                        subMenu.style.setProperty('display', 'block', 'important');
                        if (arrowIcon && !isMini) arrowIcon.style.transform = 'rotate(180deg)';
                        taskToggle.classList.add('parent-active');
                    } else {
                        subMenu.style.setProperty('display', 'none', 'important');
                        if (arrowIcon && !isMini) arrowIcon.style.transform = 'rotate(0deg)';
                        taskToggle.classList.remove('parent-active');
                    }
                });
            }

            document.addEventListener('click', function(e) {
                if (sidebar && sidebar.classList.contains('is-collapsed') && subMenu) {
                    if (!e.target.closest('#btnToggleTaskMenu') && !e.target.closest('#menuTaskChildren')) {
                        subMenu.style.setProperty('display', 'none', 'important');
                    }
                }
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initEvents);
        } else {
            initEvents();
        }
    })();
</script>