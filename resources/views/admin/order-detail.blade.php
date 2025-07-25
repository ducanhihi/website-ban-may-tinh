@extends('layout.app')
@section('content')
    <main id="main" class="main">
        <!-- Fixed Sticky Header -->

                <div class="header-left">
                    <h1 class="page-title">
                        <i class="bi bi-receipt"></i>
                        Chi Tiết Đơn Hàng #{{ $orders->first()->order_id }}
                    </h1>
                </div>

        <div class="content-wrapper">
            <div class="row">
                <!-- Order Items Section -->
                <div class="col-lg-8 mb-4">
                    <div class="modern-card">
                        <div class="card-header">
                            <h3>
                                <i class="bi bi-bag-check"></i>
                                Sản phẩm trong đơn hàng
                            </h3>

                            <div class="header-right">
                                <a href="{{ route('admin.print-invoice', $orders->first()->order_id) }}"
                                   target="_blank"
                                   class="btn-print">
                                    <i class="bi bi-printer"></i>
                                    <span>In hóa đơn</span>
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            @foreach ($orders as $item)
                                <div class="product-item">
                                    <div class="product-image">
                                        <img src="{{ asset('image/'.$item->image) }}"
                                             alt="{{ $item->image }}"
                                             class="product-img">
                                    </div>
                                    <div class="product-details">
                                        <div class="product-info">
                                            <h4 class="product-name">{{ $item->product_name }}</h4>
                                            <div class="product-meta">
                                                <span class="product-price">{{ number_format($item->price, 0, ',', '.')}} VNĐ</span>
                                                <span class="product-quantity">
                                                    <i class="bi bi-x"></i> {{ $item->quantity }}
                                                </span>
                                                <span class="product-total">{{ number_format($item->price * $item->quantity, 0, ',', '.') }} VNĐ</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if (!$loop->last)
                                    <hr class="product-divider">
                                @endif
                            @endforeach

                            @php
                                $totalPrice = 0;
                                foreach ($orders as $item) {
                                    $totalPrice += $item->price * $item->quantity;
                                }
                            @endphp
                        </div>
                        <div class="card-footer">
                            <div class="order-summary">
                                <div class="summary-row">
                                    <span class="summary-label">Tổng tiền sản phẩm:</span>
                                    <span class="summary-value">{{ number_format($totalPrice, 0, ',', '.') }} VNĐ</span>
                                </div>
                                <div class="summary-row">
                                    <span class="summary-label">Phí vận chuyển:</span>
                                    <span class="summary-value">0 VNĐ</span>
                                </div>
                                <div class="summary-row total-row">
                                    <span class="summary-label">Tổng thanh toán:</span>
                                    <span class="summary-value">{{ number_format($totalPrice, 0, ',', '.') }} VNĐ</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Information Section -->
                <div class="col-lg-4 mb-4">
                    <div class="modern-card">
                        <div class="card-header">
                            <h3>
                                <i class="bi bi-person-circle"></i>
                                Thông tin khách hàng
                            </h3>
                        </div>
                        <div class="card-body">
                            @php
                                $customerInfo = [];
                            @endphp

                            @foreach ($orders as $order)
                                @php
                                    if (!isset($customerInfo[$order->customer_name])) {
                                        $customerInfo[$order->customer_name] = [
                                            'name' => $order->customer_name,
                                            'phone' => $order->phone,
                                            'email' => $order->email,
                                            'address' => $order->address
                                        ];
                                    }
                                @endphp
                            @endforeach

                            @foreach ($customerInfo as $customer)
                                <div class="customer-info">
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="bi bi-person"></i>
                                            Tên khách hàng
                                        </div>
                                        <div class="info-value">{{ $customer['name'] }}</div>
                                    </div>

                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="bi bi-envelope"></i>
                                            Email
                                        </div>
                                        <div class="info-value">{{ $customer['email'] }}</div>
                                    </div>

                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="bi bi-telephone"></i>
                                            Số điện thoại
                                        </div>
                                        <div class="info-value">{{ $customer['phone'] }}</div>
                                    </div>

                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="bi bi-geo-alt"></i>
                                            Địa chỉ giao hàng
                                        </div>
                                        <div class="info-value">{{ $customer['address'] }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Payment Information Card -->
                    <div class="modern-card">
                        <div class="card-header">
                            <h3>
                                <i class="bi bi-credit-card"></i>
                                Thông tin thanh toán
                            </h3>
                        </div>
                        <div class="card-body">
                            @php
                                $firstOrder = $orders->first();
                            @endphp
                            <div class="payment-info">
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-check-circle"></i>
                                        Trạng thái thanh toán
                                    </div>
                                    <div class="info-value">
                                        <span class="payment-status {{ $firstOrder->payment_status == 'paid' ? 'status-paid' : ($firstOrder->payment_status == 'unpaid' ? 'status-unpaid' : 'status-pending') }}">
                                            {{ $firstOrder->payment_status ?? 'Chưa xác định' }}
                                        </span>
                                    </div>
                                </div>

                                @if($firstOrder->prepay && $firstOrder->prepay > 0)
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="bi bi-cash-coin"></i>
                                            Tiền trả trước
                                        </div>
                                        <div class="info-value">{{ number_format($firstOrder->prepay, 0, ',', '.') }} VNĐ</div>
                                    </div>
                                @endif

                                @if($firstOrder->postpaid && $firstOrder->postpaid > 0)
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="bi bi-cash-stack"></i>
                                            Tiền trả sau
                                        </div>
                                        <div class="info-value">{{ number_format($firstOrder->postpaid, 0, ',', '.') }} VNĐ</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Status Section -->
            <div class="row">
                <div class="col-12">
                    <div class="modern-card">
                        <div class="card-header">
                            <h3>
                                <i class="bi bi-clock-history"></i>
                                Trạng thái đơn hàng
                            </h3>
                        </div>
                        <div class="card-body">
                            @php
                                $stt = [];
                                foreach ($orders as $order) {
                                    if (!isset($stt[$order->status])) {
                                        $stt[$order->status] = [
                                            'status' => $order->status,
                                            'button_text' => $order->status
                                        ];
                                        if ($order->status == 'Chờ xác nhận') {
                                            $stt[$order->status]['button_text'] = 'Xác nhận đơn hàng';
                                        }
                                    }
                                }
                            @endphp

                            @foreach ($stt as $item)
                                <div class="status-section">
                                    <div class="status-info">
                                        <div class="status-current">
                                            <span class="status-label">Trạng thái hiện tại:</span>
                                            <span class="status-badge {{ app('App\Http\Controllers\OrderController')->getOrderStatusClass($item['status']) }}">
                                                <i class="bi bi-circle-fill"></i>
                                                {{ $item['status'] }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="status-actions">
                                        @if ($item['status'] != 'Đã hủy' && $item['status'] != 'Đã giao' && $item['status'] != 'Đang giao' && $item['status'] != 'Đã xác nhận' && $item['status'] != 'Đã nhận hàng')
                                            <form action="{{ route('admin.update-order', ['order_id' => $order->order_id]) }}" method="POST" class="action-form">
                                                @csrf
                                                <button type="submit" class="btn btn-confirm">
                                                    <i class="bi bi-check-circle"></i>
                                                    Xác nhận đơn hàng
                                                </button>
                                            </form>
                                        @endif

                                        @if ($item['status'] != 'Đã hủy' && $item['status'] != 'Chờ xác nhận')
                                            <form action="{{ route('admin.update-after-accept', ['order_id' => $order->order_id]) }}" method="POST" class="action-form">
                                                @csrf
                                                <button type="submit" class="btn btn-update">
                                                    <i class="bi bi-arrow-clockwise"></i>
                                                    Cập nhật đơn hàng
                                                </button>
                                            </form>
                                        @endif

                                        @if ($item['status'] != 'Đã hủy' && $item['status'] != 'Đã giao' && $item['status'] != 'Đang giao' && $item['status'] != 'Đã nhận hàng')
                                            <button type="button" class="btn btn-cancel" data-toggle="modal" data-target="#cancelOrderModal">
                                                <i class="bi bi-x-circle"></i>
                                                Hủy đơn hàng
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Hủy đơn hàng -->
        <div class="modal fade" id="cancelOrderModal" tabindex="-1" role="dialog" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content modern-modal">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cancelOrderModalLabel">
                            <i class="bi bi-exclamation-triangle"></i>
                            Xác nhận hủy đơn hàng
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('admin.cancel-order', ['order_id' => $order->order_id]) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="note" class="form-label">Lý do hủy đơn hàng</label>
                                <textarea class="form-control" id="note" name="note" rows="3" required placeholder="Nhập lý do hủy đơn hàng..."></textarea>
                                <small class="form-text">Lý do hủy sẽ được gửi đến khách hàng.</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <i class="bi bi-x"></i>Đóng
                            </button>
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-ban"></i>Hủy đơn hàng
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal XÁC NHẬN ĐƠN HÀNG -->
        <div id="acceptOrder" class="modal fade" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content modern-modal">
                    <form action="{{route('admin.create-product')}}" method="GET">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="bi bi-folder-plus"></i>Chọn Danh Mục
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="form-group">
                                <label class="form-label">
                                    Thể loại
                                    <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Chọn thể loại phù hợp với sản phẩm của bạn"></i>
                                </label>
                                <select class="form-control" id="categorySelect" name="category_id" required>
                                    <option value="">Chọn thể loại</option>
                                    @foreach ($categoryOptions as $categoryId => $categoryName)
                                        <option value="{{ $categoryId }}" data-href="{{ route('admin.create-product', ['category_id' => $categoryId]) }}">
                                            {{ $categoryName }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text">Vui lòng chọn thể loại phù hợp với sản phẩm của bạn.</small>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <i class="bi bi-x"></i>Hủy
                            </button>
                            <button type="submit" class="btn btn-primary" id="chooseButton">
                                <i class="bi bi-check"></i>Chọn
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <style>
        /* Modern Design Variables */
        :root {
            --primary-color: #6366f1;
            --secondary-color: #8b5cf6;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
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

        /* Reset and Base */
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--gray-50);
            color: var(--gray-900);
            line-height: 1.6;
        }

        /* Sticky Header - Cố định header khi scroll */
        .sticky-header {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 1.5rem 0;
            margin: 0 0 2rem 0;
            border-radius: 0 0 16px 16px;
            box-shadow: var(--shadow-lg);
            backdrop-filter: blur(10px);
        }

        /* Đảm bảo header không bị che bởi navigation chính */
        .main {
            padding-top: 0 !important;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 100%;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .page-title i {
            font-size: 1.5rem;
        }

        .breadcrumb {
            background: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 0.5rem;
        }

        .breadcrumb-item {
            list-style: none;
        }

        .breadcrumb-item a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.2s;
        }

        .breadcrumb-item a:hover {
            color: white;
        }

        .breadcrumb-item.active {
            color: rgba(255, 255, 255, 0.9);
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: '/';
            color: rgba(255, 255, 255, 0.6);
            margin: 0 0.5rem;
        }

        .btn-print {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-print:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            text-decoration: none;
        }

        /* Content Wrapper - Thêm padding-top để tránh bị che bởi sticky header */
        .content-wrapper {
            padding: 0 2rem;
            max-width: 100%;
            margin: 0 auto;
        }

        /* Modern Card */
        .modern-card {
            background: white;
            border-radius: 20px;
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .modern-card .card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 1.5rem 2rem;
            border: none;
        }

        .modern-card .card-header h3 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .modern-card .card-body {
            padding: 2rem;
        }

        .modern-card .card-footer {
            background: var(--gray-50);
            padding: 1.5rem 2rem;
            border-top: 1px solid var(--gray-200);
        }

        /* Product Items */
        .product-item {
            display: flex;
            gap: 1.5rem;
            align-items: center;
            padding: 1rem 0;
        }

        .product-image {
            flex-shrink: 0;
        }

        .product-img {
            width: 100px;
            height: 80px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            transition: transform 0.2s ease;
        }

        .product-img:hover {
            transform: scale(1.05);
        }

        .product-details {
            flex: 1;
        }

        .product-name {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
        }

        .product-meta {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .product-price {
            font-weight: 600;
            color: var(--gray-700);
        }

        .product-quantity {
            background: var(--gray-100);
            padding: 0.25rem 0.75rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--gray-700);
        }

        .product-total {
            font-weight: 700;
            color: var(--success-color);
            font-size: 1.125rem;
            margin-left: auto;
        }

        .product-divider {
            border: none;
            height: 1px;
            background: var(--gray-200);
            margin: 1rem 0;
        }

        /* Order Summary */
        .order-summary {
            max-width: 400px;
            margin-left: auto;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--gray-200);
        }

        .summary-row:last-child {
            border-bottom: none;
        }

        .summary-row.total-row {
            border-top: 2px solid var(--gray-300);
            padding-top: 1rem;
            margin-top: 0.5rem;
        }

        .summary-label {
            font-weight: 500;
            color: var(--gray-700);
        }

        .summary-value {
            font-weight: 600;
            color: var(--gray-900);
        }

        .total-row .summary-label,
        .total-row .summary-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--success-color);
        }

        /* Customer Info */
        .customer-info {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .info-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            color: var(--gray-600);
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-weight: 500;
            color: var(--gray-900);
            padding-left: 1.5rem;
        }

        /* Payment Info Styles - Lấy trực tiếp từ DB */
        .payment-info {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .payment-status {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
        }

        .status-paid {
            background: #d1fae5;
            color: #065f46;
        }

        .status-unpaid {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        /* Status Section */
        .status-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .status-current {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .status-label {
            font-weight: 600;
            color: var(--gray-700);
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .status-badge i {
            font-size: 0.75rem;
        }

        /* Status Badge Colors */
        .status-badge.bg-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .status-badge.bg-info {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-badge.bg-primary {
            background: #e0e7ff;
            color: #3730a3;
        }

        .status-badge.bg-success {
            background: #d1fae5;
            color: #065f46;
        }

        .status-badge.bg-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Status Actions */
        .status-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .action-form {
            margin: 0;
        }

        /* Buttons */
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
        }

        .btn-confirm {
            background: var(--warning-color);
            color: white;
        }

        .btn-confirm:hover {
            background: #d97706;
            color: white;
            text-decoration: none;
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-update {
            background: var(--info-color);
            color: white;
        }

        .btn-update:hover {
            background: #0891b2;
            color: white;
            text-decoration: none;
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-cancel {
            background: var(--danger-color);
            color: white;
        }

        .btn-cancel:hover {
            background: #dc2626;
            color: white;
            text-decoration: none;
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-secondary {
            background: var(--gray-500);
            color: white;
        }

        .btn-secondary:hover {
            background: var(--gray-600);
            color: white;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: var(--secondary-color);
            color: white;
            text-decoration: none;
        }

        .btn-danger {
            background: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
            color: white;
            text-decoration: none;
        }

        /* Modern Modal */
        .modern-modal {
            border-radius: 16px;
            border: none;
            box-shadow: var(--shadow-xl);
        }

        .modern-modal .modal-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 16px 16px 0 0;
            border: none;
            padding: 1.5rem 2rem;
        }

        .modern-modal .modal-title {
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modern-modal .close {
            color: white;
            opacity: 0.8;
        }

        .modern-modal .close:hover {
            opacity: 1;
        }

        .modern-modal .modal-body {
            padding: 2rem;
        }

        .modern-modal .modal-footer {
            padding: 1.5rem 2rem;
            border-top: 1px solid var(--gray-200);
            background: var(--gray-50);
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control {
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            padding: 0.75rem;
            font-size: 0.875rem;
            transition: border-color 0.2s ease;
            width: 100%;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            outline: none;
        }

        .form-text {
            font-size: 0.75rem;
            color: var(--gray-500);
            margin-top: 0.25rem;
        }

        /* Print Styles */
        @media print {
            .sticky-header {
                position: static !important;
                background: white !important;
                color: var(--gray-900) !important;
                box-shadow: none !important;
            }

            .btn-print,
            .status-actions,
            .breadcrumb {
                display: none !important;
            }

            .modern-card {
                border: 1px solid var(--gray-300) !important;
                box-shadow: none !important;
                break-inside: avoid;
            }

            .page-title {
                color: var(--gray-900) !important;
            }

            body {
                background: white !important;
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
                padding: 0 1rem;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .content-wrapper {
                padding: 0 1rem;
            }

            .modern-card .card-header,
            .modern-card .card-body,
            .modern-card .card-footer {
                padding: 1rem;
            }

            .product-item {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .product-meta {
                justify-content: center;
            }

            .product-total {
                margin-left: 0;
            }

            .status-section {
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
            }

            .status-actions {
                justify-content: center;
            }

            .order-summary {
                margin-left: 0;
                max-width: none;
            }

            /* Mobile sticky header adjustments */
            .sticky-header {
                padding: 1rem 0;
            }
        }

        @media (max-width: 480px) {
            .page-title {
                font-size: 1.25rem;
            }

            .btn {
                padding: 0.5rem 1rem;
                font-size: 0.75rem;
            }

            .product-img {
                width: 80px;
                height: 64px;
            }
        }
    </style>
@endsection
