<?php
// Lấy tên file hoặc slug hiện tại từ URL để active menu
$currentPage = basename($_SERVER['PHP_SELF']);
$currentUri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$currentSlug = basename($currentUri);
?>

<!-- SCRIPT CHẶN GIẬT GIAO DIỆN -->
<script>
    (function() {
        const isCollapsed = localStorage.getItem('admin_sidebar_collapsed') === 'true';
        if (isCollapsed) {
            document.documentElement.classList.add('sidebar-is-collapsed');
        }
    })();
</script>

<style>
    :root {
        --sidebar-width: 260px;
        --sidebar-collapsed-width: 70px;
        --sidebar-active-bg: linear-gradient(135deg, #d97706 0%, #b45309 100%);
    }

    #main-content {
        margin-left: var(--sidebar-width) !important;
        width: calc(100% - var(--sidebar-width)) !important;
        transition: margin-left 0.25s cubic-bezier(0.4, 0, 0.2, 1), width 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
        box-sizing: border-box !important;
    }

    html.sidebar-is-collapsed #main-content {
        margin-left: var(--sidebar-collapsed-width) !important;
        width: calc(100% - var(--sidebar-collapsed-width)) !important;
    }

    /* KHUNG SIDEBAR */
    #sidebar {
        width: var(--sidebar-width) !important;
        height: 100vh !important;
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: auto !important;
        bottom: 0 !important;
        margin: 0 !important;
        display: flex !important;
        flex-direction: column !important;
        visibility: visible !important;
        opacity: 1 !important;
        transform: none !important;
        z-index: 1050 !important;
        background: linear-gradient(180deg, #581313 0%, #7f1d1d 45%, #450a0a 100%) !important;
        box-shadow: 3px 0 14px rgba(0, 0, 0, 0.25) !important;
        transition: width 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    #sidebar:not(.collapsed) {
        width: var(--sidebar-width) !important;
        overflow-y: auto !important;
        overflow-x: hidden !important;
    }

    #sidebar.collapsed {
        width: var(--sidebar-collapsed-width) !important;
        overflow: visible !important;
    }

    html.sidebar-is-collapsed #sidebar {
        width: var(--sidebar-collapsed-width) !important;
        overflow: visible !important;
    }

    /* Hoa văn Trống Đồng */
    #sidebar::before {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 500 500' width='500' height='500'%3E%3Ccircle cx='250' cy='250' r='230' fill='none' stroke='%23ffffff' stroke-width='3' stroke-dasharray='4,6'/%3E%3Ccircle cx='250' cy='250' r='200' fill='none' stroke='%23ffffff' stroke-width='2'/%3E%3Ccircle cx='250' cy='250' r='160' fill='none' stroke='%23ffffff' stroke-width='1.5' stroke-dasharray='10,5'/%3E%3Ccircle cx='250' cy='250' r='120' fill='none' stroke='%23ffffff' stroke-width='2'/%3E%3Ccircle cx='250' cy='250' r='70' fill='none' stroke='%23ffffff' stroke-width='1.5'/%3E%3Cpolygon points='250,180 258,235 295,205 268,242 320,250 268,258 295,295 258,265 250,320 242,265 205,295 232,258 180,250 232,242 205,205 242,235' fill='%23ffffff'/%3E%3Cpath d='M250,30 A220,220 0 0,1 470,250' fill='none' stroke='%23ffffff' stroke-width='1'/%3E%3Cpath d='M470,250 A220,220 0 0,1 250,470' fill='none' stroke='%23ffffff' stroke-width='1'/%3E%3Cpath d='M250,470 A220,220 0 0,1 30,250' fill='none' stroke='%23ffffff' stroke-width='1'/%3E%3Cpath d='M30,250 A220,220 0 0,1 250,30' fill='none' stroke='%23ffffff' stroke-width='1'/%3E%3C/svg%3E");
        background-repeat: repeat-y;
        background-position: center 20px;
        background-size: 270px 270px;
        opacity: 0.07;
        pointer-events: none;
        z-index: 0;
    }

    /* =========================================================
       TỐI ƯU MƯỢT MÀ KHU VỰC TOPBAR & ANND PORTAL
       ========================================================= */
    .sidebar-top-bar {
        position: relative;
        z-index: 1;
        padding: 12px 14px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        background: rgba(0, 0, 0, 0.18);
        min-height: 60px;
        box-sizing: border-box;
        overflow: hidden;
        transition: padding 0.25s ease;
    }

    .sidebar-brand-container {
        display: flex;
        align-items: center;
        gap: 8px;
        overflow: hidden;
        white-space: nowrap;
    }

    /* Hiệu ứng trượt và làm mờ chữ ANND PORTAL */
    .sidebar-brand-text {
        overflow: hidden;
        white-space: nowrap !important;
        transition: max-width 0.25s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.2s ease, transform 0.25s ease;
        max-width: 160px;
        opacity: 1;
        transform: translateX(0);
    }

    .btn-hamburger-toggle {
        background: transparent;
        border: none;
        color: #fef08a;
        font-size: 1.25rem;
        cursor: pointer;
        padding: 6px 10px;
        border-radius: 6px;
        transition: all 0.2s;
        flex-shrink: 0;
    }
    .btn-hamburger-toggle:hover {
        background: rgba(255, 255, 255, 0.15);
        color: #ffffff;
    }

    /* CÁC LIÊN KẾT MENU */
    .sidebar-nav-link {
        color: #fecdd3 !important;
        padding: 9px 12px !important;
        border-radius: 7px !important;
        font-size: 0.9rem !important;
        font-weight: 500 !important;
        text-decoration: none !important;
        white-space: nowrap !important;
        transition: background 0.2s ease, color 0.2s ease !important;
        background: transparent;
        overflow: hidden;
    }
    .sidebar-nav-link:hover {
        color: #ffffff !important;
        background: rgba(255, 255, 255, 0.12) !important;
    }
    .sidebar-nav-link.parent-active {
        color: #ffffff !important;
        background: rgba(255, 255, 255, 0.15) !important;
        font-weight: 600 !important;
    }
    .sidebar-nav-link.active-item {
        background: var(--sidebar-active-bg) !important;
        color: #ffffff !important;
        font-weight: 600 !important;
        box-shadow: 0 3px 8px rgba(180, 83, 9, 0.4);
    }

    .menu-item-text {
        white-space: nowrap !important;
        transition: opacity 0.2s ease;
        opacity: 1;
    }

    .menu-icon {
        width: 22px !important;
        font-size: 1.1rem !important;
        text-align: center !important;
        flex-shrink: 0 !important;
        margin-right: 12px;
        transition: margin 0.25s ease;
    }

    .submenu-arrow {
        font-size: 0.75rem;
        transition: transform 0.25s ease, opacity 0.2s ease;
        margin-left: auto;
    }

    /* Menu con khi mở rộng */
    #sidebar:not(.collapsed) .admin-sub-menu {
        list-style: none !important;
        padding: 3px 0 3px 14px !important;
        margin: 3px 0 5px 0 !important;
        border-left: 2px solid rgba(253, 224, 71, 0.25);
        margin-left: 18px !important;
    }

    #sidebar:not(.collapsed) .admin-sub-link {
        padding: 7px 12px !important;
        font-size: 0.84rem !important;
        color: #fecaca !important;
        border-radius: 6px !important;
        text-decoration: none !important;
        display: flex;
        align-items: center;
        white-space: nowrap !important;
        margin-bottom: 2px !important;
        transition: all 0.2s ease !important;
    }

    #sidebar:not(.collapsed) .admin-sub-link:hover {
        color: #ffffff !important;
        background: rgba(255, 255, 255, 0.12) !important;
    }

    #sidebar:not(.collapsed) .admin-sub-link.active-sub {
        background: var(--sidebar-active-bg) !important;
        color: #ffffff !important;
        font-weight: 600 !important;
        box-shadow: 0 3px 8px rgba(180, 83, 9, 0.35);
    }

    #sidebar:not(.collapsed) .flyout-header {
        display: none !important;
    }

    /* =========================================================
       TRẠNG THÁI THU NHỎ (COLLAPSED): LƯỚT CHỮ MƯỢT KHÔNG GIẬT
       ========================================================= */
    #sidebar.collapsed .sidebar-top-bar,
    html.sidebar-is-collapsed #sidebar .sidebar-top-bar {
        padding: 12px 0 !important;
        justify-content: center !important;
    }

    /* Khi thu nhỏ, chữ ANND PORTAL thu hẹp width và tan biến mềm mại */
    #sidebar.collapsed .sidebar-brand-text,
    html.sidebar-is-collapsed #sidebar .sidebar-brand-text {
        max-width: 0 !important;
        opacity: 0 !important;
        transform: translateX(-15px) !important;
        pointer-events: none !important;
        margin: 0 !important;
    }

    #sidebar.collapsed .sidebar-brand-container,
    html.sidebar-is-collapsed #sidebar .sidebar-brand-container {
        gap: 0 !important;
    }

    #sidebar.collapsed .menu-item-text,
    #sidebar.collapsed .submenu-arrow,
    #sidebar.collapsed .btn-logout-text,
    html.sidebar-is-collapsed #sidebar .menu-item-text,
    html.sidebar-is-collapsed #sidebar .submenu-arrow,
    html.sidebar-is-collapsed #sidebar .btn-logout-text {
        display: none !important;
    }

    #sidebar.collapsed .sidebar-nav-link,
    html.sidebar-is-collapsed #sidebar .sidebar-nav-link {
        padding: 12px 0 !important;
        justify-content: center !important;
    }

    #sidebar.collapsed .sidebar-nav-link .menu-icon,
    html.sidebar-is-collapsed #sidebar .sidebar-nav-link .menu-icon {
        margin-right: 0 !important;
    }

    #sidebar.collapsed .admin-sub-menu,
    html.sidebar-is-collapsed #sidebar .admin-sub-menu {
        position: absolute !important;
        top: 0 !important;
        left: 70px !important;
        width: 215px !important;
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

    #sidebar.collapsed .flyout-header,
    html.sidebar-is-collapsed #sidebar .flyout-header {
        display: block !important;
        padding: 8px 12px;
        font-size: 0.8rem;
        font-weight: 700;
        color: #1e293b;
        border-bottom: 1px solid #f1f5f9;
        margin-bottom: 4px;
    }

    #sidebar.collapsed .admin-sub-link,
    html.sidebar-is-collapsed #sidebar .admin-sub-link {
        color: #334155 !important;
        padding: 8px 12px !important;
        font-size: 0.85rem !important;
        border-radius: 6px !important;
        display: flex;
        align-items: center;
        text-decoration: none !important;
        margin-bottom: 2px !important;
        transition: all 0.15s ease !important;
    }

    #sidebar.collapsed .admin-sub-link:hover,
    html.sidebar-is-collapsed #sidebar .admin-sub-link:hover {
        background-color: #f1f5f9 !important;
        color: #0f172a !important;
    }

    #sidebar.collapsed .admin-sub-link.active-sub,
    html.sidebar-is-collapsed #sidebar .admin-sub-link.active-sub {
        background-color: #eff6ff !important;
        color: #2563eb !important;
        font-weight: 600 !important;
    }
