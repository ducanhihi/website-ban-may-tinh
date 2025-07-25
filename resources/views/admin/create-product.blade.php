@extends('layout.app')

@section('content')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
    <main id="main" class="main">
        <!-- Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header mb-4">
                <div class="row align-items-center">
                    <div class="col-sm mb-2 mb-sm-0">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-no-gutter">
                                <li class="breadcrumb-item"><a class="breadcrumb-link text-decoration-none" href="ecommerce-products.html">Sản phẩm</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Thêm sản phẩm</li>
                            </ol>
                        </nav>

                        <h1 class="page-header-title">Thêm sản phẩm mới</h1>
                    </div>
                </div>
                <!-- End Row -->
            </div>
            <!-- End Page Header -->

            <form id="addProductForm" method="POST" action="/admin/create/product" enctype="multipart/form-data" class="needs-validation" novalidate>
                @csrf
                <div class="row">
                    <div class="col-lg-8">
                        <!-- Thông tin cơ bản -->
                        <div class="card mb-4 shadow-sm border-0 rounded-lg">
                            <!-- Header -->
                            <div class="card-header bg-white py-3">
                                <h4 class="card-header-title mb-0">
                                    <i class="fas fa-info-circle text-primary mr-2"></i>Thông tin cơ bản
                                </h4>
                            </div>
                            <!-- End Header -->

                            <!-- Body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <!-- Form Group -->
                                        <div class="form-group mb-4">
                                            <label for="productCodeLabel" class="input-label font-weight-bold">
                                                Mã sản phẩm <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light border-right-0">
                                                        <i class="fas fa-barcode text-muted"></i>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control border-left-0"
                                                       name="product_code" id="productCodeLabel"
                                                       placeholder="VD: SP001" required>
                                                <div class="invalid-feedback">Vui lòng nhập mã sản phẩm</div>
                                            </div>
                                            <small class="form-text text-muted">Mã sản phẩm dùng để phân biệt các sản phẩm</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <!-- Form Group -->
                                        <div class="form-group mb-4">
                                            <label for="productNameLabel" class="input-label font-weight-bold">
                                                Tên sản phẩm <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light border-right-0">
                                                        <i class="fas fa-tag text-muted"></i>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control border-left-0"
                                                       name="name" id="productNameLabel"
                                                       placeholder="Nhập tên sản phẩm" required>
                                                <div class="invalid-feedback">Vui lòng nhập tên sản phẩm</div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Form Group -->
                                </div>
                                <!-- End Form Group -->

                                <div class="row">
                                    <div class="col-sm-6">
                                        <!-- Form Group -->
                                        <div class="form-group mb-4">
                                            <label for="categoryLabel" class="input-label font-weight-bold">Thể loại</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light border-right-0">
                                                        <i class="fas fa-folder text-muted"></i>
                                                    </span>
                                                </div>
                                                <input type="text" value="{{ $category->name ?? '' }}" disabled
                                                       class="form-control border-left-0 bg-light" id="categoryLabel" />
                                                <input type="hidden" name="category_id" value="{{ $categoryId }}">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Form Group -->

                                    <div class="col-sm-6">
                                        <!-- Form Group -->
                                        <div class="form-group mb-4">
                                            <label for="brandSelect" class="input-label font-weight-bold">
                                                Thương hiệu <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light border-right-0">
                                                        <i class="fas fa-copyright text-muted"></i>
                                                    </span>
                                                </div>
                                                <select class="form-control custom-select border-left-0"
                                                        id="brandSelect" name="brand_id" required>
                                                    <option value="">Chọn thương hiệu</option>
                                                    @foreach ($brandOptions as $brandId => $brandName)
                                                        <option value="{{ $brandId }}">{{ $brandName }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">Vui lòng chọn thương hiệu</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <!-- Form Group -->
                                        <div class="form-group mb-4">
                                            <label for="quantityLabel" class="input-label font-weight-bold">
                                                Số lượng <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light border-right-0">
                                                        <i class="fas fa-cubes text-muted"></i>
                                                    </span>
                                                </div>
                                                <input type="number" class="form-control border-left-0"
                                                       name="quantity" id="quantityLabel"
                                                       placeholder="VD: 100" min="0" required>
                                                <div class="invalid-feedback">Vui lòng nhập số lượng hợp lệ</div>
                                            </div>
                                        </div>
                                        <!-- End Form Group -->
                                    </div>

                                    <!-- Form Group -->
                                    <div class="col-sm-6">
                                        <div class="form-group mb-4">
                                            <label for="productPriceLabel" class="input-label font-weight-bold">
                                                Giá <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light border-right-0">
                                                        <i class="fas fa-money-bill-wave text-muted"></i>
                                                    </span>
                                                </div>
                                                <input type="number" class="form-control border-left-0"
                                                       name="price_out" id="productPriceLabel"
                                                       placeholder="VD: 199000" min="0" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text bg-light border-left-0">VNĐ</span>
                                                </div>
                                                <div class="invalid-feedback">Vui lòng nhập giá hợp lệ</div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-sm-6">
                                        <!-- Form Group -->
                                        <div class="form-group mb-4">
                                            <label for="productNameLabel" class="input-label font-weight-bold">
                                                Thông số <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light border-right-0">
                                                        <i class="fas fa text-muted"></i>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control border-left-0"
                                                       name="name" id="productNameLabel"
                                                       placeholder="Nhập chi tiết" required>
                                                <div class="invalid-feedback">Vui lòng nhập chi tiết</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Row -->




                                <div class="form-group">
                                    <label for="editor" class="input-label font-weight-bold">
                                        Mô tả sản phẩm <span class="text-muted">(Tùy chọn)</span>
                                    </label>
                                    <textarea id="editor" class="form-control" name="description"
                                              placeholder="Nhập mô tả chi tiết về sản phẩm..."></textarea>
                                </div>
                            </div>
                            <!-- Body -->
                        </div>
                        <!-- End Card -->
                    </div>

                    <div class="col-lg-4">
                        <!-- Card -->
                        <div class="card mb-4 shadow-sm border-0 rounded-lg">
                            <!-- Header -->
                            <div class="card-header bg-white py-3">
                                <h4 class="card-header-title mb-0">
                                    <i class="fas fa-image text-primary mr-2"></i>Hình ảnh
                                </h4>
                            </div>
                            <!-- Body -->
                            <div class="card-body">
                                <!-- Ảnh chính -->
                                <div class="form-group mb-4">
                                    <label class="input-label font-weight-bold d-block">
                                        Ảnh chính <span class="text-danger">*</span>
                                    </label>
                                    <div class="main-image-preview text-center mb-3 p-3 bg-light rounded">
                                        <img id="mainImagePreview" src="/placeholder.svg?height=200&width=200"
                                             class="img-fluid" style="max-height: 200px; object-fit: contain;">
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" name="image" class="custom-file-input"
                                               id="mainImageInput" accept="image/*" required
                                               onchange="previewMainImage(this)">
                                        <label class="custom-file-label" for="mainImageInput">Chọn ảnh chính</label>
                                        <div class="invalid-feedback">Vui lòng chọn ảnh chính cho sản phẩm</div>
                                    </div>
                                    <small class="form-text text-muted">
                                        Ảnh chính sẽ được hiển thị trong danh sách sản phẩm
                                    </small>
                                </div>

                                <!-- Ảnh chi tiết -->
                                <div class="form-group">
                                    <label class="input-label font-weight-bold d-block">
                                        Ảnh chi tiết <span class="text-muted">(Tùy chọn)</span>
                                    </label>

                                    <div id="detailImagesPreview" class="row mb-3">
                                        <!-- Preview images will be displayed here -->
                                    </div>

                                    <div class="custom-file mb-2">
                                        <input type="file" id="detailImagesInput" class="custom-file-input"
                                               accept="image/*" multiple onchange="handleDetailImages(this)">
                                        <label class="custom-file-label" for="detailImagesInput">Chọn ảnh chi tiết</label>
                                    </div>

                                    <div id="detailImagesContainer">
                                        <!-- Hidden inputs will be added here -->
                                    </div>

                                    <small class="form-text text-muted">
                                        Bạn có thể thêm nhiều ảnh chi tiết cho sản phẩm
                                    </small>
                                </div>
                            </div>
                            <!-- Body -->
                        </div>
                        <!-- End Card -->

                        <!-- Card -->
                        <div class="card mb-4 shadow-sm border-0 rounded-lg">
                            <div class="card-body">
                                <button type="submit" class="btn btn-primary btn-lg btn-block">
                                    <i class="fas fa-save mr-2"></i>Lưu sản phẩm
                                </button>
                                <a href="{{ route('admin.products') }}" class="btn btn-outline-secondary btn-block mt-2">
                                    <i class="fas fa-arrow-left mr-2"></i>Quay lại
                                </a>
                            </div>
                        </div>
                        <!-- End Card -->
                    </div>
                </div>
            </form>
            <!-- End Row -->
        </div>
        <!-- End Content -->
    </main>

    <script>
        // CKEditor
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });

        // Form validation
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var form = document.getElementById('addProductForm');
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            }, false);
        })();

        // Preview main image
        function previewMainImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('mainImagePreview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);

                // Update file label
                var fileName = input.files[0].name;
                input.nextElementSibling.innerHTML = fileName;
            }
        }

        // Handle detail images
        function handleDetailImages(input) {
            if (input.files && input.files.length > 0) {
                // Update file label with count
                input.nextElementSibling.innerHTML = input.files.length + ' ảnh đã chọn';

                // Clear previous previews
                var previewContainer = document.getElementById('detailImagesPreview');
                var inputContainer = document.getElementById('detailImagesContainer');

                // Generate previews
                for (var i = 0; i < input.files.length; i++) {
                    var file = input.files[i];
                    var reader = new FileReader();

                    reader.onload = (function(file, index) {
                        return function(e) {
                            // Create preview element
                            var col = document.createElement('div');
                            col.className = 'col-4 mb-2';

                            var img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'img-thumbnail w-100';
                            img.style = 'height: 80px; object-fit: cover;';
                            img.title = file.name;

                            col.appendChild(img);
                            previewContainer.appendChild(col);

                            // Create hidden input for the file
                            var hiddenInput = document.createElement('input');
                            hiddenInput.type = 'file';
                            hiddenInput.name = 'images[]';
                            hiddenInput.style.display = 'none';
                            hiddenInput.className = 'detail-image-input';

                            // Create a DataTransfer object and add the file
                            var dataTransfer = new DataTransfer();
                            dataTransfer.items.add(file);
                            hiddenInput.files = dataTransfer.files;

                            inputContainer.appendChild(hiddenInput);
                        };
                    })(file, i);

                    reader.readAsDataURL(file);
                }
            }
        }

        // Custom file input label
        document.querySelectorAll('.custom-file-input').forEach(function(input) {
            input.addEventListener('change', function(e) {
                var fileName = this.files[0].name;
                this.nextElementSibling.innerHTML = fileName;
            });
        });
    </script>

    <style>
        /* Improved form styling */
        .form-group label {
            margin-bottom: 0.5rem;
            color: #333;
        }

        .card {
            transition: all 0.2s ease;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        .input-group-text {
            border-color: #e2e8f0;
        }

        .form-control:focus {
            border-color: #4299e1;
            box-shadow: 0 0 0 0.2rem rgba(66, 153, 225, 0.25);
        }

        .custom-file-label {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .custom-file-input:focus ~ .custom-file-label {
            border-color: #4299e1;
            box-shadow: 0 0 0 0.2rem rgba(66, 153, 225, 0.25);
        }

        .btn-primary {
            background-color: #3182ce;
            border-color: #3182ce;
        }

        .btn-primary:hover {
            background-color: #2b6cb0;
            border-color: #2b6cb0;
        }

        /* Image preview styling */
        .main-image-preview {
            border: 1px dashed #cbd5e0;
            transition: all 0.2s ease;
        }

        .main-image-preview:hover {
            border-color: #4299e1;
        }

        #detailImagesPreview img {
            transition: transform 0.2s ease;
        }

        #detailImagesPreview img:hover {
            transform: scale(1.05);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .col-4 {
                flex: 0 0 33.333333%;
                max-width: 33.333333%;
            }
        }
    </style>
@endsection
