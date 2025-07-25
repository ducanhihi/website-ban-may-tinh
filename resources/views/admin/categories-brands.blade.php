@extends('layout.app')

@section('content')
    <main id="main" class="main">
        <!-- Phần tiêu đề trang -->
        <div class="pagetitle mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-1">Quản Lý Danh Mục & Thương Hiệu</h1>
                    <nav>
                        <ol class="breadcrumb bg-transparent p-0 mb-0">
                            <li class="breadcrumb-item"><a href="{{route('admin.home')}}" class="text-decoration-none">Home</a></li>
                            <li class="breadcrumb-item active">Danh Mục & Thương Hiệu</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div><!-- End Page Title -->

        <!-- Hai bảng hiển thị theo chiều ngang -->
        <div class="row">
            <!-- Bảng Thể Loại - Chiếm nửa bên trái -->
            <div class="col-md-6">
                <div class="col-lg-12 px-0">
                    <div class="card card-table border-0 shadow-sm">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 font-weight-bold text-gray-800">Danh sách thể loại</h5>
                            <div class="tools">
                                <div class="d-flex align-items-center">
                                    <div class="input-group mr-2">
                                        <input type="text" class="form-control form-control-sm border-right-0" placeholder="Tìm kiếm..." id="searchCategory">
                                        <div class="input-group-append">
                                            <span class="input-group-text bg-white border-left-0">
                                                <i class="fas fa-search text-gray-400"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <a href="#addCategoryModal" class="btn btn-dark btn-sm d-flex align-items-center" data-toggle="modal">
                                        <i class="fas fa-plus-circle mr-1"></i>
                                        <span>Thêm</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="category_table" data-page-length='10' class="table table-hover table-striped">
                                    <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">ID</th>
                                        <th class="text-center">Tên thể loại</th>
                                        <th class="text-center">Ngày tạo</th>
                                        <th class="text-center">Cập nhật</th>
                                        <th class="text-center">Hành động</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($allCategories as $category)
                                        <tr>
                                            <td class="text-center align-middle">{{$category->id}}</td>
                                            <td class="text-center align-middle font-weight-bold">{{$category->name}}</td>
                                            <td class="text-center align-middle">{{$category->created_at}}</td>
                                            <td class="text-center align-middle">{{$category->updated_at}}</td>
                                            <td class="text-center align-middle">
                                                <div class="btn-group">
                                                    <button class="btn btn-sm btn-outline-primary mr-1 edit-category-btn"
                                                            data-id="{{$category->id}}"
                                                            data-name="{{$category->name}}"
                                                            data-toggle="tooltip"
                                                            title="Chỉnh sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <a href="#deleteCategoryModal"
                                                       data-id="{{$category->id}}"
                                                       class="btn btn-sm btn-outline-danger delete-category"
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
                                            <td colspan="5" class="text-center py-5">
                                                <div class="text-center text-muted">
                                                    <i class="fas fa-folder-open fa-3x mb-3"></i>
                                                    <p>Không có thể loại nào</p>
                                                    <a href="#addCategoryModal" class="btn btn-sm btn-outline-primary" data-toggle="modal">
                                                        Thêm thể loại mới
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
                                    <span class="text-muted">Hiển thị <span id="showing-entries-category">5</span> trên tổng số {{count($allCategories)}} thể loại</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bảng Thương Hiệu - Chiếm nửa bên phải -->
            <div class="col-md-6">
                <div class="col-lg-12 px-0">
                    <div class="card card-table border-0 shadow-sm">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 font-weight-bold text-gray-800">Danh sách thương hiệu</h5>
                            <div class="tools">
                                <div class="d-flex align-items-center">
                                    <div class="input-group mr-2">
                                        <input type="text" class="form-control form-control-sm border-right-0" placeholder="Tìm kiếm..." id="searchBrand">
                                        <div class="input-group-append">
                                            <span class="input-group-text bg-white border-left-0">
                                                <i class="fas fa-search text-gray-400"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <a href="#addBrandModal" class="btn btn-dark btn-sm d-flex align-items-center" data-toggle="modal">
                                        <i class="fas fa-plus-circle mr-1"></i>
                                        <span>Thêm</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="brand_table" data-page-length='10' class="table table-hover table-striped">
                                    <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">ID</th>
                                        <th class="text-center">Tên thương hiệu</th>
                                        <th class="text-center">Ngày tạo</th>
                                        <th class="text-center">Cập nhật</th>
                                        <th class="text-center">Hành động</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($allBrands as $brand)
                                        <tr>
                                            <td class="text-center align-middle">{{$brand->id}}</td>
                                            <td class="text-center align-middle font-weight-bold">{{$brand->name}}</td>
                                            <td class="text-center align-middle">{{$brand->created_at}}</td>
                                            <td class="text-center align-middle">{{$brand->updated_at}}</td>
                                            <td class="text-center align-middle">
                                                <div class="btn-group">
                                                    <button class="btn btn-sm btn-outline-primary mr-1 edit-brand-btn"
                                                            data-id="{{$brand->id}}"
                                                            data-name="{{$brand->name}}"
                                                            data-toggle="tooltip"
                                                            title="Chỉnh sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <a href="#deleteBrandModal"
                                                       data-id="{{$brand->id}}"
                                                       class="btn btn-sm btn-outline-danger delete-brand"
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
                                            <td colspan="5" class="text-center py-5">
                                                <div class="text-center text-muted">
                                                    <i class="fas fa-tags fa-3x mb-3"></i>
                                                    <p>Không có thương hiệu nào</p>
                                                    <a href="#addBrandModal" class="btn btn-sm btn-outline-primary" data-toggle="modal">
                                                        Thêm thương hiệu mới
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
                                    <span class="text-muted">Hiển thị <span id="showing-entries-brand">5</span> trên tổng số {{count($allBrands)}} thương hiệu</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Category Modal -->
        <div id="addCategoryModal" class="modal fade" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-lg">
                    <form action="/admin/create/category" method="POST">
                        @csrf
                        <div class="modal-header bg-gradient-to-r from-blue-50 to-blue-100 border-bottom-0 py-3">
                            <h5 class="modal-title font-weight-bold text-gray-800">
                                <i class="fas fa-folder-plus mr-2 text-blue-500"></i>Thêm Thể Loại
                            </h5>
                            <button type="button" class="close text-gray-600 hover:text-gray-800 transition-colors" data-dismiss="modal" aria-hidden="true">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="form-group mb-3">
                                <label for="categoryName" class="form-label fw-bold">Tên thể loại</label>
                                <input type="text" name="name" id="categoryName" class="form-control" required placeholder="Nhập tên thể loại">
                                <div class="form-text text-muted">Tên thể loại nên ngắn gọn và dễ nhớ.</div>
                            </div>
                        </div>
                        <div class="modal-footer bg-gray-50 border-top-0 py-3">
                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                                <i class="fas fa-times mr-1"></i>Hủy
                            </button>
                            <button type="submit" class="btn bg-black border-black hover:bg-gray-800 hover:border-gray-800 text-white transition-colors">
                                <i class="fas fa-save mr-1"></i>Lưu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Category Modal -->
        <div id="editCategoryModal" class="modal fade" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-lg">
                    <form id="editCategoryForm" method="POST">
                        @csrf
                        <div class="modal-header bg-gradient-to-r from-green-50 to-green-100 border-bottom-0 py-3">
                            <h5 class="modal-title font-weight-bold text-gray-800">
                                <i class="fas fa-edit mr-2 text-green-500"></i>Chỉnh Sửa Thể Loại
                            </h5>
                            <button type="button" class="close text-gray-600 hover:text-gray-800 transition-colors" data-dismiss="modal" aria-hidden="true">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="form-group mb-3">
                                <label for="editCategoryName" class="form-label fw-bold">Tên thể loại</label>
                                <input type="text" name="name" id="editCategoryName" class="form-control" required placeholder="Nhập tên thể loại">
                                <div class="form-text text-muted">Tên thể loại nên ngắn gọn và dễ nhớ.</div>
                            </div>
                        </div>
                        <div class="modal-footer bg-gray-50 border-top-0 py-3">
                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                                <i class="fas fa-times mr-1"></i>Hủy
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save mr-1"></i>Cập Nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Add Brand Modal -->
        <div id="addBrandModal" class="modal fade" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-lg">
                    <form action="/admin/create/brand" method="POST">
                        @csrf
                        <div class="modal-header bg-gradient-to-r from-blue-50 to-blue-100 border-bottom-0 py-3">
                            <h5 class="modal-title font-weight-bold text-gray-800">
                                <i class="fas fa-tag mr-2 text-blue-500"></i>Thêm Thương Hiệu
                            </h5>
                            <button type="button" class="close text-gray-600 hover:text-gray-800 transition-colors" data-dismiss="modal" aria-hidden="true">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="form-group mb-3">
                                <label for="brandName" class="form-label fw-bold">Tên thương hiệu</label>
                                <input type="text" name="name" id="brandName" class="form-control" required placeholder="Nhập tên thương hiệu">
                                <div class="form-text text-muted">Tên thương hiệu nên chính xác như tên chính thức.</div>
                            </div>
                        </div>
                        <div class="modal-footer bg-gray-50 border-top-0 py-3">
                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                                <i class="fas fa-times mr-1"></i>Hủy
                            </button>
                            <button type="submit" class="btn bg-black border-black hover:bg-gray-800 hover:border-gray-800 text-white transition-colors">
                                <i class="fas fa-save mr-1"></i>Lưu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Brand Modal -->
        <div id="editBrandModal" class="modal fade" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-lg">
                    <form id="editBrandForm" method="POST">
                        @csrf
                        <div class="modal-header bg-gradient-to-r from-green-50 to-green-100 border-bottom-0 py-3">
                            <h5 class="modal-title font-weight-bold text-gray-800">
                                <i class="fas fa-edit mr-2 text-green-500"></i>Chỉnh Sửa Thương Hiệu
                            </h5>
                            <button type="button" class="close text-gray-600 hover:text-gray-800 transition-colors" data-dismiss="modal" aria-hidden="true">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="form-group mb-3">
                                <label for="editBrandName" class="form-label fw-bold">Tên thương hiệu</label>
                                <input type="text" name="name" id="editBrandName" class="form-control" required placeholder="Nhập tên thương hiệu">
                                <div class="form-text text-muted">Tên thương hiệu nên chính xác như tên chính thức.</div>
                            </div>
                        </div>
                        <div class="modal-footer bg-gray-50 border-top-0 py-3">
                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                                <i class="fas fa-times mr-1"></i>Hủy
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save mr-1"></i>Cập Nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Category Modal -->
        <div id="deleteCategoryModal" class="modal fade">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <form action="/home/category/" method="POST" id="deleteCategoryForm">
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
                            <p class="mb-3">Bạn có chắc chắn muốn xóa thể loại này?</p>
                            <p class="text-danger mb-0"><small>Hành động này không thể hoàn tác và có thể ảnh hưởng đến các sản phẩm liên quan.</small></p>
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
                </div>
            </div>
        </div>

        <!-- Delete Brand Modal -->
        <div id="deleteBrandModal" class="modal fade">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <form action="/home/brand/" method="POST" id="deleteBrandForm">
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
                            <p class="mb-3">Bạn có chắc chắn muốn xóa thương hiệu này?</p>
                            <p class="text-danger mb-0"><small>Hành động này không thể hoàn tác và có thể ảnh hưởng đến các sản phẩm liên quan.</small></p>
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
                </div>
            </div>
        </div>

    </main>

    <script>
        $(document).ready(function() {
            // Khởi tạo DataTable cho bảng thể loại
            let categoryTable = new DataTable('#category_table', {
                pageLength: 5,
                responsive: true,
                language: {
                    search: "Tìm kiếm:",
                    lengthMenu: "Hiển thị _MENU_ mục",
                    info: "Hiển thị _START_ đến _END_ của _TOTAL_ mục",
                    infoEmpty: "Hiển thị 0 đến 0 của 0 mục",
                    infoFiltered: "(lọc từ _MAX_ mục)",
                    paginate: {
                        first: "Đầu",
                        last: "Cuối",
                        next: "Sau",
                        previous: "Trước"
                    }
                },
                drawCallback: function(settings) {
                    $('#showing-entries-category').text(settings._iDisplayLength);
                }
            });

            // Khởi tạo DataTable cho bảng thương hiệu
            let brandTable = new DataTable('#brand_table', {
                pageLength: 5,
                responsive: true,
                language: {
                    search: "Tìm kiếm:",
                    lengthMenu: "Hiển thị _MENU_ mục",
                    info: "Hiển thị _START_ đến _END_ của _TOTAL_ mục",
                    infoEmpty: "Hiển thị 0 đến 0 của 0 mục",
                    infoFiltered: "(lọc từ _MAX_ mục)",
                    paginate: {
                        first: "Đầu",
                        last: "Cuối",
                        next: "Sau",
                        previous: "Trước"
                    }
                },
                drawCallback: function(settings) {
                    $('#showing-entries-brand').text(settings._iDisplayLength);
                }
            });

            // Kết nối ô tìm kiếm tùy chỉnh với DataTable
            $('#searchCategory').keyup(function() {
                categoryTable.search($(this).val()).draw();
            });

            $('#searchBrand').keyup(function() {
                brandTable.search($(this).val()).draw();
            });

            // Xử lý chỉnh sửa thể loại
            $(document).on("click", ".edit-category-btn", function () {
                var categoryId = $(this).data('id');
                var categoryName = $(this).data('name');

                $('#editCategoryName').val(categoryName);
                $('#editCategoryForm').attr('action', '/admin/edit/category/' + categoryId);
                $('#editCategoryModal').modal('show');
            });

            // Xử lý chỉnh sửa thương hiệu
            $(document).on("click", ".edit-brand-btn", function () {
                var brandId = $(this).data('id');
                var brandName = $(this).data('name');

                $('#editBrandName').val(brandName);
                $('#editBrandForm').attr('action', '/admin/edit/brand/' + brandId);
                $('#editBrandModal').modal('show');
            });

            // Xử lý xóa thể loại
            $(document).on("click", ".delete-category", function () {
                var categoryId = $(this).data('id');
                $("#deleteCategoryForm").attr('action', '/home/category/' + categoryId);
            });

            // Xử lý xóa thương hiệu
            $(document).on("click", ".delete-brand", function () {
                var brandId = $(this).data('id');
                $("#deleteBrandForm").attr('action', '/home/brand/' + brandId);
            });

            // Khởi tạo tooltip
            $('[data-toggle="tooltip"]').tooltip();
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

        /* Cải thiện giao diện form */
        .form-control:focus {
            border-color: #000;
            box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0.1);
        }

        /* Cải thiện giao diện modal */
        .modal-content {
            border-radius: 0.5rem;
            overflow: hidden;
        }

        /* Gradient background cho header */
        .bg-gradient-to-r.from-blue-50.to-blue-100 {
            background: linear-gradient(to right, #eff6ff, #dbeafe);
        }

        .bg-gradient-to-r.from-green-50.to-green-100 {
            background: linear-gradient(to right, #f0fdf4, #dcfce7);
        }

        /* Đảm bảo chiều cao bằng nhau */
        @media (min-width: 768px) {
            .row {
                display: flex;
                flex-wrap: wrap;
            }
            .row > [class*='col-'] {
                display: flex;
                flex-direction: column;
            }
            .card {
                flex: 1;
            }
        }

        /* Cải thiện giao diện cho màn hình nhỏ */
        @media (max-width: 767.98px) {
            .col-md-6 {
                margin-bottom: 1.5rem;
            }
        }
    </style>
@endsection
