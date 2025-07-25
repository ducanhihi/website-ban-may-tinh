@extends('layout.app')
@section('content')
    <main id="main" class="main">
        <!-- Phần tiêu đề trang -->
        <div class="pagetitle mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>

                    <h1 class="mb-1">Quản Lý Sản Phẩm</h1>
                </div>
                <div>
                    <a href="{{ route('admin.create-ram') }}" class="btn btn-dark d-flex align-items-center">
                        <i class="fas fa-plus-circle mr-2"></i>
                        <span>Thêm sản phẩm</span>
                    </a>
                </div>
            </div>
        </div><!-- End Page Title -->


        <!-- Stats Cards -->
        <div class="stats-section">
            <div class="stats-cards">
                <div class="stat-card total">
                    <div class="stat-icon">
                        <i class="bi bi-box"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-number">{{ count($allProducts) }}</div>
                        <div class="stat-label">Tổng sản phẩm</div>
                        <div class="stat-progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
                <div class="stat-card in-stock">
                    <div class="stat-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-number">{{ $allProducts->where('quantity', '>', 0)->count() }}</div>
                        <div class="stat-label">Còn hàng</div>
                        <div class="stat-progress">
                            <div class="progress-bar" style="width: {{ count($allProducts) > 0 ? ($allProducts->where('quantity', '>', 0)->count() / count($allProducts) * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
                <div class="stat-card low-stock">
                    <div class="stat-icon">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-number">{{ $allProducts->where('quantity', '<=', 10)->where('quantity', '>', 0)->count() }}</div>
                        <div class="stat-label">Sắp hết</div>
                        <div class="stat-progress">
                            <div class="progress-bar" style="width: {{ count($allProducts) > 0 ? ($allProducts->where('quantity', '<=', 10)->where('quantity', '>', 0)->count() / count($allProducts) * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
                <div class="stat-card out-stock">
                    <div class="stat-icon">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-number">{{ $allProducts->where('quantity', 0)->count() }}</div>
                        <div class="stat-label">Hết hàng</div>
                        <div class="stat-progress">
                            <div class="progress-bar" style="width: {{ count($allProducts) > 0 ? ($allProducts->where('quantity', 0)->count() / count($allProducts) * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Card sản phẩm -->
        <div class="col-lg-12 px-0">
            <div class="card card-table border-0 shadow-sm">

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="products_table" data-page-length='10' class="table table-hover table-striped">
                            <thead class="thead-light">
                            <tr>
                                <th class="text-center">ID</th>
                                <th class="text-center">Mã SP</th>
                                <th class="text-center">Tên sản phẩm</th>
                                <th class="text-center">Ảnh</th>
                                <th class="text-center">Giá</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-center">Thể loại</th>
                                <th class="text-center">Thương hiệu</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($allProducts as $product)
                                <tr>
                                    <td class="text-center align-middle">{{$product->id }}</td>

                                    <td class="text-center align-middle">{{$product->product_code}}</td>
                                    <td class="text-center align-middle font-weight-bold">{{$product->name}}</td>
                                    <td class="text-center">
                                        <img src="{{ asset('image/'.$product->image) }}"
                                             alt="{{ $product->image }}"
                                             class="img-thumbnail"
                                             style="width: 80px; height: 60px; object-fit: cover;">
                                    </td>
                                    <td class="text-center align-middle">{{number_format($product->price_out, 0, ',', '.')}} đ</td>
                                    <td class="text-center align-middle">
                                        <span class="badge badge-pill {{ $product->quantity > 10 ? 'badge-success' : ($product->quantity > 0 ? 'badge-warning' : 'badge-danger') }} px-3 py-2">
                                            {{$product->quantity}}
                                        </span>
                                    </td>
                                    <td class="text-center align-middle">{{$product->category_name}}</td>
                                    <td class="text-center align-middle">{{$product->brand_name}}</td>
                                    <td class="text-center align-middle">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.edit-product-dynamic', ['id' => $product->id]) }}"
                                               class="btn btn-sm btn-outline-primary mr-1"
                                               data-toggle="tooltip"
                                               title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#deleteProductModal"
                                               data-id="{{$product->id}}"
                                               class="btn btn-sm btn-outline-danger delete"
                                               data-toggle="modal"
                                               data-toggle="tooltip"
                                               title="Xóa">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="text-center text-muted">
                                            <i class="fas fa-box-open fa-3x mb-3"></i>
                                            <p>Không có sản phẩm nào</p>
                                            <a href="{{ route('admin.create-ram') }}" class="btn btn-sm btn-outline-primary">
                                                Thêm sản phẩm mới
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted">Hiển thị <span id="showing-entries">10</span> trên tổng số {{count($allProducts)}} sản phẩm</span>
                        </div>
                        <!-- Phân trang sẽ được tự động thêm bởi DataTables -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Modal HTML -->
        <div id="deleteProductModal" class="modal fade">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    @if(isset($product) && $product)
                        <form action="/home/products/{{$product->id}}" method="POST">
                            @method('DELETE')
                            @csrf
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    Xác nhận xóa
                                </h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body p-4">
                                <p class="mb-3">Bạn có chắc chắn muốn xóa sản phẩm này?</p>
                                <p class="text-danger mb-0"><small>Hành động này không thể hoàn tác.</small></p>
                            </div>
                            <div class="modal-footer border-top-0">
                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                                    <i class="fas fa-times mr-1"></i>Hủy
                                </button>
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash-alt mr-1"></i>Xóa
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">Lỗi</h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Không tìm thấy sản phẩm để xóa.</p>
                        </div>
                        <div class="modal-footer border-top-0">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Modal Chọn Danh Mục -->
        <div id="chooseCategory" class="modal fade" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content border-0 shadow-lg rounded-lg">
                    <form action="{{route('admin.create-product')}}" method="GET">
                        @csrf
                        <div class="modal-header bg-gradient-to-r from-blue-50 to-blue-100 border-bottom-0 py-3">
                            <h5 class="modal-title font-weight-bold text-gray-800">
                                <i class="fas fa-folder-plus mr-2 text-blue-500"></i>Chọn Danh Mục
                            </h5>
                            <button type="button" class="close text-gray-600 hover:text-gray-800 transition-colors" data-dismiss="modal" aria-hidden="true">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body px-4 py-4">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group mb-4">
                                        <label class="input-label font-weight-bold text-gray-700 mb-2">
                                            Thể loại
                                            <i class="fas fa-question-circle text-muted ml-1"
                                               data-toggle="tooltip"
                                               data-placement="top"
                                               title="Chọn thể loại phù hợp với sản phẩm của bạn"></i>
                                        </label>
                                        <select class="form-control custom-select border-gray-300 rounded-lg shadow-sm"
                                                id="categorySelect"
                                                name="category_id"
                                                required>
                                            <option value="" class="text-muted">Chọn thể loại</option>
                                            @foreach ($categoryOptions as $categoryId => $categoryName)
                                                <option value="{{ $categoryId }}" data-href="{{ route('admin.create-product', ['category_id' => $categoryId]) }}">
                                                    {{ $categoryName }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted mt-2">Vui lòng chọn thể loại phù hợp với sản phẩm của bạn.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer bg-gray-50 border-top-0 py-3">
                            <button type="button"
                                    class="btn btn-outline-secondary px-4 mr-2 border-gray-300 text-gray-700 hover:bg-gray-100 transition-colors"
                                    data-dismiss="modal">
                                <i class="fas fa-times mr-1"></i>Hủy
                            </button>
                            <button type="submit" class="btn px-4 bg-black border-black hover:bg-gray-800 hover:border-gray-800 text-white transition-colors" id="chooseButton">
                                <i class="fas fa-check mr-1"></i>Chọn
                            </button>

                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>


            $(document).ready(function() {
                $('#categorySelect').change(function() {
                    // Lưu lại giá trị danh mục đã chọn
                    selectedCategoryId = $(this).val();
                });

                $('#chooseButton').click(function() {
                    // Lấy đường dẫn tương ứng với danh mục đã chọn
                    var selectedOption = $('#categorySelect').find('option:selected');
                    var url = selectedOption.data('href');

                    if (url) {
                        // Điều hướng đến đường dẫn tương ứng
                        window.location.href = url;
                    }
                });
            });

        </script>


    </main><!-- End #main -->

    <script>
        $(document).on("click", ".delete", function () {
            var productId = $(this).data('id');
            $("#deleteProductModal form").attr('action', '/home/product/' + productId);
        });
    </script>

    <script>
        $(document).ready(function() {
            // Khởi tạo DataTable với các tùy chọn
            const dataTableConfig = {
                responsive: true,
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Tất cả"]],
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
                order: [[0, 'desc']]

            };
            let table = new DataTable('#products_table',dataTableConfig)

        });
    </script>

    <style>
        /* Cải thiện giao diện bảng */
        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }

        /* Cải thiện giao diện nút */
        .btn-outline-primary, .btn-outline-danger {
            border-width: 1px;
        }

        .btn-outline-primary:hover {
            background-color: rgba(0, 123, 255, 0.1);
            color: #007bff;
        }

        .btn-outline-danger:hover {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        /* Cải thiện giao diện card */
        .card {
            border-radius: 0.5rem;
            overflow: hidden;
        }

        /* Cải thiện giao diện ảnh */
        .img-thumbnail {
            transition: transform 0.2s;
            border-radius: 0.3rem;
        }

        .img-thumbnail:hover {
            transform: scale(1.05);
        }

        /* Cải thiện giao diện badge */
        .badge-pill {
            font-weight: 500;
            font-size: 0.8rem;
        }


        /* Stats Section */
        .stats-section {
            padding: 0 1rem;
            margin-bottom: 2rem;
        }

        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-card.total .stat-icon {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .stat-card.in-stock .stat-icon {
            background: linear-gradient(135deg, var(--success-color), #059669);
            color: white;
        }

        .stat-card.low-stock .stat-icon {
            background: linear-gradient(135deg, var(--warning-color), #d97706);
            color: white;
        }

        .stat-card.out-stock .stat-icon {
            background: linear-gradient(135deg, var(--danger-color), #dc2626);
            color: white;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--gray-900);
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--gray-600);
            font-weight: 500;
        }



        .new-status-badge.in-stock .status-icon {
            background: #10b981;
        }

        .new-status-badge.low-stock .status-icon {
            background: #f59e0b;
        }

        .new-status-badge.out-stock .status-icon {
            background: #ef4444;
        }



        .new-status-badge.in-stock .status-text {
            color: #065f46;
        }

        .new-status-badge.low-stock .status-text {
            color: #92400e;
        }

        .new-status-badge.out-stock .status-text {
            color: #991b1b;
        }


        .new-status-badge.in-stock .quantity-number {
            color: #10b981;
        }

        .new-status-badge.low-stock .quantity-number {
            color: #f59e0b;
        }

        .new-status-badge.out-stock .quantity-number {
            color: #ef4444;
        }


        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    <style>
        /* Modern Stats Cards */
        .stats-section {
            padding: 0 2rem;
            margin-bottom: 2rem;
        }

        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            max-width: 100%;
            margin: 0 auto;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.75rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            gap: 1.25rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 6px;
            height: 100%;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .stat-icon {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            transition: all 0.3s ease;
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1);
        }

        .stat-card.total::before {
            background: linear-gradient(to bottom, #6366f1, #8b5cf6);
        }

        .stat-card.in-stock::before {
            background: linear-gradient(to bottom, #10b981, #059669);
        }

        .stat-card.low-stock::before {
            background: linear-gradient(to bottom, #f59e0b, #d97706);
        }

        .stat-card.out-stock::before {
            background: linear-gradient(to bottom, #ef4444, #dc2626);
        }

        .stat-card.total .stat-icon {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(139, 92, 246, 0.1));
            color: #6366f1;
        }

        .stat-card.in-stock .stat-icon {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1));
            color: #10b981;
        }

        .stat-card.low-stock .stat-icon {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(217, 119, 6, 0.1));
            color: #f59e0b;
        }

        .stat-card.out-stock .stat-icon {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.1));
            color: #ef4444;
        }

        .stat-info {
            flex: 1;
        }

        .stat-number {
            font-size: 2.25rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            line-height: 1.2;
            background: linear-gradient(to right, #1f2937, #4b5563);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .stat-card.total .stat-number {
            background: linear-gradient(to right, #6366f1, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .stat-card.in-stock .stat-number {
            background: linear-gradient(to right, #10b981, #059669);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .stat-card.low-stock .stat-number {
            background: linear-gradient(to right, #f59e0b, #d97706);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .stat-card.out-stock .stat-number {
            background: linear-gradient(to right, #ef4444, #dc2626);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .stat-label {
            font-size: 0.95rem;
            color: #6b7280;
            font-weight: 500;
            margin-bottom: 0.75rem;
        }

        .stat-progress {
            height: 6px;
            background: #f3f4f6;
            border-radius: 3px;
            overflow: hidden;
            margin-top: 0.5rem;
        }

        .stat-progress .progress-bar {
            height: 100%;
            border-radius: 3px;
            transition: width 1s ease-in-out;
        }

        .stat-card.total .stat-progress .progress-bar {
            background: linear-gradient(to right, #6366f1, #8b5cf6);
        }

        .stat-card.in-stock .stat-progress .progress-bar {
            background: linear-gradient(to right, #10b981, #059669);
        }

        .stat-card.low-stock .stat-progress .progress-bar {
            background: linear-gradient(to right, #f59e0b, #d97706);
        }

        .stat-card.out-stock .stat-progress .progress-bar {
            background: linear-gradient(to right, #ef4444, #dc2626);
        }

        /* Animation khi load */
        .stat-card {
            animation: fadeInUp 0.5s ease-out forwards;
            opacity: 0;
        }

        .stat-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .stat-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .stat-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .stat-card:nth-child(4) {
            animation-delay: 0.4s;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .stats-section {
                padding: 0 1rem;
            }

            .stats-cards {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 1rem;
            }

            .stat-card {
                padding: 1.25rem;
                gap: 1rem;
            }

            .stat-icon {
                width: 50px;
                height: 50px;
                font-size: 1.5rem;
            }

            .stat-number {
                font-size: 1.75rem;
            }

            .stat-label {
                font-size: 0.85rem;
                margin-bottom: 0.5rem;
            }
        }
    </style>




@endsection
