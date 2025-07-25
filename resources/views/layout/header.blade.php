<header class="modern-header">
    <div class="header-container">
        <!-- Mobile Menu Toggle -->
        <button class="mobile-toggle" id="mobileToggle">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </button>

        <!-- Breadcrumb Navigation -->
        <div class="breadcrumb-section">
            <nav class="breadcrumb-nav">
                <div class="breadcrumb-item">
                    <i class="fas fa-home breadcrumb-icon"></i>
                    <a href="{{route('admin.home')}}" class="breadcrumb-link">Dashboard</a>
                </div>
                <div class="breadcrumb-separator">
                    <i class="fas fa-chevron-right"></i>
                </div>
                <div class="breadcrumb-item active">
                    <span class="breadcrumb-current">{{ $pageTitle ?? 'Trang quản trị' }}</span>
                </div>
            </nav>
        </div>

        <!-- Header Actions -->
        <div class="header-actions">
            <!-- Quick Actions -->
            <div class="quick-actions">
                <button class="action-btn" title="Tìm kiếm nhanh">
                    <i class="fas fa-search"></i>
                </button>
                <button class="action-btn" title="Thông báo">
                    <i class="fas fa-plus"></i>
                </button>
            </div>


            <!-- User Profile -->
            <div class="user-dropdown">
                @guest
                    <a href="javascript:;" class="user-btn">
                        <div class="user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <span class="user-name">Đăng nhập</span>
                    </a>
                @endguest
                @auth
                    <button class="user-btn" id="userBtn">
                        <div class="user-avatar">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(\Illuminate\Support\Facades\Auth::user()->name) }}&background=3b82f6&color=fff&bold=true"
                                 alt="Avatar" class="avatar-img">
                        </div>
                        <div class="user-info">
                            <span class="user-name">{{ \Illuminate\Support\Facades\Auth::user()->name }}</span>
                            <span class="user-role">Administrator</span>
                        </div>
                        <i class="fas fa-chevron-down user-arrow"></i>
                    </button>
                    <div class="user-menu" id="userMenu">
                        <div class="user-menu-header">
                            <div class="user-menu-avatar">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(\Illuminate\Support\Facades\Auth::user()->name) }}&background=3b82f6&color=fff&bold=true"
                                     alt="Avatar" class="menu-avatar-img">
                            </div>
                            <div class="user-menu-info">
                                <h4 class="menu-user-name">{{ \Illuminate\Support\Facades\Auth::user()->name }}</h4>
                                <span class="menu-user-email">{{ \Illuminate\Support\Facades\Auth::user()->email ?? 'admin@example.com' }}</span>
                            </div>
                        </div>
                        <div class="user-menu-divider"></div>
                        <div class="user-menu-items">
                            <a href="#" class="user-menu-item">
                                <i class="fas fa-user"></i>
                                <span>Hồ sơ cá nhân</span>
                            </a>
                            <a href="#" class="user-menu-item">
                                <i class="fas fa-cog"></i>
                                <span>Cài đặt tài khoản</span>
                            </a>
                            <a href="#" class="user-menu-item">
                                <i class="fas fa-envelope"></i>
                                <span>Tin nhắn</span>
                            </a>
                            <a href="#" class="user-menu-item">
                                <i class="fas fa-question-circle"></i>
                                <span>Trợ giúp</span>
                            </a>
                        </div>
                        <div class="user-menu-divider"></div>
                        <div class="user-menu-footer">
                            <form method="post" action="{{route('logout')}}" class="logout-form">
                                @csrf
                                <button type="submit" class="logout-btn">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Đăng xuất</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</header>

