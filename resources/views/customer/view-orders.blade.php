@extends('layout.customerApp')

@section('content')
    <div class="orders-page">
        <!-- Hero Section -->
        <div class="hero-section">
            <div class="container-wide">
                <div class="hero-content">
                    <div class="hero-text">
                        <h1 class="hero-title">
                            <i class="fas fa-shopping-bag"></i>
                            Đơn Hàng Của Tôi
                        </h1>
                        <p class="hero-subtitle">Theo dõi và quản lý tất cả đơn hàng một cách dễ dàng</p>
                    </div>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <span class="stat-number">{{ count($pendingOrders) + count($confirmedOrders) + count($shippingOrders) + count($completedOrders) + count($receivedOrders) + count($canceledOrders) }}</span>
                            <span class="stat-label">Tổng đơn hàng</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="container-wide">
            <!-- Status Navigation -->
            <div class="status-nav-wrapper">
                <div class="status-nav" id="orderStatusNav">
                    <button class="status-btn active" data-status="pending" data-count="{{ count($pendingOrders) }}">
                        <div class="status-icon pending">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="status-info">
                            <span class="status-name">Chờ xác nhận</span>
                            <span class="status-count">{{ count($pendingOrders) }} đơn</span>
                        </div>
                    </button>

                    <button class="status-btn" data-status="wait" data-count="{{ count($wait_payment) }}">
                        <div class="status-icon shipping">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="status-info">
                            <span class="status-name">Chờ thanh toán</span>
                            <span class="status-count">{{ count($wait_payment) }} đơn</span>
                        </div>
                    </button>

                    <button class="status-btn" data-status="confirmed" data-count="{{ count($confirmedOrders) }}">
                        <div class="status-icon confirmed">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="status-info">
                            <span class="status-name">Đã xác nhận</span>
                            <span class="status-count">{{ count($confirmedOrders) }} đơn</span>
                        </div>
                    </button>

                    <button class="status-btn" data-status="shipping" data-count="{{ count($shippingOrders) }}">
                        <div class="status-icon shipping">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div class="status-info">
                            <span class="status-name">Đang giao</span>
                            <span class="status-count">{{ count($shippingOrders) }} đơn</span>
                        </div>
                    </button>

                    <button class="status-btn" data-status="completed" data-count="{{ count($completedOrders) }}">
                        <div class="status-icon completed">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <div class="status-info">
                            <span class="status-name">Đã giao</span>
                            <span class="status-count">{{ count($completedOrders) }} đơn</span>
                        </div>
                    </button>

                    <button class="status-btn" data-status="received" data-count="{{ count($receivedOrders) }}">
                        <div class="status-icon received">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <div class="status-info">
                            <span class="status-name">Đã nhận</span>
                            <span class="status-count">{{ count($receivedOrders) }} đơn</span>
                        </div>
                    </button>

                    <button class="status-btn" data-status="canceled" data-count="{{ count($canceledOrders) }}">
                        <div class="status-icon canceled">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="status-info">
                            <span class="status-name">Đã hủy</span>
                            <span class="status-count">{{ count($canceledOrders) }} đơn</span>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Orders Content -->
            <div class="orders-content">
                <!-- Pending Orders -->
                <div class="orders-section active" id="pending-section">
                    @include('customer.partials.order-list', ['orders' => $pendingOrders, 'status' => 'pending'])
                </div>

                <div class="orders-section" id="wait-section">
                    @include('customer.partials.order-list', ['orders' => $wait_payment, 'status' => 'wait'])
                </div>

                <!-- Confirmed Orders -->
                <div class="orders-section" id="confirmed-section">
                    @include('customer.partials.order-list', ['orders' => $confirmedOrders, 'status' => 'confirmed'])
                </div>

                <!-- Shipping Orders -->
                <div class="orders-section" id="shipping-section">
                    @include('customer.partials.order-list', ['orders' => $shippingOrders, 'status' => 'shipping'])
                </div>

                <!-- Completed Orders -->
                <div class="orders-section" id="completed-section">
                    @include('customer.partials.order-list', ['orders' => $completedOrders, 'status' => 'completed'])
                </div>

                <!-- Received Orders -->
                <div class="orders-section" id="received-section">
                    @include('customer.partials.order-list', ['orders' => $receivedOrders, 'status' => 'received'])
                </div>

                <!-- Canceled Orders -->
                <div class="orders-section" id="canceled-section">
                    @include('customer.partials.order-list', ['orders' => $canceledOrders, 'status' => 'canceled'])
                </div>
            </div>
        </div>
    </div>

    <style>
        :root {
            --primary: #374151;
            --primary-dark: #1f2937;
            --primary-light: #f3f4f6;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #06b6d4;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .orders-page {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--gray-50) 0%, #ffffff 100%);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        /* Wide Container - Made much wider */
        .container-wide {
            max-width: 95%; /* Changed from 1200px to 95% of screen */
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--gray-800) 0%, var(--gray-900) 100%);
            color: white;
            padding: 2rem 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.1;
        }

        .hero-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .hero-title {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .hero-title i {
            font-size: 1.5rem;
        }

        .hero-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            font-weight: 400;
        }

        .hero-stats {
            text-align: center;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 900;
            line-height: 1;
        }

        .stat-label {
            font-size: 0.875rem;
            opacity: 0.8;
            margin-top: 0.25rem;
        }

        /* Status Navigation - Full width */
        .status-nav-wrapper {
            margin: -1.5rem 0 3rem 0;
            position: relative;
            z-index: 10;
        }

        .status-nav {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); /* Increased min width */
            gap: 1rem;
            background: white;
            padding: 2rem; /* Increased padding */
            border-radius: 0.25rem;
            box-shadow: var(--shadow-xl);
        }

        .status-btn {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.25rem; /* Increased padding */
            border: 2px solid var(--gray-200);
            border-radius: 0.25rem;
            background: white;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-align: left;
        }

        .status-btn:hover {
            border-color: var(--gray-700);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .status-btn.active {
            border-color: var(--gray-700);
            background: var(--gray-100);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .status-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
            flex-shrink: 0;
        }

        .status-icon.pending { background: var(--warning); }
        .status-icon.confirmed { background: var(--info); }
        .status-icon.shipping { background: var(--gray-700); }
        .status-icon.completed { background: var(--success); }
        .status-icon.received { background: #10b981; }
        .status-icon.canceled { background: var(--danger); }

        .status-info {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .status-name {
            font-weight: 600;
            color: var(--gray-800);
            font-size: 0.875rem;
        }

        .status-count {
            font-size: 0.75rem;
            color: var(--gray-500);
            margin-top: 0.125rem;
        }

        /* Orders Content - Full width */
        .orders-content {
            position: relative;
        }

        .orders-section {
            display: none;
            animation: fadeIn 0.3s ease-in-out;
        }

        .orders-section.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Modern Order Cards - Full width */
        .modern-orders-grid {
            display: grid;
            gap: 1.5rem;
        }

        .modern-order-card {
            background: white;
            border-radius: 0.25rem;
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid var(--gray-200);
        }

        .modern-order-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-4px);
        }

        .order-card-header {
            padding: 2rem; /* Increased padding */
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
        }

        .order-header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .order-id {
            font-weight: 700;
            color: var(--gray-800);
            font-size: 1.125rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .order-status-badge {
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Keep original status colors */
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-confirmed { background: #dbeafe; color: #1e40af; }
        .status-shipping { background: #e0e7ff; color: #5b21b6; }
        .status-completed { background: #d1fae5; color: #065f46; }
        .status-received { background: #dcfce7; color: #166534; }
        .status-canceled { background: #fee2e2; color: #991b1b; }

        .order-meta-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); /* Increased min width */
            gap: 1rem; /* Increased gap */
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
        }

        .meta-icon {
            color: var(--gray-400);
            width: 1rem;
            flex-shrink: 0;
        }

        .meta-value {
            color: var(--gray-700);
            font-weight: 500;
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .order-card-body {
            padding: 2rem; /* Increased padding */
        }

        .order-summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .order-total {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--gray-800);
        }

        .order-date {
            color: var(--gray-500);
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .order-note {
            background: var(--gray-50);
            padding: 1rem; /* Increased padding */
            border-radius: 0.25rem;
            margin-bottom: 1rem;
            border-left: 3px solid var(--gray-700);
        }

        .order-actions {
            display: flex;
            gap: 1rem; /* Increased gap */
            flex-wrap: wrap;
        }

        /* Dark buttons */
        .btn-modern {
            padding: 0.875rem 2rem; /* Increased padding */
            border-radius: 0.25rem;
            font-weight: 600;
            font-size: 0.875rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }

        .btn-outline {
            background: white;
            color: var(--gray-700);
            border: 2px solid var(--gray-700);
        }

        .btn-outline:hover {
            background: var(--gray-700);
            color: white;
            transform: translateY(-1px);
        }

        .btn-solid {
            background: var(--gray-700);
            color: white;
            border: 2px solid var(--gray-700);
        }

        .btn-solid:hover {
            background: var(--gray-800);
            border-color: var(--gray-800);
            transform: translateY(-1px);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 0.25rem;
            box-shadow: var(--shadow);
        }

        .empty-icon {
            font-size: 4rem;
            color: var(--gray-300);
            margin-bottom: 1.5rem;
        }

        .empty-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gray-800);
            margin-bottom: 0.5rem;
        }

        .empty-text {
            color: var(--gray-500);
            margin-bottom: 2rem;
        }

        /* Order Details Dropdown */
        .order-details-dropdown {
            background: var(--gray-50);
            border-top: 1px solid var(--gray-200);
            padding: 2rem; /* Increased padding */
            display: none;
        }

        .order-details-dropdown.show {
            display: block;
            animation: slideDown 0.3s ease;
        }

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
        @media (max-width: 1200px) {
            .container-wide {
                max-width: 98%;
            }
        }

        @media (max-width: 768px) {
            .container-wide {
                max-width: 100%;
                padding: 0 0.5rem;
            }

            .hero-title {
                font-size: 1.75rem;
            }

            .hero-content {
                flex-direction: column;
                gap: 1.5rem;
                text-align: center;
            }

            .status-nav {
                grid-template-columns: 1fr;
                gap: 0.75rem;
                padding: 1rem;
            }

            .status-btn {
                padding: 0.75rem;
            }

            .order-meta-grid {
                grid-template-columns: 1fr;
            }

            .order-actions {
                flex-direction: column;
            }

            .btn-modern {
                justify-content: center;
            }

            .order-card-header,
            .order-card-body,
            .order-details-dropdown {
                padding: 1rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Status navigation functionality
            const statusButtons = document.querySelectorAll('.status-btn');
            const orderSections = document.querySelectorAll('.orders-section');

            statusButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const status = this.dataset.status;

                    // Remove active class from all buttons
                    statusButtons.forEach(btn => btn.classList.remove('active'));

                    // Add active class to clicked button
                    this.classList.add('active');

                    // Hide all sections
                    orderSections.forEach(section => {
                        section.classList.remove('active');
                    });

                    // Show target section
                    const targetSection = document.getElementById(status + '-section');
                    if (targetSection) {
                        targetSection.classList.add('active');
                    }
                });
            });

            // Toggle order details
            window.toggleOrderDetails = function(orderId) {
                const dropdown = document.getElementById('details-' + orderId);
                const icon = document.getElementById('icon-' + orderId);
                const text = document.getElementById('text-' + orderId);

                if (!dropdown || !icon || !text) return;

                const isShowing = dropdown.classList.contains('show');

                // Hide all other dropdowns and reset their buttons
                document.querySelectorAll('.order-details-dropdown.show').forEach(el => {
                    if (el.id !== 'details-' + orderId) {
                        el.classList.remove('show');
                    }
                });

                // Reset all other buttons
                document.querySelectorAll('[id^="icon-"]').forEach(otherIcon => {
                    if (otherIcon.id !== 'icon-' + orderId) {
                        otherIcon.classList.remove('fa-chevron-up');
                        otherIcon.classList.add('fa-chevron-down');
                    }
                });

                document.querySelectorAll('[id^="text-"]').forEach(otherText => {
                    if (otherText.id !== 'text-' + orderId) {
                        otherText.textContent = 'Xem nhanh';
                    }
                });

                // Toggle current dropdown
                if (isShowing) {
                    dropdown.classList.remove('show');
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                    text.textContent = 'Xem nhanh';
                } else {
                    dropdown.classList.add('show');
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                    text.textContent = 'Ẩn bớt';
                }
            };
        });
    </script>
@endsection
