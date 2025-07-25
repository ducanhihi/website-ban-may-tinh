@extends('layout.customerApp')

@section('content')
    <main id="content" role="main">
        <!-- Enhanced Breadcrumb -->
        <div class="bg-gradient-light py-3">
            <div class="container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 bg-transparent">
                        <li class="breadcrumb-item">
                            <a href="{{asset('/customer/main-home')}}" class="text-navy text-decoration-none">
                                <i class="fas fa-home mr-1"></i>Trang chủ
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="#" class="text-navy text-decoration-none">{{ $product->category_name }}</a>
                        </li>
                        <li class="breadcrumb-item active text-muted" aria-current="page">
                            {{ Str::limit($product->name, 50) }}
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Main Product Section -->
        <section class="py-5">
            <div class="container">
                <!-- Product Overview -->
                <div class="row mb-5">
                    <!-- Product Gallery -->
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <div class="product-gallery-wrapper">
                            <div class="main-gallery mb-3">
                                <div id="sliderSyncingNav" class="js-slick-carousel u-slick"
                                     data-infinite="true"
                                     data-arrows-classes="d-none d-lg-inline-block u-slick__arrow-classic u-slick__arrow-centered--y rounded-circle"
                                     data-arrow-left-classes="fas fa-arrow-left u-slick__arrow-classic-inner u-slick__arrow-classic-inner--left ml-lg-2 ml-xl-4"
                                     data-arrow-right-classes="fas fa-arrow-right u-slick__arrow-classic-inner u-slick__arrow-classic-inner--right mr-lg-2 mr-xl-4"
                                     data-nav-for="#sliderSyncingThumb">
                                    <div class="js-slide">
                                        <div class="image-container">
                                            <img class="img-fluid" src="{{ asset('image/' . $product->image) }}" alt="{{ $product->name }}">
                                        </div>
                                    </div>
                                    @foreach($product->images as $items)
                                        <div class="js-slide">
                                            <div class="image-container">
                                                <img class="img-fluid" src="{{ asset('image/' . $items->URL) }}" alt="{{ $product->name }}">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div id="sliderSyncingThumb"
                                 class="js-slick-carousel u-slick u-slick--slider-syncing u-slick--slider-syncing-size u-slick--gutters-1 u-slick--transform-off"
                                 data-infinite="true" data-slides-show="5" data-is-thumbs="true"
                                 data-nav-for="#sliderSyncingNav">
                                <div class="js-slide" style="cursor: pointer;">
                                    <div class="thumb-container">
                                        <img class="img-fluid" src="{{ asset('image/' . $product->image) }}" alt="{{ $product->name }}">
                                    </div>
                                </div>
                                @foreach($product->images as $items)
                                    <div class="js-slide" style="cursor: pointer;">
                                        <div class="thumb-container">
                                            <img class="img-fluid" src="{{ asset('image/' . $items->URL) }}" alt="{{ $product->name }}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Product Info -->
                    @if($product)
                        <div class="col-lg-6">
                            <div class="product-info">
                                <!-- Product Header -->
                                <div class="product-header mb-4">
                                    <div class="d-flex align-items-center mb-2">
{{--                                        <span class="badge badge-navy mr-2">{{ $product->category_name }}</span>--}}
{{--                                        <span class="badge badge-outline-navy">{{ $product->brand_name }}</span>--}}
                                    </div>
                                    <h1 class="product-title mb-3">{{ $product->name }}</h1>

                                    <!-- Rating & Reviews -->
                                    <div class="rating-section mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="stars mr-2">
                                                @php
                                                    $avgRating = isset($averageRating) ? $averageRating : 0;
                                                    $reviewCount = isset($feedbacks) ? count($feedbacks) : 0;
                                                @endphp
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $avgRating)
                                                        <i class="fas fa-star text-warning"></i>
                                                    @elseif($i - 0.5 <= $avgRating)
                                                        <i class="fas fa-star-half-alt text-warning"></i>
                                                    @else
                                                        <i class="far fa-star text-muted"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="rating-text">{{ number_format($avgRating, 1) }}</span>
                                            <span class="text-muted mx-2">•</span>
                                            <a href="#reviews" class="text-navy text-decoration-none">{{ $reviewCount }} đánh giá</a>
                                        </div>
                                    </div>

                                    <!-- Stock Status -->
                                    <div class="stock-status mb-4">
                                        @if($product->quantity > 0)
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-check-circle text-success mr-2"></i>
                                                <span class="text-success font-weight-medium">Còn hàng</span>
                                                <span class="text-muted ml-2">({{ $product->quantity }} sản phẩm)</span>
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-times-circle text-danger mr-2"></i>
                                                <span class="text-danger font-weight-medium">Hết hàng</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Price Section -->
                                <div class="price-section mb-4">
                                    <div class="price-container">
                                        <div class="current-price">
                                            <span class="price-label">Giá bán:</span>
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

                                            <br>

                                            <span class="price-value">{{ number_format($product->final_price, 0, ',', '.') }} VNĐ</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quick Info -->
                                <div class="quick-info mb-4">
                                    <div class="info-card">
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <div class="info-item">
                                                    <span class="info-label">Mã sản phẩm:</span>
                                                    <span class="info-value">{{ $product->product_code }}</span>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="info-item">
                                                    <span class="info-label">Thương hiệu:</span>
                                                    <span class="info-value">{{ $product->brand->name }}</span>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="info-item">
                                                    <span class="info-label">Danh mục:</span>
                                                    <span class="info-value">{{ $product->category->name }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="action-section">
                                    @if($product->quantity > 0)
                                        <div class="quantity-cart-section mb-3">
                                            <div class="row align-items-end">
                                                <div class="col-4">
                                                    <label class="form-label font-weight-medium">Số lượng</label>
                                                    <div class="quantity-input">
                                                        <div class="js-quantity d-flex align-items-center">
                                                            <button class="js-minus quantity-btn" type="button">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                            <input name="quantity"
                                                                   class="js-result quantity-field"
                                                                   type="text"
                                                                   value="1"
                                                                   min="1"
                                                                   max="{{ $product->quantity }}">
                                                            <button class="js-plus quantity-btn" type="button" data-max-quantity="{{ $product->quantity }}">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-8">
                                                    <button type="button"
                                                            id="btnAddToCart"
                                                            class="btn btn-navy btn-lg w-100"
                                                            data-product-id="{{ $product->id }}">
                                                        <i class="fas fa-shopping-cart mr-2"></i>
                                                        Thêm vào giỏ hàng
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <button type="button" class="btn btn-danger btn-lg w-100 mb-3" disabled>
                                            <i class="fas fa-times-circle mr-2"></i>
                                            Hết hàng
                                        </button>
                                    @endif

{{--                                    <!-- Secondary Actions -->--}}
{{--                                    <div class="secondary-actions">--}}
{{--                                        <div class="row g-2">--}}
{{--                                            <div class="col-6">--}}
{{--                                                <button class="btn btn-outline-navy w-100">--}}
{{--                                                    <i class="far fa-heart mr-2"></i>Yêu thích--}}
{{--                                                </button>--}}
{{--                                            </div>--}}
{{--                                            <div class="col-6">--}}
{{--                                                <button class="btn btn-outline-navy w-100">--}}
{{--                                                    <i class="fas fa-exchange-alt mr-2"></i>So sánh--}}
{{--                                                </button>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Product Details Tabs -->
                <div class="product-details-section">
                    <div class="row">
                        <div class="col-12">
                            <!-- Tab Navigation -->
                            <ul class="nav nav-tabs custom-tabs mb-4" id="productTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab">
                                        <i class="fas fa-align-left mr-2"></i>Mô tả sản phẩm
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="specifications-tab" data-bs-toggle="tab" data-bs-target="#specifications" type="button" role="tab">
                                        <i class="fas fa-cogs mr-2"></i>Thông số kỹ thuật
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">
                                        <i class="fas fa-star mr-2"></i>Đánh giá ({{ isset($feedbacks) ? count($feedbacks) : 0 }})
                                    </button>
                                </li>
                            </ul>

                            <!-- Tab Content -->
                            <div class="tab-content" id="productTabsContent">
                                <!-- Description Tab -->
                                <div class="tab-pane fade show active" id="description" role="tabpanel">
                                    <div class="content-card">
                                        <div class="card-body">
                                            <div class="description-content">
                                                {!! $product->description !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Specifications Tab -->
                                <div class="tab-pane fade" id="specifications" role="tabpanel">
                                    <div class="content-card">
                                        <div class="card-body">
                                            <div class="specifications-table">
                                                @if(is_string($product->details))
                                                    @foreach(json_decode($product->details, true) as $key => $value)
                                                        <div class="spec-row">
                                                            <div class="spec-label">{{ $key }}</div>
                                                            <div class="spec-value">{{ $value }}</div>
                                                        </div>
                                                    @endforeach
                                                @elseif(is_array($product->details))
                                                    @foreach($product->details as $key => $value)
                                                        <div class="spec-row">
                                                            <div class="spec-label">{{ $key }}</div>
                                                            <div class="spec-value">{{ $value }}</div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="text-center text-muted py-4">
                                                        <i class="fas fa-info-circle mb-2"></i>
                                                        <p>Không có thông số kỹ thuật</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reviews Tab -->
                                <div class="tab-pane fade" id="reviews" role="tabpanel">
                                    <div class="reviews-section">
                                        <!-- Review Summary -->
                                        <div class="row mb-4">
                                            <div class="col-md-4">
                                                <div class="review-summary-card">
                                                    <div class="text-center">
                                                        <div class="rating-score">{{ isset($averageRating) ? number_format($averageRating, 1) : '0.0' }}</div>
                                                        <div class="rating-stars mb-2">
                                                            @php $avgRating = isset($averageRating) ? $averageRating : 0; @endphp
                                                            @for($i = 1; $i <= 5; $i++)
                                                                @if($i <= $avgRating)
                                                                    <i class="fas fa-star text-warning"></i>
                                                                @elseif($i - 0.5 <= $avgRating)
                                                                    <i class="fas fa-star-half-alt text-warning"></i>
                                                                @else
                                                                    <i class="far fa-star text-muted"></i>
                                                                @endif
                                                            @endfor
                                                        </div>
                                                        <p class="text-muted">{{ isset($feedbacks) ? count($feedbacks) : 0 }} đánh giá</p>
                                                    </div>

                                                    <!-- Rating Breakdown -->
                                                    <div class="rating-breakdown">
                                                        @if(isset($ratingCounts) && is_array($ratingCounts))
                                                            @for($i = 5; $i >= 1; $i--)
                                                                @php
                                                                    $count = $ratingCounts[$i] ?? 0;
                                                                    $percentage = isset($feedbacks) && count($feedbacks) > 0 ? ($count / count($feedbacks) * 100) : 0;
                                                                @endphp
                                                                <div class="rating-bar">
                                                                    <span class="rating-label">{{ $i }} sao</span>
                                                                    <div class="progress">
                                                                        <div class="progress-bar bg-warning" style="width: {{ $percentage }}%"></div>
                                                                    </div>
                                                                    <span class="rating-count">{{ $count }}</span>
                                                                </div>
                                                            @endfor
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-8">
                                                <!-- Write Review Button -->
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h5 class="mb-0">Nhận xét từ khách hàng</h5>
                                                    @auth
                                                        @php
                                                            $hasPurchased = DB::table('orders')
                                                                ->join('orderdetails', 'orders.id', '=', 'orderdetails.order_id')
                                                                ->where('orders.user_id', auth()->id())
                                                                ->where('orderdetails.product_id', $product->id)
                                                                ->where('orders.status', '!=', 'Đã hủy')
                                                                ->exists();

                                                            $existingReview = DB::table('feedback')
                                                                ->where('user_id', auth()->id())
                                                                ->where('product_id', $product->id)
                                                                ->first();

                                                            $hasReceivedOrder = DB::table('orders')
                                                                ->join('orderdetails', 'orders.id', '=', 'orderdetails.order_id')
                                                                ->where('orders.user_id', auth()->id())
                                                                ->where('orderdetails.product_id', $product->id)
                                                                ->where('orders.status', 'Đã nhận hàng')
                                                                ->exists();
                                                        @endphp

                                                        @if($hasPurchased && $hasReceivedOrder)
                                                            <button type="button" class="btn btn-navy" data-toggle="modal" data-target="#reviewModal">
                                                                <i class="fas fa-star mr-2"></i>
                                                                {{ $existingReview ? 'Sửa đánh giá' : 'Viết đánh giá' }}
                                                            </button>
                                                        @endif
                                                    @else
                                                        <a href="{{ route('login') }}" class="btn btn-outline-navy">
                                                            <i class="fas fa-sign-in-alt mr-2"></i>Đăng nhập để đánh giá
                                                        </a>
                                                    @endauth
                                                </div>

                                                <!-- Reviews List -->
                                                <div class="reviews-list">
                                                    @if(isset($feedbacks) && count($feedbacks) > 0)
                                                        @foreach($feedbacks as $feedback)
                                                            <div class="review-item">
                                                                <div class="review-header">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="user-avatar">
                                                                            {{ substr($feedback->user->name ?? 'U', 0, 1) }}
                                                                        </div>
                                                                        <div class="user-info">
                                                                            <h6 class="user-name">{{ $feedback->user->name ?? 'Người dùng ẩn danh' }}</h6>
                                                                            <div class="review-meta">
                                                                                <div class="rating-stars">
                                                                                    @for($i = 1; $i <= 5; $i++)
                                                                                        @if($i <= $feedback->rating)
                                                                                            <i class="fas fa-star text-warning"></i>
                                                                                        @else
                                                                                            <i class="far fa-star text-muted"></i>
                                                                                        @endif
                                                                                    @endfor
                                                                                </div>
                                                                                <span class="review-date">{{ $feedback->created_at ? $feedback->created_at->diffForHumans() : '' }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="review-content">
                                                                    <p>{{ $feedback->comment }}</p>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <div class="no-reviews">
                                                            <div class="text-center py-5">
                                                                <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                                                                <h5 class="text-muted">Chưa có đánh giá nào</h5>
                                                                <p class="text-muted">Hãy là người đầu tiên đánh giá sản phẩm này!</p>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Review Modal (giữ nguyên logic cũ) -->
    @auth
        @if(isset($hasPurchased) && $hasPurchased)
            <div class="modal fade" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="reviewModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-navy text-white">
                            <h5 class="modal-title" id="reviewModalLabel">
                                <i class="fas fa-star mr-2"></i>
                                {{ isset($existingReview) && $existingReview ? 'Sửa đánh giá sản phẩm' : 'Đánh giá sản phẩm' }}
                            </h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @if(isset($existingReview) && $existingReview)
                                <div class="alert alert-info mb-3">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <strong>Đánh giá hiện tại của bạn:</strong><br>
                                    <div class="text-warning mb-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $existingReview->rating)
                                                <i class="fas fa-star"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <p class="mb-0">"{{ $existingReview->comment }}"</p>
                                    <small class="text-muted">Đánh giá mới sẽ thay thế đánh giá này.</small>
                                </div>
                            @endif

                            <div class="d-flex align-items-center mb-4 p-3 bg-light rounded">
                                <img src="{{ asset('image/' . $product->image) }}" alt="{{ $product->name }}" class="rounded mr-3" style="width: 80px; height: 80px; object-fit: cover;">
                                <div>
                                    <h6 class="mb-1 text-navy font-weight-bold">{{ $product->name }}</h6>
                                    <p class="mb-0 text-muted">{{ $product->category_name }}</p>
                                </div>
                            </div>

                            <form action="{{ route('product.review', $product->id) }}" method="POST" id="reviewForm">
                                @csrf
                                <div class="mb-4">
                                    <label class="form-label font-weight-bold text-navy">
                                        <i class="fas fa-star mr-2"></i>Đánh giá của bạn
                                    </label>
                                    <div class="rating-stars d-flex align-items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <label class="star-label mr-1" for="rating_{{ $i }}">
                                                <input type="radio" name="rating" value="{{ $i }}" id="rating_{{ $i }}"
                                                       {{ (isset($existingReview) && $existingReview && $existingReview->rating == $i) ? 'checked' : '' }} required>
                                                <i class="fas fa-star star-icon" data-rating="{{ $i }}"></i>
                                            </label>
                                        @endfor
                                        <span class="ml-3 text-muted" id="ratingText">
                                            {{ isset($existingReview) && $existingReview ? 'Đánh giá hiện tại: ' . $existingReview->rating . ' sao' : 'Chọn số sao' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="comment" class="form-label font-weight-bold text-navy">
                                        <i class="fas fa-comment mr-2"></i>Nhận xét của bạn
                                    </label>
                                    <textarea name="comment" id="comment" class="form-control" rows="4"
                                              placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này...">{{ isset($existingReview) && $existingReview ? $existingReview->comment : '' }}</textarea>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">
                                        <i class="fas fa-times mr-2"></i>Hủy
                                    </button>
                                    <button type="submit" class="btn btn-navy">
                                        <i class="fas fa-paper-plane mr-2"></i>
                                        {{ isset($existingReview) && $existingReview ? 'Cập nhật đánh giá' : 'Gửi đánh giá' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endauth

    <style>
        /* Enhanced Color Scheme */
        :root {
            --navy: #1a4b8c;
            --navy-light: #e8f1ff;
            --navy-dark: #0d3b76;
            --accent: #f8a51b;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
            --light: #f8f9fa;
            --gray-100: #f8f9fa;
            --gray-200: #e9ecef;
            --gray-300: #dee2e6;
            --gray-600: #6c757d;
            --gray-900: #212529;
        }

        /* Global Styles */
        .text-navy { color: var(--navy) !important; }
        .bg-navy { background-color: var(--navy) !important; color: white !important; }
        .bg-gradient-light { background: linear-gradient(135deg, var(--navy-light) 0%, #f8f9fa 100%); }
        .border-navy { border-color: var(--navy) !important; }

        /* Enhanced Breadcrumb */
        .breadcrumb {
            padding: 0.75rem 0;
            margin-bottom: 0;
        }
        .breadcrumb-item + .breadcrumb-item::before {
            content: "›";
            font-size: 1.1rem;
            color: var(--gray-600);
        }

        /* Product Gallery */
        .product-gallery-wrapper {
            position: sticky;
            top: 20px;
        }
        .image-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        .image-container:hover {
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }
        .image-container img {
            width: 100%;
            height: 400px;
            object-fit: contain;
            border-radius: 8px;
        }
        .thumb-container {
            background: white;
            border-radius: 8px;
            padding: 10px;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        .thumb-container:hover,
        .thumb-container.slick-current {
            border-color: var(--navy);
        }
        .thumb-container img {
            width: 100%;
            height: 60px;
            object-fit: contain;
        }

        /* Product Info */
        .product-info {
            padding: 20px;
        }
        .product-header {
            border-bottom: 1px solid var(--gray-200);
            padding-bottom: 1.5rem;
        }
        .product-title {
            font-size: 1.75rem;
            font-weight: 700;
            line-height: 1.3;
            color: var(--gray-900);
        }

        /* Badges */
        .badge-navy {
            background-color: var(--navy);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 500;
        }
        .badge-outline-navy {
            border: 1px solid var(--navy);
            color: var(--navy);
            background: transparent;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 500;
        }

        /* Rating */
        .rating-section .stars {
            font-size: 1.1rem;
        }
        .rating-text {
            font-weight: 600;
            color: var(--gray-900);
            font-size: 1.1rem;
        }

        /* Price */
        .price-section {
            background: linear-gradient(135deg, var(--navy-light) 0%, #f8f9fa 100%);
            border-radius: 12px;
            padding: 1.5rem;
        }
        .price-label {
            font-size: 0.9rem;
            color: var(--gray-600);
            display: block;
            margin-bottom: 0.5rem;
        }
        .price-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--navy);
        }

        /* Info Card */
        .info-card {
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            padding: 1.5rem;
        }
        .info-item {
            display: flex;
            flex-direction: column;
        }
        .info-label {
            font-size: 0.85rem;
            color: var(--gray-600);
            margin-bottom: 0.25rem;
        }
        .info-value {
            font-weight: 600;
            color: var(--gray-900);
        }

        /* Quantity Input */
        .quantity-input {
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            overflow: hidden;
        }
        .quantity-btn {
            background: var(--gray-100);
            border: none;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        .quantity-btn:hover {
            background: var(--navy-light);
            color: var(--navy);
        }
        .quantity-field {
            border: none;
            text-align: center;
            width: 60px;
            height: 40px;
            font-weight: 600;
        }

        /* Buttons */
        .btn-navy {
            background-color: var(--navy);
            border-color: var(--navy);
            color: white;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .btn-navy:hover {
            background-color: var(--navy-dark);
            border-color: var(--navy-dark);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(26, 75, 140, 0.3);
        }
        .btn-outline-navy {
            border-color: var(--navy);
            color: var(--navy);
            background: transparent;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .btn-outline-navy:hover {
            background-color: var(--navy-light);
            border-color: var(--navy);
            color: var(--navy);
        }

        /* Custom Tabs */
        .custom-tabs {
            border-bottom: 2px solid var(--gray-200);
        }
        .custom-tabs .nav-link {
            border: none;
            border-bottom: 3px solid transparent;
            color: var(--gray-600);
            font-weight: 500;
            padding: 1rem 1.5rem;
            transition: all 0.3s ease;
        }
        .custom-tabs .nav-link:hover {
            color: var(--navy);
            background: var(--navy-light);
        }
        .custom-tabs .nav-link.active {
            color: var(--navy);
            border-bottom-color: var(--navy);
            background: transparent;
        }

        /* Content Cards */
        .content-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .content-card .card-body {
            padding: 2rem;
        }

        /* Specifications */
        .specifications-table {
            display: grid;
            gap: 1rem;
        }
        .spec-row {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 1rem;
            padding: 1rem;
            background: var(--gray-100);
            border-radius: 8px;
            align-items: center;
        }
        .spec-label {
            font-weight: 600;
            color: var(--navy);
        }
        .spec-value {
            color: var(--gray-900);
        }

        /* Reviews */
        .review-summary-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            text-align: center;
        }
        .rating-score {
            font-size: 3rem;
            font-weight: 700;
            color: var(--navy);
            line-height: 1;
        }
        .rating-breakdown {
            margin-top: 1.5rem;
        }
        .rating-bar {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.5rem;
        }
        .rating-label {
            font-size: 0.85rem;
            color: var(--gray-600);
            min-width: 40px;
        }
        .rating-count {
            font-size: 0.85rem;
            color: var(--gray-600);
            min-width: 20px;
        }
        .progress {
            height: 6px;
            background: var(--gray-200);
            border-radius: 3px;
        }

        /* Review Items */
        .review-item {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            transition: all 0.3s ease;
        }
        .review-item:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        }
        .user-avatar {
            width: 48px;
            height: 48px;
            background: var(--navy);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 1rem;
        }
        .user-name {
            margin-bottom: 0.25rem;
            font-weight: 600;
            color: var(--gray-900);
        }
        .review-meta {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .review-date {
            font-size: 0.85rem;
            color: var(--gray-600);
        }
        .review-content {
            margin-top: 1rem;
            color: var(--gray-700);
            line-height: 1.6;
        }

        /* Modal Enhancements */
        .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        }
        .modal-header {
            border-radius: 12px 12px 0 0;
            border-bottom: none;
        }

        /* Star Rating in Modal */
        .star-label {
            cursor: pointer;
            margin: 0;
        }
        .star-label input[type="radio"] {
            display: none;
        }
        .star-icon {
            font-size: 1.5rem;
            color: var(--gray-300);
            transition: color 0.2s ease;
        }
        .star-label:hover .star-icon,
        .star-label input[type="radio"]:checked ~ .star-icon,
        .star-icon.active {
            color: var(--warning);
        }

        /* Responsive Design */
        @media (max-width: 991.98px) {
            .product-gallery-wrapper {
                position: static;
                margin-bottom: 2rem;
            }
            .image-container img {
                height: 300px;
            }
        }

        @media (max-width: 767.98px) {
            .product-title {
                font-size: 1.5rem;
            }
            .price-value {
                font-size: 1.75rem;
            }
            .spec-row {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }
            .rating-bar {
                flex-direction: column;
                align-items: stretch;
                gap: 0.25rem;
            }
        }
    </style>

    <script>
        $(function () {
            // Giữ nguyên tất cả JavaScript logic cũ
            $('#descriptionToggle').click(function() {
                var $content = $('#descriptionContent');
                var $button = $(this);

                if ($content.hasClass('show')) {
                    $button.html('<i class="fas fa-chevron-down mr-1"></i> Xem thêm');
                } else {
                    $button.html('<i class="fas fa-chevron-up mr-1"></i> Thu gọn');
                }
            });

            $('.star-label').on('click', function() {
                var rating = $(this).find('input').val();
                var ratingTexts = {
                    1: 'Rất tệ',
                    2: 'Tệ',
                    3: 'Bình thường',
                    4: 'Tốt',
                    5: 'Rất tốt'
                };

                $('#ratingText').text(ratingTexts[rating] + ' (' + rating + ' sao)');

                $('.star-icon').removeClass('active');
                for(var i = 1; i <= rating; i++) {
                    $('[data-rating="' + i + '"]').addClass('active');
                }
            });

            $('.star-label').on('mouseenter', function() {
                var rating = $(this).find('input').val();
                $('.star-icon').removeClass('hover');
                for(var i = 1; i <= rating; i++) {
                    $('[data-rating="' + i + '"]').addClass('hover');
                }
            });

            $('.rating-stars').on('mouseleave', function() {
                $('.star-icon').removeClass('hover');
            });

            $('#reviewForm').on('submit', function(e) {
                e.preventDefault();

                var formData = $(this).serialize();
                var submitBtn = $(this).find('button[type="submit"]');
                var originalText = submitBtn.html();

                submitBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Đang gửi...').prop('disabled', true);

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        if(response.success) {
                            $('#reviewModal').modal('hide');

                            $('body').prepend(`
                                <div class="alert alert-success alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999;">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    ${response.message}
                                    <button type="button" class="close" data-dismiss="alert">
                                        <span>&times;</span>
                                    </button>
                                </div>
                            `);

                            setTimeout(function() {
                                location.reload();
                            }, 2000);
                        }
                    },
                    error: function(xhr) {
                        var errorMessage = 'Có lỗi xảy ra khi gửi đánh giá!';
                        if(xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        $('body').prepend(`
                            <div class="alert alert-danger alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999;">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                ${errorMessage}
                                <button type="button" class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                            </div>
                        `);
                    },
                    complete: function() {
                        submitBtn.html(originalText).prop('disabled', false);
                    }
                });
            });

            if (typeof $.fn.slick !== 'undefined') {
                $('#sliderSyncingNav').slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: true,
                    fade: true,
                    asNavFor: '#sliderSyncingThumb'
                });

                $('#sliderSyncingThumb').slick({
                    slidesToShow: 5,
                    slidesToScroll: 1,
                    asNavFor: '#sliderSyncingNav',
                    dots: false,
                    centerMode: false,
                    focusOnSelect: true,
                    responsive: [
                        {
                            breakpoint: 992,
                            settings: {
                                slidesToShow: 4
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 3
                            }
                        }
                    ]
                });
            }

            // Bootstrap 5 tabs compatibility
            if (typeof bootstrap !== 'undefined') {
                var triggerTabList = [].slice.call(document.querySelectorAll('#productTabs button'))
                triggerTabList.forEach(function (triggerEl) {
                    var tabTrigger = new bootstrap.Tab(triggerEl)
                    triggerEl.addEventListener('click', function (event) {
                        event.preventDefault()
                        tabTrigger.show()
                    })
                })
            }
        });

        $(document).ready(function() {
            const urlParams = new URLSearchParams(window.location.search);
            const reviewParam = urlParams.get('review');

            if (reviewParam === 'new' || reviewParam === 'edit') {
                @auth
                @if(isset($hasPurchased) && $hasPurchased)
                setTimeout(function() {
                    $('#reviewModal').modal('show');
                }, 500);
                @else
                $('body').prepend(`
                    <div class="alert alert-warning alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999;">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Bạn cần mua sản phẩm này trước khi có thể đánh giá!
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>
                `);
                @endif
                @else
                $('body').prepend(`
                    <div class="alert alert-info alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999;">
                        <i class="fas fa-info-circle mr-2"></i>
                        Vui lòng đăng nhập để đánh giá sản phẩm!
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>
                `);
                @endauth
            }

            setTimeout(function() {
                $('.alert.position-fixed').fadeOut();
            }, 5000);
        });
    </script>
@endsection
