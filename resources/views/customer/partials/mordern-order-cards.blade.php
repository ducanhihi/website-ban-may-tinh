@if(count($orders) > 0)
    <div class="orders-grid">
        @foreach($orders as $order)
            <div class="order-card">
                <div class="order-header">
                    <div class="order-header-row">
                        <div class="order-id">
                            <i class="fas fa-receipt"></i>
                            #{{ $order->id }}
                        </div>
                        <div class="order-status status-{{ str_replace(' ', '-', strtolower($order->status)) }}">
                            {{ $order->status }}
                        </div>
                    </div>
                    <div class="order-meta">
                        <div class="meta-item">
                            <i class="fas fa-user meta-icon"></i>
                            <span class="meta-value">{{ $order->name }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-phone meta-icon"></i>
                            <span class="meta-value">{{ $order->phone }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-map-marker-alt meta-icon"></i>
                            <span class="meta-value">{{ Str::limit($order->address, 30) }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-credit-card meta-icon"></i>
                            <span class="meta-value">{{ $order->payment ?? 'Chưa xác định' }}</span>
                        </div>
                    </div>
                </div>

                <div class="order-body">
                    <div class="order-summary">
                        <div class="order-total">
                            {{ number_format($order->total, 0, ',', '.') }}đ
                        </div>
                        <div class="order-date">
                            <i class="fas fa-calendar-alt"></i>
                            {{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y H:i') }}
                        </div>
                    </div>

                    @if($order->note)
                        <div class="order-note">
                            <strong>Ghi chú:</strong> {{ $order->note }}
                        </div>
                    @endif

                    <div class="order-actions">
                        <button class="btn btn-outline" onclick="toggleOrderDetails({{ $order->id }})">
                            <i class="fas fa-chevron-down" id="icon-{{ $order->id }}"></i>
                            <span id="text-{{ $order->id }}">Xem nhanh</span>
                        </button>

                        <a href="{{ route('customer.order-detail', $order->id) }}" class="btn btn-primary">
                            <i class="fas fa-eye"></i>
                            Chi tiết
                        </a>
                    </div>
                </div>

                <!-- Order Details Dropdown -->
                <div class="order-details" id="details-{{ $order->id }}">
                    <div id="details-content-{{ $order->id }}">
                        <div class="text-center">
                            <i class="fas fa-spinner fa-spin"></i>
                            Đang tải thông tin...
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="empty-state">
        <div class="empty-icon">
            <i class="fas fa-inbox"></i>
        </div>
        <h3 class="empty-title">Không có đơn hàng nào</h3>
        <p class="empty-text">
            @if($status == 'pending')
                Bạn chưa có đơn hàng nào đang chờ xác nhận.
            @elseif($status == 'wait-payment')
                Bạn chưa có đơn hàng nào đang chờ thanh toán.
            @elseif($status == 'confirmed')
                Bạn chưa có đơn hàng nào đã được xác nhận.
            @elseif($status == 'shipping')
                Bạn chưa có đơn hàng nào đang được giao.
            @elseif($status == 'completed')
                Bạn chưa có đơn hàng nào đã được giao.
            @elseif($status == 'received')
                Bạn chưa có đơn hàng nào đã nhận.
            @elseif($status == 'canceled')
                Bạn chưa có đơn hàng nào bị hủy.
            @endif
        </p>
        <a href="{{ route('customer.home') }}" class="btn btn-primary">
            <i class="fas fa-shopping-cart"></i>
            Tiếp tục mua sắm
        </a>
    </div>
@endif
