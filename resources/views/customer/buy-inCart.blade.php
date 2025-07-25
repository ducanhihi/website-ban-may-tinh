@extends('layout.customerApp')

@section('content')
    <div class="checkout-container py-5">
        <div class="container">
            <!-- Header -->
            <div class="text-center mb-5">
                <h1 class="fw-bold">Thanh Toán Đơn Hàng</h1>
                <div class="checkout-steps d-flex justify-content-center mt-4">
                    <div class="step active">
                        <div class="step-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="step-text">Giỏ hàng</div>
                    </div>
                    <div class="step-connector active"></div>
                    <div class="step active">
                        <div class="step-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="step-text">Thanh toán</div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step">
                        <div class="step-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="step-text">Hoàn tất</div>
                    </div>
                </div>
            </div>

            <form action="{{ url('/vnpay_payment') }}" method="post" id="checkoutForm">
                @csrf
                <input type="hidden" name="selected_products" value="{{ implode(',', $selectedProductIds) }}">

                <div class="row">
                    <!-- Thông tin khách hàng -->
                    <div class="col-lg-7 mb-4 mb-lg-0">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                            <div class="card-header bg-gradient-primary text-white py-3">
                                <h3 class="card-title mb-0 fs-5">
                                    <i class="fas fa-user-circle me-2"></i>Thông Tin Khách Hàng
                                </h3>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="name" name="name" placeholder="Họ và tên" value="{{ Auth::user()->name ?? '' }}" required>
                                            <label for="name">
                                                <i class="fas fa-user text-muted me-2"></i>Họ và tên
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="tel" class="form-control" id="phone" name="phone" placeholder="Số điện thoại" value="{{ Auth::user()->phone ?? '' }}" required>
                                            <label for="phone">
                                                <i class="fas fa-phone text-muted me-2"></i>Số điện thoại
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ Auth::user()->email ?? '' }}" required>
                                            <label for="email">
                                                <i class="fas fa-envelope text-muted me-2"></i>Email
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="address" name="address" placeholder="Địa chỉ" value="{{ Auth::user()->address ?? '' }}" required>
                                            <label for="address">
                                                <i class="fas fa-map-marker-alt text-muted me-2"></i>Địa chỉ
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <textarea class="form-control" id="notes" name="notes" placeholder="Ghi chú" style="height: 100px"></textarea>
                                            <label for="notes">
                                                <i class="fas fa-sticky-note text-muted me-2"></i>Ghi chú đơn hàng (tùy chọn)
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Phương thức thanh toán -->
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mt-4">
                            <div class="card-header bg-gradient-primary text-white py-3">
                                <h3 class="card-title mb-0 fs-5">
                                    <i class="fas fa-credit-card me-2"></i> Phương Thức Thanh Toán
                                </h3>
                            </div>
                            <div class="card-body p-4">
                                <div class="payment-methods">
                                    <div class="payment-method">
                                        <input type="radio" class="payment-method-input" name="payment" id="payment-bank" value="Chuyển khoản" checked>
                                        <label for="payment-bank" class="payment-method-label">
                                        <span class="payment-icon">
                                            <i class="fas fa-university"></i>
                                        </span>
                                            <div class="payment-info">
                                                <span class="payment-title">Chuyển Khoản Ngân Hàng</span>
                                                <span class="payment-description">Thực hiện thanh toán trực tiếp vào tài khoản ngân hàng của chúng tôi</span>
                                            </div>
                                        </label>
                                        <div class="payment-details">
                                            <div class="alert alert-light border-start border-4 border-primary">
                                                <p class="mb-1"><strong>Thanh toán qua VNPAY</strong> </p>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="payment-method">
                                        <input type="radio" class="payment-method-input" name="payment" id="payment-cod" value="Thanh toán khi nhận hàng">
                                        <label for="payment-cod" class="payment-method-label">
                                        <span class="payment-icon">
                                            <i class="fas fa-truck"></i>
                                        </span>
                                            <div class="payment-info">
                                                <span class="payment-title">Thanh Toán Khi Nhận Hàng (COD)</span>
                                                <span class="payment-description">Thanh toán bằng tiền mặt khi nhận hàng</span>
                                            </div>
                                        </label>
                                        <div class="payment-details">
                                            <div class="alert alert-light border-start border-4 border-primary">
                                                <p class="mb-0">Bạn cần thanh toán trước 40% giá trị đơn hàng để xác nhận. Phần còn lại sẽ được thanh toán khi nhận và kiểm tra hàng. Vui lòng chuẩn bị số tiền còn lại để thanh toán cho nhân viên giao hàng.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tóm tắt đơn hàng -->
                    <div class="col-lg-5">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden sticky-lg-top" style="top: 20px; z-index: 1;">
                            <div class="card-header bg-gradient-primary text-white py-3">
                                <h3 class="card-title mb-0 fs-5">
                                    <i class="fas fa-shopping-basket me-2"></i>Tóm Tắt Đơn Hàng
                                </h3>
                            </div>
                            <div class="card-body p-4">
                                <div class="order-summary">
                                    <div class="order-products mb-4">
                                        @foreach($selectedCartDetails as $cartDetail)
                                            @php
                                                $product = $cartDetail->product;
                                                $quantity = $cartDetail->quantity;
                                                $lineTotal = $product->final_price * $quantity;
                                            @endphp

                                            <div class="order-product">
                                                <div class="product-image">
                                                    <img src="{{ $product->image ? asset('image/' . $product->image) : asset('image/no-image.png') }}"
                                                         alt="{{ $product->name }}"
                                                         onerror="this.src='{{ asset('image/no-image.png') }}'">
                                                    <span class="product-quantity">{{ $quantity }}</span>
                                                </div>
                                                <div class="product-info">
                                                    <h6 class="product-name">{{ $product->name }}</h6>
                                                    <div class="product-meta">
                                                        <span class="product-price">
                                                            {{ number_format($product->final_price, 0, ',', '.') }} VNĐ
                                                        </span>
                                                        <span class="product-quantity-text">x {{ $quantity }}</span>
                                                    </div>
                                                </div>
                                                <div class="product-total">
                                                    {{ number_format($lineTotal, 0, ',', '.') }} VNĐ
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>


                                    <div class="order-totals">
                                        <div class="order-subtotal">
                                            <span>Tạm tính</span>
                                            <span>{{ number_format($totalAmount, 0, ',', '.') }} VNĐ</span>
                                        </div>
                                        <div class="order-shipping">
                                            <span>Phí vận chuyển</span>
                                            <span>Tính ngoài hóa đơn</span>
                                        </div>
                                        <div class="order-total">
                                            <span>Tổng cộng</span>
                                            <span>{{ number_format($totalAmount, 0, ',', '.') }} VNĐ</span>
                                        </div>
                                    </div>

                                    <div class="form-check mt-4 mb-4">
                                        <input class="form-check-input" type="checkbox" id="terms" required>
                                        <label class="form-check-label" for="terms">
                                            Tôi đã đọc và đồng ý với <a href="#" class="text-primary">điều khoản và điều kiện</a> của website
                                        </label>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                                        <i class="fas fa-lock me-2"></i>Thanh Toán Qua VN Pay
                                    </button>

                                    <div class="payment-security text-center mt-3">
                                        <small class="text-muted">
                                            <i class="fas fa-shield-alt me-1"></i>
                                            Thanh toán an toàn & bảo mật
                                        </small>
                                        <div class="payment-icons mt-2">
                                            <i class="fab fa-cc-visa mx-1"></i>
                                            <i class="fab fa-cc-mastercard mx-1"></i>
                                            <i class="fab fa-cc-jcb mx-1"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <style>
        /* Gradient Background */
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }

        /* Checkout Container */
        .checkout-container {
            min-height: 100vh;
        }

        /* Checkout Steps */
        .checkout-steps {
            max-width: 600px;
            margin: 0 auto;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .step-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            font-size: 20px;
            margin-bottom: 8px;
            transition: all 0.3s ease;
        }

        .step.active .step-icon {
            background: linear-gradient(45deg, #475569, #334155);
            color: white;
            box-shadow: 0 4px 10px rgba(71, 85, 105, 0.3);
        }

        .step-text {
            font-size: 14px;
            font-weight: 600;
            color: #64748b;
        }

        .step.active .step-text {
            color: #334155;
        }

        .step-connector {
            height: 3px;
            width: 100px;
            background-color: #e2e8f0;
            margin: 0 15px;
            margin-top: 25px;
        }

        .step-connector.active {
            background: linear-gradient(45deg, #475569, #334155);
        }

        /* Card Styling */
        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .bg-gradient-primary {
            background: linear-gradient(45deg, #475569, #334155);
        }

        .rounded-4 {
            border-radius: 1rem !important;
        }

        /* Form Controls */
        .form-control, .form-select {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #475569;
            box-shadow: 0 0 0 0.2rem rgba(71, 85, 105, 0.25);
        }

        .form-floating > label {
            padding-left: 1.75rem;
        }

        /* Payment Methods */
        .payment-methods {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .payment-method {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .payment-method:hover {
            border-color: #cbd5e1;
        }

        .payment-method-input {
            display: none;
        }

        .payment-method-label {
            display: flex;
            align-items: center;
            padding: 15px;
            cursor: pointer;
            margin: 0;
            width: 100%;
        }

        .payment-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: #64748b;
            font-size: 18px;
            transition: all 0.3s ease;
        }

        .payment-info {
            flex: 1;
        }

        .payment-title {
            display: block;
            font-weight: 600;
            margin-bottom: 3px;
        }

        .payment-description {
            display: block;
            font-size: 14px;
            color: #64748b;
        }

        .payment-details {
            display: none;
            padding: 0 15px 15px;
        }

        .payment-method-input:checked + .payment-method-label .payment-icon {
            background: linear-gradient(45deg, #475569, #334155);
            color: white;
        }

        .payment-method-input:checked + .payment-method-label + .payment-details {
            display: block;
        }

        .payment-method-input:checked ~ .payment-method {
            border-color: #475569;
        }

        /* Order Summary */
        .order-products {
            max-height: 300px;
            overflow-y: auto;
            padding-right: 5px;
        }

        .order-products::-webkit-scrollbar {
            width: 5px;
        }

        .order-products::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        .order-products::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .order-product {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .order-product:last-child {
            border-bottom: none;
        }

        .product-image {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
            margin-right: 15px;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-quantity {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #475569;
            color: white;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .product-info {
            flex: 1;
        }

        .product-name {
            margin-bottom: 5px;
            font-weight: 600;
        }

        .product-meta {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
        }

        .product-price {
            color: #64748b;
        }

        .product-quantity-text {
            color: #94a3b8;
        }

        .product-total {
            font-weight: 600;
            color: #475569;
        }

        .order-totals {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px dashed #e5e7eb;
        }

        .order-subtotal, .order-shipping {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            color: #64748b;
        }

        .order-total {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            font-weight: 700;
            font-size: 18px;
        }

        /* Button Styling */
        .btn-primary {
            background: linear-gradient(45deg, #475569, #334155);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #334155, #1e293b);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(71, 85, 105, 0.4);
        }

        /* Payment Security */
        .payment-icons {
            font-size: 24px;
            color: #64748b;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .step-connector {
                width: 50px;
            }

            .product-total {
                display: none;
            }

            .product-image {
                width: 50px;
                height: 50px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hiển thị chi tiết phương thức thanh toán khi chọn
            const paymentInputs = document.querySelectorAll('.payment-method-input');

            paymentInputs.forEach(input => {
                input.addEventListener('change', function() {
                    // Hiển thị chi tiết của phương thức được chọn
                    document.querySelectorAll('.payment-method').forEach(method => {
                        method.style.borderColor = '#e5e7eb';
                    });

                    if (this.checked) {
                        this.closest('.payment-method').style.borderColor = '#475569';
                    }
                });
            });

            // Kiểm tra form trước khi submit
            document.getElementById('checkoutForm').addEventListener('submit', function(e) {
                const termsCheckbox = document.getElementById('terms');

                if (!termsCheckbox.checked) {
                    e.preventDefault();
                    alert('Vui lòng đồng ý với điều khoản và điều kiện để tiếp tục.');
                    return;
                }

                // Kiểm tra các trường bắt buộc
                const requiredFields = ['name', 'phone', 'email', 'address'];
                let hasError = false;

                requiredFields.forEach(field => {
                    const input = document.getElementById(field);
                    if (!input.value.trim()) {
                        input.classList.add('is-invalid');
                        hasError = true;
                    } else {
                        input.classList.remove('is-invalid');
                    }
                });

                if (hasError) {
                    e.preventDefault();
                    alert('Vui lòng điền đầy đủ thông tin bắt buộc.');
                }
            });
        });
    </script>
@endsection