<style>
    /* Modern Header Styles */
    .modern-header {
        position: fixed;
        top: 0;
        left: 280px;
        right: 0;
        height: 70px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        z-index: 999;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .header-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 100%;
        padding: 0 24px;
    }

    /* Mobile Toggle */
    .mobile-toggle {
        display: none;
        flex-direction: column;
        justify-content: center;
        width: 32px;
        height: 32px;
        background: none;
        border: none;
        cursor: pointer;
        padding: 4px;
    }

    .hamburger-line {
        width: 20px;
        height: 2px;
        background: #374151;
        margin: 2px 0;
        transition: all 0.3s ease;
        border-radius: 1px;
    }

    /* Breadcrumb */
    .breadcrumb-section {
        flex: 1;
        max-width: 400px;
    }

    .breadcrumb-nav {
        display: flex;
        align-items: center;
    }

    .breadcrumb-item {
        display: flex;
        align-items: center;
        color: #6b7280;
        font-size: 14px;
        font-weight: 500;
    }

    .breadcrumb-item.active {
        color: #111827;
    }

    .breadcrumb-icon {
        margin-right: 8px;
        font-size: 12px;
    }

    .breadcrumb-link {
        color: #6b7280;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .breadcrumb-link:hover {
        color: #3b82f6;
        text-decoration: none;
    }

    .breadcrumb-separator {
        margin: 0 12px;
        color: #d1d5db;
        font-size: 10px;
    }

    .breadcrumb-current {
        font-weight: 600;
    }

    /* Header Actions */
    .header-actions {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    /* Quick Actions */
    .quick-actions {
        display: flex;
        gap: 8px;
    }

    .action-btn {
        width: 40px;
        height: 40px;
        border: none;
        background: #f9fafb;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6b7280;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .action-btn:hover {
        background: #f3f4f6;
        color: #374151;
        transform: translateY(-1px);
    }

    /* Notifications */
    .notification-dropdown {
        position: relative;
    }

    .notification-btn {
        width: 40px;
        height: 40px;
        border: none;
        background: #f9fafb;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6b7280;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }

    .notification-btn:hover {
        background: #f3f4f6;
        color: #374151;
    }

    .notification-badge {
        position: absolute;
        top: -2px;
        right: -2px;
        width: 18px;
        height: 18px;
        background: #ef4444;
        color: white;
        border-radius: 50%;
        font-size: 10px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid white;
    }

    .notification-menu {
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        width: 320px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(0, 0, 0, 0.05);
        display: none;
        z-index: 1000;
    }

    .notification-menu.show {
        display: block;
        animation: slideDown 0.2s ease;
    }

    .notification-header {
        padding: 20px 20px 16px 20px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .notification-title {
        font-size: 16px;
        font-weight: 600;
        color: #111827;
        margin: 0;
    }

    .notification-count {
        font-size: 12px;
        color: #6b7280;
        background: #f3f4f6;
        padding: 4px 8px;
        border-radius: 6px;
    }

    .notification-list {
        max-height: 300px;
        overflow-y: auto;
    }

    .notification-item {
        display: flex;
        align-items: center;
        padding: 16px 20px;
        border-bottom: 1px solid #f9fafb;
        transition: background 0.2s ease;
        cursor: pointer;
    }

    .notification-item:hover {
        background: #f9fafb;
    }

    .notification-item:last-child {
        border-bottom: none;
    }

    .notification-avatar {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #3b82f6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-right: 12px;
        font-size: 14px;
    }

    .notification-content {
        flex: 1;
    }

    .notification-text {
        font-size: 14px;
        font-weight: 500;
        color: #111827;
        margin: 0 0 4px 0;
        line-height: 1.4;
    }

    .notification-time {
        font-size: 12px;
        color: #6b7280;
    }

    .notification-footer {
        padding: 16px 20px;
        border-top: 1px solid #f3f4f6;
        text-align: center;
    }

    .view-all-btn {
        color: #3b82f6;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: color 0.2s ease;
    }

    .view-all-btn:hover {
        color: #1d4ed8;
        text-decoration: none;
    }

    /* User Dropdown */
    .user-dropdown {
        position: relative;
    }

    .user-btn {
        display: flex;
        align-items: center;
        background: none;
        border: none;
        cursor: pointer;
        padding: 8px 12px;
        border-radius: 12px;
        transition: all 0.2s ease;
        text-decoration: none;
        color: inherit;
    }

    .user-btn:hover {
        background: #f9fafb;
        text-decoration: none;
        color: inherit;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #3b82f6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-right: 12px;
        overflow: hidden;
    }

    .avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .user-info {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        margin-right: 8px;
    }

    .user-name {
        font-size: 14px;
        font-weight: 600;
        color: #111827;
        line-height: 1.2;
    }

    .user-role {
        font-size: 12px;
        color: #6b7280;
        line-height: 1.2;
    }

    .user-arrow {
        font-size: 10px;
        color: #9ca3af;
        transition: transform 0.2s ease;
    }

    .user-btn.active .user-arrow {
        transform: rotate(180deg);
    }

    .user-menu {
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        width: 280px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(0, 0, 0, 0.05);
        display: none;
        z-index: 1000;
    }

    .user-menu.show {
        display: block;
        animation: slideDown 0.2s ease;
    }

    .user-menu-header {
        padding: 20px;
        display: flex;
        align-items: center;
    }

    .user-menu-avatar {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        overflow: hidden;
        margin-right: 12px;
    }

    .menu-avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .user-menu-info {
        flex: 1;
    }

    .menu-user-name {
        font-size: 16px;
        font-weight: 600;
        color: #111827;
        margin: 0 0 4px 0;
        line-height: 1.2;
    }

    .menu-user-email {
        font-size: 13px;
        color: #6b7280;
        line-height: 1.2;
    }

    .user-menu-divider {
        height: 1px;
        background: #f3f4f6;
        margin: 0 20px;
    }

    .user-menu-items {
        padding: 16px 0;
    }

    .user-menu-item {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        color: #374151;
        text-decoration: none;
        transition: all 0.2s ease;
        font-size: 14px;
    }

    .user-menu-item:hover {
        background: #f9fafb;
        color: #111827;
        text-decoration: none;
    }

    .user-menu-item i {
        width: 16px;
        margin-right: 12px;
        color: #6b7280;
    }

    .user-menu-footer {
        padding: 16px 20px;
    }

    .logout-form {
        margin: 0;
    }

    .logout-btn {
        display: flex;
        align-items: center;
        width: 100%;
        padding: 12px 0;
        background: none;
        border: none;
        color: #ef4444;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .logout-btn:hover {
        color: #dc2626;
    }

    .logout-btn i {
        width: 16px;
        margin-right: 12px;
    }

    /* Animations */
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .modern-header {
            left: 0;
        }

        .mobile-toggle {
            display: flex;
        }

        .breadcrumb-section {
            display: none;
        }

        .quick-actions {
            display: none;
        }

        .user-info {
            display: none;
        }
    }

    /* Content adjustment */
    body {
        margin: 0;
        padding-top: 70px;
    }

    .main-content {
        margin-left: 280px;
        padding: 24px;
        transition: all 0.3s ease;
    }

    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
            padding: 16px;
        }
    }
