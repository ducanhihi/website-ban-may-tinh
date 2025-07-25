@extends('layout.customerApp')

@section('content')
    <div class="pc-builder-page">
        <!-- Hero Section -->
        <div class="hero-section">
            <div class="container-wide">
                <div class="hero-content">
                    <div class="hero-text">
                        <h1 class="hero-title">
                            <i class="fas fa-desktop"></i>
                            Xây Dựng PC Của Bạn
                        </h1>
                        <p class="hero-subtitle">Tạo cấu hình PC hoàn hảo với công cụ tương thích thông minh</p>
                    </div>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-calculator"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-number" id="totalPrice">0</span>
                                <span class="stat-label">VNĐ</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="container-wide">
            <div class="builder-layout">
                <!-- Left Panel - Component Selection -->
                <div class="components-panel">
                    <div class="panel-header">
                        <div class="panel-title">
                            <i class="fas fa-cogs"></i>
                            <h2>Chọn Linh Kiện</h2>
                        </div>

                    </div>

                    <!-- Component Categories -->
                    <div class="component-categories" id="componentCategories">
                        @foreach($categories as $category)
                            <div class="component-category" data-category="{{ $category->name }}">
                                <div class="category-header">
                                    <div class="category-info">
                                        <div class="category-icon">
                                            <i class="fas fa-{{ $category->name == 'CPU' ? 'microchip' : ($category->name == 'GPU' ? 'tv' : ($category->name == 'RAM' ? 'memory' : ($category->name == 'Motherboard' ? 'memory' : ($category->name == 'SSD' ? 'hdd' : ($category->name == 'HDD' ? 'hdd' : ($category->name == 'PSU' ? 'plug' : 'cube')))))) }}"></i>
                                        </div>
                                        <div class="category-details">
                                            <h3>{{ $category->name }}</h3>
                                            <p class="category-desc">Chọn {{ strtolower($category->name) }} phù hợp</p>
                                        </div>
                                    </div>
                                    <div class="category-status">
                                        <span class="price" id="{{ $category->name }}-price">Chưa chọn</span>
                                        <button class="select-btn" onclick="openComponentModal('{{ $category->id }}', '{{ $category->name }}')">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="selected-components" id="{{ $category->name }}-selected">
                                    <!-- Selected components will be displayed here -->
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Right Panel - Summary & Actions -->
                <div class="summary-panel">
                    <div class="panel-header">
                        <div class="panel-title">
                            <i class="fas fa-clipboard-list"></i>
                            <h2>Tóm Tắt Cấu Hình</h2>
                        </div>
                    </div>

                    <!-- Build Summary -->
                    <div class="build-summary">
                        <div class="summary-card">
                            <div class="summary-item">
                                <div class="summary-icon">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <div class="summary-content">
                                    <span class="label">Tổng giá trị</span>
                                    <span class="value" id="summaryTotal">0 VNĐ</span>
                                </div>
                            </div>
                            <div class="summary-item">
                                <div class="summary-icon">
                                    <i class="fas fa-cubes"></i>
                                </div>
                                <div class="summary-content">
                                    <span class="label">Số linh kiện</span>
                                    <span class="value" id="componentCount">0</span>
                                </div>
                            </div>
                            <div class="summary-item">
                                <div class="summary-icon">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div class="summary-content">
                                    <span class="label">Trạng thái</span>
                                    <span class="value compatible" id="compatibilityText">
                                        <i class="fas fa-check-circle"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="build-actions">
                        <button class="btn-action btn-save" onclick="saveBuild()">
                            <i class="fas fa-save"></i>
                            <span>Lưu Cấu Hình</span>
                        </button>
                        <button class="btn-action btn-cart" onclick="addToCart()">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Thêm Vào Giỏ</span>
                        </button>
                        <button class="btn-action btn-clear" onclick="clearBuild()">
                            <i class="fas fa-trash"></i>
                            <span>Xóa Tất Cả</span>
                        </button>
                        @auth
                            <a href="{{ route('pc-builder.my-builds') }}" class="btn-action btn-builds">
                                <i class="fas fa-list"></i>
                                <span>Cấu Hình Của Tôi</span>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Component Selection Modal -->
    <div class="modal-overlay" id="componentModal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Chọn Linh Kiện</h2>
                <button class="modal-close" onclick="closeComponentModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="component-filters">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label>Tìm kiếm:</label>
                            <input type="text" id="searchInput" placeholder="Nhập tên sản phẩm...">
                        </div>
                        <div class="filter-group">
                            <label>Hãng:</label>
                            <select id="brandFilter">
                                <option value="">Tất cả</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group">
                            <label>Giá:</label>
                            <select id="priceFilter">
                                <option value="">Tất cả</option>
                                <option value="0-5000000">Dưới 5 triệu</option>
                                <option value="5000000-10000000">5-10 triệu</option>
                                <option value="10000000-20000000">10-20 triệu</option>
                                <option value="20000000-999999999">Trên 20 triệu</option>
                            </select>
                        </div>
                        <button class="btn-filter" onclick="filterProducts()">
                            <i class="fas fa-search"></i>
                            Lọc
                        </button>
                    </div>
                </div>

                <div class="spec-filters" id="specFilters" style="display: none;">
                    <h4>Lọc theo thông số kỹ thuật:</h4>
                    <div class="spec-filters-grid" id="specFiltersGrid">
                        <!-- Dynamic spec filters will be loaded here -->
                    </div>
                </div>

                <div class="component-list" id="componentList">
                    <!-- Products will be loaded here -->
                </div>

                <div class="pagination-container" id="paginationContainer">
                    <!-- Pagination will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Save Build Modal -->
    <div class="modal-overlay" id="saveBuildModal" style="display: none;">
        <div class="modal-content small">
            <div class="modal-header">
                <h2>Lưu Cấu Hình</h2>
                <button class="modal-close" onclick="closeSaveBuildModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="saveBuildForm">
                    <div class="form-group">
                        <label for="buildName">Tên cấu hình:</label>
                        <input type="text" id="buildName" name="name" required placeholder="Nhập tên cấu hình...">
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn-cancel" onclick="closeSaveBuildModal()">Hủy</button>
                        <button type="submit" class="btn-save">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Custom Confirm Delete Modal -->
    <div class="confirm-modal-overlay" id="confirmDeleteModal" style="display: none;">
        <div class="confirm-modal-content">
            <div class="confirm-modal-header">
                <h3 id="confirmModalTitle">127.0.0.1:8000 cho biết</h3>
            </div>
            <div class="confirm-modal-body">
                <p id="confirmModalMessage">Bạn có chắc chắn muốn xóa linh kiện này?</p>
            </div>
            <div class="confirm-modal-actions">
                <button class="btn-confirm-cancel" onclick="closeConfirmModal()">Hủy</button>
                <button class="btn-confirm-ok" onclick="confirmDeleteAction()">OK</button>
            </div>
        </div>
    </div>

    <!-- Tooltip for product details -->
    <div id="productTooltip" class="product-tooltip" style="display: none;">
        <div class="tooltip-content">
            <h4 class="tooltip-title">Thông số kỹ thuật</h4>
            <div class="tooltip-specs" id="tooltipSpecs">
                <!-- Specs will be loaded here -->
            </div>
        </div>
    </div>

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

        .pc-builder-page {
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
            background: #0f172a;
            color: white;
            padding: 2rem 0;
            position: relative;
            overflow: hidden;
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
            border: 1px solid rgba(255, 255, 255, 0.2);
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

        /* Builder Layout */
        .builder-layout {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 2rem;
            margin-top: 2rem;
            margin-bottom: 2rem;
        }

        /* Components Panel */
        .components-panel {
            background: white;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            border: 1px solid var(--gray-200);
        }

        .panel-header {
            background: var(--primary-dark);
            color: white;
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        .compatibility-status {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            background: rgba(255, 255, 255, 0.1);
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }

        .compatibility-status.incompatible {
            background: rgba(239, 68, 68, 0.2);
            color: #fca5a5;
        }

        /* Component Categories */
        .component-categories {
            padding: 1.5rem;
        }

        .component-category {
            border: 2px solid var(--gray-200);
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .component-category:hover {
            border-color: var(--gray-400);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .component-category.has-selection {
            border-color: var(--success);
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        }

        .category-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem;
        }

        .category-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .category-icon {
            width: 3rem;
            height: 3rem;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .category-details h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 0.25rem;
        }

        .category-desc {
            font-size: 0.875rem;
            color: var(--gray-500);
        }

        .category-status {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .price {
            font-weight: 600;
            color: var(--gray-700);
            min-width: 120px;
            text-align: right;
            font-size: 0.875rem;
        }

        .select-btn {
            background: var(--primary);
            color: white;
            border: none;
            width: 2.5rem;
            height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
        }

        .select-btn:hover {
            background: var(--primary-dark);
            transform: scale(1.1);
            box-shadow: var(--shadow-md);
        }

        /* Selected Components */
        .selected-components {
            display: none;
            background: var(--gray-50);
            border-top: 1px solid var(--gray-200);
            padding: 1rem;
            animation: slideDown 0.3s ease;
        }

        .selected-components.show {
            display: block;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .selected-component {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: white;
            margin-bottom: 0.75rem;
            border: 1px solid var(--gray-200);
            transition: all 0.3s ease;
        }

        .selected-component:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .selected-component:last-child {
            margin-bottom: 0;
        }

        .component-image {
            width: 80px;
            height: 80px;
            object-fit: contain;
            border: 1px solid var(--gray-200);
            background: var(--gray-100);
            padding: 0.25rem;
        }

        .component-details {
            flex: 1;
        }

        .component-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 0.25rem;
            line-height: 1.4;
        }

        .component-price {
            font-size: 0.875rem;
            color: var(--success);
            font-weight: 600;
        }

        .remove-btn {
            background: var(--danger);
            color: white;
            border: none;
            width: 2rem;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .remove-btn:hover {
            background: #b91c1c;
            transform: scale(1.1);
        }

        /* Summary Panel */
        .summary-panel {
            background: white;
            box-shadow: var(--shadow-lg);
            height: fit-content;
            position: sticky;
            top: 2rem;
            border: 1px solid var(--gray-200);
        }

        .build-summary {
            padding: 1.5rem;
        }

        .summary-card {
            background: var(--gray-50);
            padding: 1.5rem;
            border: 1px solid var(--gray-200);
        }

        .summary-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .summary-item:last-child {
            margin-bottom: 0;
        }

        .summary-icon {
            width: 2.5rem;
            height: 2.5rem;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .summary-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .summary-content .label {
            font-size: 0.875rem;
            color: var(--gray-600);
            margin-bottom: 0.25rem;
        }

        .summary-content .value {
            font-weight: 700;
            color: var(--gray-800);
            font-size: 1rem;
        }

        .summary-content .value.compatible {
            color: var(--success);
        }

        .summary-content .value.incompatible {
            color: var(--danger);
        }

        /* Build Actions */
        .build-actions {
            padding: 1.5rem;
            border-top: 1px solid var(--gray-200);
        }

        .btn-action {
            width: 100%;
            padding: 0.875rem 1rem;
            border: none;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
            text-decoration: none;
            box-shadow: var(--shadow-sm);
        }

        .btn-action:last-child {
            margin-bottom: 0;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-save {
            background: var(--primary);
            color: white;
        }

        .btn-cart {
            background: var(--success);
            color: white;
        }

        .btn-clear {
            background: var(--danger);
            color: white;
        }

        .btn-builds {
            background: var(--info);
            color: white;
        }

        /* Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            backdrop-filter: blur(4px);
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            background: white;
            box-shadow: var(--shadow-xl);
            width: 90%;
            max-width: 1200px;
            max-height: 90vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-content.small {
            max-width: 500px;
        }

        .modal-header {
            background: var(--primary-dark);
            color: white;
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-close {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
            transition: all 0.3s ease;
        }

        .modal-close:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.1);
        }

        .modal-body {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
        }

        .component-filters {
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--gray-200);
        }

        .filter-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto;
            gap: 1rem;
            align-items: end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .filter-group label {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--gray-700);
        }

        .filter-group input,
        .filter-group select {
            padding: 0.75rem;
            border: 1px solid var(--gray-300);
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .filter-group input:focus,
        .filter-group select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(30, 41, 59, 0.1);
        }

        .btn-filter {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            box-shadow: var(--shadow-sm);
        }

        .btn-filter:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .spec-filters {
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--gray-200);
        }

        .spec-filters h4 {
            margin-bottom: 1rem;
            color: var(--gray-700);
            font-size: 1rem;
            font-weight: 600;
        }

        .spec-filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }

        .component-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            min-height: 300px;
            padding: 1rem 0;
        }

        /* Product Card Styles - Giống như trong ảnh mẫu */
        .component-item {
            background: white;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            padding: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            display: flex;
            flex-direction: column;
            height: 100%;
            box-shadow: var(--shadow-sm);
        }

        .component-item:hover {
            border-color: var(--primary);
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .component-item-image {
            width: 100%;
            height: 200px;
            object-fit: contain;
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .component-item-badges {
            position: absolute;
            top: 1rem;
            left: 1rem;
            display: flex;
            gap: 0.5rem;
            z-index: 2;
        }

        .component-badge {
            background: var(--success);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .component-badge.category {
            background: var(--primary);
        }

        .component-item-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .component-item-header {
            margin-bottom: 1rem;
        }

        .component-item-name {
            font-size: 1rem;
            font-weight: 700;
            color: var(--gray-800);
            margin-bottom: 0.5rem;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .component-item-code {
            font-size: 0.75rem;
            color: var(--gray-500);
            background: var(--gray-100);
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            display: inline-block;
            margin-bottom: 0.5rem;
        }

        .component-item-brand {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            background: var(--gray-800);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;


            letter-spacing: 0.5px;
        }

        .component-item-footer {
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid var(--gray-200);
        }

        .component-item-pricing {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 1rem;
        }

        .component-item-original-price {
            font-size: 0.875rem;
            color: var(--gray-500);
            text-decoration: line-through;
            margin-bottom: 0.25rem;
        }

        .component-item-price {
            font-size: 1.25rem;
            font-weight: 900;
            color: var(--danger);
            text-align: center;
        }

        .component-item-stock {
            font-size: 0.75rem;
            color: var(--success);
            font-weight: 500;
            text-align: center;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.25rem;
        }

        .component-item-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-add-to-build {
            flex: 1;
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.75rem 1rem;
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

        .btn-add-to-build:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .btn-quick-view {
            background: var(--gray-200);
            color: var(--gray-700);
            border: none;
            padding: 0.75rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-quick-view:hover {
            background: var(--gray-300);
        }

        /* Updated Product Card Styles */
        /*.component-item {*/
        /*    display: flex;*/
        /*    align-items: center;*/
        /*    gap: 1.5rem;*/
        /*    padding: 1.5rem;*/
        /*    border: 2px solid var(--gray-200);*/
        /*    cursor: pointer;*/
        /*    transition: all 0.3s ease;*/
        /*    background: white;*/
        /*    border-radius: 8px;*/
        /*    position: relative;*/
        /*}*/

        /*.component-item:hover {*/
        /*    border-color: var(--primary);*/
        /*    transform: translateY(-4px);*/
        /*    box-shadow: var(--shadow-lg);*/
        /*}*/

        /*.component-item-image {*/
        /*    width: 120px;*/
        /*    height: 120px;*/
        /*    object-fit: contain;*/
        /*    background: var(--gray-50);*/
        /*    border: 1px solid var(--gray-200);*/
        /*    padding: 0.5rem;*/
        /*    border-radius: 4px;*/
        /*    flex-shrink: 0;*/
        /*}*/

        /*.component-item-content {*/
        /*    flex: 1;*/
        /*    display: flex;*/
        /*    flex-direction: column;*/
        /*    gap: 0.5rem;*/
        /*}*/

        /*.component-item-header {*/
        /*    display: flex;*/
        /*    justify-content: space-between;*/
        /*    align-items: flex-start;*/
        /*    gap: 1rem;*/
        /*}*/

        /*.component-item-info {*/
        /*    flex: 1;*/
        /*}*/

        /*.component-item-name {*/
        /*    font-size: 1.125rem;*/
        /*    font-weight: 700;*/
        /*    color: var(--gray-800);*/
        /*    margin-bottom: 0.5rem;*/
        /*    line-height: 1.4;*/
        /*}*/

        /*.component-item-brand {*/
        /*    display: inline-flex;*/
        /*    align-items: center;*/
        /*    gap: 0.5rem;*/
        /*    background: var(--primary);*/
        /*    color: white;*/
        /*    padding: 0.25rem 0.75rem;*/
        /*    border-radius: 20px;*/
        /*    font-size: 0.75rem;*/
        /*    font-weight: 600;*/
        /*    text-transform: uppercase;*/
        /*    letter-spacing: 0.5px;*/
        /*}*/

        /*.component-item-stock {*/
        /*    font-size: 0.875rem;*/
        /*    color: var(--success);*/
        /*    font-weight: 500;*/
        /*    margin-top: 0.5rem;*/
        /*}*/

        /*.component-item-price {*/
        /*    font-size: 1.5rem;*/
        /*    font-weight: 900;*/
        /*    color: var(--success);*/
        /*    text-align: right;*/
        /*    white-space: nowrap;*/
        /*}*/

        /*.component-item-original-price {*/
        /*    font-size: 1rem;*/
        /*    color: var(--gray-500);*/
        /*    text-decoration: line-through;*/
        /*    margin-bottom: 0.25rem;*/
        /*}*/

        /* Product Tooltip */
        .product-tooltip {
            position: absolute;
            background: var(--gray-900);
            color: white;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: var(--shadow-xl);
            z-index: 10000;
            max-width: 300px;
            font-size: 0.875rem;
            pointer-events: none;
        }

        .tooltip-title {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: #fbbf24;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding-bottom: 0.5rem;
        }

        .tooltip-specs {
            display: grid;
            gap: 0.5rem;
        }

        .tooltip-spec-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.25rem 0;
        }

        .tooltip-spec-label {
            font-weight: 600;
            color: #cbd5e1;
        }

        .tooltip-spec-value {
            color: white;
            font-weight: 500;
        }

        .pagination-container {
            margin-top: 1.5rem;
            display: flex;
            justify-content: center;
        }

        .pagination {
            display: flex;
            gap: 0.5rem;
        }

        .pagination button {
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--gray-300);
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .pagination button:hover {
            background: var(--gray-100);
            transform: translateY(-1px);
        }

        .pagination button.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .pagination button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.875rem;
            border: 1px solid var(--gray-300);
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(30, 41, 59, 0.1);
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .btn-cancel {
            background: var(--gray-300);
            color: var(--gray-700);
            border: none;
            padding: 0.875rem 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .btn-cancel:hover {
            background: var(--gray-400);
            transform: translateY(-1px);
        }

        /* Custom Confirm Modal */
        .confirm-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            animation: fadeIn 0.3s ease;
        }

        .confirm-modal-content {
            background: white;
            width: 400px;
            max-width: 90%;
            box-shadow: var(--shadow-xl);
            animation: slideUp 0.3s ease;
        }

        .confirm-modal-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
        }

        .confirm-modal-header h3 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--gray-800);
            margin: 0;
        }

        .confirm-modal-body {
            padding: 1.5rem;
        }

        .confirm-modal-body p {
            color: var(--gray-600);
            line-height: 1.5;
            margin: 0;
        }

        .confirm-modal-actions {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--gray-200);
            display: flex;
            gap: 0.75rem;
            justify-content: flex-end;
        }

        .btn-confirm-cancel {
            background: var(--gray-300);
            color: var(--gray-700);
            border: none;
            padding: 0.5rem 1.5rem;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-confirm-cancel:hover {
            background: var(--gray-400);
        }

        .btn-confirm-ok {
            background: #7c3aed;
            color: white;
            border: none;
            padding: 0.5rem 1.5rem;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-confirm-ok:hover {
            background: #6d28d9;
        }

        /* Loading State */
        .loading {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            color: var(--gray-500);
            font-size: 1rem;
        }

        .loading i {
            animation: spin 1s linear infinite;
            margin-right: 0.75rem;
            font-size: 1.25rem;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .builder-layout {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .summary-panel {
                position: static;
            }

            .filter-row {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .container-wide {
                max-width: 100%;
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

            .category-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .category-status {
                align-self: stretch;
                justify-content: space-between;
            }

            .modal-content {
                width: 95%;
                max-height: 95vh;
            }

            .component-item {
                flex-direction: column;
                text-align: center;
            }

            .component-item-image {
                width: 100px;
                height: 100px;
            }
        }

        /* Utility Classes */
        .text-green-500 {
            color: var(--success);
        }

        .text-red-500 {
            color: var(--danger);
        }
    </style>

    <script>
        let currentCategoryId = null;
        let currentCategoryName = null;
        let currentPage = 1;
        let currentBuild = @json($currentBuild);
        let pendingDeleteAction = null;

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            updateBuildDisplay();
            checkCompatibility();

            // Add smooth scrolling
            document.documentElement.style.scrollBehavior = 'smooth';

            // Chỉ khôi phục localStorage nếu không có cấu hình từ server
            const hasServerBuild = Object.keys(currentBuild).length > 0;
            if (!hasServerBuild) {
                const savedTotalPrice = localStorage.getItem('pc_builder_total_price');
                const lastUpdate = localStorage.getItem('pc_builder_last_update');

                // Chỉ khôi phục nếu được lưu trong vòng 1 giờ
                if (savedTotalPrice && lastUpdate && (Date.now() - parseInt(lastUpdate)) < 3600000) {
                    updateTotalPrice(parseInt(savedTotalPrice));
                } else {
                    // Xóa localStorage cũ
                    localStorage.removeItem('pc_builder_total_price');
                    localStorage.removeItem('pc_builder_last_update');
                }
            } else {
                // Nếu có cấu hình từ server, tính lại giá từ đầu
                calculateTotalFromBuild();
            }
        });

        // Calculate total price from current build
        function calculateTotalFromBuild() {
            let totalPrice = 0;

            Object.values(currentBuild).forEach(items => {
                if (Array.isArray(items)) {
                    items.forEach(item => {
                        const finalPrice = item.product.final_price || item.product.price_out;
                        totalPrice += finalPrice * item.quantity;
                    });
                } else if (items && items.product) {
                    const finalPrice = items.product.final_price || items.product.price_out;
                    totalPrice += finalPrice * items.quantity;
                }
            });

            updateTotalPrice(totalPrice);
        }

        // Open component selection modal
        function openComponentModal(categoryId, categoryName) {
            currentCategoryId = categoryId;
            currentCategoryName = categoryName;
            currentPage = 1;

            const modal = document.getElementById('componentModal');
            const modalTitle = document.getElementById('modalTitle');

            modalTitle.textContent = `Chọn ${categoryName}`;

            // Show modal with animation
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';

            loadProducts();
            loadSpecFilters();
        }

        // Close component selection modal
        function closeComponentModal() {
            const modal = document.getElementById('componentModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';

            currentCategoryId = null;
            currentCategoryName = null;
        }

        // Load products with better error handling
        function loadProducts(page = 1) {
            const componentList = document.getElementById('componentList');
            componentList.innerHTML = '<div class="loading"><i class="fas fa-spinner"></i>Đang tải sản phẩm...</div>';

            const params = new URLSearchParams({
                category_id: currentCategoryId,
                page: page
            });

            const search = document.getElementById('searchInput').value;
            const brand = document.getElementById('brandFilter').value;
            const price = document.getElementById('priceFilter').value;

            if (search) params.append('search', search);
            if (brand) params.append('brand_id', brand);
            if (price) params.append('price_range', price);

            // Add spec filters
            const specFiltersGrid = document.getElementById('specFiltersGrid');
            if (specFiltersGrid) {
                const specSelects = specFiltersGrid.querySelectorAll('select');
                specSelects.forEach(select => {
                    if (select.value) {
                        const specKey = select.id.replace('spec-', '');
                        params.append(`spec_${specKey}`, select.value);
                    }
                });
            }

            fetch(`{{ route('pc-builder.products') }}?${params}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    displayProducts(data.products);
                    displayPagination(data.pagination);
                })
                .catch(error => {
                    console.error('Error:', error);
                    componentList.innerHTML = '<div class="loading"><i class="fas fa-exclamation-triangle"></i>Có lỗi xảy ra khi tải sản phẩm</div>';
                });
        }

        // Display products with card layout như trong ảnh mẫu
        function displayProducts(products) {
            const componentList = document.getElementById('componentList');

            if (products.length === 0) {
                componentList.innerHTML = '<div class="loading"><i class="fas fa-search"></i>Không tìm thấy sản phẩm nào</div>';
                return;
            }

            componentList.innerHTML = '';

            products.forEach(product => {
                const productItem = document.createElement('div');
                productItem.className = 'component-item';

                const finalPrice = product.final_price || product.price_out;
                const originalPrice = product.price_out;
                const hasDiscount = finalPrice < originalPrice;

                // Sửa cách xử lý ảnh để hiển thị đúng
                let imageUrl = '/images/no-image.png';
                if (product.image) {
                    // Thử nhiều đường dẫn khác nhau
                    imageUrl = `/image/${product.image}`;
                }

                // Create price display
                let priceDisplay = `<div class="component-item-price">${formatPrice(finalPrice)} VNĐ</div>`;
                if (hasDiscount) {
                    priceDisplay = `
                        <div class="component-item-original-price">${formatPrice(originalPrice)} VNĐ</div>
                        <div class="component-item-price">${formatPrice(finalPrice)} VNĐ</div>
                    `;
                }

                productItem.innerHTML = `
            <div class="component-item-badges">
                <span class="component-badge">${product.quantity}</span>
                <span class="component-badge category">${currentCategoryName}</span>
            </div>

            <img src="${imageUrl}"
                 alt="${product.name}"
                 class="component-item-image"
                 onerror="this.src='/images/no-image.png'"
                 loading="lazy">

            <div class="component-item-content">
                <div class="component-item-header">
                    <div class="component-item-code">${product.product_code || 'N/A'}</div>
                    <div class="component-item-name">${product.name}</div>
                    <div class="component-item-brand">
                        <i class="fas fa-tag"></i>
                        ${product.brand ? product.brand.name : 'N/A'}
                    </div>
                </div>

                <div class="component-item-footer">
                    <div class="component-item-pricing">
                        ${priceDisplay}
                    </div>

                    <div class="component-item-stock">
                        <i class="fas fa-box"></i>
                        Còn ${product.quantity} sản phẩm
                    </div>

                    <div class="component-item-actions">
                        <button class="btn-add-to-build" onclick="selectComponent(${JSON.stringify(product).replace(/"/g, '&quot;')})">
                            <i class="fas fa-shopping-cart"></i>
                            Thêm vào giỏ
                        </button>
                        <button class="btn-quick-view" title="Xem nhanh">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;

                // Add hover event for tooltip
                if (product.parsed_details && Object.keys(product.parsed_details).length > 0) {
                    productItem.addEventListener('mouseenter', (e) => showProductTooltip(e, product.parsed_details));
                    productItem.addEventListener('mouseleave', hideProductTooltip);
                    productItem.addEventListener('mousemove', moveProductTooltip);
                }

                componentList.appendChild(productItem);
            });
        }

        // Show product tooltip with specifications
        function showProductTooltip(event, specs) {
            const tooltip = document.getElementById('productTooltip');
            const tooltipSpecs = document.getElementById('tooltipSpecs');

            // Clear previous content
            tooltipSpecs.innerHTML = '';

            // Add specifications
            Object.entries(specs).forEach(([key, value]) => {
                const specItem = document.createElement('div');
                specItem.className = 'tooltip-spec-item';
                specItem.innerHTML = `
                    <span class="tooltip-spec-label">${getSpecLabel(key)}:</span>
                    <span class="tooltip-spec-value">${value}</span>
                `;
                tooltipSpecs.appendChild(specItem);
            });

            // Show tooltip
            tooltip.style.display = 'block';
            moveProductTooltip(event);
        }

        // Hide product tooltip
        function hideProductTooltip() {
            const tooltip = document.getElementById('productTooltip');
            tooltip.style.display = 'none';
        }

        // Move tooltip with mouse
        function moveProductTooltip(event) {
            const tooltip = document.getElementById('productTooltip');
            const offset = 15;

            tooltip.style.left = (event.pageX + offset) + 'px';
            tooltip.style.top = (event.pageY + offset) + 'px';
        }

        // Display pagination
        function displayPagination(pagination) {
            const paginationContainer = document.getElementById('paginationContainer');

            if (pagination.last_page <= 1) {
                paginationContainer.innerHTML = '';
                return;
            }

            let paginationHTML = '<div class="pagination">';

            // Previous button
            paginationHTML += `<button onclick="loadProducts(${pagination.current_page - 1})" ${pagination.current_page <= 1 ? 'disabled' : ''}>
                <i class="fas fa-chevron-left"></i>
            </button>`;

            // Page numbers
            const startPage = Math.max(1, pagination.current_page - 2);
            const endPage = Math.min(pagination.last_page, pagination.current_page + 2);

            if (startPage > 1) {
                paginationHTML += `<button onclick="loadProducts(1)">1</button>`;
                if (startPage > 2) {
                    paginationHTML += `<span>...</span>`;
                }
            }

            for (let i = startPage; i <= endPage; i++) {
                if (i === pagination.current_page) {
                    paginationHTML += `<button class="active">${i}</button>`;
                } else {
                    paginationHTML += `<button onclick="loadProducts(${i})">${i}</button>`;
                }
            }

            if (endPage < pagination.last_page) {
                if (endPage < pagination.last_page - 1) {
                    paginationHTML += `<span>...</span>`;
                }
                paginationHTML += `<button onclick="loadProducts(${pagination.last_page})">${pagination.last_page}</button>`;
            }

            // Next button
            paginationHTML += `<button onclick="loadProducts(${pagination.current_page + 1})" ${pagination.current_page >= pagination.last_page ? 'disabled' : ''}>
                <i class="fas fa-chevron-right"></i>
            </button>`;

            paginationHTML += '</div>';
            paginationContainer.innerHTML = paginationHTML;
        }

        // Filter products
        function filterProducts() {
            currentPage = 1;
            loadProducts();
        }

        // Select component with better feedback
        function selectComponent(product) {
            const data = {
                product_id: product.id,
                category_name: currentCategoryName
            };

            fetch('{{ route("pc-builder.add-to-session") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        currentBuild = data.build;
                        updateBuildDisplay();
                        updateTotalPrice(data.total_price);
                        checkCompatibility();
                        closeComponentModal();

                        // Show success notification
                        showNotification('Đã thêm sản phẩm thành công!', 'success');
                    } else {
                        showNotification('Có lỗi xảy ra khi thêm sản phẩm', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Có lỗi xảy ra khi thêm sản phẩm', 'error');
                });
        }

        // Show custom confirm modal
        function showConfirmModal(productId, categoryName) {
            pendingDeleteAction = { productId, categoryName };

            const modal = document.getElementById('confirmDeleteModal');
            const title = document.getElementById('confirmModalTitle');
            const message = document.getElementById('confirmModalMessage');

            title.textContent = '127.0.0.1:8000 cho biết';
            message.textContent = 'Bạn có chắc chắn muốn xóa linh kiện này?';

            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        // Close confirm modal
        function closeConfirmModal() {
            const modal = document.getElementById('confirmDeleteModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
            pendingDeleteAction = null;
        }

        // Confirm delete action
        function confirmDeleteAction() {
            if (pendingDeleteAction) {
                if (pendingDeleteAction.action === 'clearAll') {
                    executeClearBuild();
                } else {
                    executeRemoveComponent(pendingDeleteAction.productId, pendingDeleteAction.categoryName);
                }
                closeConfirmModal();
            }
        }

        // Remove component with confirmation
        function removeComponent(productId, categoryName) {
            showConfirmModal(productId, categoryName);
        }

        // Execute remove component after confirmation
        function executeRemoveComponent(productId, categoryName) {
            const data = {
                product_id: productId,
                category_name: categoryName
            };

            fetch('{{ route("pc-builder.remove-from-session") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Immediately remove the component from the UI
                        const categoryElement = document.querySelector(`[data-category="${categoryName}"]`);
                        if (categoryElement) {
                            const selectedContainer = categoryElement.querySelector('.selected-components');
                            if (selectedContainer) {
                                selectedContainer.classList.remove('show');
                                selectedContainer.innerHTML = '';
                            }
                            categoryElement.classList.remove('has-selection');
                            const priceElement = categoryElement.querySelector('.price');
                            if (priceElement) {
                                priceElement.textContent = 'Chưa chọn';
                            }
                        }

                        // Update the current build data
                        if (currentBuild[categoryName]) {
                            delete currentBuild[categoryName];
                        }

                        updateTotalPrice(data.total_price);
                        updateComponentCount();
                        checkCompatibility();

                        showNotification('Đã xóa sản phẩm thành công!', 'success');
                    } else {
                        showNotification('Có lỗi xảy ra khi xóa sản phẩm', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Có lỗi xảy ra khi xóa sản phẩm', 'error');
                });
        }

        // Update component count
        function updateComponentCount() {
            let componentCount = 0;
            Object.values(currentBuild).forEach(items => {
                if (Array.isArray(items)) {
                    componentCount += items.length;
                } else if (items && items.product) {
                    componentCount += 1;
                }
            });

            document.getElementById('componentCount').textContent = componentCount;
        }

        // Update build display with animations
        function updateBuildDisplay() {
            // First, reset all categories
            document.querySelectorAll('.component-category').forEach(category => {
                const categoryName = category.getAttribute('data-category');
                const selectedContainer = category.querySelector('.selected-components');
                const priceElement = category.querySelector('.price');

                category.classList.remove('has-selection');
                if (selectedContainer) {
                    selectedContainer.classList.remove('show');
                    selectedContainer.innerHTML = '';
                }
                if (priceElement) {
                    priceElement.textContent = 'Chưa chọn';
                }
            });

            // Then update only categories that have components
            Object.keys(currentBuild).forEach(categoryName => {
                const categoryElement = document.querySelector(`[data-category="${categoryName}"]`);
                if (!categoryElement) return;

                const selectedContainer = categoryElement.querySelector('.selected-components');
                const priceElement = categoryElement.querySelector('.price');

                const items = currentBuild[categoryName];

                if (items && ((Array.isArray(items) && items.length > 0) || items.product)) {
                    categoryElement.classList.add('has-selection');
                    selectedContainer.classList.add('show');
                    selectedContainer.innerHTML = '';

                    let totalPrice = 0;

                    if (Array.isArray(items)) {
                        items.forEach(item => {
                            const finalPrice = item.product.final_price || item.product.price_out;
                            totalPrice += finalPrice * item.quantity;

                            const componentDiv = createComponentElement(item, categoryName, finalPrice);
                            selectedContainer.appendChild(componentDiv);
                        });
                    } else {
                        const finalPrice = items.product.final_price || items.product.price_out;
                        totalPrice = finalPrice * items.quantity;

                        const componentDiv = createComponentElement(items, categoryName, finalPrice);
                        selectedContainer.appendChild(componentDiv);
                    }

                    priceElement.textContent = formatPrice(totalPrice) + ' VNĐ';
                }
            });

            updateComponentCount();
        }

        // Create component element
        function createComponentElement(item, categoryName, finalPrice) {
            const componentDiv = document.createElement('div');
            componentDiv.className = 'selected-component';

            // Better image URL handling
            let imageUrl = '/images/no-image.png';
            if (item.product.image) {
                imageUrl = `/image/${item.product.image}`;
            } else if (item.product.image_url) {
                imageUrl = item.product.image_url;
            }

            componentDiv.innerHTML = `
                <img src="${imageUrl}"
                     alt="${item.product.name}"
                     class="component-image"
                     onerror="this.src='/images/no-image.png'"
                     loading="lazy">
                <div class="component-details">
                    <div class="component-name">${item.product.name}</div>
                    <div class="component-price">${formatPrice(finalPrice)} VNĐ${item.quantity > 1 ? ` x ${item.quantity}` : ''}</div>
                </div>
                <button class="remove-btn" onclick="removeComponent(${item.product.id}, '${categoryName}')">
                    <i class="fas fa-times"></i>
                </button>
            `;

            return componentDiv;
        }

        // Update total price with animation
        function updateTotalPrice(totalPrice) {
            const totalPriceElement = document.getElementById('totalPrice');
            const summaryTotalElement = document.getElementById('summaryTotal');

            // Add animation class
            totalPriceElement.style.transform = 'scale(1.1)';
            setTimeout(() => {
                totalPriceElement.style.transform = 'scale(1)';
            }, 200);

            totalPriceElement.textContent = formatPrice(totalPrice);
            summaryTotalElement.textContent = formatPrice(totalPrice) + ' VNĐ';

            // Chỉ lưu localStorage khi có thay đổi thực sự, không lưu khi tải từ server
            if (totalPrice > 0) {
                localStorage.setItem('pc_builder_total_price', totalPrice);
                localStorage.setItem('pc_builder_last_update', Date.now());
            } else {
                localStorage.removeItem('pc_builder_total_price');
                localStorage.removeItem('pc_builder_last_update');
            }
        }

        // Check compatibility
        function checkCompatibility() {
            fetch('{{ route("pc-builder.check-compatibility") }}')
                .then(response => response.json())
                .then(data => {
                    const statusElement = document.getElementById('compatibilityStatus');
                    const textElement = document.getElementById('compatibilityText');

                    if (data.compatible) {
                        statusElement.innerHTML = '<i class="fas fa-check-circle"></i> <span>Tương thích</span>';
                        statusElement.className = 'compatibility-status';
                        textElement.innerHTML = '<i class="fas fa-check-circle"></i> Tương thích';
                        textElement.className = 'value compatible';
                    } else {
                        statusElement.innerHTML = '<i class="fas fa-exclamation-triangle"></i> <span>Có vấn đề</span>';
                        statusElement.className = 'compatibility-status incompatible';
                        textElement.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Có vấn đề';
                        textElement.className = 'value incompatible';

                        if (data.issues.length > 0) {
                            console.log('Compatibility issues:', data.issues);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error checking compatibility:', error);
                });
        }

        // Save build
        function saveBuild() {
            @guest
            showNotification('Vui lòng đăng nhập để lưu cấu hình', 'warning');
            return;
            @endguest

            if (Object.keys(currentBuild).length === 0) {
                showNotification('Vui lòng chọn ít nhất một linh kiện', 'warning');
                return;
            }

            document.getElementById('saveBuildModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        // Close save build modal
        function closeSaveBuildModal() {
            document.getElementById('saveBuildModal').style.display = 'none';
            document.body.style.overflow = 'auto';
            document.getElementById('buildName').value = '';
        }

        // Handle save build form
        document.getElementById('saveBuildForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const buildName = document.getElementById('buildName').value;

            fetch('{{ route("pc-builder.save-build") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ name: buildName })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification(data.message, 'success');
                        closeSaveBuildModal();
                        currentBuild = {};
                        updateBuildDisplay();
                        updateTotalPrice(0);
                    } else {
                        showNotification(data.error || 'Có lỗi xảy ra khi lưu cấu hình', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Có lỗi xảy ra khi lưu cấu hình', 'error');
                });
        });

        // Add to cart
        function addToCart() {
            @guest
            showNotification('Vui lòng đăng nhập để thêm vào giỏ hàng', 'warning');
            return;
            @endguest

            if (Object.keys(currentBuild).length === 0) {
                showNotification('Vui lòng chọn ít nhất một linh kiện', 'warning');
                return;
            }

            fetch('{{ route("pc-builder.add-to-cart") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification(data.message, 'success');
                    } else {
                        showNotification(data.error || 'Có lỗi xảy ra khi thêm vào giỏ hàng', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Có lỗi xảy ra khi thêm vào giỏ hàng', 'error');
                });
        }

        // Clear build
        function clearBuild() {
            pendingDeleteAction = { action: 'clearAll' };

            const modal = document.getElementById('confirmDeleteModal');
            const title = document.getElementById('confirmModalTitle');
            const message = document.getElementById('confirmModalMessage');

            title.textContent = '127.0.0.1:8000 cho biết';
            message.textContent = 'Bạn có chắc chắn muốn xóa tất cả linh kiện?';

            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        // Execute clear build after confirmation
        function executeClearBuild() {
            fetch('{{ route("pc-builder.clear-build") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Clear all components from UI immediately
                        document.querySelectorAll('.component-category').forEach(category => {
                            const selectedContainer = category.querySelector('.selected-components');
                            if (selectedContainer) {
                                selectedContainer.classList.remove('show');
                                selectedContainer.innerHTML = '';
                            }
                            category.classList.remove('has-selection');
                            const priceElement = category.querySelector('.price');
                            if (priceElement) {
                                priceElement.textContent = 'Chưa chọn';
                            }
                        });

                        currentBuild = {};
                        updateTotalPrice(0);
                        updateComponentCount();
                        checkCompatibility();

                        showNotification(data.message, 'success');
                    } else {
                        showNotification('Có lỗi xảy ra khi xóa cấu hình', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Có lỗi xảy ra khi xóa cấu hình', 'error');
                });
        }

        // Load specification filters
        function loadSpecFilters() {
            const specFilters = document.getElementById('specFilters');
            const specFiltersGrid = document.getElementById('specFiltersGrid');

            fetch(`{{ route('pc-builder.spec-filters') }}?category_id=${currentCategoryId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.specs && Object.keys(data.specs).length > 0) {
                        specFilters.style.display = 'block';
                        specFiltersGrid.innerHTML = '';

                        Object.keys(data.specs).forEach(specKey => {
                            const specValues = data.specs[specKey];
                            if (specValues.length > 1) {
                                const filterGroup = document.createElement('div');
                                filterGroup.className = 'filter-group';

                                const label = document.createElement('label');
                                label.textContent = getSpecLabel(specKey);

                                const select = document.createElement('select');
                                select.id = `spec-${specKey}`;
                                select.innerHTML = '<option value="">Tất cả</option>';

                                specValues.forEach(value => {
                                    const option = document.createElement('option');
                                    option.value = value;
                                    option.textContent = value;
                                    select.appendChild(option);
                                });

                                filterGroup.appendChild(label);
                                filterGroup.appendChild(select);
                                specFiltersGrid.appendChild(filterGroup);
                            }
                        });
                    } else {
                        specFilters.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error loading spec filters:', error);
                    specFilters.style.display = 'none';
                });
        }

        // Get specification label in Vietnamese
        function getSpecLabel(specKey) {
            const labels = {
                'generation': 'Thế hệ',
                'capacity': 'Dung lượng',
                'speed': 'Tốc độ',
                'socket': 'Socket',
                'cores': 'Số nhân',
                'threads': 'Số luồng',
                'memory': 'Bộ nhớ',
                'interface': 'Giao tiếp',
                'form_factor': 'Form Factor',
                'wattage': 'Công suất',
                'efficiency': 'Hiệu suất',
                'size': 'Kích thước',
                'type': 'Loại',
                'connection': 'Kết nối'
            };
            return labels[specKey] || specKey.charAt(0).toUpperCase() + specKey.slice(1);
        }

        // Format price
        function formatPrice(price) {
            return new Intl.NumberFormat('vi-VN').format(price);
        }

        // Show notification
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'}"></i>
                <span>${message}</span>
            `;

            // Add notification styles
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? 'var(--success)' : type === 'error' ? 'var(--danger)' : type === 'warning' ? 'var(--warning)' : 'var(--info)'};
                color: white;
                padding: 1rem 1.5rem;
                box-shadow: var(--shadow-lg);
                z-index: 10000;
                display: flex;
                align-items: center;
                gap: 0.5rem;
                font-weight: 600;
                animation: slideInRight 0.3s ease;
                max-width: 400px;
            `;

            document.body.appendChild(notification);

            // Remove notification after 3 seconds
            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }

        // Close modals when clicking outside
        document.getElementById('componentModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeComponentModal();
            }
        });

        document.getElementById('saveBuildModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeSaveBuildModal();
            }
        });

        document.getElementById('confirmDeleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeConfirmModal();
            }
        });

        // Search on enter
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                filterProducts();
            }
        });

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
