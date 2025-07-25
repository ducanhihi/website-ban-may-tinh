@extends('layout.app')

@section('content')
    <main id="main" class="main">
        <!-- Modern Header -->

                <div class="header-left">
                    <h1 class="page-title">
                        <i class="bi bi-pencil-square"></i>
                        Chỉnh sửa đơn hàng
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{route('admin.home')}}">
                                    <i class="bi bi-house"></i> Home
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{route('admin.orders')}}">Đơn Hàng</a>
                            </li>
                            <li class="breadcrumb-item active">Chỉnh sửa</li>
                        </ol>
                    </nav>
                </div>
                <div class="header-right">
                    <div class="header-actions">
                        <a href="{{route('admin.orders')}}" class="btn-back">
                            <i class="bi bi-arrow-left"></i>
                            <span>Quay lại</span>
                        </a>
                    </div>
                </div>


        <!-- Order Info Card -->
        <div class="order-info-section">
            <div class="order-info-card">
                <div class="order-header">
                    <div class="order-id">
                        <span class="id-label">#{{ $order->id }}</span>
                        <span class="status-badge pending">{{ $order->status ?? 'Chờ xác nhận' }}</span>
                    </div>
                    <div class="order-date">
                        <i class="bi bi-calendar"></i>
                        {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="edit-form-section">
            <form action="{{ route('admin.update-save', ['order_id' => $order->id]) }}" method="POST" class="modern-form">
                @csrf
                <div class="form-grid">
                    <!-- Customer Information -->
                    <div class="form-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="bi bi-person"></i>
                                Thông tin khách hàng
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="name" class="form-label">
                                        <i class="bi bi-person-circle"></i>
                                        Họ và tên
                                    </label>
                                    <input type="text"
                                           class="form-input"
                                           id="name"
                                           name="name"
                                           value="{{ $order->name }}"
                                           placeholder="Nhập họ và tên">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="phone" class="form-label">
                                        <i class="bi bi-telephone"></i>
                                        Số điện thoại
                                    </label>
                                    <input type="text"
                                           class="form-input"
                                           id="phone"
                                           name="phone"
                                           value="{{ $order->phone }}"
                                           placeholder="Nhập số điện thoại">
                                </div>
                                <div class="form-group">
                                    <label for="email" class="form-label">
                                        <i class="bi bi-envelope"></i>
                                        Email
                                    </label>
                                    <input type="email"
                                           class="form-input"
                                           id="email"
                                           name="email"
                                           value="{{ $order->email }}"
                                           placeholder="Nhập email">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group full-width">
                                    <label for="address" class="form-label">
                                        <i class="bi bi-geo-alt"></i>
                                        Địa chỉ
                                    </label>
                                    <input type="text"
                                           class="form-input"
                                           id="address"
                                           name="address"
                                           value="{{ $order->address }}"
                                           placeholder="Nhập địa chỉ giao hàng">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Details -->
                    <div class="form-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="bi bi-box"></i>
                                Chi tiết đơn hàng
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="landing_code" class="form-label">
                                        <i class="bi bi-truck"></i>
                                        Mã vận đơn
                                    </label>
                                    <input type="text"
                                           class="form-input"
                                           id="landing_code"
                                           name="landing_code"
                                           value="{{ $order->landing_code }}"
                                           placeholder="Nhập mã vận đơn">
                                </div>


                                <div class="form-group">
                                    <label for="shipping_unit" class="form-label">
                                        <i class="bi bi-truck"></i>
                                        Đơn vị vận chuyển
                                    </label>
                                    <input type="text"
                                           class="form-input"
                                           id="shipping_unit"
                                           name="shipping_unit"
                                           value="{{ $order->shipping_unit }}"
                                           placeholder="Nhập đơn vị vận chuyển">
                                </div>



                                <div class="form-group">
                                    <label for="status" class="form-label">
                                        <i class="bi bi-flag"></i>
                                        Trạng thái
                                    </label>
                                    @php
                                        $statusList = [
                                            'Chờ xác nhận',
                                            'Đã xác nhận',
                                            'Đang giao',
                                            'Đã giao',
                                            'Đã nhận hàng',
                                            'Đã hủy',
                                        ];

                                        $currentStatusIndex = array_search($order->status, $statusList);
                                    @endphp

                                    <select class="form-select" id="status" name="status">
                                        @foreach ($statusList as $index => $status)
                                            @php
                                                $isSelected = $order->status == $status ? 'selected' : '';
                                                // Nếu trạng thái hiện tại là 'Đã nhận hàng' thì disable các trạng thái trước đó
                                                $isDisabled = ($index < $currentStatusIndex && $order->status != 'Đã hủy') ? 'disabled' : '';
                                            @endphp
                                            <option value="{{ $status }}" {{ $isSelected }} {{ $isDisabled }}>
                                                {{ match($status) {
                                                    'Chờ xác nhận' => 'Đang chờ xử lý',
                                                    'Đã xác nhận' => 'Đã xác nhận',
                                                    'Đang giao' => 'Đang giao',
                                                    'Đã giao' => 'Đã giao',
                                                    'Đã nhận hàng' => 'Đã nhận hàng',
                                                    'Đã hủy' => 'Đã hủy',
                                                    default => $status
                                                } }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group full-width">
                                    <label for="note" class="form-label">
                                        <i class="bi bi-chat-text"></i>
                                        Ghi chú
                                    </label>
                                    <textarea class="form-textarea"
                                              id="note"
                                              name="note"
                                              rows="3"
                                              placeholder="Nhập ghi chú cho đơn hàng">{{ $order->note ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Serial Numbers Section -->
                <div class="serial-section">
                    <div class="serial-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="bi bi-hash"></i>
                                Số sê-ri sản phẩm
                            </h3>
                            <div class="card-subtitle">
                                Quản lý số sê-ri cho từng sản phẩm trong đơn hàng
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="serial-table-container">
                                <table class="serial-table">
                                    <thead>
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Số sê-ri</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($order->orderdetails as $orderdetail)
                                        @php
                                            $serialList = $orderdetail->serial ?? collect();
                                        @endphp

                                        @for ($i = 0; $i < $orderdetail->quantity; $i++)
                                            <tr class="serial-row">
                                                <td class="product-info">
                                                    <div class="product-name">
                                                        {{ $orderdetail->product->name }}
                                                    </div>
                                                    <div class="product-meta">
                                                        Sản phẩm {{ $i + 1 }} / {{ $orderdetail->quantity }}
                                                    </div>
                                                </td>
                                                <td class="serial-input-cell">
                                                    <div class="serial-input-group">
                                                        <input type="text"
                                                               class="serial-input"
                                                               name="serial[{{ $orderdetail->id }}][]"
                                                               value="{{ $serialList[$i]->serial_number ?? '' }}"
                                                               placeholder="Nhập số sê-ri">
                                                        <button type="button" class="generate-serial-btn" title="Tạo số sê-ri tự động">
                                                            <i class="bi bi-magic"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                                <td class="serial-status">
                                                    @if(isset($serialList[$i]->serial_number) && !empty($serialList[$i]->serial_number))
                                                        <span class="status-badge completed">
                                                            <i class="bi bi-check-circle"></i>
                                                            Đã có
                                                        </span>
                                                    @else
                                                        <span class="status-badge pending">
                                                            <i class="bi bi-clock"></i>
                                                            Chưa có
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endfor
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-section">
                    <div class="action-buttons">
                        <a href="{{route('admin.orders')}}" class="btn-cancel">
                            <i class="bi bi-x-circle"></i>
                            Hủy bỏ
                        </a>
                        <button type="submit" class="btn-save">
                            <i class="bi bi-check-circle"></i>
                            Cập nhật đơn hàng
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <script>
        // Auto-generate serial numbers
        document.addEventListener('DOMContentLoaded', function() {
            const generateBtns = document.querySelectorAll('.generate-serial-btn');

            generateBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const input = this.parentElement.querySelector('.serial-input');
                    const randomSerial = 'SN' + Date.now() + Math.floor(Math.random() * 1000);
                    input.value = randomSerial;

                    // Update status badge
                    const statusCell = this.closest('tr').querySelector('.serial-status');
                    statusCell.innerHTML = `
                <span class="status-badge completed">
                    <i class="bi bi-check-circle"></i>
                    Đã có
                </span>
            `;
                });
            });

            // Update status when typing
            const serialInputs = document.querySelectorAll('.serial-input');
            serialInputs.forEach(input => {
                input.addEventListener('input', function() {
                    const statusCell = this.closest('tr').querySelector('.serial-status');
                    if (this.value.trim()) {
                        statusCell.innerHTML = `
                    <span class="status-badge completed">
                        <i class="bi bi-check-circle"></i>
                        Đã có
                    </span>
                `;
                    } else {
                        statusCell.innerHTML = `
                    <span class="status-badge pending">
                        <i class="bi bi-clock"></i>
                        Chưa có
                    </span>
                `;
                    }
                });
            });
        });
    </script>

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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--gray-50);
            color: var(--gray-900);
            line-height: 1.6;
        }



        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .page-title i {
            font-size: 2rem;
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

        .btn-back {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            transition: all 0.2s ease;
            backdrop-filter: blur(10px);
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            text-decoration: none;
            transform: translateY(-1px);
        }

        /* Order Info Section */
        .order-info-section {
            padding: 0 1rem;
            margin-bottom: 2rem;
        }

        .order-info-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: var(--shadow);
            max-width: 1200px;
            margin: 0 auto;
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order-id {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .id-label {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gray-900);
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-badge.pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-badge.completed {
            background: #d1fae5;
            color: #065f46;
        }

        .order-date {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--gray-600);
            font-weight: 500;
        }

        /* Edit Form Section */
        .edit-form-section {
            padding: 0 1rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .form-card {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .card-header {
            background: var(--gray-50);
            padding: 1.5rem;
            border-bottom: 1px solid var(--gray-200);
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-900);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0;
        }

        .card-subtitle {
            color: var(--gray-600);
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .form-row:last-child {
            margin-bottom: 0;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
        }

        .form-input,
        .form-select,
        .form-textarea {
            padding: 0.75rem 1rem;
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            background: white;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .form-textarea {
            resize: vertical;
            min-height: 80px;
        }

        /* Serial Section */
        .serial-section {
            margin-bottom: 2rem;
        }

        .serial-card {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .serial-table-container {
            overflow-x: auto;
        }

        .serial-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .serial-table thead th {
            background: var(--gray-800);
            color: white;
            padding: 1rem;
            font-weight: 600;
            text-align: left;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .serial-table thead th:first-child {
            border-radius: 0;
        }

        .serial-table thead th:last-child {
            border-radius: 0;
        }

        .serial-table tbody td {
            padding: 1rem;
            border-bottom: 1px solid var(--gray-200);
            vertical-align: middle;
        }

        .serial-row:hover {
            background: var(--gray-50);
        }

        .product-info {
            min-width: 200px;
        }

        .product-name {
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 0.25rem;
        }

        .product-meta {
            font-size: 0.75rem;
            color: var(--gray-500);
        }

        .serial-input-cell {
            min-width: 300px;
        }

        .serial-input-group {
            display: flex;
            gap: 0.5rem;
        }

        .serial-input {
            flex: 1;
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--gray-300);
            border-radius: 6px;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .serial-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.1);
        }

        .generate-serial-btn {
            padding: 0.5rem;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
        }

        .generate-serial-btn:hover {
            background: var(--secondary-color);
            transform: translateY(-1px);
        }

        .serial-status {
            min-width: 120px;
        }

        /* Action Section */
        .action-section {
            display: flex;
            justify-content: center;
            padding: 2rem 0;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn-cancel,
        .btn-save {
            padding: 0.75rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
        }

        .btn-cancel {
            background: var(--gray-100);
            color: var(--gray-700);
            border: 1px solid var(--gray-300);
        }

        .btn-cancel:hover {
            background: var(--gray-200);
            color: var(--gray-900);
            text-decoration: none;
            transform: translateY(-1px);
        }

        .btn-save {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: 1px solid var(--primary-color);
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 1.5rem;
                text-align: center;
            }

            .page-title {
                font-size: 2rem;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .order-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .action-buttons {
                flex-direction: column;
                width: 100%;
            }

            .btn-cancel,
            .btn-save {
                justify-content: center;
                width: 100%;
            }

            .serial-table-container {
                font-size: 0.75rem;
            }

            .serial-table thead th,
            .serial-table tbody td {
                padding: 0.75rem 0.5rem;
            }
        }

        @media (max-width: 480px) {
            .modern-header {
                padding: 1.5rem 0;
            }

            .page-title {
                font-size: 1.75rem;
            }

            .order-info-card,
            .form-card,
            .serial-card {
                margin: 0 0.5rem;
                border-radius: 12px;
            }

            .card-body {
                padding: 1rem;
            }
        }
    </style>
@endsection
