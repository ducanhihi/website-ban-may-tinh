@extends('layout.customerApp')

@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <div class="container-fluid">
        <!-- Header -->
        <div class="header">
            <button class="back-btn" onclick="window.location.href='{{ route('customer.main-home') }}'">
                <i class="fas fa-arrow-left"></i>
                Tiếp tục mua sắm
            </button>
            <h1>Giỏ hàng của bạn</h1>
            <p class="cart-count">{{ count($cartDetails) }} sản phẩm trong giỏ hàng</p>
        </div>

        <!-- Hiển thị thông báo lỗi nếu có -->
        @if(session('error'))
            <div class="alert alert-danger mb-3">
                {{ session('error') }}
            </div>
        @endif

        <!-- Select All Section -->
        <div class="select-all-section">
            <label class="select-all-label">
                <input type="checkbox" id="selectAll">
                <span>Chọn tất cả sản phẩm có sẵn</span>
            </label>
            <div class="selected-info">
                <span id="selectedCount">0</span> sản phẩm được chọn
            </div>
        </div>

        <div class="cart-layout">
            <!-- Cart Items -->
            <div class="cart-items">
                @foreach ($cartDetails as $detail)
                    <div class="cart-item {{ $detail->product->quantity <= 0 ? 'out-of-stock' : '' }}"
                         data-product-id="{{ $detail->product_id }}"
                         data-price="{{ $detail->product->final_price ?? $detail->product->price_out }}">
                        <div class="item-content">
                            <div class="checkbox-section">
                                <input type="checkbox"
                                       class="product-checkbox"
                                       data-product-id="{{ $detail->product_id }}"
                                    {{ $detail->product->quantity <= 0 ? 'disabled' : '' }}>
                            </div>
                            <div class="item-image">
                                <img src="{{ asset('image/' . $detail->product->image) }}"
                                     alt="{{ $detail->product->name }}"
                                     onerror="this.src='/images/no-image.png'">

                                @if($detail->product->quantity <= 0)
                                    <span class="out-of-stock-badge">Hết hàng</span>
                                @endif

                                @if($detail->product->discount_percent !== null && $detail->product->discount_percent > 0)
                                    <span class="discount-badge">-{{ $detail->product->discount_percent }}%</span>
                                @endif
                            </div>

                            <div class="item-details">
                                <div class="item-header">
                                    <div class="item-info">
                                        <h3>{{ $detail->product->name }}</h3>
                                        <div class="product-meta">
                                            <div class="brand-info">
                                                <span class="brand-label">Thương hiệu {{ $detail->product->brand->name ?? 'N/A' }}</span>
                                            </div>
                                            <div class="category-info">
                                                <span class="category-label">Danh mục: {{ $detail->product->category->name ?? 'N/A' }}</span>
                                            </div>
                                            <div class="stock-info">
                                                @if($detail->product->quantity > 0)
                                                    <i class="fas fa-check-circle text-success"></i>
                                                    <span class="stock-text">Còn {{ $detail->product->quantity }} sản phẩm</span>
                                                @else
                                                    <i class="fas fa-times-circle text-danger"></i>
                                                    <span class="stock-text">Hết hàng</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <button class="remove-btn" data-product-id="{{ $detail->product_id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>

                                <div class="warranty">
                                    <i class="fas fa-shield-alt"></i>
                                    <span>Bảo hành 1 năm</span>
                                </div>

                                <div class="item-footer">
                                    <div class="price-section">
                                        @if($detail->product->discount_percent > 0)
                                            <div class="old-price">
                                                {{ number_format($detail->product->price_out, 0, ',', '.') }} VNĐ
                                            </div>
                                        @endif
                                        <div class="current-price">
                                            {{ number_format($detail->product->final_price ?? $detail->product->price_out, 0, ',', '.') }} VNĐ
                                        </div>
                                    </div>

                                    <div class="quantity-controls">
                                        @if($detail->product->quantity > 0)
                                            <button type="button" class="qty-btn minus-btn" data-product-id="{{ $detail->product_id }}">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <span class="quantity" id="qty-{{ $detail->product_id }}">{{ $detail->quantity }}</span>
                                            <button type="button" class="qty-btn plus-btn" data-product-id="{{ $detail->product_id }}" data-max-qty="{{ $detail->product->quantity }}">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        @else
                                            <button type="button" class="qty-btn" disabled>
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <span class="quantity" id="qty-{{ $detail->product_id }}">{{ $detail->quantity }}</span>
                                            <button type="button" class="qty-btn" disabled>
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                @if($detail->product->quantity <= 0)
                                    <p class="out-of-stock-text">Sản phẩm tạm hết hàng!</p>
                                @elseif($detail->product->quantity < 5)
                                    <p class="low-stock-text">Chỉ còn {{ $detail->product->quantity }} sản phẩm</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

                @if(count($cartDetails) == 0)
                    <div class="empty-cart">
                        <div class="empty-cart-message">
                            <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                            <h3>Giỏ hàng của bạn đang trống</h3>
                            <p>Hãy thêm sản phẩm vào giỏ hàng để tiếp tục mua sắm</p>
                            <a href="{{ route('customer.main-home') }}" class="btn-shop-now">Mua sắm ngay</a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Order Summary -->
            <div class="order-summary">
                <div class="summary-card">
                    <h2>Tóm tắt đơn hàng</h2>

                    <!-- Selected Products Info -->
                    <div class="selected-products-info">
                        <p><strong>Sản phẩm đã chọn:</strong></p>
                        <div id="selectedProductsList" class="selected-list">
                            <p class="no-selection">Chưa có sản phẩm nào được chọn</p>
                        </div>
                    </div>

                    <div class="divider"></div>

                    <!-- Price Breakdown -->
                    <div class="price-breakdown">
                        <div class="price-row">
                            <span>Tạm tính</span>
                            <span id="subtotal">0 VNĐ</span>
                        </div>
                        <div class="price-row">
                            <span>Phí vận chuyển</span>
                            <span id="shipping">Tính ngoài hóa đơn</span>
                        </div>
                        <div class="divider"></div>
                        <div class="price-row total-row">
                            <span>Tổng cộng</span>
                            <span id="total">0 VNĐ</span>
                        </div>
                    </div>

                    <!-- Shipping Info -->
                    <div class="shipping-info">
                        <div class="shipping-text">
                            <i class="fas fa-truck"></i>
                            <span id="shippingMessage">Đơn hàng được miễn phí vận chuyển</span>
                        </div>
                    </div>

                    <!-- Checkout Buttons -->
                    <div class="checkout-section">
                        <form action="{{ route('customer.buy-inCart') }}" method="post" id="checkoutForm">
                            @csrf
                            <input type="hidden" name="selected_products" id="selectedProductsInput" value="">

                            <button type="submit" class="checkout-btn primary" id="checkoutBtn" disabled>
                                <i class="fas fa-shopping-cart"></i>
                                Đặt hàng (<span id="checkoutCount">0</span> sản phẩm)
                            </button>
                        </form>

                        <div class="security-text">
                            <i class="fas fa-shield-alt"></i>
                            <span>Thanh toán an toàn & bảo mật</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden iframe for form submissions -->
    <iframe id="hiddenFrame" name="hiddenFrame" style="display:none;"></iframe>

    <!-- Hidden forms for cart actions -->
    <form id="updateQuantityForm" method="POST" target="hiddenFrame" style="display:none;">
        @csrf
        <input type="hidden" name="quantity" id="updateQuantityValue">
        <input type="hidden" name="_method" value="PUT">
    </form>

    <form id="removeItemForm" method="POST" target="hiddenFrame" style="display:none;">
        @csrf
        <input type="hidden" name="_method" value="DELETE">
    </form>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }

        .container-fluid {
            max-width: 100%;
            margin: 0;
            padding: 20px 40px;
        }

        /* Header */
        .header {
            margin-bottom: 30px;
        }

        .back-btn {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 8px 16px;
            border-radius: 6px;
            color: #6c757d;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 15px;
        }

        .back-btn:hover {
            background: #e9ecef;
            color: #495057;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 600;
            color: #212529;
            margin-bottom: 5px;
        }

        .cart-count {
            color: #6c757d;
            font-size: 14px;
        }

        /* Select All Section */
        .select-all-section {
            background: white;
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #e9ecef;
        }

        .select-all-label {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
        }

        .select-all-label input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #007bff;
        }

        .selected-info {
            color: #6c757d;
            font-size: 14px;
        }

        /* Cart Layout - Full Width */
        .cart-layout {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 30px;
            align-items: start;
        }

        /* Cart Items */
        .cart-items {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .cart-item {
            background: white;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            overflow: hidden;
            transition: all 0.2s;
        }

        .cart-item:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .cart-item.selected {
            border-color: #007bff;
            box-shadow: 0 0 0 1px #007bff;
        }

        .cart-item.out-of-stock {
            opacity: 0.6;
        }

        .item-content {
            padding: 20px;
            display: flex;
            gap: 16px;
        }

        .checkbox-section {
            display: flex;
            align-items: flex-start;
            padding-top: 8px;
        }

        .checkbox-section input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #007bff;
        }

        .item-image {
            width: 120px;
            height: 120px;
            border-radius: 6px;
            overflow: hidden;
            border: 1px solid #e9ecef;
            flex-shrink: 0;
            position: relative;
        }

        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .out-of-stock-badge {
            position: absolute;
            top: 4px;
            left: 4px;
            background: #dc3545;
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
        }

        .discount-badge {
            position: absolute;
            top: 4px;
            right: 4px;
            background: #dc3545;
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
        }

        .item-details {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .item-info h3 {
            font-size: 16px;
            font-weight: 600;
            color: #212529;
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .product-meta {
            display: flex;
            flex-direction: column;
            gap: 4px;
            font-size: 13px;
        }

        .brand-info, .category-info {
            color: #6c757d;
        }

        .brand-label, .category-label {
            font-weight: 500;
        }

        .stock-info {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 4px;
        }

        .stock-text {
            font-size: 13px;
            font-weight: 500;
        }

        .text-success {
            color: #28a745 !important;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        .remove-btn {
            background: none;
            border: none;
            color: #dc3545;
            cursor: pointer;
            padding: 8px;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .remove-btn:hover {
            background: #f8d7da;
        }

        .warranty {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #28a745;
            font-size: 13px;
            font-weight: 500;
        }

        .item-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
        }

        .price-section {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .old-price {
            color: #6c757d;
            text-decoration: line-through;
            font-size: 14px;
        }

        .current-price {
            font-size: 18px;
            font-weight: 700;
            color: #007bff;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 4px;
        }

        .qty-btn {
            background: none;
            border: none;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border-radius: 4px;
            transition: all 0.2s;
            color: #6c757d;
        }

        .qty-btn:hover:not(:disabled) {
            background: #f8f9fa;
            color: #495057;
        }

        .qty-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .quantity {
            font-weight: 600;
            color: #212529;
            min-width: 24px;
            text-align: center;
        }

        .out-of-stock-text {
            color: #dc3545;
            font-size: 13px;
            margin-top: 8px;
        }

        .low-stock-text {
            color: #fd7e14;
            font-size: 13px;
            margin-top: 8px;
        }

        /* Order Summary */
        .order-summary {
            position: sticky;
            top: 20px;
        }

        .summary-card {
            background: white;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            overflow: hidden;
        }

        .summary-card h2 {
            background: #f8f9fa;
            padding: 16px 20px;
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            color: #212529;
            border-bottom: 1px solid #e9ecef;
        }

        .selected-products-info {
            padding: 20px;
        }

        .selected-products-info p {
            margin-bottom: 12px;
            color: #495057;
            font-size: 14px;
        }

        .selected-list {
            max-height: 200px;
            overflow-y: auto;
        }

        .no-selection {
            color: #6c757d;
            font-style: italic;
            text-align: center;
            padding: 16px;
            font-size: 14px;
        }

        .selected-product-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 6px;
            margin-bottom: 8px;
            border: 1px solid #e9ecef;
        }

        .selected-product-info {
            flex: 1;
        }

        .selected-product-name {
            font-weight: 600;
            color: #212529;
            font-size: 14px;
            margin-bottom: 4px;
            line-height: 1.3;
        }

        .selected-product-details {
            font-size: 12px;
            color: #6c757d;
        }

        .selected-product-price {
            font-weight: 700;
            color: #007bff;
            font-size: 14px;
            margin-left: 12px;
        }

        .divider {
            height: 1px;
            background: #e9ecef;
            margin: 0 20px;
        }

        .price-breakdown {
            padding: 20px;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            font-size: 14px;
        }

        .price-row:last-child {
            margin-bottom: 0;
        }

        .total-row {
            font-size: 16px;
            font-weight: 700;
            color: #212529;
            padding-top: 12px;
            border-top: 1px solid #e9ecef;
        }

        .total-row span:last-child {
            color: #007bff;
        }

        .shipping-info {
            padding: 12px 20px;
            background: #e3f2fd;
            border-top: 1px solid #e9ecef;
            transition: background-color 0.3s ease;
        }

        .shipping-info.free-shipping {
            background: #e8f5e8;
        }

        .shipping-info.need-more {
            background: #fff3cd;
        }

        .shipping-text {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #1976d2;
            font-weight: 500;
        }

        .shipping-text.free {
            color: #28a745;
        }

        .shipping-text.warning {
            color: #856404;
        }

        .checkout-section {
            padding: 20px;
        }

        .checkout-btn {
            width: 100%;
            background: #007bff;
            color: white;
            border: none;
            padding: 14px 20px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 12px;
        }

        .checkout-btn:hover:not(:disabled) {
            background: #0056b3;
        }

        .checkout-btn:disabled {
            background: #6c757d;
            cursor: not-allowed;
        }

        .security-text {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            color: #6c757d;
            font-size: 12px;
        }

        /* Empty Cart */
        .empty-cart {
            background: white;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            padding: 60px 40px;
            text-align: center;
        }

        .empty-cart-message h3 {
            font-size: 20px;
            font-weight: 600;
            color: #212529;
            margin-bottom: 8px;
        }

        .empty-cart-message p {
            color: #6c757d;
            margin-bottom: 24px;
        }

        .btn-shop-now {
            background: #007bff;
            color: white;
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .btn-shop-now:hover {
            background: #0056b3;
            text-decoration: none;
            color: white;
        }

        /* Alert */
        .alert {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .container-fluid {
                padding: 20px 20px;
            }
        }

        @media (max-width: 768px) {
            .container-fluid {
                padding: 15px;
            }

            .cart-layout {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .order-summary {
                position: static;
            }

            .item-content {
                flex-direction: column;
                gap: 12px;
            }

            .item-image {
                width: 100%;
                height: 200px;
            }

            .item-footer {
                flex-direction: column;
                gap: 12px;
                align-items: stretch;
            }

            .select-all-section {
                flex-direction: column;
                gap: 12px;
                align-items: stretch;
            }
        }
    </style>

    <script>
        // Cart data from Laravel
        window.cartData = {
            @foreach ($cartDetails as $detail)
                {{ $detail->product_id }}: {
                name: "{{ addslashes($detail->product->name) }}",
                price: {{ round($detail->product->final_price ?? $detail->product->price_out) }},
                quantity: {{ $detail->quantity }},
                inStock: {{ $detail->product->quantity > 0 ? 'true' : 'false' }},
                stockQuantity: {{ $detail->product->quantity }},
                image: "{{ $detail->product->image }}",
                brand: "{{ addslashes($detail->product->brand->name ?? 'Thương hiệu') }}",
                category: "{{ addslashes($detail->product->category->name ?? 'Danh mục') }}"
            },
            @endforeach
        };

        // URLs for cart actions
        window.cartUrls = {
            delete: "{{ route('cart.delete', ':id') }}",
            update: "{{ route('cart.update', ':id') }}"
        };

        // Selected products tracking
        const selectedProducts = new Set();
        const FREE_SHIPPING_THRESHOLD = 500000; // 500k VNĐ

        // Initialize cart functionality
        document.addEventListener('DOMContentLoaded', function() {
            initializeCart();
        });

        function initializeCart() {
            // Select all checkbox
            const selectAllCheckbox = document.getElementById('selectAll');
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', handleSelectAll);
            }

            // Product checkboxes
            document.querySelectorAll('.product-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', handleProductSelection);
            });

            // Quantity buttons
            document.querySelectorAll('.minus-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const productId = this.dataset.productId;
                    updateQuantity(productId, -1);
                });
            });

            document.querySelectorAll('.plus-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const productId = this.dataset.productId;
                    const maxQty = parseInt(this.dataset.maxQty);
                    updateQuantity(productId, 1, maxQty);
                });
            });

            // Remove buttons
            document.querySelectorAll('.remove-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const productId = this.dataset.productId;
                    removeItem(productId);
                });
            });

            // Initialize summary
            updateOrderSummary();
        }

        function handleSelectAll() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const productCheckboxes = document.querySelectorAll('.product-checkbox:not(:disabled)');

            // Clear selected products first
            selectedProducts.clear();

            productCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
                const productId = checkbox.dataset.productId;
                const cartItem = checkbox.closest('.cart-item');

                if (selectAllCheckbox.checked) {
                    selectedProducts.add(productId);
                    cartItem.classList.add('selected');
                } else {
                    cartItem.classList.remove('selected');
                }
            });

            updateOrderSummary();
        }

        function handleProductSelection() {
            const productId = this.dataset.productId;
            const cartItem = this.closest('.cart-item');

            if (this.checked) {
                selectedProducts.add(productId);
                cartItem.classList.add('selected');
            } else {
                selectedProducts.delete(productId);
                cartItem.classList.remove('selected');
            }

            // Update select all checkbox
            const selectAllCheckbox = document.getElementById('selectAll');
            const productCheckboxes = document.querySelectorAll('.product-checkbox:not(:disabled)');
            const checkedCheckboxes = document.querySelectorAll('.product-checkbox:checked');

            selectAllCheckbox.checked = productCheckboxes.length === checkedCheckboxes.length;
            selectAllCheckbox.indeterminate = checkedCheckboxes.length > 0 && checkedCheckboxes.length < productCheckboxes.length;

            updateOrderSummary();
        }

        function updateQuantity(productId, change, maxQty = null) {
            const qtyElement = document.getElementById(`qty-${productId}`);
            if (!qtyElement) return;

            const currentQty = parseInt(qtyElement.textContent);
            let newQty = currentQty + change;

            // Validate quantity
            if (newQty < 1) newQty = 1;
            if (maxQty && newQty > maxQty) {
                showNotification(`Chỉ còn ${maxQty} sản phẩm trong kho`, 'warning');
                newQty = maxQty;
            }

            if (newQty === currentQty) return;

            // Update UI immediately
            qtyElement.textContent = newQty;

            // Update cart data
            if (window.cartData[productId]) {
                window.cartData[productId].quantity = newQty;
            }

            // Update order summary if product is selected
            if (selectedProducts.has(productId)) {
                updateOrderSummary();
            }

            // Send update to server
            updateQuantityOnServer(productId, newQty);
        }

        function updateQuantityOnServer(productId, quantity) {
            const form = document.getElementById('updateQuantityForm');
            const quantityInput = document.getElementById('updateQuantityValue');

            form.action = window.cartUrls.update.replace(':id', productId);
            quantityInput.value = quantity;
            form.submit();
        }

        function removeItem(productId) {
            if (!confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')) {
                return;
            }

            // Remove from selected products
            selectedProducts.delete(productId);

            // Remove from UI
            const itemElement = document.querySelector(`[data-product-id="${productId}"]`);
            if (itemElement) {
                itemElement.style.transition = 'all 0.3s ease';
                itemElement.style.transform = 'scale(0.8)';
                itemElement.style.opacity = '0';

                setTimeout(() => {
                    itemElement.remove();
                    updateOrderSummary();

                    // Check if cart is empty
                    const remainingItems = document.querySelectorAll('.cart-item');
                    if (remainingItems.length === 0) {
                        location.reload();
                    }
                }, 300);
            }

            // Remove from server
            const form = document.getElementById('removeItemForm');
            form.action = window.cartUrls.delete.replace(':id', productId);
            form.submit();

            showNotification('Đã xóa sản phẩm khỏi giỏ hàng', 'success');
        }

        function updateOrderSummary() {
            const selectedCount = document.getElementById('selectedCount');
            const checkoutCount = document.getElementById('checkoutCount');
            const selectedProductsList = document.getElementById('selectedProductsList');
            const subtotalElement = document.getElementById('subtotal');
            const shippingElement = document.getElementById('shipping');
            const totalElement = document.getElementById('total');
            const checkoutBtn = document.getElementById('checkoutBtn');
            const selectedProductsInput = document.getElementById('selectedProductsInput');
            const shippingMessage = document.getElementById('shippingMessage');

            // Update counts
            const count = selectedProducts.size;
            selectedCount.textContent = count;
            checkoutCount.textContent = count;

            // Calculate totals
            let subtotal = 0;
            const selectedProductsData = [];

            // Clear selected products list
            selectedProductsList.innerHTML = '';

            if (count === 0) {
                selectedProductsList.innerHTML = '<p class="no-selection">Chưa có sản phẩm nào được chọn</p>';
                checkoutBtn.disabled = true;

                // Reset all values to 0 when no products selected
                subtotalElement.textContent = '0 VNĐ';
                totalElement.textContent = '0 VNĐ';
                shippingElement.textContent = 'Tính bên ngoài hóa đơn';
                shippingMessage.textContent = 'Chọn sản phẩm để xem thông tin vận chuyển';
            } else {
                selectedProducts.forEach(productId => {
                    const productData = window.cartData[productId];
                    if (productData) {
                        const qtyElement = document.getElementById(`qty-${productId}`);
                        const currentQty = qtyElement ? parseInt(qtyElement.textContent) : productData.quantity;
                        const itemTotal = productData.price * currentQty;

                        subtotal += itemTotal;
                        selectedProductsData.push({
                            id: productId,
                            quantity: currentQty
                        });

                        // Add to selected products list
                        const productItem = document.createElement('div');
                        productItem.className = 'selected-product-item';
                        productItem.innerHTML = `
                    <div class="selected-product-info">
                        <div class="selected-product-name">${productData.name}</div>
                        <div class="selected-product-details">${productData.brand} • ${productData.category} • SL: ${currentQty}</div>
                    </div>
                    <div class="selected-product-price">${formatPrice(itemTotal)} VNĐ</div>
                `;
                        selectedProductsList.appendChild(productItem);
                    }
                });

                checkoutBtn.disabled = false;

                // Update shipping message and info based on subtotal
                if (subtotal >= FREE_SHIPPING_THRESHOLD) {
                    // Đơn hàng >= 500k: Miễn phí vận chuyển
                    shippingElement.textContent = 'Miễn phí';
                    shippingMessage.innerHTML = '<i class="fas fa-check-circle"></i> Đơn hàng được miễn phí vận chuyển';
                } else {
                    // Đơn hàng < 500k: Hiển thị cần mua thêm bao nhiêu
                    const remaining = FREE_SHIPPING_THRESHOLD - subtotal;
                    shippingElement.textContent = 'Tính ngoài hóa đơn';
                    shippingMessage.innerHTML = `Mua thêm ${formatPrice(remaining)} VNĐ để được miễn phí vận chuyển`;
                }

                // Update price display - total = subtotal (shipping always free in calculation)
                subtotalElement.textContent = formatPrice(subtotal) + ' VNĐ';
                totalElement.textContent = formatPrice(subtotal) + ' VNĐ';
            }

            // Update hidden input for form submission
            selectedProductsInput.value = Array.from(selectedProducts).join(',');

            // Thêm vào cuối function updateOrderSummary(), sau phần update shipping message:
            const shippingInfo = document.querySelector('.shipping-info');
            const shippingText = document.querySelector('.shipping-text');

            if (count === 0) {
                shippingInfo.className = 'shipping-info';
                shippingText.className = 'shipping-text';
            } else if (subtotal >= FREE_SHIPPING_THRESHOLD) {
                shippingInfo.className = 'shipping-info free-shipping';
                shippingText.className = 'shipping-text free';
            } else {
                shippingInfo.className = 'shipping-info need-more';
                shippingText.className = 'shipping-text warning';
            }
        }

        function formatPrice(price) {
            return new Intl.NumberFormat('vi-VN').format(price);
        }

        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'}"></i>
                <span>${message}</span>
            `;

            const bgColor = type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : type === 'warning' ? '#ffc107' : '#17a2b8';
            const textColor = type === 'warning' ? '#212529' : 'white';

            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${bgColor};
                color: ${textColor};
                padding: 12px 20px;
                border-radius: 6px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 10000;
                display: flex;
                align-items: center;
                gap: 8px;
                font-weight: 500;
                animation: slideInRight 0.3s ease;
                max-width: 400px;
                font-size: 14px;
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }

        // Add notification animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }

            @keyframes slideOutRight {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
@endsection
