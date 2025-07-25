@extends('layout.customerApp')

@section('content')
    <div class="my-builds-page">
        <!-- Hero Section -->
        <div class="hero-section">
            <div class="container-wide">
                <div class="hero-content">
                    <div class="hero-text">
                        <h1 class="hero-title">
                            <i class="fas fa-list"></i>
                            Cấu Hình PC Của Tôi
                        </h1>
                        <p class="hero-subtitle">Quản lý và sử dụng lại các cấu hình PC đã lưu</p>
                    </div>
                    <div class="hero-actions">
                        <a href="{{ route('pc-builder.main-builder-pc') }}" class="btn-create-new">
                            <i class="fas fa-plus"></i>
                            <span>Tạo Cấu Hình Mới</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="container-wide">
            @if($builds->count() > 0)
                <div class="builds-grid" id="buildsGrid">
                    @foreach($builds as $build)
                        <div class="build-card" data-build-id="{{ $build->id }}">
                            <!-- Build Header -->
                            <div class="build-header">
                                <div class="build-info">
                                    <h3 class="build-name">{{ $build->name }}</h3>
                                    <div class="build-meta">
                                        <span class="build-date">
                                            <i class="fas fa-calendar-alt"></i>
                                            {{ $build->created_at->format('d/m/Y H:i') }}
                                        </span>
                                        <span class="build-components-count">
                                            <i class="fas fa-cubes"></i>
                                            {{ $build->components->count() }} linh kiện
                                        </span>
                                    </div>
                                </div>
                                <div class="build-price">
                                    <span class="price-label">Tổng giá trị</span>
                                    <span class="price-value" data-price="{{ $build->components->sum(function($component) { return ($component->product->final_price ?? $component->product->price_out) * $component->quantity; }) }}">
                                        {{ number_format($build->components->sum(function($component) { return ($component->product->final_price ?? $component->product->price_out) * $component->quantity; }), 0, ',', '.') }} VNĐ
                                    </span>
                                </div>
                            </div>

                            <!-- Components Preview -->
                            <div class="components-preview">
                                <h4 class="preview-title">
                                    <i class="fas fa-cogs"></i>
                                    Linh kiện trong cấu hình
                                </h4>
                                <div class="components-grid">
                                    @foreach($build->components->take(4) as $component)
                                        <div class="component-preview">
                                            <div class="component-icon">
                                                <i class="fas fa-{{
                                                    $component->product->category->name == 'CPU' ? 'microchip' :
                                                    ($component->product->category->name == 'GPU' ? 'tv' :
                                                    ($component->product->category->name == 'RAM' ? 'memory' :
                                                    ($component->product->category->name == 'Motherboard' ? 'memory' :
                                                    ($component->product->category->name == 'SSD' ? 'hdd' :
                                                    ($component->product->category->name == 'HDD' ? 'hdd' :
                                                    ($component->product->category->name == 'PSU' ? 'plug' : 'cube'))))))
                                                }}"></i>
                                            </div>
                                            <div class="component-info">
                                                <div class="component-category">{{ $component->product->category->name }}</div>
                                                <div class="component-name">{{ Str::limit($component->product->name, 25) }}</div>
                                                <div class="component-price">
                                                    {{ number_format($component->product->final_price ?? $component->product->price_out, 0, ',', '.') }} VNĐ
                                                    @if($component->quantity > 1)
                                                        <span class="quantity">x{{ $component->quantity }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    @if($build->components->count() > 4)
                                        <div class="component-preview more-components">
                                            <div class="more-icon">
                                                <i class="fas fa-ellipsis-h"></i>
                                            </div>
                                            <div class="more-text">
                                                +{{ $build->components->count() - 4 }} khác
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Build Actions -->
                            <div class="build-actions">
                                <button class="btn-action btn-load" onclick="loadBuild({{ $build->id }})">
                                    <i class="fas fa-download"></i>
                                    <span>Tải Cấu Hình</span>
                                </button>
{{--                                <button class="btn-action btn-cart" onclick="addBuildToCart({{ $build->id }})">--}}
{{--                                    <i class="fas fa-shopping-cart"></i>--}}
{{--                                    <span>Thêm Vào Giỏ</span>--}}
{{--                                </button>--}}
                                <button class="btn-action btn-delete" onclick="deleteBuild({{ $build->id }})">
                                    <i class="fas fa-trash"></i>
                                    <span>Xóa</span>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($builds->hasPages())
                    <div class="pagination-container">
                        {{ $builds->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-desktop"></i>
                    </div>
                    <h3 class="empty-title">Chưa có cấu hình nào</h3>
                    <p class="empty-text">Bạn chưa lưu cấu hình PC nào. Hãy tạo cấu hình đầu tiên của bạn!</p>
                    <a href="{{ route('pc-builder.main-builder-pc') }}" class="btn-create-first">
                        <i class="fas fa-plus"></i>
                        <span>Tạo Cấu Hình Đầu Tiên</span>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="deleteModal" style="display: none;">
        <div class="modal-content small">
            <div class="modal-header">
                <h2>Xác nhận xóa</h2>
                <button class="modal-close" onclick="closeDeleteModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa cấu hình này? Hành động này không thể hoàn tác.</p>
            </div>
            <div class="modal-actions">
                <button class="btn-cancel" onclick="closeDeleteModal()">Hủy</button>
                <button class="btn-confirm-delete" onclick="confirmDelete()">Xóa</button>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay" style="display: none;">
        <div class="loading-spinner">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Đang xử lý...</p>
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

        .my-builds-page {
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

        .btn-create-new {
            background: #059669;
            color: white;
            padding: 1rem 2rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-lg);
        }

        .btn-create-new:hover {
            background: #047857;
            transform: translateY(-2px);
            box-shadow: var(--shadow-xl);
        }

        /* Builds Grid - Optimized */
        .builds-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(450px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
            margin-bottom: 2rem;
        }

        /* Build Card - Simplified */
        .build-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            overflow: hidden;
            transition: all 0.2s ease;
            border: 1px solid var(--gray-200);
            will-change: transform;
        }

        .build-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary);
        }

        /* Build Header */
        .build-header {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            color: white;
            padding: 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .build-name {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: white;
        }

        .build-meta {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .build-date,
        .build-components-count {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            opacity: 0.9;
        }

        .build-price {
            text-align: right;
        }

        .price-label {
            display: block;
            font-size: 0.8rem;
            opacity: 0.8;
            margin-bottom: 0.25rem;
        }

        .price-value {
            font-size: 1.25rem;
            font-weight: 900;
            color: #fbbf24;
        }

        /* Components Preview - Simplified */
        .components-preview {
            padding: 1.25rem;
        }

        .preview-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .components-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }

        .component-preview {
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: 6px;
            padding: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: background-color 0.2s ease;
        }

        .component-preview:hover {
            background: var(--gray-100);
        }

        .component-icon {
            width: 2rem;
            height: 2rem;
            background: var(--primary);
            color: white;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            flex-shrink: 0;
        }

        .component-info {
            flex: 1;
            min-width: 0;
        }

        .component-category {
            font-size: 0.65rem;
            color: var(--gray-500);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.125rem;
        }

        .component-name {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 0.125rem;
            line-height: 1.2;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .component-price {
            font-size: 0.65rem;
            color: var(--success);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .quantity {
            background: var(--gray-200);
            color: var(--gray-700);
            padding: 0.0625rem 0.25rem;
            border-radius: 3px;
            font-size: 0.6rem;
        }

        .more-components {
            background: var(--gray-100);
            border: 1px dashed var(--gray-300);
            justify-content: center;
            text-align: center;
            flex-direction: column;
            gap: 0.25rem;
        }

        .more-icon {
            font-size: 1rem;
            color: var(--gray-400);
        }

        .more-text {
            font-size: 0.7rem;
            color: var(--gray-600);
            font-weight: 500;
        }

        /* Build Actions - Simplified */
        .build-actions {
            padding: 1rem;
            border-top: 1px solid var(--gray-200);
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
        }

        .btn-action {
            padding: 0.625rem 0.75rem;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.375rem;
            text-decoration: none;
            box-shadow: var(--shadow-sm);
        }

        .btn-action:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-load {
            background: var(--primary);
            color: white;
        }

        .btn-load:hover {
            background: var(--primary-dark);
        }

        .btn-cart {
            background: var(--success);
            color: white;
        }

        .btn-cart:hover {
            background: #047857;
        }

        .btn-delete {
            background: var(--danger);
            color: white;
        }

        .btn-delete:hover {
            background: #b91c1c;
        }

        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            backdrop-filter: blur(2px);
        }

        .loading-spinner {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            text-align: center;
            box-shadow: var(--shadow-xl);
        }

        .loading-spinner i {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        .loading-spinner p {
            color: var(--gray-700);
            font-weight: 600;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            margin-top: 2rem;
        }

        .empty-icon {
            width: 6rem;
            height: 6rem;
            background: var(--gray-200);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            font-size: 2rem;
            color: var(--gray-400);
        }

        .empty-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gray-800);
            margin-bottom: 0.75rem;
        }

        .empty-text {
            font-size: 1rem;
            color: var(--gray-600);
            margin-bottom: 2rem;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-create-first {
            background: var(--primary);
            color: white;
            padding: 1rem 2rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-lg);
        }

        .btn-create-first:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-xl);
        }

        /* Modal Styles */
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

        .modal-content {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-xl);
            width: 90%;
            max-width: 400px;
            overflow: hidden;
            animation: slideUp 0.3s ease;
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
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .modal-close:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-actions {
            padding: 1.5rem;
            border-top: 1px solid var(--gray-200);
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .btn-cancel {
            background: var(--gray-300);
            color: var(--gray-700);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            background: var(--gray-400);
        }

        .btn-confirm-delete {
            background: var(--danger);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-confirm-delete:hover {
            background: #b91c1c;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
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

        /* Responsive */
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

            .builds-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .build-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .build-price {
                text-align: left;
            }

            .components-grid {
                grid-template-columns: 1fr;
            }

            .build-actions {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <script>
        let buildToDelete = null;

        // Optimized functions with better performance
        function showLoading() {
            document.getElementById('loadingOverlay').style.display = 'flex';
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').style.display = 'none';
        }

        function loadBuild(buildId) {
            showLoading();
            showNotification('Đang tải cấu hình...', 'info');

            // Clear localStorage before loading new build
            localStorage.removeItem('pc_builder_total_price');
            localStorage.removeItem('pc_builder_last_update');

            fetch(`{{ route('pc-builder.load-build', '') }}/${buildId}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => {
                    hideLoading();
                    if (response.redirected) {
                        window.location.href = response.url;
                    } else {
                        return response.json();
                    }
                })
                .then(data => {
                    if (data && data.success) {
                        showNotification(data.message, 'success');
                        setTimeout(() => {
                            window.location.href = '{{ route("pc-builder.main-builder-pc") }}';
                        }, 1000);
                    } else if (data && data.error) {
                        showNotification(data.error, 'error');
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error:', error);
                    showNotification('Có lỗi xảy ra khi tải cấu hình', 'error');
                });
        }

        function addBuildToCart(buildId) {
            showLoading();
            showNotification('Đang thêm vào giỏ hàng...', 'info');

            fetch(`{{ route('pc-builder.add-build-to-cart', '') }}/${buildId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => {
                    hideLoading();
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showNotification(data.message, 'success');
                    } else {
                        showNotification(data.error || 'Có lỗi xảy ra khi thêm vào giỏ hàng', 'error');
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error:', error);
                    showNotification('Có lỗi xảy ra khi thêm vào giỏ hàng', 'error');
                });
        }

        function deleteBuild(buildId) {
            buildToDelete = buildId;
            document.getElementById('deleteModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
            document.body.style.overflow = 'auto';
            buildToDelete = null;
        }

        function confirmDelete() {
            if (buildToDelete) {
                showLoading();

                fetch(`{{ route('pc-builder.delete-build', '') }}/${buildToDelete}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => {
                        hideLoading();
                        if (response.redirected) {
                            window.location.href = response.url;
                        } else {
                            return response.json();
                        }
                    })
                    .then(data => {
                        if (data && data.success) {
                            showNotification(data.message, 'success');

                            // Remove build card with animation
                            const buildCard = document.querySelector(`[data-build-id="${buildToDelete}"]`);
                            if (buildCard) {
                                buildCard.style.transition = 'all 0.3s ease';
                                buildCard.style.transform = 'scale(0.8)';
                                buildCard.style.opacity = '0';
                                setTimeout(() => {
                                    buildCard.remove();

                                    // Check if no builds left
                                    const remainingBuilds = document.querySelectorAll('.build-card');
                                    if (remainingBuilds.length === 0) {
                                        location.reload();
                                    }
                                }, 300);
                            }
                        } else if (data && data.error) {
                            showNotification(data.error, 'error');
                        }
                    })
                    .catch(error => {
                        hideLoading();
                        console.error('Error:', error);
                        showNotification('Có lỗi xảy ra khi xóa cấu hình', 'error');
                    });

                closeDeleteModal();
            }
        }

        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'}"></i>
                <span>${message}</span>
            `;

            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? 'var(--success)' : type === 'error' ? 'var(--danger)' : type === 'warning' ? 'var(--warning)' : 'var(--info)'};
                color: white;
                padding: 1rem 1.5rem;
                border-radius: 8px;
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

            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
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

        // Performance optimization: Lazy load images if any
        document.addEventListener('DOMContentLoaded', function() {
            // Add intersection observer for better performance if needed
            const cards = document.querySelectorAll('.build-card');
            cards.forEach(card => {
                card.style.willChange = 'transform';
            });
        });
    </script>
@endsection
