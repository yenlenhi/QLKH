<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$currentUri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$currentSlug = basename($currentUri);
?>
<style>
    :root {
        --sidebar-width: 260px;
    }

    .sidebar {
        width: var(--sidebar-width);
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        background-color: #1e293b;
        color: #fff;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .sidebar.collapsed {
        transform: translateX(-100%);
    }

    .btn-toggle-sidebar {
        position: absolute;
        top: 16px;
        right: -32px;
        width: 32px;
        height: 32px;
        background: #ffffff !important;
        color: #0f172a;
        border: 1px solid #cbd5e1;
        border-left: none;
        border-radius: 0 6px 6px 0;
        cursor: pointer;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.2s, border-color 0.2s;
        z-index: 1001;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.05);
    }

    .btn-toggle-sidebar:hover {
        color: #2563eb;
        border-color: #2563eb;
    }

    .main-wrapper {
        margin-left: var(--sidebar-width);
        width: calc(100% - var(--sidebar-width));
        min-height: 100vh;
        transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1), width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-sizing: border-box;
    }

    .main-wrapper.expanded {
        margin-left: 0 !important;
        width: 100% !important;
    }

    .r3-menu-item {
        padding: 12px 20px;
        cursor: pointer;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .r3-menu-item:hover {
        background: rgba(255, 255, 255, 0.05);
        color: #ffffff;
    }

    .r3-menu-item.active {
        background: #2563eb;
        color: #ffffff;
    }
</style>

<!-- SIDEBAR R3 ĐỘNG -->
<aside class="sidebar" id="sidebar">
    <button class="btn-toggle-sidebar" id="toggleBtn" onclick="window.toggleSidebar()" title="Ẩn/Hiện Menu">
        ◀
    </button>
    <div>
        <div class="sidebar-brand" style="padding: 20px; font-weight: bold; font-size: 1.1rem; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-graduation-cap" style="color: #38bdf8;"></i> NCKH PORTAL
        </div>
        <ul class="sidebar-menu" id="r3DynamicSidebarList" style="list-style: none; padding: 0; margin: 10px 0;">
            <!-- Mặc định vẽ sẵn mục Đề tài để không bị giật, link thân thiện dẫn về de-tai-nckh -->
            <li class="r3-menu-item active" onclick="window.location.href='/QLNCKH/de-tai-nckh'">
                <i class="fa-solid fa-folder-open" style="width: 20px;"></i> ĐỀ TÀI NCKH
            </li>
        </ul>
    </div>
    <div style="padding: 15px; border-top: 1px solid rgba(255,255,255,0.1); margin-top: auto;">
        <a href="javascript:void(0)" onclick="clearSessionAndRedirect()" class="btn-action danger" style="display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; width: 100%; box-sizing: border-box; padding: 10px 0; color: #ef4444; border: 1px solid #ef4444; border-radius: 6px; font-weight: 600;">
            <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
        </a>
    </div>
</aside>

<script>
    window.toggleSidebar = function() {
        const sidebar = document.getElementById('sidebar');
        const mainWrapper = document.getElementById('mainWrapper') || document.getElementById('main-content');
        const toggleBtn = document.getElementById('toggleBtn');

        if (sidebar && mainWrapper) {
            sidebar.classList.toggle('collapsed');
            mainWrapper.classList.toggle('expanded');
            if (toggleBtn) {
                toggleBtn.innerText = sidebar.classList.contains('collapsed') ? '▶' : '◀';
            }
        }
    };

    (function() {
        const CACHE_R3_MENU = 'cached_r3_sub_system_menus';
        const currentPage = "<?= $currentPage ?>";
        const currentSlug = "<?= $currentSlug ?>";

        // Tự động nhận diện đường dẫn gốc (/QLNCKH/) giống sidebar.php
        const pathSegments = window.location.pathname.split('/');
        const appBasePath = pathSegments.length > 2 ? `/${pathSegments[1]}/` : '/';
        const API_URL = `${appBasePath}index_api.php`;

        // Chuẩn hóa đường dẫn link thân thiện cho R3
        function resolveR3Url(link) {
            if (!link || link === 'r3_home.php') {
                return `${appBasePath}de-tai-nckh`;
            }
            if (link.startsWith('http://') || link.startsWith('https://')) {
                return link;
            }
            const clean = link.replace(/^\/+/, '');
            return `${appBasePath}${clean}`;
        }

        function isMenuActive(link) {
            const raw = link || 'de-tai-nckh';
            const clean = raw.replace(/^\/+/, '');

            // Nếu là trang Đề tài NCKH, vẫn active khi đứng ở các trang chi tiết/cập nhật đề tài
            if (clean === 'de-tai-nckh' || clean === 'r3_home.php') {
                return ['r3_home.php', 'r3_project_detail.php', 'r3_project_update.php'].includes(currentPage) || ['de-tai-nckh', 'chi-tiet-de-tai', 'cap-nhat-de-tai'].includes(currentSlug);
            }

            return (currentPage === clean || currentSlug === clean);
        }

        function renderR3Menu(items) {
            const listEl = document.getElementById('r3DynamicSidebarList');
            if (!listEl || !Array.isArray(items)) return;

            // Lọc các phân hệ được bật show_in_r3 = 1
            const visibleItems = items.filter(item => item.show_in_r3 === undefined || parseInt(item.show_in_r3) === 1);

            if (visibleItems.length === 0) return;

            listEl.innerHTML = visibleItems.map(item => {
                const rawLink = item.menu_link || 'de-tai-nckh';
                const targetUrl = resolveR3Url(rawLink);
                const icon = item.icon_class || 'fa-solid fa-folder-open';
                const activeClass = isMenuActive(rawLink) ? 'active' : '';
                const displayName = (item.code === 'de_tai_sinh_vien') ? 'ĐỀ TÀI NCKH' : (item.name || '').toUpperCase();

                return `
                    <li class="r3-menu-item ${activeClass}" onclick="window.location.href='${targetUrl}'">
                        <i class="${icon}" style="width: 20px;"></i> ${displayName}
                    </li>
                `;
            }).join('');
        }

        // 1. Vẽ tức thì từ Cache (Chống nhấp nháy)
        const cached = sessionStorage.getItem(CACHE_R3_MENU);
        if (cached) {
            try {
                renderR3Menu(JSON.parse(cached));
            } catch (e) {}
        }

        // 2. Đồng bộ ngầm từ API project_sup_get
        async function syncR3Sidebar() {
            try {
                const targetApi = `${API_URL}?detect=project_sup_get`;
                let response;

                if (typeof fetchWithAuth === 'function') {
                    response = await fetchWithAuth(targetApi, {
                        method: 'GET'
                    });
                } else {
                    const token = sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
                    response = await fetch(targetApi, {
                        method: 'GET',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json'
                        }
                    });
                }

                const result = await response.json();
                if (response.ok && result.status === 'success' && Array.isArray(result.data)) {
                    const newJson = JSON.stringify(result.data);
                    if (newJson !== sessionStorage.getItem(CACHE_R3_MENU)) {
                        sessionStorage.setItem(CACHE_R3_MENU, newJson);
                        renderR3Menu(result.data);
                    }
                }
            } catch (err) {
                console.error("Lỗi tải sidebar R3:", err);
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener("DOMContentLoaded", syncR3Sidebar);
        } else {
            syncR3Sidebar();
        }
    })();
</script>