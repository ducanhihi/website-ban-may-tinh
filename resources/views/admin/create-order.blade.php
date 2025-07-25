@extends('layout.app')

@section('content')
    <!-- Thêm meta tag cho CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .main-content {
            margin-left: 250px;
            padding: 20px;
            min-height: 100vh;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }

        .product-card {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border-radius: 15px;
            overflow: hidden;
            background: white;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .cart-section {
            position: sticky;
            top: 20px;
            max-height: calc(100vh - 40px);
            overflow-y: auto;
        }

        .price-highlight {
            background: linear-gradient(45deg, #dc2626, #b91c1c);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: bold;
            font-size: 1.2em;
        }

        .btn-add-cart {
            background: linear-gradient(45deg, #475569, #334155);
            border: none;
            transition: all 0.3s ease;
            border-radius: 10px;
            font-weight: 600;
        }

        .btn-add-cart:hover {
            background: linear-gradient(45deg, #334155, #1e293b);
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(71, 85, 105, 0.4);
        }

        .btn-add-cart:disabled {
            background: #6b7280;
            transform: none;
            box-shadow: none;
        }

        .search-box {
            border-radius: 25px;
            border: 2px solid #e5e7eb;
            transition: all 0.3s ease;
            padding: 12px 20px;
        }

        .search-box:focus {
            border-color: #475569;
            box-shadow: 0 0 0 0.2rem rgba(71, 85, 105, 0.25);
            transform: scale(1.02);
        }

        .card-header-custom {
            background: linear-gradient(45deg, #475569, #334155);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 15px 20px;
        }

        .cart-item {
            border-left: 4px solid #475569;
            margin-bottom: 12px;
            padding: 15px;
            background: linear-gradient(135deg, #f8fafc 0%, #e5e7eb 100%);
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .cart-item:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .total-section {
            background: linear-gradient(45deg, #374151, #1f2937);
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin-top: 15px;
            box-shadow: 0 4px 15px rgba(55, 65, 81, 0.3);
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
        }

        .quantity-btn {
            background: #f9fafb;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .quantity-btn:hover {
            background: #475569;
            color: white;
        }

        .quantity-input {
            border: none;
            text-align: center;
            width: 60px;
            padding: 8px;
            font-weight: 600;
        }

        .stock-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 4px 8px;
            border-radius: 15px;
            font-size: 0.8em;
        }

        .out-of-stock {
            background: rgba(220, 38, 38, 0.9);
        }

        .low-stock {
            background: rgba(245, 158, 11, 0.9);
        }

        .in-stock {
            background: rgba(34, 197, 94, 0.9);
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #475569;
            box-shadow: 0 0 0 0.2rem rgba(71, 85, 105, 0.25);
        }

        .btn-success {
            background: linear-gradient(45deg, #374151, #1f2937);
            border: none;
            border-radius: 15px;
            padding: 15px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            background: linear-gradient(45deg, #1f2937, #111827);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(55, 65, 81, 0.4);
        }

        .alert-custom {
            border-radius: 10px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 20px;
        }

        .alert-error {
            background: linear-gradient(45deg, #dc2626, #b91c1c);
            color: white;
        }

        .alert-success {
            background: linear-gradient(45deg, #059669, #047857);
            color: white;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 10px;
            }
        }

        .product-info {
            padding: 20px;
        }

        .category-badge {
            background: linear-gradient(45deg, #475569, #334155);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
        }

        .header-card {
            background: linear-gradient(45deg, #475569, #334155);
            color: white;
            border-radius: 20px;
            border: none;
        }

        .search-icon {
            background: linear-gradient(45deg, #475569, #334155);
            color: white;
            border: none;
            border-radius: 25px 0 0 25px;
        }

        .payment-header {
            background: linear-gradient(45deg, #374151, #1f2937);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 15px 20px;
        }

        .text-primary-custom {
            color: #475569 !important;
        }

        .text-success-custom {
            color: #374151 !important;
        }

        .text-warning-custom {
            color: #d97706 !important;
        }

        .text-info-custom {
            color: #0891b2 !important;
        }

        .badge-primary-custom {
            background: #475569;
            color: white;
        }
    </style>

    <div class="main-content">
        <div class="container-fluid">
            <!-- Alert Messages -->
            <div id="alertContainer"></div>

            <!-- Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card header-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="mb-1"><i class="fas fa-shopping-cart me-2"></i>Tạo Đơn Hàng</h2>
                                    <p class="mb-0 opacity-75">Quản lý đơn hàng laptop và phụ kiện điện tử</p>
                                </div>
                                <div class="badge bg-light text-dark fs-6">
                                    <i class="fas fa-box me-1"></i>{{ count($allProducts) }} sản phẩm
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Cột trái: Danh sách sản phẩm -->
                <div class="col-lg-8 col-md-7">
                    <!-- Thanh tìm kiếm -->
                    <div class="card mb-4" style="border-radius: 15px; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        <div class="card-body">
                            <div class="input-group">
                                <span class="input-group-text search-icon">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" id="searchInput" class="form-control search-box"
                                       placeholder="Tìm kiếm theo tên, mã sản phẩm, thương hiệu..." style="border-radius: 0 25px 25px 0;">
                            </div>
                        </div>
                    </div>

                    <!-- Danh sách sản phẩm -->
                    <div id="mix-container" class="row">
                        @foreach($allProducts as $product)
                            <div class="mix col-12 col-md-6 col-xl-4 mb-4"
                                 data-name="{{ strtolower($product->name) }}"
                                 data-cate="{{ strtolower($product->category_name) }}"
                                 data-brand="{{ strtolower($product->brand_name) }}"
                                 data-code="{{ strtolower($product->product_code) }}"
                                 data-stock="{{ $product->quantity }}">
                                <div class="card product-card h-100">
                                    <div class="position-relative">
                                        <img class="card-img-top" src="{{ asset('image/' . $product->image) }}"
                                             alt="{{ $product->name }}" style="height:220px; object-fit:cover;">

                                        <!-- Stock Badge -->
                                        <div class="stock-badge {{ $product->quantity == 0 ? 'out-of-stock' : ($product->quantity <= 5 ? 'low-stock' : 'in-stock') }}">
                                            <i class="fas fa-box me-1"></i>{{ $product->quantity }}
                                        </div>

                                        <!-- Category Badge -->
                                        <div class="position-absolute top-0 end-0 m-2">
                                            <span class="category-badge">{{ $product->category_name }}</span>
                                        </div>
                                    </div>
                                    <div class="product-info d-flex flex-column">
                                        <h6 class="card-title text-truncate fw-bold" title="{{ $product->name }}">{{ $product->name }}</h6>
                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="fas fa-barcode me-1"></i>{{ $product->product_code }}
                                            </small>
                                        </div>
                                        <div class="mb-3">
                                            <small class="text-muted">
                                                <i class="fas fa-tag me-1"></i>{{ $product->brand_name }}
                                            </small>
                                        </div>
                                        <div class="mt-auto">
                                            <div class="price-section">
                                                @if($product->has_discount)
                                                    <div class="old-price-discount">
                                                        <span class="old-price" style="text-decoration: line-through;">
                                                            {{ number_format($product->price_out, 0, ',', '.') }} VNĐ
                                                        </span>
                                                        @if($product->discount_percent)
                                                            <span class="discount-info">-{{ $product->discount_percent }}%</span>
                                                        @endif
                                                    </div>

                                                @endif

                                                <div class="price-highlight mb-3">
                                                    {{ number_format($product->final_price, 0, ',', '.') }} VNĐ
                                                </div>
                                            </div>
                                            <div class="add-to-cart-form">
                                                <div class="quantity-controls mb-2">
                                                    <button type="button" class="quantity-btn" onclick="decreaseQuantity(this)">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <input type="number" name="quantity" class="quantity-input"
                                                           min="1" max="{{ $product->quantity }}" value="1"
                                                           data-max="{{ $product->quantity }}" readonly>
                                                    <button type="button" class="quantity-btn" onclick="increaseQuantity(this)">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                                <button type="button" class="btn btn-primary btn-add-cart add-to-cart w-100"
                                                        data-id="{{ $product->id }}"
                                                        data-name="{{ $product->name }}"
                                                        data-price="{{ $product->final_price }}"
                                                        data-stock="{{ $product->quantity }}"
                                                    {{ $product->quantity == 0 ? 'disabled' : '' }}>
                                                    <i class="fas fa-cart-plus me-2"></i>
                                                    {{ $product->quantity == 0 ? 'Hết hàng' : 'Thêm vào giỏ' }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Phân trang -->
                    <div id="pagination" class="my-4 d-flex justify-content-center"></div>
                </div>

                <!-- Cột phải: Thông tin đơn hàng -->
                <div class="col-lg-4 col-md-5">
                    <div class="cart-section">
                        <!-- Giỏ hàng -->
                        <div class="card mb-4" style="border-radius: 15px; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                            <div class="card-header card-header-custom">
                                <h5 class="mb-0">
                                    <i class="fas fa-shopping-basket me-2"></i>Giỏ Hàng
                                    <span class="badge bg-light text-dark ms-2" id="cart-count">0</span>
                                </h5>
                            </div>
                            <div class="card-body" style="max-height: 350px; overflow-y: auto;">
                                <ul id="cart-items" class="list-unstyled">
                                    <li class="text-center text-muted py-4">
                                        <i class="fas fa-shopping-cart fa-3x mb-3 opacity-50"></i>
                                        <p class="mb-0">Giỏ hàng trống</p>
                                        <small>Thêm sản phẩm để bắt đầu</small>
                                    </li>
                                </ul>
                                <div class="total-section text-center">
                                    <h5 class="mb-0">
                                        <i class="fas fa-calculator me-2"></i>Tổng tiền:
                                        <span id="total-price">0</span> VNĐ
                                    </h5>
                                </div>
                            </div>
                        </div>

                        <!-- Form đơn hàng -->
                        <form method="POST" action="{{ route('admin.order-save') }}" id="orderForm">
                            @csrf
                            <!-- Hidden inputs cho cart data -->
                            <input type="hidden" name="cartItems" id="cartItemsInput">
                            <input type="hidden" name="total" id="totalInput">

                            <!-- Thông tin khách hàng -->
                            <div class="card mb-4" style="border-radius: 15px; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                                <div class="card-header card-header-custom">
                                    <h5 class="mb-0">
                                        <i class="fas fa-user me-2"></i>Thông Tin Khách Hàng
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="customer_name" class="form-label fw-bold">
                                            <i class="fas fa-user me-1 text-primary-custom"></i>Họ và tên *
                                        </label>
                                        <input type="text" name="customer_name" id="customer_name" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="customer_phone" class="form-label fw-bold">
                                            <i class="fas fa-phone me-1 text-primary-custom"></i>Số điện thoại *
                                        </label>
                                        <input type="tel" name="customer_phone" id="customer_phone" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-bold">
                                            <i class="fas fa-envelope me-1 text-primary-custom"></i>Email *
                                        </label>
                                        <input type="email" name="email" id="email" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="customer_address" class="form-label fw-bold">
                                            <i class="fas fa-map-marker-alt me-1 text-primary-custom"></i>Địa chỉ *
                                        </label>
                                        <textarea name="customer_address" id="customer_address" class="form-control" rows="3" required></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Phương thức thanh toán -->
                            <div class="card mb-4" style="border-radius: 15px; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                                <div class="card-header payment-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-credit-card me-2"></i>Phương Thức Thanh Toán
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="payment_method" class="form-label fw-bold">
                                            <i class="fas fa-money-bill-wave me-1 text-success-custom"></i>Chọn phương thức *
                                        </label>
                                        <select name="payment_method" id="payment_method" class="form-select" required>
                                            <option value="">-- Chọn phương thức thanh toán --</option>
                                            <option value="Tiền mặt">💵 Tiền mặt</option>
                                            <option value="Chuyển khoản">🏦 Chuyển khoản ngân hàng</option>
                                            <option value="Quẹt thẻ">💳 Thẻ tín dụng</option>
                                            <option value="Trả góp">📅 Trả góp</option>
                                        </select>
                                    </div>



                                    <div class="mb-3">
                                        <label for="notes" class="form-label fw-bold">
                                            <i class="fas fa-sticky-note me-1 text-info-custom"></i>Ghi chú
                                        </label>
                                        <textarea name="notes" id="notes" class="form-control" rows="2" placeholder="Ghi chú thêm về đơn hàng..."></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-success w-100 btn-lg" id="submitOrder" disabled>
                                        <i class="fas fa-check-circle me-2"></i>Tạo Đơn Hàng
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MixItUp + Pagination.js -->
    <script src="https://cdn.jsdelivr.net/npm/mixitup/dist/mixitup.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/paginationjs/2.1.5/pagination.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/paginationjs/2.1.5/pagination.css" rel="stylesheet"/>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var container = document.getElementById('mix-container');
            var allItems = Array.from(container.querySelectorAll('.mix'));
            var searchInput = document.getElementById('searchInput');
            var cartItems = [];
            var totalPrice = 0;

            // Quantity control functions
            window.increaseQuantity = function(button) {
                const input = button.parentElement.querySelector('.quantity-input');
                const max = parseInt(input.getAttribute('data-max'));
                const current = parseInt(input.value);

                if (current < max) {
                    input.value = current + 1;
                } else {
                    showAlert('Không thể thêm quá số lượng tồn kho!', 'error');
                }
            };

            window.decreaseQuantity = function(button) {
                const input = button.parentElement.querySelector('.quantity-input');
                const current = parseInt(input.value);

                if (current > 1) {
                    input.value = current - 1;
                }
            };

            // Alert function
            function showAlert(message, type = 'error') {
                const alertContainer = document.getElementById('alertContainer');
                const alertClass = type === 'error' ? 'alert-error' : 'alert-success';

                const alertHtml = `
                    <div class="alert alert-custom ${alertClass} alert-dismissible fade show" role="alert">
                        <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : 'check-circle'} me-2"></i>
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;

                alertContainer.innerHTML = alertHtml;

                // Auto remove after 5 seconds
                setTimeout(() => {
                    const alert = alertContainer.querySelector('.alert');
                    if (alert) {
                        alert.remove();
                    }
                }, 5000);
            }

            function renderPage(items) {
                allItems.forEach(item => item.style.display = 'none');
                items.forEach(item => item.style.display = '');
            }

            // Phân trang mặc định
            $('#pagination').pagination({
                dataSource: allItems,
                pageSize: 6,
                callback: function (data) {
                    renderPage(data);
                }
            });

            // Tìm kiếm
            searchInput.addEventListener('input', function () {
                var searchText = this.value.toLowerCase();
                var filtered = allItems.filter(item =>
                    item.dataset.name.includes(searchText) ||
                    item.dataset.cate.includes(searchText) ||
                    item.dataset.brand.includes(searchText) ||
                    item.dataset.code.includes(searchText)
                );

                if (filtered.length) {
                    $('#pagination').pagination({
                        dataSource: filtered,
                        pageSize: 6,
                        callback: function (data) {
                            renderPage(data);
                        }
                    });
                    $('#pagination').pagination('go', 1);
                } else {
                    renderPage([]);
                }
            });

            // Xử lý thêm sản phẩm vào giỏ hàng với validation
            document.querySelectorAll('.add-to-cart').forEach(button => {
                button.addEventListener('click', function () {
                    const productId = this.getAttribute('data-id');
                    const productName = this.getAttribute('data-name');
                    const productPrice = parseFloat(this.getAttribute('data-price'));
                    const productStock = parseInt(this.getAttribute('data-stock'));
                    const quantityInput = this.closest('.add-to-cart-form').querySelector('input[name="quantity"]');
                    const quantity = parseInt(quantityInput.value);

                    // Validation
                    if (quantity <= 0) {
                        showAlert('Số lượng phải lớn hơn 0!', 'error');
                        quantityInput.value = 1;
                        return;
                    }

                    if (quantity > productStock) {
                        showAlert(`Chỉ còn ${productStock} sản phẩm trong kho!`, 'error');
                        quantityInput.value = productStock;
                        return;
                    }

                    // Kiểm tra tổng số lượng trong giỏ hàng
                    const existingItem = cartItems.find(item => item.id === productId);
                    const currentCartQuantity = existingItem ? existingItem.quantity : 0;
                    const totalQuantity = currentCartQuantity + quantity;

                    if (totalQuantity > productStock) {
                        showAlert(`Tổng số lượng trong giỏ hàng không được vượt quá ${productStock}!`, 'error');
                        return;
                    }

                    // Thêm vào giỏ hàng
                    if (existingItem) {
                        existingItem.quantity += quantity;
                    } else {
                        const cartItem = {
                            id: productId,
                            name: productName,
                            price: productPrice,
                            quantity: quantity,
                            stock: productStock
                        };
                        cartItems.push(cartItem);
                    }

                    totalPrice += productPrice * quantity;
                    updateCart();

                    // Hiệu ứng thêm vào giỏ hàng
                    this.innerHTML = '<i class="fas fa-check me-2"></i>Đã thêm!';
                    this.style.background = 'linear-gradient(45deg, #059669, #047857)';
                    setTimeout(() => {
                        this.innerHTML = '<i class="fas fa-cart-plus me-2"></i>Thêm vào giỏ';
                        this.style.background = 'linear-gradient(45deg, #475569, #334155)';
                    }, 1500);

                    showAlert(`Đã thêm ${quantity} ${productName} vào giỏ hàng!`, 'success');
                });
            });

            function updateCart() {
                const cartList = document.getElementById('cart-items');
                const cartCount = document.getElementById('cart-count');
                const submitButton = document.getElementById('submitOrder');

                cartCount.textContent = cartItems.length;
                cartList.innerHTML = '';

                if (cartItems.length === 0) {
                    cartList.innerHTML = `
                        <li class="text-center text-muted py-4">
                            <i class="fas fa-shopping-cart fa-3x mb-3 opacity-50"></i>
                            <p class="mb-0">Giỏ hàng trống</p>
                            <small>Thêm sản phẩm để bắt đầu</small>
                        </li>
                    `;
                    submitButton.disabled = true;
                } else {
                    cartItems.forEach((item, index) => {
                        const listItem = document.createElement('li');
                        listItem.className = 'cart-item';
                        listItem.innerHTML = `
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">${item.name}</h6>
                                    <div class="d-flex align-items-center mb-2">
                                        <button class="btn btn-sm btn-outline-secondary me-2" onclick="updateCartQuantity(${index}, -1)">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <span class="fw-bold mx-2">${item.quantity}</span>
                                        <button class="btn btn-sm btn-outline-secondary ms-2" onclick="updateCartQuantity(${index}, 1)">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted">${numberWithCommas(item.price)} VNĐ x ${item.quantity}</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-primary-custom mb-2">${numberWithCommas(item.price * item.quantity)} VNĐ</div>
                                    <button class="btn btn-sm btn-outline-danger" onclick="removeFromCart(${index})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        `;
                        cartList.appendChild(listItem);
                    });
                    submitButton.disabled = false;
                }

                document.getElementById('total-price').textContent = numberWithCommas(totalPrice);

                // Cập nhật hidden inputs
                document.getElementById('cartItemsInput').value = JSON.stringify(cartItems);
                document.getElementById('totalInput').value = totalPrice;
            }

            // Update cart quantity
            window.updateCartQuantity = function(index, change) {
                const item = cartItems[index];
                const newQuantity = item.quantity + change;

                if (newQuantity <= 0) {
                    removeFromCart(index);
                    return;
                }

                if (newQuantity > item.stock) {
                    showAlert(`Chỉ còn ${item.stock} sản phẩm trong kho!`, 'error');
                    return;
                }

                totalPrice += item.price * change;
                item.quantity = newQuantity;
                updateCart();
            };

            // Remove from cart
            window.removeFromCart = function(index) {
                const item = cartItems[index];
                totalPrice -= item.price * item.quantity;
                cartItems.splice(index, 1);
                updateCart();
                showAlert(`Đã xóa ${item.name} khỏi giỏ hàng!`, 'success');
            };

            function numberWithCommas(x) {
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }

            // Xử lý submit form
            document.getElementById('orderForm').addEventListener('submit', function(e) {
                e.preventDefault();

                if (cartItems.length === 0) {
                    showAlert('Vui lòng thêm sản phẩm vào giỏ hàng!', 'error');
                    return;
                }

                // Kiểm tra các trường bắt buộc
                const customerName = document.getElementById('customer_name').value.trim();
                const customerPhone = document.getElementById('customer_phone').value.trim();
                const email = document.getElementById('email').value.trim();
                const customerAddress = document.getElementById('customer_address').value.trim();
                const paymentMethod = document.getElementById('payment_method').value;

                if (!customerName || !customerPhone || !email || !customerAddress || !paymentMethod) {
                    showAlert('Vui lòng điền đầy đủ thông tin bắt buộc!', 'error');
                    return;
                }

                // Hiển thị loading
                const submitBtn = document.getElementById('submitOrder');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang xử lý...';
                submitBtn.disabled = true;

                const formData = new FormData(this);

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showAlert(data.message + (data.order_code ? ` - Mã đơn hàng: ${data.order_code}` : ''), 'success');
                            // Reset form và giỏ hàng
                            cartItems = [];
                            totalPrice = 0;
                            updateCart();
                            this.reset();
                        } else {
                            showAlert('Lỗi: ' + data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        showAlert('Có lỗi xảy ra khi tạo đơn hàng! Vui lòng thử lại.', 'error');
                    })
                    .finally(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = cartItems.length === 0;
                    });
            });
        });
    </script>
@endsection
