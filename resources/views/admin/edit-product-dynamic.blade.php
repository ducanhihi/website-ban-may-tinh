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
                                <li class="breadcrumb-item"><a class="breadcrumb-link text-decoration-none" href="{{ route('admin.products') }}">Sản phẩm</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Sửa sản phẩm</li>
                            </ol>
                        </nav>

                        <h1 class="page-header-title">Sửa sản phẩm: {{ $product->name }}</h1>
                    </div>
                </div>
                <!-- End Row -->
            </div>
            <!-- End Page Header -->

            <form id="editProductForm" method="POST" action="{{ route('admin.edit-product-dynamic-post', $product->id) }}" enctype="multipart/form-data" class="needs-validation" novalidate>
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
                                                       value="{{ $product->product_code }}" required>
                                                <div class="invalid-feedback">Vui lòng nhập mã sản phẩm</div>
                                            </div>
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
                                                       value="{{ $product->name }}" required>
                                                <div class="invalid-feedback">Vui lòng nhập tên sản phẩm</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

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
                                                        <option value="{{ $categoryId }}" {{ $product->category_id == $categoryId ? 'selected' : '' }}>
                                                            {{ $categoryName }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">Vui lòng chọn thể loại</div>
                                            </div>
                                        </div>
                                    </div>

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
                                                        <option value="{{ $brandId }}" {{ $product->brand_id == $brandId ? 'selected' : '' }}>
                                                            {{ $brandName }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">Vui lòng chọn thương hiệu</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-4">
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
                                                       value="{{ $product->quantity }}" min="0" required>
                                                <div class="invalid-feedback">Vui lòng nhập số lượng hợp lệ</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
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
                                                       value="{{ $product->price_out }}" min="0" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text bg-light border-left-0">VNĐ</span>
                                                </div>
                                                <div class="invalid-feedback">Vui lòng nhập giá hợp lệ</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group mb-4">
                                            <label for="discountLabel" class="input-label font-weight-bold">
                                                Giảm giá (%)
                                            </label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light border-right-0">
                                                        <i class="fas fa-percentage text-muted"></i>
                                                    </span>
                                                </div>
                                                <input type="number" class="form-control border-left-0"
                                                       name="discount_percent" id="discountLabel"
                                                       value="{{ $product->discount_percent }}" min="0" max="100">
                                                <div class="input-group-append">
                                                    <span class="input-group-text bg-light border-left-0">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="editor" class="input-label font-weight-bold">
                                        Mô tả sản phẩm <span class="text-muted">(Tùy chọn)</span>
                                    </label>
                                    <textarea id="editor" class="form-control" name="description"
                                              placeholder="Nhập mô tả chi tiết về sản phẩm...">{{ $product->description }}</textarea>
                                </div>
                            </div>
                        </div>

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

                            <!-- Body -->
                            <div class="card-body">
                                <div id="specificationsContainer">
                                    <!-- Existing specifications will be loaded here -->
                                </div>

                                <div class="text-center mt-3" id="noSpecsMessage" style="display: none;">
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Chưa có thông số nào. Nhấn "Thêm thông số" để bắt đầu.
                                    </p>
                                </div>
                            </div>
                        </div>
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
                                        Ảnh chính
                                    </label>
                                    <div class="main-image-preview text-center mb-3 p-3 bg-light rounded">
                                        <img id="mainImagePreview"
                                             src="{{ $product->image ? asset('image/' . $product->image) : '/placeholder.svg?height=200&width=200' }}"
                                             class="img-fluid" style="max-height: 200px; object-fit: contain;">
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" name="image" class="custom-file-input"
                                               id="mainImageInput" accept="image/*"
                                               onchange="previewMainImage(this)">
                                        <label class="custom-file-label" for="mainImageInput">
                                            {{ $product->image ? 'Thay đổi ảnh chính' : 'Chọn ảnh chính' }}
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">
                                        Để trống nếu không muốn thay đổi ảnh hiện tại
                                    </small>
                                </div>

                                <!-- Ảnh chi tiết hiện tại -->
                                @if($product->images && $product->images->count() > 0)
                                    <div class="form-group mb-4">
                                        <label class="input-label font-weight-bold d-block">
                                            Ảnh chi tiết hiện tại
                                        </label>
                                        <div class="row" id="currentImagesContainer">
                                            @foreach($product->images as $image)
                                                <div class="col-4 mb-2 position-relative" id="current-image-{{ $image->id }}">
                                                    {{-- FIX: Use uppercase URL --}}
                                                    <img src="{{ asset('image/' . $image->URL) }}"
                                                         class="img-thumbnail w-100"
                                                         style="height: 80px; object-fit: cover;"
                                                         title="{{ $image->URL }}">
                                                    <button type="button"
                                                            class="btn btn-danger btn-sm position-absolute"
                                                            style="top: 5px; right: 15px; padding: 2px 6px;"
                                                            onclick="markImageForDeletion({{ $image->id }})"
                                                            title="Xóa ảnh này">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Ảnh chi tiết mới -->
                                <div class="form-group">
                                    <label class="input-label font-weight-bold d-block">
                                        Thêm ảnh chi tiết mới <span class="text-muted">(Tùy chọn)</span>
                                    </label>

                                    <div id="detailImagesPreview" class="row mb-3">
                                        <!-- Preview images will be displayed here -->
                                    </div>

                                    <div class="custom-file mb-2">
                                        <input type="file" id="detailImagesInput" class="custom-file-input"
                                               name="images[]" accept="image/*" multiple onchange="previewDetailImages(this)">
                                        <label class="custom-file-label" for="detailImagesInput">Chọn ảnh chi tiết mới</label>
                                    </div>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="replaceAllImages" name="replace_all_images" value="1">
                                        <label class="form-check-label" for="replaceAllImages">
                                            Thay thế toàn bộ ảnh chi tiết hiện tại
                                        </label>
                                    </div>

                                    <small class="form-text text-muted">
                                        Mặc định sẽ thêm ảnh mới vào ảnh cũ. Tích vào ô trên để thay thế toàn bộ.
                                    </small>
                                </div>

                                <!-- Hidden inputs for images to delete -->
                                <div id="imagesToDeleteContainer"></div>
                            </div>
                        </div>

                        <!-- Card -->
                        <div class="card mb-4 shadow-sm border-0 rounded-lg">
                            <div class="card-body">
                                <button type="submit" class="btn btn-primary btn-lg btn-block">
                                    <i class="fas fa-save mr-2"></i>Cập nhật sản phẩm
                                </button>
                                <a href="{{ route('admin.products') }}" class="btn btn-outline-secondary btn-block mt-2">
                                    <i class="fas fa-arrow-left mr-2"></i>Quay lại
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <script>
        let specificationCounter = 0;
        let existingSpecs = @json($existingSpecs);
        let imagesToDelete = [];

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
                var form = document.getElementById('editProductForm');
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

        // Load existing specifications
        function loadExistingSpecifications() {
            const container = document.getElementById('specificationsContainer');
            const noMessage = document.getElementById('noSpecsMessage');

            if (existingSpecs && Object.keys(existingSpecs).length > 0) {
                Object.entries(existingSpecs).forEach(([title, content]) => {
                    addSpecification(title, content);
                });
                noMessage.style.display = 'none';
            } else {
                noMessage.style.display = 'block';
            }
        }

        // Initialize with existing specs
        document.addEventListener('DOMContentLoaded', function() {
            loadExistingSpecifications();
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

        // Preview detail images
        function previewDetailImages(input) {
            if (input.files && input.files.length > 0) {
                // Update file label with count
                input.nextElementSibling.innerHTML = input.files.length + ' ảnh đã chọn';

                // Clear previous previews
                var previewContainer = document.getElementById('detailImagesPreview');
                previewContainer.innerHTML = '';

                // Generate previews
                for (var i = 0; i < input.files.length; i++) {
                    var file = input.files[i];
                    var reader = new FileReader();

                    reader.onload = (function(file) {
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
                        };
                    })(file);

                    reader.readAsDataURL(file);
                }
            }
        }

        // Mark image for deletion
        function markImageForDeletion(imageId) {
            const imageElement = document.getElementById(`current-image-${imageId}`);
            if (imageElement) {
                // Add to deletion list
                imagesToDelete.push(imageId);

                // Create hidden input
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'delete_images[]';
                hiddenInput.value = imageId;
                document.getElementById('imagesToDeleteContainer').appendChild(hiddenInput);

                // Visual feedback
                imageElement.style.opacity = '0.5';
                imageElement.innerHTML += '<div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center" style="top: 0; left: 0; background: rgba(255,0,0,0.7); color: white; font-weight: bold;">SẼ XÓA</div>';

                // Disable the delete button
                const deleteBtn = imageElement.querySelector('button');
                if (deleteBtn) {
                    deleteBtn.disabled = true;
                    deleteBtn.onclick = null;
                }
            }
        }

        // Custom file input label
        document.querySelectorAll('.custom-file-input').forEach(function(input) {
            input.addEventListener('change', function(e) {
                if (this.files.length > 0) {
                    var fileName = this.files.length > 1 ?
                        this.files.length + ' files selected' :
                        this.files[0].name;
                    this.nextElementSibling.innerHTML = fileName;
                }
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

        /* Image deletion styling */
        .position-relative .btn-danger {
            z-index: 10;
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
@endsection