</style>

<script>
    // Modern Header JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        // Notification dropdown
        const notificationBtn = document.getElementById('notificationBtn');
        const notificationMenu = document.getElementById('notificationMenu');

        if (notificationBtn && notificationMenu) {
            notificationBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                // Close user menu if open
                const userMenu = document.getElementById('userMenu');
                const userBtn = document.getElementById('userBtn');
                if (userMenu && userBtn) {
                    userMenu.classList.remove('show');
                    userBtn.classList.remove('active');
                }

                // Toggle notification menu
                notificationMenu.classList.toggle('show');
            });
        }

        // User dropdown
        const userBtn = document.getElementById('userBtn');
        const userMenu = document.getElementById('userMenu');

        if (userBtn && userMenu) {
            userBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                // Close notification menu if open
                if (notificationMenu) {
                    notificationMenu.classList.remove('show');
                }

                // Toggle user menu
                userMenu.classList.toggle('show');
                this.classList.toggle('active');
            });
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (notificationMenu && !notificationMenu.contains(e.target) && !notificationBtn.contains(e.target)) {
                notificationMenu.classList.remove('show');
            }

            if (userMenu && !userMenu.contains(e.target) && !userBtn.contains(e.target)) {
                userMenu.classList.remove('show');
                if (userBtn) {
                    userBtn.classList.remove('active');
                }
            }
        });

        // Prevent dropdown from closing when clicking inside
        if (notificationMenu) {
            notificationMenu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }

        if (userMenu) {
            userMenu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }

        // Mobile toggle
        const mobileToggle = document.getElementById('mobileToggle');
        const sidebar = document.querySelector('.modern-sidebar');

        if (mobileToggle && sidebar) {
            mobileToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');
            });
        }
    });
</script>
