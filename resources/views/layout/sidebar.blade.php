<nav id="sidebar" class="modern-sidebar">
    <div class="sidebar-container">
        <!-- Logo và tên ứng dụng -->
        <div class="sidebar-header">
            <a href="{{route('admin.home')}}" class="logo-container">
                <div class="logo-icon">
                    <i class="fas fa-store"></i>
                </div>
                <div class="logo-text">
                    <h1 class="app-name">Admin Panel</h1>
                    <span class="app-subtitle">Management System</span>
                </div>
            </a>
        </div>

        <!-- Navigation Menu -->
        <div class="sidebar-content">
            <ul class="nav-menu">
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.home')}}">
                        <div class="nav-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <span class="nav-text">Báo cáo</span>
                        <div class="nav-indicator"></div>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.products')}}">
                        <div class="nav-icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <span class="nav-text">Quản lý sản phẩm</span>
                        <div class="nav-indicator"></div>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.users')}}">
                        <div class="nav-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <span class="nav-text">Danh sách người dùng</span>
                        <div class="nav-indicator"></div>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.orders')}}">
                        <div class="nav-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <span class="nav-text">Quản lý đơn hàng</span>
                        <div class="nav-indicator"></div>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.categories-brands')}}">
                        <div class="nav-icon">
                            <i class="fas fa-th-large"></i>
                        </div>
                        <span class="nav-text">Danh mục & Thương hiệu</span>
                        <div class="nav-indicator"></div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.create-order')}}">
                        <div class="nav-icon">
                            <i class="fas fa-th-large"></i>
                        </div>
                        <span class="nav-text">Tạo đơn hàng</span>
                        <div class="nav-indicator"></div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('customer.show') }}">
                        <div class="nav-icon">
                            <i class="fas fa-th-large"></i>
                        </div>
                        <span class="nav-text">Thông tin cá nhân</span>
                        <div class="nav-indicator"></div>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{route("admin.statistics")}}">
                        <div class="nav-icon">
                            <i class="fas fa-th-large"></i>
                        </div>
                        <span class="nav-text">Thống kê doanh thu</span>
                        <div class="nav-indicator"></div>
                    </a>
                </li>

            </ul>
        </div>

        <!-- Footer Sidebar -->
        <div class="sidebar-footer">
            <div class="footer-divider"></div>
            <a href="#" class="settings-link">
                <div class="settings-icon">
                    <i class="fas fa-cog"></i>
                </div>
                <span class="settings-text">Cài đặt</span>
            </a>
            <div class="version-info">
                <span class="version-text">Version 0.0.1</span>
            </div>
        </div>
    </div>
</nav>

<style>
    /* Modern Sidebar Styles */
    .modern-sidebar {
        position: fixed;
        left: 0;
        top: 0;
        width: 280px;
        height: 100vh;
        background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
        z-index: 1000;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        box-shadow: 4px 0 24px rgba(0, 0, 0, 0.15);
    }

    .sidebar-container {
        display: flex;
        flex-direction: column;
        height: 100%;
        padding: 0;
    }

    /* Header */
    .sidebar-header {
        padding: 24px 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .logo-container {
        display: flex;
        align-items: center;
        text-decoration: none;
        color: white;
        transition: all 0.3s ease;
    }

    .logo-container:hover {
        transform: translateY(-1px);
        color: white;
        text-decoration: none;
    }

    .logo-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 16px;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .logo-icon i {
        font-size: 20px;
        color: white;
    }

    .logo-text {
        flex: 1;
    }

    .app-name {
        font-size: 20px;
        font-weight: 700;
        margin: 0;
        color: white;
        line-height: 1.2;
    }

    .app-subtitle {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.6);
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Content */
    .sidebar-content {
        flex: 1;
        padding: 20px 0;
        overflow-y: auto;
    }

    .nav-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .nav-item {
        margin: 0 16px 8px 16px;
        position: relative;
    }

    .nav-link {
        display: flex;
        align-items: center;
        padding: 14px 16px;
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        font-weight: 500;
    }

    .nav-link::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(29, 78, 216, 0.1) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .nav-link:hover {
        color: white;
        text-decoration: none;
        transform: translateX(4px);
    }

    .nav-link:hover::before {
        opacity: 1;
    }

    .nav-link.active {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .nav-link.active::before {
        opacity: 0;
    }

    .nav-icon {
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        transition: all 0.3s ease;
    }

    .nav-icon i {
        font-size: 16px;
    }

    .nav-text {
        flex: 1;
        font-size: 14px;
        line-height: 1.4;
    }

    .nav-indicator {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.4);
        opacity: 0;
        transition: all 0.3s ease;
    }

    .nav-link.active .nav-indicator {
        opacity: 1;
        background: white;
        box-shadow: 0 0 8px rgba(255, 255, 255, 0.5);
    }

    /* Footer */
    .sidebar-footer {
        padding: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .footer-divider {
        height: 1px;
        background: rgba(255, 255, 255, 0.1);
        margin-bottom: 16px;
    }

    .settings-link {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        color: rgba(255, 255, 255, 0.6);
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.3s ease;
        margin-bottom: 12px;
    }

    .settings-link:hover {
        color: rgba(255, 255, 255, 0.9);
        background: rgba(255, 255, 255, 0.05);
        text-decoration: none;
    }

    .settings-icon {
        width: 16px;
        height: 16px;
        margin-right: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .settings-text {
        font-size: 13px;
        font-weight: 500;
    }

    .version-info {
        text-align: center;
        padding-top: 8px;
    }

    .version-text {
        font-size: 11px;
        color: rgba(255, 255, 255, 0.4);
        font-weight: 500;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .modern-sidebar {
            width: 100%;
            transform: translateX(-100%);
        }

        .modern-sidebar.show {
            transform: translateX(0);
        }
    }

    /* Scrollbar */
    .sidebar-content::-webkit-scrollbar {
        width: 4px;
    }

    .sidebar-content::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
    }

    .sidebar-content::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 2px;
    }

    .sidebar-content::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.3);
    }
</style>

<script>
    // Modern Sidebar JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        // Get current URL
        var currentUrl = window.location.href;

        // Loop through menu links to find the corresponding link for the current page
        var menuLinks = document.querySelectorAll('.nav-link');
        menuLinks.forEach(function(link) {
            // Compare the link URL with the current page URL
            if (link.href === currentUrl) {
                // Add active class
                link.classList.add('active');
            }
        });

        // Add hover effects
        menuLinks.forEach(function(link) {
            link.addEventListener('mouseenter', function() {
                if (!this.classList.contains('active')) {
                    this.style.background = 'rgba(255, 255, 255, 0.08)';
                }
            });

            link.addEventListener('mouseleave', function() {
                if (!this.classList.contains('active')) {
                    this.style.background = '';
                }
            });
        });
    });
</script>
