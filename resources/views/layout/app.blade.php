<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.datatables.net/v/bs5/dt-2.0.3/datatables.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.datatables.net/v/bs5/dt-2.0.3/datatables.min.js"></script>

    <!-- Thêm Font Awesome cho icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- CSS tùy chỉnh cho modal -->
    <style>
        /* CSS cho modal chọn danh mục */
        #chooseCategory .modal-content {
            overflow: hidden;
            transition: all 0.3s ease;
        }

        #chooseCategory .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
        }

        #chooseCategory .custom-select {
            height: calc(2.5rem + 2px);
            padding: 0.5rem 1rem;
            font-size: 1rem;
            background-position: right 0.75rem center;
        }

        #chooseCategory .btn {
            font-weight: 500;
            padding: 0.5rem 1.25rem;
            border-radius: 0.375rem;
            transition: all 0.2s ease-in-out;
        }

        #chooseCategory .modal-header,
        #chooseCategory .modal-footer {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }

        #chooseCategory .close:focus {
            outline: none;
        }

        #chooseCategory .close:hover {
            opacity: 1;
        }

        #chooseCategory label {
            font-size: 0.95rem;
        }

        #chooseCategory .tio-help-outlined {
            font-size: 0.85rem;
        }

        /* Thêm các class từ Tailwind CSS đang sử dụng */
        .bg-gradient-to-r.from-blue-50.to-blue-100 {
            background: linear-gradient(to right, #eff6ff, #dbeafe);
        }
        .text-gray-800 { color: #1f2937; }
        .text-gray-700 { color: #374151; }
        .text-gray-600 { color: #4b5563; }
        .text-blue-500 { color: #3b82f6; }
        .border-gray-300 { border-color: #d1d5db; }
        .bg-gray-50 { background-color: #f9fafb; }
        .bg-blue-500 { background-color: #3b82f6; }
        .border-blue-500 { border-color: #3b82f6; }
        .hover\:bg-blue-600:hover { background-color: #2563eb; }
        .hover\:border-blue-600:hover { border-color: #2563eb; }
        .hover\:bg-gray-100:hover { background-color: #f3f4f6; }
        .hover\:text-gray-800:hover { color: #1f2937; }
        .shadow-sm { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
        .shadow-lg { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }
        .rounded-lg { border-radius: 0.5rem; }
        .transition-colors { transition-property: color, background-color, border-color; transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); transition-duration: 150ms; }
        .py-3 { padding-top: 0.75rem; padding-bottom: 0.75rem; }
        .px-4 { padding-left: 1rem; padding-right: 1rem; }
        .py-4 { padding-top: 1rem; padding-bottom: 1rem; }
        .mr-2 { margin-right: 0.5rem; }
        .mb-4 { margin-bottom: 1rem; }
        .mb-2 { margin-bottom: 0.5rem; }
        .ml-1 { margin-left: 0.25rem; }
        .mt-2 { margin-top: 0.5rem; }
        .border-0 { border-width: 0; }
        .border-bottom-0 { border-bottom-width: 0; }
        .border-top-0 { border-top-width: 0; }
        .font-weight-bold { font-weight: 700; }
    </style>

    @include('layout.cssCRUD')
</head>
<body>
<div>
    @include('layout.header')
</div>
<div>
    @yield('content')
</div>
<div>
    @include('layout.sidebar')
</div>

<!-- Vendor JS Files -->
<script src="{{asset('/adminCSS/assets/vendor/apexcharts/apexcharts.min.js')}}"></script>
<script src="{{asset('/adminCSS/assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('/adminCSS/assets/vendor/chart.js/chart.umd.js')}}"></script>
<script src="{{asset('/adminCSS/assets/vendor/echarts/echarts.min.js')}}"></script>
<script src="{{asset('/adminCSS/assets/vendor/quill/quill.js')}}"></script>
<script src="{{asset('/adminCSS/assets/vendor/simple-datatables/simple-datatables.js')}}"></script>
<script src="{{asset('/adminCSS/assets/vendor/tinymce/tinymce.min.js')}}"></script>
<script src="{{asset('/adminCSS/assets/vendor/php-email-form/validate.js')}}"></script>

<!-- Template Main JS File -->
<script src="{{asset('/adminCSS/assets/js/main.js')}}"></script>
</body>
</html>
