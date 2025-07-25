<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandsController;
use App\Http\Controllers\BuildPcController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailsController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PcBuilderController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RevenueReportController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\UsersController;
use App\Models\Cart;
use App\Models\CartDetail;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/check-auth', function () {
    return response()->json([
        'authenticated' => auth()->check()
    ]);
});


Route::get('/', function () {
    return redirect('/customer/main-home');
});

Route::get('/admin/home', function () {
    return view('admin.home');
})->middleware('auth')->name('admin.home');

Route::get('/customer/home', function () {
    return view('customer.home');
})->name('customer.home');

Route::get('/login', [AuthController::class, 'viewLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'viewRegister'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

///product
Route::get('/admin/products', [ProductsController::class, 'viewAdminProducts'])->name('admin.products')->middleware('auth');
Route::get('/admin/create/product', [ProductsController::class, 'viewCreateProduct'])->name('admin.create-product')->middleware('auth');
Route::get('/admin/create/ram', [ProductsController::class, 'viewCreateRam'])->name('admin.create-ram')->middleware('auth');
Route::post('/admin/create/ram', [ProductsController::class, 'createRam'])->name('admin.create-ram-post')->middleware('auth');





Route::post('/admin/create/product', [ProductsController::class, 'createProduct'])->middleware('auth');
Route::delete('/home/product/{id}', [ProductsController::class, 'deleteProductById'])->middleware('auth');
Route::get('/admin/edit-product/{id}', [ProductsController::class, 'viewEditProduct'])->name('admin.edit-product')->middleware('auth');
Route::post('/admin/edit/product/{id}', [ProductsController::class, 'editProductById'])->middleware('auth');

// Trong file routes/web.php

//Route::post('/admin/create/category', 'YourController@storeCategory');


//category
Route::get('/admin/categories', [CategoriesController::class, 'viewAdminCategories'])->name('admin.categories')->middleware('auth');
Route::post('/admin/create/category', [CategoriesController::class, 'createCategory'])->middleware('auth');
Route::delete('/home/category/{id}', [CategoriesController::class, 'deleteCategoryById'])->middleware('auth');
Route::get('/admin/edit-category/{id}', [CategoriesController::class, 'viewEditCategory'])->name('admin.edit-category')->middleware('auth');
Route::post('/admin/edit/category/{id}', [CategoriesController::class, 'editCategoryById'])->middleware('auth');

Route::get('/admin/categories-brands', [CategoriesController::class, 'viewAdminCategoriesAndBrands'])->name('admin.categories-brands')->middleware('auth');

//brands
Route::get('/admin/brands', [BrandsController::class, 'viewAdminBrands'])->name('admin.brands')->middleware('auth');
Route::post('/admin/create/brand', [BrandsController::class, 'createBrand'])->middleware('auth');
Route::delete('/home/brand/{id}', [BrandsController::class, 'deleteBrandById'])->middleware('auth');
Route::get('/admin/edit-brand/{id}', [BrandsController::class, 'viewEditBrand'])->name('admin.edit-brand')->middleware('auth');
Route::post('/admin/edit/brand/{id}', [BrandsController::class, 'editBrandById'])->middleware('auth');

//orders-admin
Route::get('/admin/orders', [\App\Http\Controllers\OrderController::class, 'viewAdminOrders'])->name('admin.orders')->middleware('auth');
//tạo đơn hàng cho admin
Route::get('/admin/order/create', [\App\Http\Controllers\OrderController::class, 'showProducts'])->name('admin.create-order')->middleware('auth');
Route::post('/admin/order/save', [\App\Http\Controllers\OrderController::class, 'store'])->name('admin.order-save')->middleware('auth');



//user-admin
Route::get('/admin/users', [\App\Http\Controllers\UsersController::class, 'viewAdminUsers'])->name('admin.users')->middleware('auth');
// sua fil nay
Route::delete('/home/user/{id}', [UsersController::class, 'deleteUser'])->middleware('auth');


// Cart
//Route::get('/', [\App\Http\Controllers\CartController::class, 'viewCart'])->middleware('auth')->name('cart.viewCart');
Route::post('/add/{product}', [\App\Http\Controllers\CartController::class, 'add'])->middleware('auth')->name('cart.add');
Route::delete('/delete/{product}', [\App\Http\Controllers\CartController::class, 'delete'])->name('cart.delete')->middleware('auth');
Route::put('/customer/cart/update/{product}', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update')->middleware('auth');
Route::get('/clear', [\App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear')->middleware('auth');

//orders
Route::get('customer/order-detail', [OrderController::class, 'viewOrder'])->name('customer.order-detail')->middleware('auth');
Route::get('customer/order-save', [OrderController::class, 'newOrder'])->name('customer.order-save')->middleware('auth');
Route::post('customer/buy-now/{product_id}', [OrderController::class, 'buyNow'])->middleware('auth')->name('customer.buy-now');
Route::post('customer/buy-save/{product_id}', [OrderController::class, 'buySave'])->name('customer.buy-save')->middleware('auth');

Route::post('/customer/rate/{order_id}', [OrderController::class, 'rateOrder'])->name('customer.rate')->middleware('auth');


Route::get('customer/view-order-history', [\App\Http\Controllers\HistoryController::class, 'viewOrderHistory'])->middleware('auth')->name('customer.viewOrderHistory');
Route::get('customer/show-detai-history/{id}', [\App\Http\Controllers\HistoryController::class, 'showDetailHistory'])->middleware('auth')->name('customer.showDetailHistory');
Route::post('customer/buy-inCart', [OrderController::class, 'buyInCart'])->name('customer.buy-inCart')->middleware('auth');



//customer
Route::get('/customer/main-home', [ProductsController::class, 'showProducts'])->name('customer.main-home');
Route::get('/customer/view-detail/{id}', [ProductsController::class, 'viewDetailProduct'])->name('customer.view-detail');
Route::get('/customer/cart', [\App\Http\Controllers\CartController::class, 'viewCart'])->name('customer.cart')->middleware('auth');


// Route cho việc đánh giá đơn hàng
Route::post('/customer/rate/{order_id}', [OrderController::class, 'rateOrder'])->name('customer.rate')->middleware('auth');
// Route cho việc hoàn trả đơn hàng
Route::post('/customer/refund/{order_id}', [OrderController::class, 'refundOrder'])->name('customer.refund')->middleware('auth');




// order-details
Route::get('/admin/order-detail/{order_id}', [OrderDetailsController::class, 'viewOrderDetail'])->name('admin.order-detail')->middleware('auth');
Route::get('/customer/order-detail/{order_id}', [OrderDetailsController::class, 'customerOrderDetail'])->name('customer.order-detail')->middleware('auth');

//xem đơn hàng của customer
Route::get('/customer/view-orders', [OrderDetailsController::class, 'viewCustomerOrders'])->name('customer.view-orders')->middleware('auth');
Route::post('/admin/accept-order/{order_id}', [OrderController::class, 'acceptOrder'])->name('admin.accept')->middleware('auth');
Route::post('/customer/cancel-order2/{order_id}', [OrderController::class, 'cancelOrder'])->name('customer.cancel')->middleware('auth');
Route::post('/admin/update-order/{order_id}', [OrderController::class, 'updateOrder'])->name('admin.update-order')->middleware('auth');
Route::post('/admin/update-save/{order_id}', [OrderController::class, 'updateSave'])->name('admin.update-save')->middleware('auth');
Route::post('/customer/update-done/{order_id}', [OrderController::class, 'doneOrder'])->name('customer.done')->middleware('auth');






//admin hủy
Route::post('/admin/cancel-order/{order_id}', [OrderController::class, 'cancelOrder2'])->name('admin.cancel-order')->middleware('auth');

// thống kê
Route::get('admin/get-dashboard-data', [ProductsController::class,'getDashboardData'])->name('admin.get-dashboard-data')->middleware('auth');
// Thêm route để lấy dữ liệu dashboard qua AJAX
Route::get('/admin/get-dashboard-data', [App\Http\Controllers\ProductsController::class, 'getDashboardData'])->name('admin.get-dashboard-data')->middleware('auth');
// Thêm route để lấy dữ liệu doanh thu theo khoảng thời gian
Route::get('/admin/get-revenue-data/{period}', [App\Http\Controllers\ProductsController::class, 'getRevenueData'])->name('admin.get-revenue-data')->middleware('auth');
// Thê route để lấy dữ liệu đơn hàng theo khoảng thời gian
Route::get('/admin/get-order-data/{period}', [App\Http\Controllers\ProductsController::class, 'getOrderData'])->name('admin.get-order-data')->middleware('auth');


Route::post('/admin/order-detail/{order_id}', [OrderController::class, 'updateOrder'])->name('admin.update-order')->middleware('auth');
Route::post('/admin/order-detail/{order_id}', [OrderController::class, 'updateSave'])->name('admin.update-save')->middleware('auth');
Route::post('/admin/update-after-accept/{order_id}', [OrderController::class, 'my'])->name('admin.update-after-accept')->middleware('auth');
Route::post('/admin/update-save-after/{order_id}', [OrderController::class, 'updateAfterAccept'])->name('admin.update-save-after')->middleware('auth');

//thanh toán bằng VN-pay( nếu lỗi vào file buy-inCart call về route customer.order-save
Route::post('/vnpay_payment', [\App\Http\Controllers\PaymentController::class, 'vnpay_payment']);
Route::get('customer/vnpay-return', [PaymentController::class, 'vnpay_return'])->name('vnpay.return');


//giao hang
//Route::get('/admin/order-info', [\App\Http\Controllers\ShippingController::class, 'getOrderInfo'])->name('admin.order-info');

// Đường dẫn hiển thị form
Route::get('/admin/order-info', [ShippingController::class, 'showOrderForm'])->name('admin.order.form')->middleware('auth');

// Route nhận POST khi submit
Route::post('/admin/order-info', [ShippingController::class, 'getOrderInfo'])->name('admin.order-info')->middleware('auth');



// Route để hiển thị thông tin cá nhân
Route::get('customer/show', [UsersController::class, 'showProfile'])->name('customer.show')->middleware('auth');
//Route để cập nhật thông tin cá nhân
Route::post('customer/profile/update', [UsersController::class, 'updateProfile'])->name('profile.update')->middleware('auth');
Route::get('customer/password', [UsersController::class, 'showChangePassword'])->name('customer.password')->middleware('auth');
Route::post('customer/password/update', [UsersController::class, 'updatePassword'])->name('profile.password.update')->middleware('auth');



// Add this route for the AJAX endpoint
Route::get('/customer/order-details/{id}', [OrderController::class, 'getOrderDetails'])
    ->name('customer.order-details')
    ->middleware('auth');


Route::get('customer/revenue-report', [RevenueReportController::class, 'index'])->name('revenue.report')->middleware('auth');
// routes/web.php
Route::post('customer/revenue-report/close', [RevenueReportController::class, 'closeRevenue'])->name('revenue.close');







Route::get('/get-cart-count', function () {
    // Lấy giỏ hàng của người dùng
    $cart = Cart::where('user_id', auth()->user()->id)->first();

    if ($cart) {
        // Tính tổng số lượng sản phẩm trong giỏ hàng
        $cartCount = CartDetail::where('cart_id', $cart->id)->sum('quantity');
    } else {
        $cartCount = 0;
    }

    return response()->json(['cartCount' => $cartCount]);
});



// Product routes
Route::get('/product/{id}', [ProductsController::class, 'show'])->name('product.detail');
Route::post('/product/{id}/review', [ProductsController::class, 'submitReview'])->name('product.review');

// Admin routes
Route::prefix('admin')->middleware(['auth'])->group(function () {
    // Dashboard routes
    Route::get('/dashboard', [ProductsController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/get-dashboard-data', [ProductsController::class, 'getDashboardDataAjax'])->name('admin.get-dashboard-data-ajax');

    // Existing admin routes...

    // Product Statistics Routes
    Route::get('/product-statistics', [ProductsController::class, 'viewProductStatistics'])->name('admin.product-statistics');
    Route::get('/export-product-statistics', [ProductsController::class, 'exportProductStatistics'])->name('admin.export-product-statistics');
    Route::get('/product-statistics/details/{id}', [ProductsController::class, 'getProductDetails'])->name('admin.product-statistics-details');
});

// Thêm route mới cho mark as received
Route::post('/customer/order/{orderId}/mark-received', [OrderDetailsController::class, 'markAsReceived'])->name('customer.mark-received');


//// Route cho thanh toán VNPay
Route::post('/vnpay-payment', [App\Http\Controllers\PaymentController::class, 'vnpay_payment'])->name('vnpay.payment');
Route::get('/vnpay-return', [App\Http\Controllers\PaymentController::class, 'vnpay_return'])->name('vnpay.return');
// Route cho thanh toán VNPay (đơn hàng đã tồn tại)
Route::post('/vnpay-payment-existing', [App\Http\Controllers\PaymentController::class, 'vnpay_payment_existing_order'])->name('vnpay.payment.existing');
Route::get('/vnpay-return-existing', [App\Http\Controllers\PaymentController::class, 'vnpay_return_existing_order'])->name('vnpay.return.existing');




// PC Builder Routes - Updated to match your file structure
Route::prefix('pc-builder')->name('pc-builder.')->middleware('auth')->group(function () {
    // Change from 'index' to 'main-builder-pc' to match your blade file name
    Route::get('/', [PcBuilderController::class, 'index'])->name('index');

    // Or if you want to keep the current route name, change it to:
    Route::get('/main-builder-pc', [PcBuilderController::class, 'index'])->name('main-builder-pc');

    Route::get('/products', [PcBuilderController::class, 'getProducts'])->name('products');
    Route::post('/add-to-session', [PcBuilderController::class, 'addToSession'])->name('add-to-session');
    Route::post('/remove-from-session', [PcBuilderController::class, 'removeFromSession'])->name('remove-from-session');
    Route::post('/save-build', [PcBuilderController::class, 'saveBuild'])->name('save-build');
    Route::post('/add-to-cart', [PcBuilderController::class, 'addToCart'])->name('add-to-cart');
    Route::post('/clear-build', [PcBuilderController::class, 'clearBuild'])->name('clear-build');
    Route::get('/check-compatibility', [PcBuilderController::class, 'checkCompatibility'])->name('check-compatibility');

    // Authenticated routes
    Route::middleware('auth')->group(function () {
        // Change to match your blade file name
        Route::get('/my-builds', [PcBuilderController::class, 'myBuilds'])->name('my-builds');
        Route::get('/load-build/{id}', [PcBuilderController::class, 'loadBuild'])->name('load-build');
        Route::delete('/delete-build/{id}', [PcBuilderController::class, 'deleteBuild'])->name('delete-build');
    });
});

// New dynamic routes
Route::get('/admin/create/product-dynamic', [ProductsController::class, 'viewCreateProductDynamic'])->name('admin.create-product-dynamic')->middleware('auth');
Route::post('/admin/create/product-dynamic', [ProductsController::class, 'createProductDynamic'])->name('admin.create-product-dynamic-post')->middleware('auth');
Route::get('/admin/edit-product-dynamic/{id}', [ProductsController::class, 'viewEditProductDynamic'])->name('admin.edit-product-dynamic')->middleware('auth');
Route::post('/admin/edit-product-dynamic/{id}', [ProductsController::class, 'editProductDynamic'])->name('admin.edit-product-dynamic-post')->middleware('auth');
Route::post('/admin/check-product-code', [ProductsController::class, 'checkProductCode'])->name('admin.check-product-code');

// Thêm route này vào file routes/web.php
Route::get('/categories/{id}', function($id) {
    $category = DB::table('categories')->find($id);
    return response()->json($category);
});
// Thêm các route này vào file routes/web.php

// Route cập nhật số lượng sản phẩm trong session
Route::post('/pc-builder/update-quantity', [PcBuilderController::class, 'updateQuantity'])->name('pc-builder.update-quantity')->middleware('auth');

// Route cập nhật thông tin build
Route::post('/pc-builder/update-build/{id}', [PcBuilderController::class, 'updateBuild'])->name('pc-builder.update-build')->middleware('auth');

// Route xóa build
Route::delete('/pc-builder/delete-build/{id}', [PcBuilderController::class, 'deleteBuild'])->name('pc-builder.delete-build')->middleware('auth');

// Route thêm build vào giỏ hàng
Route::post('/pc-builder/add-build-to-cart/{id}', [PcBuilderController::class, 'addBuildToCart'])->name('pc-builder.add-build-to-cart')->middleware('auth');

// Route tải build
Route::post('/pc-builder/load-build/{id}', [PcBuilderController::class, 'loadBuild'])->middleware('auth')->name('pc-builder.load-build');
// PC Builder routes
Route::prefix('pc-builder')->name('pc-builder.')->group(function () {
    Route::get('/', [PcBuilderController::class, 'index'])->name('main-builder-pc');
    Route::get('/products', [PcBuilderController::class, 'getProducts'])->name('products');
    Route::post('/add-to-session', [PcBuilderController::class, 'addToSession'])->name('add-to-session');
});



// Existing routes...

// PC Builder routes
Route::prefix('pc-builder')->name('pc-builder.')->middleware('auth')->group(function () {
    Route::get('/', [PcBuilderController::class, 'index'])->name('main-builder-pc');
    Route::get('/products', [PcBuilderController::class, 'getProducts'])->name('products');
    Route::get('/spec-filters', [PcBuilderController::class, 'getSpecFilters'])->name('spec-filters');
    Route::post('/add-to-session', [PcBuilderController::class, 'addToSession'])->name('add-to-session');
    Route::post('/remove-from-session', [PcBuilderController::class, 'removeFromSession'])->name('remove-from-session');
    Route::post('/check-compatibility', [PcBuilderController::class, 'checkCompatibility'])->name('check-compatibility');
    Route::post('/save-build', [PcBuilderController::class, 'saveBuild'])->name('save-build');
    Route::post('/add-to-cart', [PcBuilderController::class, 'addToCart'])->name('add-to-cart');
    Route::post('/clear-build', [PcBuilderController::class, 'clearBuild'])->name('clear-build');
    Route::get('/my-builds', [PcBuilderController::class, 'myBuilds'])->name('my-builds');
    Route::get('/load-build/{id}', [PcBuilderController::class, 'loadBuild'])->name('load-build');
    Route::delete('/delete-build/{id}', [PcBuilderController::class, 'deleteBuild'])->name('delete-build');
});




// Utility routes
Route::get('/get-cart-count', function () {
    if (!auth()->check()) {
        return response()->json(['cartCount' => 0]);
    }

    $cart = Cart::where('user_id', auth()->id())->first();
    $cartCount = $cart ? CartDetail::where('cart_id', $cart->id)->sum('quantity') : 0;

    return response()->json(['cartCount' => $cartCount]);
});

Route::get('/check-auth', function () {
    return response()->json(['authenticated' => auth()->check()]);
});


Route::get('/vchat', [App\Http\Controllers\AuthController::class, 'indexvchat'])->name('vchat.index');



Route::middleware('auth')->group(function () {
    // Route để lấy chi tiết đơn hàng qua AJAX
    Route::get('/orders/{orderId}/details', [App\Http\Controllers\OrderController::class, 'getOrderDetails'])->middleware('auth');
});

// Search routes
Route::get('/search', [SearchController::class, 'index'])->name('customer.search');
Route::get('/search/results', [SearchController::class, 'searchByName'])->name('customer.search.results');
Route::get('/search/filter', [SearchController::class, 'filterProducts'])->name('customer.search.filter');
Route::get('/api/search', [SearchController::class, 'apiSearch'])->name('customer.search.api');


//Route::get('/admin/revenue-statistics', [RevenueReportController::class, 'index'])->name('admin.statistics');
//Route::get('/api/revenue-statistics', [RevenueReportController::class, 'getRevenueData'])->name('statistics.revenue');

//Route::get('/admin/revenue-statistics', [RevenueReportController::class, 'index'])->name('admin.statistics');
//Route::get('/api/revenue-statistics', [RevenueReportController::class, 'getRevenueData'])->name('statistics.revenue');
//Route::post('/api/compare-revenue', [RevenueReportController::class, 'compareRevenue'])->name('statistics.compare');


// Route cho trang thống kê
Route::get('/admin/revenue-statistics', [RevenueReportController::class, 'index'])->name('admin.statistics')->middleware('auth');
Route::get('/api/revenue-statistics', [RevenueReportController::class, 'getRevenueData'])->name('statistics.revenue')->middleware('auth');
Route::get('/api/top-products', [RevenueReportController::class, 'getTopProducts'])->name('statistics.top-products')->middleware('auth');
Route::post('/api/compare-revenue', [RevenueReportController::class, 'compareRevenue'])->name('statistics.compare')->middleware('auth');

// trang thống kê( trang chủ)
Route::get('/admin/home', [RevenueReportController::class, 'index'])->name('admin.home')->middleware('auth');
Route::get('/api/revenue-statistics', [RevenueReportController::class, 'getRevenueData'])->name('statistics.revenue')->middleware('auth');
Route::get('/api/top-products', [RevenueReportController::class, 'getTopProducts'])->name('statistics.top-products')->middleware('auth');
Route::post('/api/compare-revenue', [RevenueReportController::class, 'compareRevenue'])->name('statistics.compare')->middleware('auth');





// in hóa đơn
Route::get('/admin/order/{id}/print-invoice', [App\Http\Controllers\OrderDetailsController::class, 'printInvoice'])
    ->name('admin.print-invoice')
    ->middleware('auth');


