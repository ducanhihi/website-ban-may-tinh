@extends('layout.customerApp')

@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <div class="container">
        <!-- Header -->
        <div class="header">
            <button class="back-btn" onclick="window.location.href='{{ route('customer.main-home') }}'">
                <i class="fas fa-arrow-left"></i>
                Tiếp tục mua sắm
            </button>
            <h1>Giỏ hàng của bạn</h1>
            {{--            <p class="cart-count">{{ count($cartDetails) }} sản phẩm trong giỏ hàng</p>--}}
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
                         data-price="{{ $detail->product->price_out}}">
                        <div class="item-content">
                            <div class="checkbox-section">
                                <input type="checkbox"
                                       class="product-checkbox"
                                       data-product-id="{{ $detail->product_id }}"
                                    {{ $detail->product->quantity <= 0 ? 'disabled' : '' }}>
                            </div>
                            <div class="item-image" style="position: relative;">
                                <img src="{{ asset('image/' . $detail->product->image) }}"
                                     alt="{{ $detail->product->name }}">

                                @if($detail->product->quantity <= 0)
                                    <span class="out-of-stock-badge">Hết hàng</span>
                                @endif

                                @if($detail->product->discount_percent !== null && $detail->product->discount_percent < 100)
                                    <span class="discount-badge">Giảm {{ $detail->product->discount_percent }}%</span>
                                @endif
                            </div>

                            <div class="item-details">
                                <div class="item-header">
                                    <div class="item-info">
                                        <h3>{{ $detail->product->name }}</h3>
                                        <div class="brand-info">
                                            <span class="brand-label">{{ $detail->product->brand->name ?? 'N/A' }}</span>
                                        </div>
                                        <div class="category-info">
                                            <span class="category-label">{{ $detail->product->category->name ?? 'N/A' }}</span>
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
                                            <div class="old-price-discount">
            <span class="old-price" style="text-decoration: line-through;">
                {{ number_format($detail->product->price_out, 0, ',', '.') }} VNĐ
            </span>
                                                <span class="discount-info">
                -{{ $detail->product->discount_percent }}%
            </span>
                                            </div>
                                        @endif

                                        <div class="current-price">
                                            {{ number_format($detail->product->final_price, 0, ',', '.') }} VNĐ
                                        </div>
                                    </div>


                                    <div class="quantity-controls">
                                        @if($detail->product->quantity > 0)
                                            <button type="button" class="qty-btn minus-btn" data-product-id="{{ $detail->product_id }}">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <span class="quantity" id="qty-{{ $detail->product_id }}">{{ $detail->quantity }}</span>
                                            <button type="button" class="qty-btn plus-btn" data-product-id="{{ $detail->product_id }}">
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
                                    <p class="out-of-stock-text">Sản phẩm tạm hết hàng. Dự kiến có hàng trong 3-5 ngày.</p>
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

                    <!-- Promo Code -->
                    <div class="promo-section">
                        <label>Mã giảm giá</label>
                        <div class="promo-input">
                            <input type="text" placeholder="Nhập mã giảm giá" id="promoCode">
                            <button type="button" id="applyPromoBtn">Áp dụng</button>
                        </div>
                        <p class="promo-success" id="promoSuccess" style="display: none;">
                            ✓ Mã giảm giá đã được áp dụng
                        </p>
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
                            <span id="shipping">0 VNĐ</span>
                        </div>
                        {{--                        <div class="price-row">--}}
                        {{--                            <span>Thuế (10%)</span>--}}
                        {{--                            <span id="tax">0 VNĐ</span>--}}
                        {{--                        </div>--}}
                        <div class="price-row discount-row" id="discountRow" style="display: none;">
                            <span>Giảm giá</span>
                            <span id="discount">-0 VNĐ</span>
                        </div>
                        <div class="divider"></div>
                        <div class="price-row total-row">
                            <span>Tổng cộng</span>
                            <span id="total">0 VNĐ</span>
                        </div>
                    </div>

                    <!-- Shipping Info -->
                    <div class="shipping-info" id="shippingInfo" style="display: none;">
                        <div class="shipping-text">
                            <i class="fas fa-truck"></i>
                            <span id="shippingText">Miễn phí vận chuyển</span>
                        </div>
                    </div>

                    <!-- Checkout Buttons -->
                    <div class="checkout-section">
                        <!-- Form gửi dữ liệu đến controller -->
                        <form action="{{ route('customer.buy-inCart') }}" method="post" id="checkoutForm">
                            @csrf
                            <input type="hidden" name="selected_products" id="selectedProductsInput" value="">

                            <button type="submit" class="checkout-btn primary" id="checkoutBtn" disabled>
                                <i class="fas fa-credit-card"></i>
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

    <!-- Inline JavaScript để test -->
    <script>
        console.log('=== CART DEBUG START ===');

        // Test cơ bản
        console.log('Document ready state:', document.readyState);
        console.log('jQuery available:', typeof $ !== 'undefined');

        // Chuyển đổi dữ liệu Laravel thành JavaScript
        window.cartData = {
            @foreach ($cartDetails as $detail)
                {{ $detail->product_id }}: {
                name: "{{ addslashes($detail->product->name) }}",
                price: {{ round($detail->product->final_price) }},
                quantity: {{ $detail->quantity }},
                inStock: {{ $detail->product->quantity > 0 ? 'true' : 'false' }},
                stockQuantity: {{ $detail->product->quantity }},
                image: "{{ $detail->product->image_url }}",
                brand: "{{ addslashes($brands[$detail->product->brand_id] ?? 'Thương hiệu') }}"
            },
            @endforeach
        };


        // URLs cho các action
        window.cartUrls = {
            delete: "{{ route('cart.delete', ':id') }}",
            update: "{{ route('cart.update', ':id') }}"
        };

        console.log('Cart data loaded:', window.cartData);
        console.log('Cart URLs loaded:', window.cartUrls);

        // Test DOM elements
        function testElements() {
            console.log('=== TESTING DOM ELEMENTS ===');
            console.log('selectAll:', document.getElementById('selectAll'));
            console.log('selectedCount:', document.getElementById('selectedCount'));
            console.log('checkoutBtn:', document.getElementById('checkoutBtn'));
            console.log('minus buttons:', document.querySelectorAll('.minus-btn').length);
            console.log('plus buttons:', document.querySelectorAll('.plus-btn').length);
            console.log('remove buttons:', document.querySelectorAll('.remove-btn').length);
            console.log('product checkboxes:', document.querySelectorAll('.product-checkbox').length);
        }

        // Test ngay lập tức
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', testElements);
        } else {
            testElements();
        }

        console.log('=== CART DEBUG END ===');
    </script>

    <!-- Load external JavaScript -->

    <!-- Fallback inline JavaScript nếu file external không load được -->
    <script>
        // Kiểm tra xem cart.js có load được không
        setTimeout(function() {
            if (typeof window.cartInitialized === 'undefined') {
                console.warn('External cart.js not loaded, using inline fallback');

                // Inline fallback code
                const selectedProducts = new Set();
                let promoApplied = false;

                function updateQuantity(productId, change) {
                    console.log('FALLBACK: updateQuantity called:', productId, change);
                    const qtyElement = document.getElementById(qty-${productId});
                    if (!qtyElement) return;

                    const currentQty = parseInt(qtyElement.textContent);
                    const newQty = currentQty + change;

                    if (newQty < 1) return;

                    qtyElement.textContent = newQty;
                    console.log('FALLBACK: Updated quantity to:', newQty);
                }

                function removeItem(productId) {
                    console.log('FALLBACK: removeItem called:', productId);
                    if (confirm('Bạn có chắc muốn xóa sản phẩm này?')) {
                        const itemElement = document.querySelector([data-product-id="${productId}"]);
                        if (itemElement) {
                            itemElement.style.display = 'none';
                            console.log('FALLBACK: Item removed');
                        }
                    }
                }

                function selectAllProducts() {
                    console.log('FALLBACK: selectAllProducts called');
                    const selectAllCheckbox = document.getElementById('selectAll');
                    const productCheckboxes = document.querySelectorAll('.product-checkbox:not(:disabled)');

                    productCheckboxes.forEach(checkbox => {
                        checkbox.checked = selectAllCheckbox.checked;
                        const productId = checkbox.dataset.productId;

                        if (selectAllCheckbox.checked) {
                            selectedProducts.add(productId);
                        } else {
                            selectedProducts.delete(productId);
                        }
                    });

                    updateSelectedProducts();
                }

                function updateSelectedProducts() {
                    console.log('FALLBACK: updateSelectedProducts called');
                    selectedProducts.clear();
                    document.querySelectorAll('.product-checkbox:checked').forEach(checkbox => {
                        selectedProducts.add(checkbox.dataset.productId);
                    });

                    const selectedCount = document.getElementById('selectedCount');
                    const checkoutCount = document.getElementById('checkoutCount');

                    if (selectedCount) selectedCount.textContent = selectedProducts.size;
                    if (checkoutCount) checkoutCount.textContent = selectedProducts.size;

                    console.log('FALLBACK: Selected products:', Array.from(selectedProducts));
                }

                // Gán event listeners
                document.addEventListener('DOMContentLoaded', function() {
                    console.log('FALLBACK: DOM loaded, adding event listeners');

                    // Select all checkbox
                    const selectAllCheckbox = document.getElementById('selectAll');
                    if (selectAllCheckbox) {
                        selectAllCheckbox.addEventListener('change', selectAllProducts);
                        console.log('FALLBACK: Select all listener added');
                    }

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
                            updateQuantity(productId, 1);
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

                    // Product checkboxes
                    document.querySelectorAll('.product-checkbox').forEach(checkbox => {
                        checkbox.addEventListener('change', updateSelectedProducts);
                    });

                    console.log('FALLBACK: All event listeners added');
                });
            }
        }, 1000);
    </script>
@endsection
