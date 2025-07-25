<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Customer App</title>


    <link href="{{ asset('css-customer/auth.css') }}" rel="stylesheet">
    <link href="{{ asset('css-customer/main-home.css') }}" rel="stylesheet">
    <link href="{{ asset('css-customer/cart.css') }}" rel="stylesheet">




</head>
<body>
<!-- Include Auth Modal trực tiếp -->
@include('customer.auth-modal')
@include('layout.CssCustomer ')

<div>
    @include('customer.header')
</div>
<div>
    @yield('content')
</div>
<div>
    @include('customer.footer')
</div>

<script src="{{ asset('js-customer/header.js') }}"></script>
<script src="{{ asset('js-customer/auth.js') }}"></script>
<script src="{{ asset('js-customer/main-home.js') }}"></script>  <!-- Tải main-home.js trước -->
<script src="{{ asset('js-customer/view-detail-updated.js') }}"></script>  <!-- Tải main-home.js trước -->
<script src="{{ asset('js-customer/cart.js') }}"></script>       <!-- Tải cart.js sau -->
<script src="{{ asset('js-customer/cart-handler.js') }}"></script>       <!-- Tải cart.js sau -->
<script>
    window.addEventListener("pageshow", function(event) {
        if (event.persisted) {
            // Reload lại nếu là trang từ cache (bfcache)
            window.location.reload();
        }
    });
</script>       <!-- Tải cart.js sau -->



</body>
</html>
