@if(count($orders) > 0)
    <div class="modern-orders-grid">
        @foreach($orders as $order)
            <div class="modern-order-card">
                <div class="order-card-header">
                    <div class="order-header-row">
                        <div class="order-id">
                            <i class="fas fa-receipt"></i>
                            Đơn hàng #{{ $order->id }}
                        </div>
                        <div class="order-status-badge status-{{ $status }}">
                            {{ $order->status }}
                        </div>
                    </div>

                    <div class="order-meta-grid">
                        <div class="meta-item">
                            <i class="fas fa-user meta-icon"></i>
                            <span class="meta-value">{{ $order->name }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-phone meta-icon"></i>
                            <span class="meta-value">{{ $order->phone }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-envelope meta-icon"></i>
                            <span class="meta-value">{{ $order->email }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-map-marker-alt meta-icon"></i>
                            <span class="meta-value">{{ Str::limit($order->address, 40) }}</span>
                        </div>
                        @if($order->landing_code)
                            <div class="meta-item">
                                <i class="fas fa-barcode meta-icon"></i>
                                <span class="meta-value">{{ $order->landing_code }}</span>
                            </div>
                        @endif
                        <div class="meta-item">
                            <i class="fas fa-credit-card meta-icon"></i>
                            <span class="meta-value">{{ $order->payment ?? 'Chưa xác định' }}</span>
                        </div>
                    </div>
                </div>

                <div class="order-card-body">
                    <div class="order-summary-row">
                        <div class="order-total">
                            {{ number_format($order->total, 0, ',', '.') }} VNĐ
                        </div>
                        <div class="order-date">
                            <i class="fas fa-calendar-alt"></i>
                            {{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y H:i') }}
                        </div>
                    </div>

                    @if($order->note)
                        <div class="order-note">
                            <small>
                                <i class="fas fa-sticky-note me-1"></i>
                                <strong>Ghi chú:</strong> {{ $order->note }}
                            </small>
                        </div>
                    @endif

                    <div class="order-actions">
                        <button class="btn-modern btn-outline" onclick="toggleOrderDetails({{ $order->id }})">
                            <i class="fas fa-chevron-down" id="icon-{{ $order->id }}"></i>
                            <span id="text-{{ $order->id }}">Xem nhanh</span>
                        </button>

                        <a href="{{ route('customer.order-detail', $order->id) }}" class="btn-modern btn-solid">
                            <i class="fas fa-external-link-alt"></i>
                            Chi tiết đầy đủ
                        </a>
                    </div>
                </div>

                <!-- Order Details Dropdown với thông tin thực -->
                <div class="order-details-dropdown" id="details-{{ $order->id }}">
                    <div class="details-content">
                        <div class="order-quick-details">
                            <div class="quick-details-grid">
                                <!-- Thông tin giao hàng -->
                                <div class="detail-section">
                                    <h6 class="detail-title">
                                        <i class="fas fa-shipping-fast me-2"></i>
                                        Thông tin giao hàng
                                    </h6>
                                    <div class="detail-list">
                                        <div class="detail-item">
                                            <span class="detail-label">Người nhận:</span>
                                            <span class="detail-value">{{ $order->name }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Điện thoại:</span>
                                            <span class="detail-value">{{ $order->phone }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Địa chỉ:</span>
                                            <span class="detail-value">{{ $order->address }}</span>
                                        </div>
                                        @if($order->landing_code)
                                            <div class="detail-item">
                                                <span class="detail-label">Mã vận đơn:</span>
                                                <span class="detail-value tracking-code">{{ $order->landing_code }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Thông tin đơn hàng -->
                                <div class="detail-section">
                                    <h6 class="detail-title">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Thông tin đơn hàng
                                    </h6>
                                    <div class="detail-list">
                                        <div class="detail-item">
                                            <span class="detail-label">Ngày đặt:</span>
                                            <span class="detail-value">{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y H:i') }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Trạng thái:</span>
                                            <span class="detail-value">
                                                <span class="status-mini status-{{ $status }}">{{ $order->status }}</span>
                                            </span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Thanh toán:</span>
                                            <span class="detail-value">{{ $order->payment ?? 'Chưa xác định' }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Tổng tiền:</span>
                                            <span class="detail-value total-amount">{{ number_format($order->total, 0, ',', '.') }} VNĐ</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($order->note)
                                <div class="detail-section full-width">
                                    <h6 class="detail-title">
                                        <i class="fas fa-sticky-note me-2"></i>
                                        Ghi chú
                                    </h6>
                                    <div class="note-content">
                                        {{ $order->note }}
                                    </div>
                                </div>
                            @endif

                            <!-- Sản phẩm trong đơn hàng (nếu có thông tin) -->
                            @if(isset($order->products) && count($order->products) > 0)
                                <div class="detail-section full-width">
                                    <h6 class="detail-title">
                                        <i class="fas fa-box me-2"></i>
                                        Sản phẩm ({{ count($order->products) }} món)
                                    </h6>
                                    <div class="products-preview">
                                        @foreach($order->products->take(3) as $product)
                                            <div class="product-preview-item">
                                                <img src="{{ asset('image/'.$product->image) }}" alt="{{ $product->name }}" class="product-preview-image">
                                                <div class="product-preview-info">
                                                    <span class="product-preview-name">{{ Str::limit($product->name, 30) }}</span>
                                                    <span class="product-preview-price">{{ number_format($product->price, 0, ',', '.') }} VNĐ</span>
                                                </div>
                                            </div>
                                        @endforeach
                                        @if(count($order->products) > 3)
                                            <div class="more-products">
                                                <span>+{{ count($order->products) - 3 }} sản phẩm khác</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Action buttons trong dropdown -->
                            <div class="quick-actions">
                                <a href="{{ route('customer.order-detail', $order->id) }}" class="btn-modern btn-solid">
                                    <i class="fas fa-eye"></i>
                                    Xem chi tiết đầy đủ
                                </a>

                                @if($status === 'shipping' && $order->landing_code)
                                    @if($order->shipping_unit === 'SPX')
                                        <a href="https://spx.vn/track?{{ $order->landing_code }}" target="_blank" class="btn-modern btn-outline">
                                            <i class="fas fa-truck"></i>
                                            Theo dõi vận đơn
                                        </a>
                                    @elseif($order->shipping_unit === 'GHTK')
                                        <a href="https://i.ghtk.vn/{{ $order->landing_code }}" target="_blank" class="btn-modern btn-outline">
                                            <i class="fas fa-truck"></i>
                                            Theo dõi vận đơn
                                        </a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <style>
        /* Quick Details Styles */
        .order-quick-details {
            padding: 0;
        }

        .quick-details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .detail-section {
            background: white;
            padding: 1rem;
            border-radius: 0.5rem;
            border: 1px solid var(--gray-200);
        }

        .detail-section.full-width {
            grid-column: 1 / -1;
        }

        .detail-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            border-bottom: 1px solid var(--gray-200);
            padding-bottom: 0.5rem;
        }

        .detail-list {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .detail-label {
            font-size: 0.75rem;
            color: var(--gray-500);
            font-weight: 500;
            min-width: 80px;
            flex-shrink: 0;
        }

        .detail-value {
            font-size: 0.75rem;
            color: var(--gray-800);
            font-weight: 500;
            text-align: right;
            word-break: break-word;
        }

        .total-amount {
            color: var(--primary);
            font-weight: 700;
        }

        .tracking-code {
            color: var(--info);
            font-family: monospace;
        }

        .status-mini {
            padding: 0.25rem 0.5rem;
            border-radius: 1rem;
            font-size: 0.625rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .note-content {
            background: var(--gray-50);
            padding: 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            color: var(--gray-700);
            line-height: 1.4;
        }

        /* Products Preview */
        .products-preview {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .product-preview-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem;
            background: var(--gray-50);
            border-radius: 0.375rem;
        }

        .product-preview-image {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 0.25rem;
            border: 1px solid var(--gray-200);
        }

        .product-preview-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.125rem;
        }

        .product-preview-name {
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--gray-800);
        }

        .product-preview-price {
            font-size: 0.625rem;
            color: var(--primary);
            font-weight: 600;
        }

        .more-products {
            text-align: center;
            padding: 0.5rem;
            background: var(--gray-100);
            border-radius: 0.375rem;
            font-size: 0.75rem;
            color: var(--gray-600);
            font-style: italic;
        }

        /* Quick Actions */
        .quick-actions {
            display: flex;
            gap: 0.75rem;
            padding-top: 1rem;
            border-top: 1px solid var(--gray-200);
            flex-wrap: wrap;
        }

        .quick-actions .btn-modern {
            flex: 1;
            justify-content: center;
            font-size: 0.75rem;
            padding: 0.5rem 1rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .quick-details-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .quick-actions {
                flex-direction: column;
            }

            .quick-actions .btn-modern {
                flex: none;
            }
        }

        /* Updated CSS */
        .order-details-dropdown {
            background: var(--gray-50);
            border-top: 1px solid var(--gray-200);
            padding: 1.5rem;
            display: none; /* Đảm bảo ẩn mặc định */
            max-height: 0;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .order-details-dropdown.show {
            display: block;
            max-height: 1000px;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                max-height: 0;
                padding-top: 0;
                padding-bottom: 0;
            }
            to {
                opacity: 1;
                max-height: 1000px;
                padding-top: 1.5rem;
                padding-bottom: 1.5rem;
            }
        }
    </style>
@else
    <div class="empty-state">
        <div class="empty-icon">
            <i class="fas fa-inbox"></i>
        </div>
        <h3 class="empty-title">Không có đơn hàng nào</h3>
        <p class="empty-text">Bạn chưa có đơn hàng nào trong trạng thái này.</p>
        <a href="{{ route('customer.main-home') }}" class="btn-modern btn-solid">
            <i class="fas fa-shopping-cart"></i>
            Bắt đầu mua sắm
        </a>
    </div>
@endif
