@extends('layout.customerApp')

@section('content')
    <div class="search-results-page">
        <!-- Hero Section -->
        <div class="hero-section">
            <div class="container-wide">
                <div class="hero-content">
                    <div class="hero-text">
                        <h1 class="hero-title">
                            <i class="fas fa-search"></i>
                            Kết Quả Tìm Kiếm
                        </h1>
                        <p class="hero-subtitle">
                            @if($query)
                                Tìm thấy {{ $products->total() }} sản phẩm cho "{{ $query }}"
                            @else
                                Tất cả sản phẩm ({{ $products->total() }} sản phẩm)
                            @endif
                        </p>
                    </div>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-box"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-number">{{ $products->total() }}</span>
                                <span class="stat-label">Sản phẩm</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="container-wide">
            <div class="search-layout">
                <!-- Filters Panel -->
                <div class="filters-panel">
                    <div class="panel-header">
                        <div class="panel-title">
                            <i class="fas fa-filter"></i>
                            <h2>Bộ Lọc Sản Phẩm</h2>
                        </div>
                    </div>

                    <form id="filterForm" method="GET" action="{{ route('customer.search') }}">
                        @if($query)
                            <input type="hidden" name="query" value="{{ $query }}">
                        @endif

                        <!-- Search Input -->
                        <div class="filter-section">
                            <div class="filter-header">
                                <h3>Tìm Kiếm</h3>
                            </div>
                            <div class="filter-content">
                                <input type="text"
                                       name="query"
                                       value="{{ $query }}"
                                       placeholder="Nhập từ khóa tìm kiếm..."
                                       class="search-input">
                            </div>
                        </div>

                        <!-- Brand Filter with Tom Select -->
                        <div class="filter-section">
                            <div class="filter-header">
                                <h3>Thương Hiệu</h3>
                            </div>
                            <div class="filter-content">
                                <select id="brandSelect" name="brand" class="tom-select">
                                    <option value="">Tất cả thương hiệu</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}"
                                            {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Category Filter with Tom Select -->
                        <div class="filter-section">
                            <div class="filter-header">
                                <h3>Danh Mục</h3>
                            </div>
                            <div class="filter-content">
                                <select id="categorySelect" name="category" class="tom-select">
                                    <option value="">Tất cả danh mục</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Price Filter -->
                        <div class="filter-section">
                            <div class="filter-header">
                                <h3>Khoảng Giá</h3>
                            </div>
                            <div class="filter-content">
                                <select name="price" class="filter-select">
                                    <option value="">Tất cả mức giá</option>
                                    <option value="0-5000000" {{ request('price') == '0-5000000' ? 'selected' : '' }}>
                                        Dưới 5 triệu
                                    </option>
                                    <option value="5000000-10000000" {{ request('price') == '5000000-10000000' ? 'selected' : '' }}>
                                        5-10 triệu
                                    </option>
                                    <option value="10000000-20000000" {{ request('price') == '10000000-20000000' ? 'selected' : '' }}>
                                        10-20 triệu
                                    </option>
                                    <option value="20000000-50000000" {{ request('price') == '20000000-50000000' ? 'selected' : '' }}>
                                        20-50 triệu
                                    </option>
                                    <option value="50000000-999999999" {{ request('price') == '50000000-999999999' ? 'selected' : '' }}>
                                        Trên 50 triệu
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Discount Filter -->
                        <div class="filter-section">
                            <div class="filter-header">
                                <h3>Mức Giảm Giá</h3>
                            </div>
                            <div class="filter-content">
                                <select name="discount" class="filter-select">
                                    <option value="">Tất cả sản phẩm</option>
                                    <option value="has_discount" {{ request('discount') == 'has_discount' ? 'selected' : '' }}>
                                        Có giảm giá
                                    </option>
                                    <option value="10_plus" {{ request('discount') == '10_plus' ? 'selected' : '' }}>
                                        Giảm từ 10%
                                    </option>
                                    <option value="20_plus" {{ request('discount') == '20_plus' ? 'selected' : '' }}>
                                        Giảm từ 20%
                                    </option>
                                    <option value="30_plus" {{ request('discount') == '30_plus' ? 'selected' : '' }}>
                                        Giảm từ 30%
                                    </option>
                                    <option value="50_plus" {{ request('discount') == '50_plus' ? 'selected' : '' }}>
                                        Giảm từ 50%
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Sort Filter -->
                        <div class="filter-section">
                            <div class="filter-header">
                                <h3>Sắp Xếp</h3>
                            </div>
                            <div class="filter-content">
                                <select name="sort" class="filter-select">
                                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>
                                        Tên A-Z
                                    </option>
                                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>
                                        Tên Z-A
                                    </option>
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                                        Giá tăng dần
                                    </option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                                        Giá giảm dần
                                    </option>
                                    <option value="discount_desc" {{ request('sort') == 'discount_desc' ? 'selected' : '' }}>
                                        Giảm giá nhiều nhất
                                    </option>
                                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>
                                        Mới nhất
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="filter-actions">
                            <button type="submit" class="btn-apply-filters">
                                <i class="fas fa-search"></i>
                                Tìm Kiếm
                            </button>
                            <button type="button" class="btn-clear-filters" onclick="clearFilters()">
                                <i class="fas fa-times"></i>
                                Xóa Bộ Lọc
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Products Panel -->
                <div class="products-panel">
                    @if($products->isEmpty())
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <h2>Không tìm thấy sản phẩm nào</h2>
                            <p>Vui lòng thử lại với từ khóa khác hoặc điều chỉnh bộ lọc</p>
                            <a href="{{ route('customer.main-home') }}" class="btn-primary">
                                <i class="fas fa-home"></i>
                                Quay lại trang chủ 1
                            </a>
                        </div>
                    @else
                        <!-- Products Grid - 5 columns like main-home -->
                        <div class="products-grid">
                            @foreach($products as $product)
                                @php
                                    $originalPrice = $product->price_out;
                                    $hasDiscount = $product->discount_percent && $product->discount_percent > 0;
                                    $finalPrice = $hasDiscount
                                        ? $originalPrice * (1 - $product->discount_percent / 100)
                                        : $originalPrice;
                                @endphp

                                <div class="product-card">
                                    <div class="product-image">
                                        <a href="{{ route('customer.view-detail', $product->id) }}">
                                            <img src="{{ $product->image ? '/image/' . $product->image : '/images/no-image.png' }}"
                                                 alt="{{ $product->name }}"
                                                 onerror="this.src='/images/no-image.png'">
                                        </a>
                                    </div>

                                    <div class="product-info">
                                        <div class="rating-code">
                                            <div class="rating">
                                                <i class="fas fa-star star"></i>
                                                <i class="fas fa-star star"></i>
                                                <i class="fas fa-star star"></i>
                                                <i class="fas fa-star star"></i>
                                                <i class="fas fa-star star"></i>
                                                <span class="review-count">(0)</span>
                                            </div>
                                            <div class="product-code">MÃ: {{ $product->product_code ?? 'N/A' }}</div>
                                        </div>

                                        <div class="product-name">{{ $product->name }}</div>

                                        <div class="price-section">
                                            @if ($hasDiscount)
                                                <div class="old-price-discount">
                            <span class="old-price" style="text-decoration: line-through;">
                                {{ number_format($originalPrice, 0, ',', '.') }} VNĐ
                            </span>
                                                    <span class="discount-info">-{{ $product->discount_percent }}%</span>
                                                </div>
                                            @endif
                                            <div class="current-price">
                                                {{ number_format($finalPrice, 0, ',', '.') }} VNĐ
                                            </div>
                                        </div>

                                        <div class="bottom-section">
                                            @if($product->quantity > 0)
                                                <div class="stock-status in-stock">
                                                    <i class="fas fa-check"></i> Sẵn hàng
                                                </div>

                                                <button class="add-to-cart" data-product-id="{{ $product->id }}" data-quantity="1">
                                                    <i class="fas fa-shopping-cart"></i>
                                                </button>

                                            @else
                                                <div class="stock-status out-of-stock">
                                                    <i class="fas fa-times"></i> Hết hàng
                                                </div>
                                                <button class="add-to-cart" disabled>
                                                    <i class="fas fa-shopping-cart"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>


                        <!-- Pagination -->
                        <div class="pagination-container">
                            {{ $products->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Tom Select CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">

    <style>
        :root {
            --primary: #1e293b;
            --primary-dark: #0f172a;
            --primary-light: #64748b;
            --success: #059669;
            --warning: #d97706;
            --danger: #dc2626;
            --info: #0891b2;
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .search-results-page {
            min-height: 100vh;
            background: #f1f5f9;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .container-wide {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            color: white;
            padding: 2rem 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }

        .hero-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .hero-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .hero-subtitle {
            font-size: 1.125rem;
            opacity: 0.9;
            font-weight: 400;
        }

        .hero-stats {
            background: rgba(255, 255, 255, 0.1);
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stat-icon {
            width: 3rem;
            height: 3rem;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .stat-content {
            display: flex;
            flex-direction: column;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 900;
            line-height: 1;
            color: #fbbf24;
        }

        .stat-label {
            font-size: 0.875rem;
            opacity: 0.8;
            margin-top: 0.25rem;
        }

        /* Search Layout */
        .search-layout {
            display: grid;
            grid-template-columns: 250px 1fr;
            gap: 2rem;
            margin-top: 2rem;
            margin-bottom: 2rem;
        }

        /* Filters Panel */
        .filters-panel {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-lg);
            height: fit-content;
            position: sticky;
            top: 2rem;
            border: 1px solid var(--gray-200);
        }

        .panel-header {
            background: var(--primary-dark);
            color: white;
            padding: 1.5rem;
            border-radius: 12px 12px 0 0;
        }

        .panel-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .panel-title h2 {
            font-size: 1.25rem;
            font-weight: 700;
        }

        /* Filter Sections */
        .filter-section {
            border-bottom: 1px solid var(--gray-200);
            padding: 1.5rem;
        }

        .filter-section:last-child {
            border-bottom: none;
        }

        .filter-header h3 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 1rem;
        }

        .filter-content {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .search-input,
        .filter-select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--gray-300);
            border-radius: 6px;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .search-input:focus,
        .filter-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(30, 41, 59, 0.1);
        }

        /* Tom Select Styling */
        .tom-select .ts-control {
            border: 1px solid var(--gray-300);
            border-radius: 6px;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            min-height: 42px;
        }

        .tom-select .ts-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(30, 41, 59, 0.1);
        }

        .tom-select .ts-dropdown {
            border: 1px solid var(--gray-300);
            border-radius: 6px;
            box-shadow: var(--shadow-lg);
        }

        .filter-actions {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .btn-apply-filters,
        .btn-clear-filters {
            width: 100%;
            padding: 0.875rem 1rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-apply-filters {
            background: var(--primary);
            color: white;
        }

        .btn-apply-filters:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-clear-filters {
            background: var(--gray-200);
            color: var(--gray-700);
        }

        .btn-clear-filters:hover {
            background: var(--gray-300);
        }

        /* Products Panel */
        .products-panel {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--gray-200);
            padding: 2rem;
        }

        /* Products Grid - 5 columns like main-home */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }



        /* Pagination */
        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
        }
        /* Hide everything except the pagination buttons */
        .pagination-container nav > div:first-child {
            display: none !important;
        }

        .pagination-container nav > div:last-child > div:first-child {
            display: none !important;
        }

        /* Only show the pagination buttons container */
        .pagination-container nav {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /*.pagination-container .hidden.sm\\:flex-1 {*/
        /*    display: flex !important;*/
        /*    justify-content: center !important;*/
        /*    width: 100% !important;*/
        /*}*/

        /*.pagination-container .hidden.sm\\:flex-1 > div:last-child {*/
        /*    display: flex;*/
        /*    justify-content: center;*/
        /*    width: 100%;*/
        /*}*/

        /* Style the pagination button container */
        .pagination-container span.relative.z-0.inline-flex {
            display: inline-flex !important;
            align-items: center !important;
            gap: 4px !important;
            box-shadow: none !important;
        }

        /* Fix all pagination links and spans */
        .pagination-container a,
        .pagination-container span[aria-current="page"] span,
        .pagination-container span[aria-disabled="true"] span {
            padding: 8px 12px !important;
            font-size: 14px !important;
            min-width: 40px !important;
            height: 40px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            margin: 0 !important;
            border-radius: 6px !important;
            line-height: 1 !important;
        }

        /* Fix arrow buttons */
        .pagination-container a[rel="prev"],
        .pagination-container a[rel="next"],
        .pagination-container span[aria-disabled="true"] span {
            padding: 0 !important;
            min-width: 40px !important;
            width: 40px !important;
            height: 40px !important;
        }

        /* Fix SVG icons */
        .pagination-container svg {
            width: 16px !important;
            height: 16px !important;
        }

        /* Remove negative margins */
        .pagination-container .-ml-px {
            margin-left: 0 !important;
        }

        /* Fix rounded corners */
        .pagination-container .rounded-l-md,
        .pagination-container .rounded-r-md {
            border-radius: 6px !important;
        }

        /* Ensure consistent spacing */
        .pagination-container span.relative.z-0.inline-flex > * {
            margin-left: 0 !important;
            margin-right: 2px !important;
        }

        .pagination-container span.relative.z-0.inline-flex > *:last-child {
            margin-right: 0 !important;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .products-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 1024px) {
            .search-layout {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .filters-panel {
                position: static;
            }

            .products-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .container-wide {
                padding: 0 0.75rem;
            }

            .hero-content {
                flex-direction: column;
                gap: 1.5rem;
                text-align: center;
            }

            .hero-title {
                font-size: 2rem;
            }

            .products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .products-panel {
                padding: 1rem;
            }
        }

        @media (max-width: 480px) {
            .products-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <!-- Tom Select JS -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

    <script>
        // Initialize Tom Select
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize brand select
            new TomSelect('#brandSelect', {
                placeholder: 'Chọn thương hiệu...',
                allowEmptyOption: true,
                searchField: ['text'],
                render: {
                    no_results: function(data, escape) {
                        return '<div class="no-results">Không tìm thấy thương hiệu nào</div>';
                    }
                }
            });

            // Initialize category select
            new TomSelect('#categorySelect', {
                placeholder: 'Chọn danh mục...',
                allowEmptyOption: true,
                searchField: ['text'],
                render: {
                    no_results: function(data, escape) {
                        return '<div class="no-results">Không tìm thấy danh mục nào</div>';
                    }
                }
            });

            // Auto-submit form when filters change
            document.querySelectorAll('select[name="price"], select[name="discount"], select[name="sort"]').forEach(select => {
                select.addEventListener('change', function() {
                    document.getElementById('filterForm').submit();
                });
            });
        });

        // Clear filters
        function clearFilters() {
            const url = new URL(window.location.origin + window.location.pathname);
            window.location.href = url.toString();
        }

        // Search on enter
        document.querySelector('input[name="query"]').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('filterForm').submit();
            }
        });
    </script>
@endsection
