@extends('layout.customerApp')

@section('content')
    <div class="order-detail-container">
        <!-- Header Section -->
        <div class="order-header-section">
            <div class="container-full">
                <div class="header-content">
                    <div class="header-left">
                        <a href="{{ route('customer.view-orders') }}" class="back-button">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div class="order-info">
                            <h1 class="order-title">Đơn hàng #{{ $orders->first()->order_id }}</h1>
                            <p class="order-date">
                                <i class="fas fa-calendar-alt"></i>
                                Đặt ngày {{ \Carbon\Carbon::parse($orders->first()->order_date)->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>

                    <div class="header-right">
                        @php
                            $status = $orders->first()->status;
                            $statusClass = match($status) {
                                'Chờ xác nhận' => 'pending',
                                'Đã xác nhận' => 'confirmed',
                                'Đang giao' => 'shipping',
                                'Đã giao' => 'delivered',
                                'Đã nhận hàng' => 'received',
                                'Đã hủy' => 'cancelled',
                                'Chờ thanh toán' => 'waiting-payment',
                                'Đang xử lý thanh toán' => 'processing-payment',
                                default => 'default'
                            };
                        @endphp
                        <div class="status-badge status-{{ $statusClass }}">
                            <div class="status-icon">
                                @switch($status)
                                    @case('Chờ xác nhận')
                                        <i class="fas fa-clock"></i>
                                        @break
                                    @case('Đã xác nhận')
                                        <i class="fas fa-check-circle"></i>
                                        @break
                                    @case('Đang giao')
                                        <i class="fas fa-truck"></i>
                                        @break
                                    @case('Đã giao')
                                        <i class="fas fa-box"></i>
                                        @break
                                    @case('Đã nhận hàng')
                                        <i class="fas fa-check-double"></i>
                                        @break
                                    @case('Đã hủy')
                                        <i class="fas fa-times-circle"></i>
                                        @break
                                    @case('Chờ thanh toán')
                                        <i class="fas fa-money-bill-wave"></i>
                                        @break
                                    @case('Đang xử lý thanh toán')
                                        <i class="fas fa-spinner fa-spin"></i>
                                        @break
                                @endswitch
                            </div>
                            <span>{{ $status }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="container-full">
            <div class="content-layout">
                <!-- Left Column - Products & Summary -->
                <div class="main-column">
                    <!-- Products Section -->
                    <div class="section-card products-card">
                        <div class="card-header">
                            <div class="header-info">
                                <h3 class="card-title">
                                    <i class="fas fa-box-open"></i>
                                    Sản phẩm đã đặt
                                </h3>
                                <span class="product-count">{{ count($orders) }} sản phẩm</span>
                            </div>
                        </div>

                        <div class="products-list">
                            @foreach ($orders as $item)
                                <div class="product-item">
                                    <div class="product-image">
                                        <img src="{{ asset('image/'.$item->image) }}" alt="{{ $item->product_name }}">
                                        <div class="quantity-badge">{{ $item->quantity }}</div>
                                    </div>

                                    <div class="product-details">
                                        <div class="product-main-info">
                                            <h4 class="product-name">{{ $item->product_name }}</h4>
                                            <p class="product-code">Mã: {{ $item->product_code }}</p>
                                        </div>

                                        <div class="product-pricing">
                                            <div class="price-breakdown">
                                                <span class="unit-price">{{ number_format($item->price, 0, ',', '.') }}đ</span>
                                                <span class="multiply">×</span>
                                                <span class="quantity">{{ $item->quantity }}</span>
                                            </div>
                                            <div class="total-price">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</div>
                                        </div>

                                        <!-- Review Section -->
                                        @php
                                            $userReview = null;
                                            if (isset($feedbacks)) {
                                                foreach ($feedbacks as $feedback) {
                                                    if ($feedback->product_id == $item->product_id) {
                                                        $userReview = $feedback;
                                                        break;
                                                    }
                                                }
                                            }
                                        @endphp

                                        @if ($userReview)
                                            <div class="review-display">
                                                <div class="stars">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= $userReview->rating ? 'filled' : '' }}"></i>
                                                    @endfor
                                                </div>
                                                @if ($userReview->comment)
                                                    <p class="review-comment">{{ $userReview->comment }}</p>
                                                @endif
                                                @if ($orders->first()->status == 'Đã nhận hàng')
                                                    <a href="{{ route('customer.view-detail', $item->product_id) }}?review=edit" class="edit-review-btn">
                                                        Sửa đánh giá
                                                    </a>
                                                @endif
                                            </div>
                                        @else
                                            @if ($orders->first()->status == 'Đã nhận hàng')
                                                <a href="{{ route('customer.view-detail', $item->product_id) }}?review=new" class="add-review-btn">
                                                    <i class="fas fa-star"></i>
                                                    Đánh giá
                                                </a>
                                            @endif
                                        @endif

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="section-card summary-card">
                        <div class="card-header">
                            <div class="header-info">
                                <h3 class="card-title">
                                    <i class="fas fa-calculator"></i>
                                    Tổng kết đơn hàng
                                </h3>
                            </div>
                        </div>

                        @php
                            $totalPrice = $orders->sum(fn($item) => $item->price * $item->quantity);
                        @endphp

                        <div class="summary-content">
                            <div class="summary-grid">
                                <div class="summary-item">
                                    <span class="summary-label">Tổng tiền sản phẩm</span>
                                    <span class="summary-value">{{ number_format($totalPrice, 0, ',', '.') }}đ</span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label">Phí vận chuyển</span>
                                    <span class="summary-value free">Miễn phí</span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label">Giảm giá</span>
                                    <span class="summary-value">0đ</span>
                                </div>
                                <div class="summary-divider"></div>
                                <div class="summary-item total">
                                    <span class="summary-label">Tổng thanh toán</span>
                                    <span class="summary-value">{{ number_format($totalPrice, 0, ',', '.') }}đ</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Info & Actions -->
                <div class="sidebar-column">
                    <!-- Payment Status -->
                    <div class="section-card payment-card">
                        <div class="card-header">
                            <div class="header-info">
                                <h3 class="card-title">
                                    <i class="fas fa-credit-card"></i>
                                    Thanh toán
                                </h3>
                            </div>
                        </div>

                        <div class="payment-content">
                            <div class="payment-method">
                                <div class="info-row">
                                    <span class="info-label">Phương thức</span>
                                    <div class="method-badge">
                                        @switch($orders->first()->payment)
                                            @case('COD')
                                                <i class="fas fa-money-bill-wave"></i>
                                                <span>COD</span>
                                                @break
                                            @case('Banking')
                                                <i class="fas fa-university"></i>
                                                <span>Banking</span>
                                                @break
                                            @case('Chuyển khoản')
                                                <i class="fas fa-university"></i>
                                                <span>Chuyển khoản</span>
                                                @break
                                            @case('Thanh toán khi nhận hàng')
                                                <i class="fas fa-money-bill-wave"></i>
                                                <span>COD</span>
                                                @break
                                            @case('Momo')
                                                <i class="fas fa-mobile-alt"></i>
                                                <span>MoMo</span>
                                                @break
                                            @default
                                                <i class="fas fa-question-circle"></i>
                                                <span>{{ $orders->first()->payment }}</span>
                                        @endswitch
                                    </div>
                                </div>
                            </div>

                            <div class="payment-status">
                                <div class="info-row">
                                    <span class="info-label">Trạng thái</span>
                                    @php
                                        $paymentStatus = $orders->first()->payment_status;
                                        $statusClass = match($paymentStatus) {
                                            'Đã thanh toán' => 'paid',
                                            'Chưa thanh toán' => 'unpaid',
                                            'Thanh toán một phần' => 'partial',
                                            'Đã cọc' => 'partial',
                                            default => 'unknown'
                                        };
                                    @endphp
                                    <div class="payment-status-badge status-{{ $statusClass }}">
                                        @switch($paymentStatus)
                                            @case('Đã thanh toán')
                                                <i class="fas fa-check-circle"></i>
                                                @break
                                            @case('Chưa thanh toán')
                                                <i class="fas fa-clock"></i>
                                                @break
                                            @case('Thanh toán một phần')
                                                <i class="fas fa-exclamation-circle"></i>
                                                @break
                                            @case('Đã cọc')
                                                <i class="fas fa-exclamation-circle"></i>
                                                @break
                                            @default
                                                <i class="fas fa-question-circle"></i>
                                        @endswitch
                                        <span>{{ $paymentStatus ?? 'Chưa xác định' }}</span>
                                    </div>
                                </div>
                            </div>

                            @if($orders->first()->prepay || $orders->first()->postpaid)
                                <div class="payment-breakdown">
                                    @if($orders->first()->prepay)
                                        <div class="breakdown-item">
                                            <span class="breakdown-label">Đã trả trước</span>
                                            <span class="breakdown-amount paid">{{ number_format($orders->first()->prepay, 0, ',', '.') }}đ</span>
                                        </div>
                                    @endif

                                    @if($orders->first()->postpaid)
                                        <div class="breakdown-item">
                                            <span class="breakdown-label">Cần trả nốt</span>
                                            <span class="breakdown-amount pending">{{ number_format($orders->first()->postpaid, 0, ',', '.') }}đ</span>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Thêm nút thanh toán VNPay nếu trạng thái là "Chưa thanh toán" hoặc "Chờ thanh toán" -->
                            @if(in_array($orders->first()->payment_status, ['Chưa thanh toán']) || $orders->first()->status == 'Chờ thanh toán')
                                <div class="payment-actions">
                                    <form action="{{ route('vnpay.payment.existing') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="order_id" value="{{ $orders->first()->order_id }}">
                                        <button type="submit" class="payment-btn vnpay-btn">
                                            <div class="btn-icon">
                                                <img src="{{ asset('images/vnpay-logo.png') }}" alt="VNPay" onerror="this.src='https://sandbox.vnpayment.vn/paymentv2/Images/brands/logo.svg'">
                                            </div>
                                            <div class="btn-content">
                                                <span class="btn-title">Thanh toán ngay</span>
                                                <small class="btn-subtitle">
                                                    @if($orders->first()->payment === 'Chuyển khoản')
                                                        Thanh toán 100% ({{ number_format($orders->first()->total, 0, ',', '.') }}đ)
                                                    @elseif($orders->first()->payment === 'Thanh toán khi nhận hàng')
                                                        Đặt cọc 40% ({{ number_format($orders->first()->total * 0.4, 0, ',', '.') }}đ)
                                                    @endif
                                                </small>
                                            </div>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Customer Info -->
                    <div class="section-card customer-card">
                        <div class="card-header">
                            <div class="header-info">
                                <h3 class="card-title">
                                    <i class="fas fa-user"></i>
                                    Thông tin giao hàng
                                </h3>
                            </div>
                        </div>

                        @php $customerInfo = $orders->first(); @endphp

                        <div class="customer-content">
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-user-circle"></i>
                                    </div>
                                    <div class="info-details">
                                        <span class="info-label">Người nhận</span>
                                        <span class="info-value">{{ $customerInfo->customer_name }}</span>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <div class="info-details">
                                        <span class="info-label">Điện thoại</span>
                                        <span class="info-value">{{ $customerInfo->phone }}</span>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="info-details">
                                        <span class="info-label">Email</span>
                                        <span class="info-value">{{ $customerInfo->email }}</span>
                                    </div>
                                </div>

                                <div class="info-item full-width">
                                    <div class="info-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="info-details">
                                        <span class="info-label">Địa chỉ</span>
                                        <span class="info-value">{{ $customerInfo->address }}</span>
                                    </div>
                                </div>

                                <!-- Tracking Info moved here -->
                                @if ($customerInfo->landing_code)
                                    <div class="info-item full-width tracking-item">
                                        <div class="info-icon">
                                            <i class="fas fa-shipping-fast"></i>
                                        </div>
                                        <div class="info-details">
                                            <span class="info-label">Vận chuyển</span>
                                            <div class="tracking-info">
                                                <div class="shipping-unit">{{ $customerInfo->shipping_unit ?? 'Nội bộ' }}</div>
                                                @if ($customerInfo->shipping_unit === 'SPX')
                                                    <a href="https://spx.vn/track?{{ $customerInfo->landing_code }}" target="_blank" class="tracking-link">
                                                        {{ $customerInfo->landing_code }}
                                                        <i class="fas fa-external-link-alt"></i>
                                                    </a>
                                                @elseif ($customerInfo->shipping_unit === 'GHTK')
                                                    <a href="https://i.ghtk.vn/{{ $customerInfo->landing_code }}" target="_blank" class="tracking-link">
                                                        {{ $customerInfo->landing_code }}
                                                        <i class="fas fa-external-link-alt"></i>
                                                    </a>
                                                @else
                                                    <span class="tracking-code">{{ $customerInfo->landing_code }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if ($status == 'Đã hủy' && $customerInfo->note)
                                <div class="cancel-reason">
                                    <div class="cancel-icon">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                    <div class="cancel-content">
                                        <span class="cancel-label">Lý do hủy đơn</span>
                                        <p class="cancel-text">{{ $customerInfo->note }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="section-card actions-card">
                        <div class="card-header">
                            <div class="header-info">
                                <h3 class="card-title">
                                    <i class="fas fa-cogs"></i>
                                    Thao tác
                                </h3>
                            </div>
                        </div>

                        <div class="actions-content">
                            @if ($status == 'Đã giao')
                                <form action="{{ route('customer.done', $customerInfo->order_id) }}" method="POST" class="action-form">
                                    @csrf
                                    <button type="submit" class="action-btn btn-received">
                                        <i class="fas fa-check-circle"></i>
                                        <div class="btn-content">
                                            <span class="btn-title">Đã nhận hàng</span>
                                            <small class="btn-subtitle">Xác nhận đã nhận được hàng</small>
                                        </div>
                                    </button>
                                </form>
                            @endif

                            @if (in_array($status, ['Chờ xác nhận', 'Đã xác nhận']))
                                <button type="button" class="action-btn btn-cancel" data-toggle="modal" data-target="#cancelOrderModal">
                                    <i class="fas fa-times-circle"></i>
                                    <div class="btn-content">
                                        <span class="btn-title">Hủy đơn hàng</span>
                                        <small class="btn-subtitle">Hủy đơn hàng này</small>
                                    </div>
                                </button>
                            @endif

                            @if ($status == 'Đã nhận hàng')
                                <button type="button" class="action-btn btn-support">
                                    <i class="fas fa-headset"></i>
                                    <div class="btn-content">
                                        <span class="btn-title">Hỗ trợ</span>
                                        <small class="btn-subtitle">Liên hệ hỗ trợ khách hàng</small>
                                    </div>
                                </button>
                            @endif

                            <button type="button" class="action-btn btn-reorder">
                                <i class="fas fa-redo"></i>
                                <div class="btn-content">
                                    <span class="btn-title">Mua lại</span>
                                    <small class="btn-subtitle">Đặt lại đơn hàng này</small>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Order Modal -->
    <div class="modal fade" id="cancelOrderModal" tabindex="-1" role="dialog" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelOrderModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Hủy đơn hàng
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.cancel-order', ['order_id' => $orders->first()->order_id]) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="cancel-warning">
                            <p>Bạn có chắc chắn muốn hủy đơn hàng này không?</p>
                        </div>
                        <div class="form-group">
                            <label for="note">Lý do hủy đơn hàng *</label>
                            <textarea class="form-control" id="note" name="note" rows="4" placeholder="Vui lòng cho chúng tôi biết lý do bạn hủy đơn hàng..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Đóng
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i>Hủy đơn hàng
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        :root {
            --primary: #1e293b;
            --primary-light: #334155;
            --primary-dark: #0f172a;
            --secondary: #64748b;
            --success: #059669;
            --warning: #d97706;
            --danger: #dc2626;
            --info: #0891b2;
            --vnpay: #0066b3;

            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;

            --white: #ffffff;
            --black: #000000;

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

        .order-detail-container {
            min-height: 100vh;
            background: var(--gray-100);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .container-full {
            max-width: 100%;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Header Section */
        .order-header-section {
            background: var(--white);
            border-bottom: 2px solid var(--gray-200);
            padding: 2rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: var(--shadow-sm);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .back-button {
            width: 3rem;
            height: 3rem;
            background: var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-600);
            text-decoration: none;
            transition: all 0.2s ease;
            border: 1px solid var(--gray-300);
        }

        .back-button:hover {
            background: var(--gray-200);
            color: var(--gray-800);
            text-decoration: none;
        }

        .order-title {
            font-size: 2rem;
            font-weight: 800;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
        }

        .order-date {
            color: var(--gray-500);
            font-size: 0.875rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .status-badge {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 1.5rem;
            font-weight: 700;
            font-size: 0.875rem;
            border: 2px solid;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-badge.status-pending {
            background: #fef3c7;
            color: #92400e;
            border-color: #fbbf24;
        }

        .status-badge.status-confirmed {
            background: #dbeafe;
            color: #1e40af;
            border-color: #3b82f6;
        }

        .status-badge.status-shipping {
            background: #e0e7ff;
            color: #5b21b6;
            border-color: #8b5cf6;
        }

        .status-badge.status-delivered {
            background: #dcfce7;
            color: #166534;
            border-color: #22c55e;
        }

        .status-badge.status-received {
            background: #dcfce7;
            color: #166534;
            border-color: #22c55e;
        }

        .status-badge.status-cancelled {
            background: #fee2e2;
            color: #dc2626;
            border-color: #ef4444;
        }

        .status-badge.status-waiting-payment {
            background: #fef9c3;
            color: #854d0e;
            border-color: #eab308;
        }

        .status-badge.status-processing-payment {
            background: #e0f2fe;
            color: #0c4a6e;
            border-color: #0ea5e9;
        }

        .status-icon {
            font-size: 1.25rem;
        }

        /* Content Layout */
        .content-layout {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 2rem;
            padding: 2rem 0;
        }

        /* Section Cards */
        .section-card {
            background: var(--white);
            border: 1px solid var(--gray-300);
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .card-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--gray-300);
            background: var(--gray-50);
        }

        .header-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--gray-900);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .product-count {
            background: var(--primary);
            color: var(--white);
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 700;
        }

        /* Products */
        .products-list {
            padding: 2rem;
        }

        .product-item {
            display: flex;
            gap: 1.5rem;
            padding: 2rem 0;
            border-bottom: 1px solid var(--gray-200);
        }

        .product-item:last-child {
            border-bottom: none;
        }

        .product-image {
            position: relative;
            flex-shrink: 0;
        }

        .product-image img {
            width: 6rem;
            height: 6rem;
            object-fit: cover;
            border: 1px solid var(--gray-300);
        }

        .quantity-badge {
            position: absolute;
            top: -0.5rem;
            right: -0.5rem;
            background: var(--primary);
            color: var(--white);
            width: 1.5rem;
            height: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            border: 2px solid var(--white);
        }

        .product-details {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .product-main-info {
            flex: 1;
        }

        .product-name {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }

        .product-code {
            color: var(--gray-500);
            font-size: 0.875rem;
            margin: 0;
        }

        .product-pricing {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
        }

        .price-breakdown {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--gray-600);
            font-size: 0.875rem;
        }

        .total-price {
            font-weight: 700;
            color: var(--gray-900);
            font-size: 1.125rem;
        }

        /* Review Section */
        .review-section {
            margin-top: 1rem;
        }

        .review-display {
            background: var(--gray-50);
            padding: 1rem;
            border-left: 3px solid var(--primary);
        }

        .stars {
            margin-bottom: 0.5rem;
        }

        .stars i {
            color: var(--gray-300);
            font-size: 0.875rem;
            margin-right: 2px;
        }

        .stars i.filled {
            color: #fbbf24;
        }

        .review-comment {
            color: var(--gray-600);
            font-size: 0.875rem;
            margin-bottom: 0.75rem;
            line-height: 1.4;
        }

        .add-review-btn,
        .edit-review-btn {
            background: var(--primary);
            color: var(--white);
            border: none;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .add-review-btn:hover,
        .edit-review-btn:hover {
            background: var(--primary-light);
        }

        /* Summary */
        .summary-content {
            padding: 2rem;
        }

        .summary-grid {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
        }

        .summary-item.total {
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--gray-900);
            padding-top: 1rem;
            border-top: 2px solid var(--gray-300);
        }

        .summary-label {
            color: var(--gray-600);
        }

        .summary-value {
            font-weight: 600;
            color: var(--gray-900);
        }

        .summary-value.free {
            color: var(--success);
        }

        .summary-divider {
            height: 1px;
            background: var(--gray-200);
            margin: 0.5rem 0;
        }

        /* Payment Card */
        .payment-content {
            padding: 2rem;
        }

        .payment-method,
        .payment-status {
            margin-bottom: 1.5rem;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .info-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--gray-500);
        }

        .method-badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: var(--gray-100);
            border: 1px solid var(--gray-300);
            font-weight: 600;
            font-size: 0.875rem;
        }

        .payment-status-badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .payment-status-badge.status-paid {
            background: #dcfce7;
            color: #166534;
        }

        .payment-status-badge.status-unpaid {
            background: #fef3c7;
            color: #92400e;
        }

        .payment-status-badge.status-partial {
            background: #fef3c7;
            color: #92400e;
        }

        .payment-breakdown {
            background: var(--gray-50);
            padding: 1rem;
            border: 1px solid var(--gray-200);
            margin-bottom: 1.5rem;
        }

        .breakdown-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
        }

        .breakdown-label {
            color: var(--gray-600);
            font-size: 0.875rem;
        }

        .breakdown-amount.paid {
            color: var(--success);
            font-weight: 600;
        }

        .breakdown-amount.pending {
            color: var(--warning);
            font-weight: 600;
        }

        /* Payment Actions */
        .payment-actions {
            margin-top: 1.5rem;
        }

        .payment-btn {
            width: 100%;
            padding: 1rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 1rem;
            text-align: left;
        }

        .vnpay-btn {
            background: var(--vnpay);
            color: var(--white);
        }

        .vnpay-btn:hover {
            background: #004d8a;
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-icon {
            width: 3rem;
            height: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--white);
            padding: 0.5rem;
            flex-shrink: 0;
        }

        .btn-icon img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        /* Customer Info */
        .customer-content {
            padding: 2rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .info-item.full-width {
            grid-column: 1 / -1;
        }

        .info-item.tracking-item {
            background: var(--gray-50);
            padding: 1rem;
            border: 1px solid var(--gray-200);
        }

        .info-icon {
            width: 2.5rem;
            height: 2.5rem;
            background: var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-600);
            flex-shrink: 0;
            border: 1px solid var(--gray-300);
        }

        .info-details {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .info-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--gray-500);
        }

        .info-value {
            color: var(--gray-900);
            font-weight: 500;
            line-height: 1.4;
        }

        .tracking-info {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .shipping-unit {
            background: var(--primary);
            color: var(--white);
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 700;
            width: fit-content;
        }

        .tracking-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .tracking-link:hover {
            text-decoration: underline;
            color: var(--primary-light);
        }

        .tracking-code {
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--gray-900);
        }

        .cancel-reason {
            background: #fee2e2;
            padding: 1rem;
            border-left: 3px solid var(--danger);
            margin-top: 1.5rem;
            display: flex;
            gap: 0.75rem;
        }

        .cancel-icon {
            color: var(--danger);
            flex-shrink: 0;
        }

        .cancel-label {
            color: var(--danger);
            font-weight: 600;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .cancel-text {
            color: #7f1d1d;
            margin: 0;
            line-height: 1.4;
        }

        /* Actions */
        .actions-content {
            padding: 2rem;
        }

        .action-btn {
            width: 100%;
            padding: 1.5rem;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            text-align: left;
            border: 1px solid var(--gray-300);
        }

        .action-btn:last-child {
            margin-bottom: 0;
        }

        .action-btn i {
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .btn-content {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .btn-title {
            font-size: 0.875rem;
            font-weight: 700;
        }

        .btn-subtitle {
            font-size: 0.75rem;
            opacity: 0.8;
            font-weight: 400;
        }

        .btn-received {
            background: var(--success);
            color: var(--white);
        }

        .btn-received:hover {
            background: #047857;
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-cancel {
            background: var(--danger);
            color: var(--white);
        }

        .btn-cancel:hover {
            background: #b91c1c;
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-support {
            background: var(--info);
            color: var(--white);
        }

        .btn-support:hover {
            background: #0e7490;
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-reorder {
            background: var(--gray-100);
            color: var(--gray-700);
            border: 1px solid var(--gray-300);
        }

        .btn-reorder:hover {
            background: var(--gray-200);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        /* Modal */
        .modal-content {
            border: none;
            box-shadow: var(--shadow-xl);
        }

        .modal-header {
            border-bottom: 1px solid var(--gray-300);
            padding: 1.5rem;
            background: var(--gray-50);
        }

        .modal-title {
            font-weight: 700;
            color: var(--gray-900);
            display: flex;
            align-items: center;
        }

        .modal-body {
            padding: 2rem;
        }

        .modal-footer {
            border-top: 1px solid var(--gray-300);
            padding: 1.5rem;
            background: var(--gray-50);
        }

        .cancel-warning {
            background: #fee2e2;
            padding: 1rem;
            border-left: 3px solid var(--danger);
            margin-bottom: 1rem;
        }

        .cancel-warning p {
            color: #7f1d1d;
            margin: 0;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--gray-300);
            font-size: 0.875rem;
            transition: border-color 0.2s ease;
            resize: vertical;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(30, 41, 59, 0.1);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-secondary {
            background: var(--gray-500);
            color: var(--white);
        }

        .btn-secondary:hover {
            background: var(--gray-600);
        }

        .btn-danger {
            background: var(--danger);
            color: var(--white);
        }

        .btn-danger:hover {
            background: #b91c1c;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .content-layout {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .container-full {
                padding: 0 1rem;
            }
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .order-title {
                font-size: 1.5rem;
            }

            .product-item {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .product-pricing {
                flex-direction: column;
                gap: 0.5rem;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .info-item {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .action-btn {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>

    <script>
        // Confirmation for received order
        document.addEventListener('DOMContentLoaded', function() {
            const receivedForm = document.querySelector('.action-form');
            if (receivedForm) {
                receivedForm.addEventListener('submit', function(e) {
                    if (!confirm('Bạn có chắc chắn đã nhận được hàng không? Hành động này không thể hoàn tác.')) {
                        e.preventDefault();
                    }
                });
            }
        });
    </script>
@endsection