</style>

<!-- Sidebar Menu động -->
<aside id="sidebar">
    <div class="sidebar-top-bar d-flex align-items-center justify-content-between">
        <div class="sidebar-brand-container">
            <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #d97706, #b45309); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #ffffff; font-size: 0.95rem; flex-shrink: 0; box-shadow: 0 2px 6px rgba(0,0,0,0.25);">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div class="sidebar-brand-text" style="line-height: 1.15;">
                <div style="font-size: 0.88rem; font-weight: 700; color: #fef08a; letter-spacing: 0.4px;">ANND PORTAL</div>
                <small style="font-size: 0.68rem; color: #fecdd3;">Quản Lý Khoa Học</small>
            </div>
        </div>
        <!-- Nút Hamburger toggle -->
        <button type="button" class="btn-hamburger-toggle" onclick="toggleSidebarMenu()" title="Đóng/Mở thanh menu">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>

    <ul id="dynamicSidebarList" class="p-2 m-0 list-unstyled">
        <li class="sidebar-nav-item">
            <div class="sidebar-nav-link text-white-50 d-flex align-items-center">
                <i class="fa-solid fa-spinner fa-spin menu-icon"></i>
                <span class="menu-item-text">Đang tải menu...</span>
            </div>
        </li>
    </ul>

    <div class="sidebar-footer p-3 mt-auto">
        <button type="button" class="btn btn-outline-warning w-100 btn-sm d-flex align-items-center justify-content-center py-2" onclick="handleLogout()" style="color: #fef08a; border-color: rgba(254, 240, 138, 0.4);">
            <i class="fa-solid fa-right-from-bracket me-2"></i>
            <span class="btn-logout-text fw-semibold">Đăng xuất</span>
        </button>
    </div>
