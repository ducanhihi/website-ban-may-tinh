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

            <form id="addProductForm" method="POST" action="{{ route('admin.create-product-dynamic-post') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
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
                                            <label for="categorySelect" class="input-label font-weight-bold">
                                                Thể loại <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light border-right-0">
                                                        <i class="fas fa-folder text-muted"></i>
                                                    </span>
                                                </div>
                                                <select class="form-control custom-select border-left-0"
                                                        id="categorySelect" name="category_id" required>
                                                    <option value="">Chọn thể loại</option>
                                                    @foreach ($categoryOptions as $categoryId => $categoryName)
                                                        <option value="{{ $categoryId }}" {{ request('category_id') == $categoryId ? 'selected' : '' }}>
                                                            {{ $categoryName }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">Vui lòng chọn thể loại</div>
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

                        <!-- Thông số kỹ thuật -->
                        <div class="card mb-4 shadow-sm border-0 rounded-lg">
                            <!-- Header -->
                            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                                <h4 class="card-header-title mb-0">
                                    <i class="fas fa-cogs text-primary mr-2"></i>Thông số kỹ thuật
                                </h4>
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="addSpecification()">
                                    <i class="fas fa-plus mr-1"></i>Thêm thông số
                                </button>
                            </div>
                            <!-- End Header -->

                            <!-- Body -->
                            <div class="card-body">
                                <div id="specificationsContainer">
                                    <!-- Default specifications will be added here -->
                                </div>

                                <div class="text-center mt-3" id="noSpecsMessage">
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Chưa có thông số nào. Nhấn "Thêm thông số" để bắt đầu.
                                    </p>
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

                                    <!-- Khu vực preview -->
                                    <div id="detailImagesPreview" class="row mb-3"></div>

                                    <!-- Input ẩn để người dùng chọn file -->
                                    <input type="file" id="detailImagesInput" name="images[]" accept="image/*" multiple>
                                    <label class="btn btn-secondary mb-2" for="detailImagesInput">Chọn ảnh chi tiết</label>

                                    {{--                                    <!-- Input thực sự để Laravel nhận -->--}}
                                    {{--                                    <input type="file" id="realDetailImagesInput" name="images[]" multiple hidden>--}}

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
        let specificationCounter = 0;

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

        // Add specification function
        function addSpecification(title = '', content = '') {
            const container = document.getElementById('specificationsContainer');
            const noMessage = document.getElementById('noSpecsMessage');

            // Hide no specs message
            if (noMessage) {
                noMessage.style.display = 'none';
            }

            const specDiv = document.createElement('div');
            specDiv.className = 'specification-item mb-3 p-3 border rounded bg-light';
            specDiv.id = `spec-${specificationCounter}`;

            specDiv.innerHTML = `
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-2">
                            <label class="font-weight-bold text-sm">Tiêu đề thông số</label>
                            <input type="text"
                                   name="spec_titles[]"
                                   class="form-control form-control-sm"
                                   placeholder="VD: Dung lượng, Tốc độ..."
                                   value="${title}"
                                   required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <label class="font-weight-bold text-sm">Nội dung</label>
                            <input type="text"
                                   name="spec_contents[]"
                                   class="form-control form-control-sm"
                                   placeholder="VD: 16GB, 5600MHz..."
                                   value="${content}"
                                   required>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button"
                                class="btn btn-outline-danger btn-sm w-100"
                                onclick="removeSpecification(${specificationCounter})"
                                title="Xóa thông số này">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;

            container.appendChild(specDiv);
            specificationCounter++;

            // Add animation
            specDiv.style.opacity = '0';
            specDiv.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                specDiv.style.transition = 'all 0.3s ease';
                specDiv.style.opacity = '1';
                specDiv.style.transform = 'translateY(0)';
            }, 10);
        }

        // Remove specification function
        function removeSpecification(id) {
            const specDiv = document.getElementById(`spec-${id}`);
            const container = document.getElementById('specificationsContainer');
            const noMessage = document.getElementById('noSpecsMessage');

            if (specDiv) {
                // Add animation
                specDiv.style.transition = 'all 0.3s ease';
                specDiv.style.opacity = '0';
                specDiv.style.transform = 'translateY(-10px)';

                setTimeout(() => {
                    specDiv.remove();

                    // Show no specs message if no specifications left
                    if (container.children.length === 0 && noMessage) {
                        noMessage.style.display = 'block';
                    }
                }, 300);
            }
        }

        // Add default specifications based on category
        function addDefaultSpecifications() {
            const categorySelect = document.getElementById('categorySelect');
            const selectedCategory = categorySelect.options[categorySelect.selectedIndex].text.toLowerCase();

            // Clear existing specifications
            const container = document.getElementById('specificationsContainer');
            container.innerHTML = '';
            specificationCounter = 0;

            // Add default specs based on category
            if (selectedCategory.includes('ram')) {
                addSpecification('Dung lượng', '');
                addSpecification('Thế hệ', '');
                addSpecification('Tốc độ', '');
            } else if (selectedCategory.includes('cpu')) {
                addSpecification('Số nhân', '');
                addSpecification('Số luồng', '');
                addSpecification('Mẫu', '');
                addSpecification('Thế hệ', '');
            } else if (selectedCategory.includes('gpu')) {
                addSpecification('Bộ nhớ', '');
                addSpecification('Loại bộ nhớ', '');
                addSpecification('Tốc độ nhân', '');
            } else if (selectedCategory.includes('ssd') || selectedCategory.includes('hdd')) {
                addSpecification('Dung lượng', '');
                addSpecification('Giao tiếp', '');
                addSpecification('Tốc độ đọc', '');
                addSpecification('Tốc độ ghi', '');
            } else if (selectedCategory.includes('màn hình')) {
                addSpecification('Kích thước', '');
                addSpecification('Độ phân giải', '');
                addSpecification('Loại sản phẩm', '');
                addSpecification('Tỉ lệ màn hình', '');
            }
        }

        // Listen for category changes
        document.getElementById('categorySelect').addEventListener('change', function() {
            if (this.value) {
                addDefaultSpecifications();
            }
        });

        // Initialize with default specs if category is pre-selected
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('categorySelect');
            if (categorySelect.value) {
                addDefaultSpecifications();
            }
        });

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
        let selectedFiles = [];

        document.getElementById('detailImagesInput').addEventListener('change', function (e) {
            const files = Array.from(e.target.files);
            files.forEach(file => {
                if (!selectedFiles.find(f => f.name === file.name && f.size === file.size)) {
                    selectedFiles.push(file);
                }
            });

            this.value = ''; // reset input để có thể chọn lại
            renderDetailPreviews();
            updateRealInputFiles();
        });

        function renderDetailPreviews() {
            const container = document.getElementById('detailImagesPreview');
            container.innerHTML = '';

            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const col = document.createElement('div');
                    col.className = 'col-4 mb-2 position-relative';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-thumbnail w-100';
                    img.style = 'height: 80px; object-fit: cover;';

                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'btn btn-sm btn-danger position-absolute';
                    btn.style = 'top: 5px; right: 10px; border-radius: 50%; line-height: 1;';
                    btn.innerHTML = '&times;';
                    btn.onclick = () => {
                        selectedFiles.splice(index, 1);
                        renderDetailPreviews();
                        updateRealInputFiles();
                    };

                    col.appendChild(img);
                    col.appendChild(btn);
                    container.appendChild(col);
                };
                reader.readAsDataURL(file);
            });
        }

        function updateRealInputFiles() {
            const input = document.getElementById('detailImagesInput');
            const dataTransfer = new DataTransfer();

            selectedFiles.forEach(file => {
                dataTransfer.items.add(file);
            });

            input.files = dataTransfer.files;
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

        /* Specification styling */
        .specification-item {
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0 !important;
        }

        .specification-item:hover {
            border-color: #4299e1 !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .text-sm {
            font-size: 0.875rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .col-4 {
                flex: 0 0 33.333333%;
                max-width: 33.333333%;
            }

            .specification-item .row .col-md-2 {
                margin-top: 10px;
            }
        }
    </style>
    <script>
        let checkTimeout = null;

        document.getElementById('productCodeLabel').addEventListener('input', function () {
            clearTimeout(checkTimeout);

            const code = this.value;

            // Gọi lại sau 500ms nếu người dùng ngừng gõ
            checkTimeout = setTimeout(() => {
                if (!code.trim()) return;

                fetch('{{ route("admin.check-product-code") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ code: code })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            alert(`⚠️ Mã sản phẩm "${code}" đã tồn tại. Vui lòng nhập mã khác.`);
                            document.getElementById('productCodeLabel').value = '';
                            document.getElementById('productCodeLabel').focus();
                        }
                    });
            }, 500);
        });

    </script>
@endsection