</aside>

<script>
    (function applyInitialSidebarState() {
        const isCollapsed = localStorage.getItem('admin_sidebar_collapsed') === 'true';
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        
        if (sidebar && isCollapsed) {
            sidebar.classList.add('collapsed');
        }
        if (mainContent && isCollapsed) {
            mainContent.style.setProperty('margin-left', 'var(--sidebar-collapsed-width)', 'important');
            mainContent.style.setProperty('width', 'calc(100% - var(--sidebar-collapsed-width))', 'important');
        }
    })();

    window.toggleSidebarMenu = function() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        if (!sidebar) return;

        sidebar.classList.toggle('collapsed');
        const isCollapsed = sidebar.classList.contains('collapsed');

        localStorage.setItem('admin_sidebar_collapsed', isCollapsed ? 'true' : 'false');
        if (isCollapsed) {
            document.documentElement.classList.add('sidebar-is-collapsed');
        } else {
            document.documentElement.classList.remove('sidebar-is-collapsed');
        }

        document.querySelectorAll('.admin-sub-menu').forEach(m => m.style.setProperty('display', 'none', 'important'));

        if (mainContent) {
            if (isCollapsed) {
                mainContent.style.setProperty('margin-left', 'var(--sidebar-collapsed-width)', 'important');
                mainContent.style.setProperty('width', 'calc(100% - var(--sidebar-collapsed-width))', 'important');
            } else {
                mainContent.style.setProperty('margin-left', 'var(--sidebar-width)', 'important');
                mainContent.style.setProperty('width', 'calc(100% - var(--sidebar-width))', 'important');
            }
        }
    };

    window.toggleAdminSubmenu = function(submenuId, el, event) {
        if (event) event.stopPropagation();

        const sidebar = document.getElementById('sidebar');
        const isCollapsed = sidebar && sidebar.classList.contains('collapsed');
        const submenu = document.getElementById(submenuId);
        const arrow = el.querySelector('.submenu-arrow');

        if (!submenu) return;

        document.querySelectorAll('.admin-sub-menu').forEach(item => {
            if (item.id !== submenuId) item.style.setProperty('display', 'none', 'important');
        });

        const isClosed = (window.getComputedStyle(submenu).display === 'none');

        if (isClosed) {
            submenu.style.setProperty('display', 'block', 'important');
            if (arrow && !isCollapsed) arrow.style.transform = 'rotate(180deg)';
            el.classList.add('parent-active');
        } else {
            submenu.style.setProperty('display', 'none', 'important');
            if (arrow && !isCollapsed) arrow.style.transform = 'rotate(0deg)';
            el.classList.remove('parent-active');
        }
    };

    document.addEventListener('click', function(e) {
        const sidebar = document.getElementById('sidebar');
        if (sidebar && sidebar.classList.contains('collapsed')) {
            if (!e.target.closest('.sidebar-nav-item')) {
                document.querySelectorAll('.admin-sub-menu').forEach(item => {
                    item.style.setProperty('display', 'none', 'important');
                });
            }
        }
    });

    (function() {
        const currentPage = "<?php echo $currentPage; ?>";
        const currentSlug = "<?php echo $currentSlug; ?>";
        const urlParams = new URLSearchParams(window.location.search);

        const pathSegments = window.location.pathname.split('/');
        const appBasePath = pathSegments.length > 2 ? `/${pathSegments[1]}/` : '/';

        const currentUserId = (typeof getUserIdFromToken === 'function' ? getUserIdFromToken() : null) || sessionStorage.getItem('user_id') || 'default';
        const CACHE_KEY = `cached_sidebar_menus_${currentUserId}`;

        function escapeSidebarHtml(str) {
            if (!str) return '';
            return String(str).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
        }

        function resolveMenuUrl(rawLink) {
            if (!rawLink || rawLink === '#') return 'javascript:void(0);';
            if (rawLink.startsWith('http://') || rawLink.startsWith('https://')) return rawLink;
            const cleanLink = rawLink.replace(/^\/+/, '');
            return `${appBasePath}${cleanLink}`;
        }

        function checkIsActive(rawLink) {
            if (!rawLink || rawLink === '#') return false;
            const clean = rawLink.replace(/^\/+/, '');

            if (clean.includes('?')) {
                const [targetPath, targetQuery] = clean.split('?');
                const targetBase = targetPath.split('/').pop();
                const targetParams = new URLSearchParams(targetQuery);

                const isPathMatch = (currentPage === targetBase || currentSlug === targetBase);
                if (!isPathMatch) return false;

                for (const [key, val] of targetParams.entries()) {
                    if (urlParams.get(key) !== val) return false;
                }
                return true;
            }

            const cleanBase = clean.split('?')[0];
            const isBaseMatch = (currentPage === cleanBase || currentSlug === cleanBase);
            if (isBaseMatch && !urlParams.has('assigned')) {
                return true;
            }
            return false;
        }

        function renderMenuHTML(menus) {
            const sidebarList = document.getElementById('dynamicSidebarList');
            if (!sidebarList || !Array.isArray(menus) || menus.length === 0) return;

            const parentMenus = menus.filter(m => !m.parent_id || parseInt(m.parent_id, 10) === 0);
            const childMenus = menus.filter(m => m.parent_id && parseInt(m.parent_id, 10) > 0);

            let html = '';

            parentMenus.forEach(item => {
                const itemId = parseInt(item.sidebar_id, 10);
                const subItems = childMenus.filter(c => parseInt(c.parent_id, 10) === itemId);
                const hasChildren = subItems.length > 0;

                if (hasChildren) {
                    const isAnyChildActive = subItems.some(c => checkIsActive(c.menu_link));
                    const collapseId = `menuSub_${itemId}`;
                    const arrowStyle = isAnyChildActive ? 'transform: rotate(180deg);' : '';

                    html += `
                        <li class="sidebar-nav-item position-relative mb-1">
                            <a class="sidebar-nav-link d-flex align-items-center ${isAnyChildActive ? 'parent-active' : ''}" 
                               href="javascript:void(0);" 
                               title="${escapeSidebarHtml(item.menu_name)}"
                               onclick="window.toggleAdminSubmenu('${collapseId}', this, event)">
                                <i class="${escapeSidebarHtml(item.icon_class)} menu-icon"></i>
                                <span class="menu-item-text text-truncate">${escapeSidebarHtml(item.menu_name)}</span>
                                <i class="fa-solid fa-chevron-down submenu-arrow" style="${arrowStyle}"></i>
                            </a>
                            <ul class="admin-sub-menu shadow-sm" id="${collapseId}" style="${isAnyChildActive ? 'display: block;' : 'display: none;'}">
                                <li class="flyout-header text-truncate">${escapeSidebarHtml(item.menu_name)}</li>
                    `;

                    subItems.forEach(sub => {
                        const isSubActive = checkIsActive(sub.menu_link) ? 'active-sub' : '';
                        const finalSubHref = resolveMenuUrl(sub.menu_link);
                        html += `
                            <li>
                                <a class="admin-sub-link ${isSubActive}" href="${escapeSidebarHtml(finalSubHref)}" title="${escapeSidebarHtml(sub.menu_name)}">
                                    <i class="${escapeSidebarHtml(sub.icon_class || 'fa-solid fa-circle-dot')} me-2" style="font-size: 0.72rem;"></i>
                                    <span class="text-truncate">${escapeSidebarHtml(sub.menu_name)}</span>
                                </a>
                            </li>
                        `;
                    });

                    html += `
                            </ul>
                        </li>
                    `;
                } else {
                    const isActive = checkIsActive(item.menu_link) ? 'active-item' : '';
                    const finalHref = resolveMenuUrl(item.menu_link);
                    const isExternal = item.menu_link.startsWith('http://') || item.menu_link.startsWith('https://');

                    html += `
                        <li class="sidebar-nav-item mb-1">
                            <a class="sidebar-nav-link d-flex align-items-center ${isActive}" href="${escapeSidebarHtml(finalHref)}" title="${escapeSidebarHtml(item.menu_name)}" ${isExternal ? 'target="_blank"' : ''}>
                                <i class="${escapeSidebarHtml(item.icon_class)} menu-icon"></i>
                                <span class="menu-item-text text-truncate">${escapeSidebarHtml(item.menu_name)}</span>
                            </a>
                        </li>
                    `;
                }
            });

            sidebarList.innerHTML = html;
        }

        const cachedMenus = sessionStorage.getItem(CACHE_KEY);
        if (cachedMenus) {
            try {
                renderMenuHTML(JSON.parse(cachedMenus));
            } catch (e) {}
        }

        async function syncSidebarFromServer() {
            try {
                const apiUrl = `${appBasePath}index_api.php?detect=sidebar_get`;
                let response;

                if (typeof fetchWithAuth === 'function') {
                    response = await fetchWithAuth(apiUrl, { method: 'GET' });
                } else {
                    const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
                    response = await fetch(apiUrl, {
                        method: 'GET',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json'
                        }
                    });
                }

                const result = await response.json();
                if (response.ok && result.status === 'success' && Array.isArray(result.data)) {
                    sessionStorage.setItem(CACHE_KEY, JSON.stringify(result.data));
                    renderMenuHTML(result.data);
                }
            } catch (err) {
                console.error("Lỗi đồng bộ sidebar:", err);
            }
        }

        syncSidebarFromServer();
    })();
</script>