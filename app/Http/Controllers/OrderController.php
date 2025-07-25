<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\CartDetail;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function viewCustomerOrders()
    {
        $userId = auth()->id();

        // Lấy đơn hàng theo từng trạng thái cho customer
        $pendingOrders = $this->getCustomerOrdersByStatus($userId, ['Chờ xác nhận']);
        $waitPaymentOrders = $this->getCustomerOrdersByStatus($userId, ['Chờ thanh toán']);
        $confirmedOrders = $this->getCustomerOrdersByStatus($userId, ['Đã xác nhận']);
        $shippingOrders = $this->getCustomerOrdersByStatus($userId, ['Đang giao']);
        $completedOrders = $this->getCustomerOrdersByStatus($userId, ['Đã giao']);
        $receivedOrders = $this->getCustomerOrdersByStatus($userId, ['Đã nhận hàng']);
        $canceledOrders = $this->getCustomerOrdersByStatus($userId, ['Đã hủy']);

        return view('customer.view-orders', compact(
            'pendingOrders',
            'waitPaymentOrders',
            'confirmedOrders',
            'shippingOrders',
            'completedOrders',
            'receivedOrders',
            'canceledOrders'
        ));
    }

    public function viewAdminOrders()
    {
        $pendingOrders = $this->getPendingOrders();
        $canceledOrders = $this->getCanceledOrders();
        $shippingOrders = $this->getShippingOrders();
        $completedOrders = $this->getCompletedOrders();
        $confirmedOrders = $this->getConfirmedOrders();
        $receivedOrders = $this->getReceivedOrders();
        $wait_payment = $this->getWaitPaymet();

        return view('admin.orders', compact(
            'pendingOrders',
            'canceledOrders',
            'shippingOrders',
            'completedOrders',
            'confirmedOrders',
            'receivedOrders',
            'wait_payment',
        ));
    }

    // Method mới để lấy đơn hàng của customer theo trạng thái
    protected function getCustomerOrdersByStatus($userId, $statuses)
    {
        return DB::table('orders')
            ->where('user_id', $userId)
            ->whereIn('status', $statuses)
            ->orderBy('order_date', 'desc')
            ->get();
    }

    public function getOrderDetails($orderId)
    {
        $userId = auth()->id();

        // Get order with details
        $order = DB::table('orders')
            ->where('id', $orderId)
            ->where('user_id', $userId)
            ->first();

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Get order items
        $orderItems = DB::table('orderdetails')
            ->join('products', 'orderdetails.product_id', '=', 'products.id')
            ->where('orderdetails.order_id', $orderId)
            ->select(
                'products.name',
                'orderdetails.quantity',
                'orderdetails.price',
                DB::raw('orderdetails.quantity * orderdetails.price as total')
            )
            ->get();

        $subtotal = $orderItems->sum('total');
        $shipping = 30000; // Fixed shipping fee
        $total = $subtotal + $shipping;

        return response()->json([
            'order' => $order,
            'items' => $orderItems,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total
        ]);
    }

    public function getOrderReviews($order_id, $user_id)
    {
        return DB::table('feedback')
            ->where('order_id', $order_id)
            ->where('user_id', $user_id)
            ->get();
    }

    public function show($order_id)
    {
        $order = Order::findOrFail($order_id);
        $orders = $order->items;
        $reviews = $this->getOrderReviews($order_id, auth()->id());

        return view('order-detail', compact('order', 'orders', 'reviews'));
    }

    public function rateOrder(Request $request, $order_id)
    {
        $user_id = auth()->id();
        $reviews = $request->input('reviews');

        if (empty($reviews)) {
            return redirect()->back()->with('error', 'Vui lòng cung cấp đánh giá cho sản phẩm.');
        }

        foreach ($reviews as $product_id => $review) {
            // Kiểm tra đánh giá theo user_id và product_id (không phụ thuộc order_id)
            $existingFeedback = DB::table('feedback')
                ->where('product_id', $product_id)
                ->where('user_id', $user_id)
                ->first();

            if (empty($review['rating']) && empty($review['comment'])) {
                return redirect()->back()->with('error', 'Vui lòng cung cấp ít nhất một đánh giá hoặc nhận xét cho sản phẩm ID: ' . $product_id);
            }

            if ($existingFeedback) {
                // Cập nhật đánh giá cũ
                DB::table('feedback')
                    ->where('id', $existingFeedback->id)
                    ->update([
                        'order_id' => $order_id, // Cập nhật order_id mới nhất
                        'rating' => $review['rating'] ?? $existingFeedback->rating,
                        'comment' => $review['comment'] ?? $existingFeedback->comment,
                        'updated_at' => now(),
                    ]);
            } else {
                // Tạo đánh giá mới
                DB::table('feedback')->insert([
                    'order_id' => $order_id,
                    'product_id' => $product_id,
                    'user_id' => $user_id,
                    'rating' => $review['rating'] ?? null,
                    'comment' => $review['comment'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Cảm ơn bạn đã đánh giá!');
    }

    public function showOrderDetails($order_id)
    {
        // Lấy thông tin đơn hàng
        $orders = Order::with('products')->where('order_id', $order_id)->get();

        // Lấy ID của người dùng đang đăng nhập
        $user_id = auth()->id();

        // Kiểm tra đánh giá đã tồn tại cho từng sản phẩm trong đơn hàng
        $feedbacks = DB::table('feedback')
            ->where('order_id', $order_id)
            ->where('user_id', $user_id)
            ->get(); // Lấy toàn bộ thông tin đánh giá

        // Tạo mảng chứa product_id đã được đánh giá để dễ kiểm tra
        $ratedProductIds = $feedbacks->pluck('product_id')->toArray();

        return view('customer.order-detail', compact('orders', 'feedbacks', 'ratedProductIds'));
    }

    // Sửa lại các method filter để đảm bảo chính xác
    protected function getPendingOrders()
    {
        return DB::table('orders')
            ->where('status', '=', 'Chờ xác nhận') // Sử dụng = thay vì whereIn
            ->orderBy('order_date', 'desc')
            ->get();
    }

    protected function getCanceledOrders()
    {
        return DB::table('orders')
            ->where('status', '=', 'Đã hủy')
            ->orderBy('order_date', 'desc')
            ->get();
    }

    protected function getShippingOrders()
    {
        return DB::table('orders')
            ->where('status', '=', 'Đang giao') // Đảm bảo chính xác trạng thái
            ->orderBy('order_date', 'desc')
            ->get();
    }

    protected function getCompletedOrders()
    {
        return DB::table('orders')
            ->where('status', '=', 'Đã giao')
            ->orderBy('order_date', 'desc')
            ->get();
    }

    protected function getConfirmedOrders()
    {
        return DB::table('orders')
            ->where('status', '=', 'Đã xác nhận')
            ->orderBy('order_date', 'desc')
            ->get();
    }

    protected function getReceivedOrders()
    {
        return DB::table('orders')
            ->where('status', '=', 'Đã nhận hàng')
            ->orderBy('order_date', 'desc')
            ->get();
    }

    protected function getWaitPaymet()
    {
        return DB::table('orders')
            ->where('status', '=', 'Chờ thanh toán')
            ->orderBy('order_date', 'desc')
            ->get();
    }

    public function getOrderStatusClass($status)
    {
        $classMap = [
            'Chờ xác nhận' => 'btn-warning',
            'Đã xác nhận' => 'btn-primary',
            'Đang giao' => 'btn-info',
            'Đã giao' => 'btn-success',
            'Xác nhận hủy' => 'btn-secondary',
            'Đã hủy' => 'btn-danger',
            'Đã nhận hàng' => 'btn-purple' // tạo class CSS btn-purple
        ];

        return $classMap[$status] ?? 'btn-default';
    }

    public function buyNow(Request $request, $product_id)
    {
        $product = DB::table('products')
            ->select('products.*')
            ->where('products.id', $product_id)
            ->first();

        $quantity = $request->input('quantity', 1);
        // Xử lý logic mua hàng tại đây

        return view('customer.buy-now', ['product' => $product, 'quantity' => $quantity]);
    }

    public function buySave(Request $request, $product_id)
    {
        $name = $request->get('name');
        $phone = $request->get('phone');
        $email = $request->get('email');
        $address = $request->get('address');
        $quantity = $request->input('quantity', 1);
        $payment = $request->input('payment');

        $user_id = auth()->user()->id;

        // Tạo đơn hàng mới
        $orderId = DB::table('orders')->insertGetId([
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'address' => $address,
            'order_date' => now(),
            'user_id' => $user_id,
            'payment' => $payment,
            'total' => 0, // Đặt tổng giá trị đơn hàng là 0 trước
        ]);

        $product = DB::table('products')
            ->select('products.*')
            ->where('products.id', $product_id)
            ->first();
        $totalAmount = $product->price_out * $quantity;

        // Cập nhật tổng giá trị đơn hàng
        DB::table('orders')
            ->where('id', $orderId)
            ->update(['total' => $totalAmount]);

        DB::table('orderdetails')->insert([
            'order_id' => $orderId,
            'product_id' => $product->id,
            'quantity' => $quantity,
            'price' => $product->price_out
        ]);

        $product = DB::table('products')
            ->select('products.*')
            ->where('products.id', $product_id)
            ->first();
        DB::table('products')
            ->where('id', $product->id)
            ->update(['quantity' => $product->quantity - $quantity]);

        // Xử lý logic mua hàng tại đây
        return view('customer.buy-now', ['product' => $product, 'quantity' => $quantity]);
    }

    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = "6UITGT2UZZ5TI5BEMKC197ZU2FYTRCC7";

        // Xử lý secure hash
        $inputData = $request->except('vnp_SecureHash');
        ksort($inputData);
        $hashData = [];
        foreach ($inputData as $key => $value) {
            if (substr($key, 0, 4) === "vnp_") {
                $hashData[] = urlencode($key) . "=" . urlencode($value);
            }
        }

        $secureHash = hash_hmac('sha512', implode('&', $hashData), $vnp_HashSecret);

        if ($secureHash === $request->input('vnp_SecureHash') && $request->input('vnp_ResponseCode') === '00') {
            $orderInfo = session('order_info');

            if (!$orderInfo) {
                flasher('Không tìm thấy thông tin đơn hàng!');
                return redirect()->route('customer.home');
            }

            DB::beginTransaction();
            try {
                // Tạo đơn hàng mới
                $order = Order::create([
                    'name' => $orderInfo['name'],
                    'phone' => $orderInfo['phone'],
                    'email' => $orderInfo['email'],
                    'address' => $orderInfo['address'],
                    'order_date' => now(),
                    'user_id' => $orderInfo['user_id'],
                    'total' => $orderInfo['total'],
                    'payment' => $orderInfo['payment']
                ]);

                // Lấy giỏ hàng và chi tiết
                $cart = Cart::with(['cartDetails.product'])
                    ->where('user_id', $orderInfo['user_id'])
                    ->first();

                if ($cart) {
                    foreach ($cart->cartDetails as $cartDetail) {
                        if (!in_array($cartDetail->product_id, $orderInfo['selected_products'])) {
                            continue;
                        }

                        $product = $cartDetail->product;
                        $finalPrice = $product->final_price;

                        // Kiểm tra tồn kho
                        if ($product->quantity < $cartDetail->quantity) {
                            throw new \Exception("Sản phẩm {$product->name} không đủ hàng.");
                        }

                        // Tạo chi tiết đơn hàng
                        OrderDetail::create([
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                            'quantity' => $cartDetail->quantity,
                            'price' => $finalPrice,
                        ]);

                        // Cập nhật tồn kho
                        $product->decrement('quantity', $cartDetail->quantity);
                    }

                    // Xoá sản phẩm khỏi giỏ hàng
                    CartDetail::where('cart_id', $cart->id)
                        ->whereIn('product_id', $orderInfo['selected_products'])
                        ->delete();
                }

                DB::commit();
                session()->forget('order_info');
                flasher('Đặt hàng thành công');
            } catch (\Exception $e) {
                DB::rollBack();
                flasher('Có lỗi xảy ra: ' . $e->getMessage());
            }
        } else {
            flasher('Thanh toán thất bại hoặc không hợp lệ!');
        }

        return redirect()->route('customer.home');
    }


    public function newOrder(Request $request)
    {
        $user = auth()->user();

        $selectedProductIds = explode(',', $request->input('selected_products'));
        if (empty($selectedProductIds[0])) {
            return redirect()->route('customer.cart')->with('error', 'Bạn chưa chọn sản phẩm nào!');
        }

        $cart = $user->cart;

        if (!$cart) {
            return redirect()->route('customer  .cart')->with('error', 'Không tìm thấy giỏ hàng!');
        }

        $cartDetails = $cart->cartDetails()
            ->whereIn('product_id', $selectedProductIds)
            ->with('product') // để dùng final_price
            ->get();

        $totalAmount = 0;
        foreach ($cartDetails as $detail) {
            $totalAmount += $detail->quantity * $detail->product->final_price;
        }

        $order = Order::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'order_date' => now(),
            'user_id' => $user->id,
            'total' => $totalAmount,
            'payment' => $request->payment
        ]);

        foreach ($cartDetails as $detail) {
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $detail->product_id,
                'quantity' => $detail->quantity,
                'price' => $detail->product->final_price
            ]);

            // Cập nhật tồn kho
            $detail->product->decrement('quantity', $detail->quantity);
        }

        // Xóa các chi tiết khỏi giỏ hàng
        $cart->cartDetails()
            ->whereIn('product_id', $selectedProductIds)
            ->delete();

        flasher('Đặt hàng thành công');
        return redirect()->route('customer.home');
    }


    public function acceptOrder($id)
    {
        $order = Order::find($id);
        if ($order->status == 'Chờ xác nhận') {
            $order->status = 'Đã xác nhận'; // Cập nhật trạng thái
            $order->save();
            return redirect()->back()->with('success', 'Đã xác nhận đơn hàng thành công!');
        } else {
            return redirect()->back()->with('error', 'Không thể xác nhận đơn hàng này!');
        }
    }

    public function cancelOrder(Request $request , $id)
    {
        $order = Order::find($id);

        if ($order->status == 'Chờ xác nhận' || $order->status == 'Đã xác nhận') {
            $orderDetails = $order->orderDetails;
            foreach ($orderDetails as $orderDetail) {
                $product = Product::find($orderDetail->product_id);
                $product->quantity += $orderDetail->quantity;
                $product->save();
            }

            $order->status = 'Đã hủy';
            $order->note = 'Khách hàng: ' . $request->input('note');
            $order->save();

            return redirect()->back()->with('success', 'Đã hủy đơn hàng thành công!');
        } else {
            return redirect()->back()->with('error', 'Không thể hủy đơn hàng này!');
        }
    }

    public function updateAfterAccept(Request $request, $order_id)
    {
        // Xác thực dữ liệu đầu vào
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
            'status' => 'required|string',
            'landing_code' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:255',
            'serial' => 'array', // Kiểm tra dữ liệu số sê-ri
        ]);

        // Lấy dữ liệu từ request
        $name = $request->input('name');
        $phone = $request->input('phone');
        $email = $request->input('email');
        $address = $request->input('address');
        $status = $request->input('status');
        $landing_code = $request->input('landing_code');
        $shipping_unit = $request->input('shipping_unit');
        $note = $request->input('note');

        // Cập nhật đơn hàng
        DB::table('orders')
            ->where('id', $order_id)
            ->update([
                'name' => $name,
                'phone' => $phone,
                'email' => $email,
                'address' => $address,
                'status' => $status,
                'landing_code' => $landing_code,
                'shipping_unit' => $shipping_unit,
                'note' => $note,
            ]);

        if ($status === 'Đã giao' || $status === 'Đã nhận hàng') {
            $order = Order::find($order_id); // Tìm đơn hàng theo ID
            $order->payment_status = 'Đã thanh toán';
            $order->prepay = $order->total; // Tính tiền đặt cọc
            $order->postpaid = 0; // Tính số tiền còn lại
            $order->save(); // Lưu thay đổi
        }

        $serials = $request->input('serial');
        if ($serials) {
            foreach ($serials as $orderdetail_id => $serial_numbers) {
                // Lấy danh sách serial hiện có theo order_detail_id, đảm bảo theo thứ tự
                $existingSerials = DB::table('serial')
                    ->where('order_detail_id', $orderdetail_id)
                    ->orderBy('id') // Hoặc một cột thể hiện thứ tự
                    ->get();

                foreach ($serial_numbers as $index => $serial_number) {
                    // Kiểm tra xem có serial tương ứng không
                    if (isset($existingSerials[$index])) {
                        $serialId = $existingSerials[$index]->id;

                        DB::table('serial')
                            ->where('id', $serialId)
                            ->update([
                                'serial_number' => $serial_number,
                            ]);
                    }
                }
            }
        }

        return redirect()->route('admin.orders')->with('success', 'Đơn hàng đã được cập nhật thành công.');
    }

    public function my($order_id)
    {
        $order = Order::with('orderdetails.product', 'orderdetails.serial')->find($order_id);

        return view('admin.update-after-accept', ['order' => $order]);
    }

    public function updateOrder($order_id)
    {
        $order = Order::with('orderdetails.product', 'orderdetails.serial')->find($order_id);

        return view('admin.update-order', ['order' => $order]);
    }

    public function updateSave(Request $request, $order_id)
    {
        $name = $request->input('name');
        $phone = $request->input('phone');

        $email = $request->input('email');
        $address = $request->input('address');
        $status = $request->input('status');
        $landing_code = $request->input('landing_code');
        $shipping_unit = $request->input('shipping_unit');
        $note = $request->input('note');

        // Cập nhật đơn hàng
        DB::table('orders')
            ->where('id', $order_id)
            ->update([
                'name' => $name,
                'phone' => $phone,
                'email' => $email,
                'address' => $address,
                'status' => $status,
                'landing_code' => $landing_code,
                'shipping_unit' => $shipping_unit,
                'note' => $note,
            ]);

        if ($status === 'Đã giao' || $status === 'Đã nhận hàng') {
            $order = Order::find($order_id); // Tìm đơn hàng theo ID
            $order->payment_status = 'Đã thanh toán';
            $order->prepay = $order->total; // Tính tiền đặt cọc
            $order->postpaid = 0; // Tính số tiền còn lại
            $order->save(); // Lưu thay đổi
        }

        // Cập nhật số sê-ri
        $serials = $request->input('serial');
        if ($serials) {
            foreach ($serials as $orderdetail_id => $serial_numbers) {
                $orderDetail = OrderDetail::find($orderdetail_id);

                foreach ($serial_numbers as $serial_number) {
                    $existingSerial = DB::table('serial')
                        ->where('serial_number', $serial_number)
                        ->where('order_detail_id', $orderdetail_id)
                        ->first();

                    if ($existingSerial) {
                        // Nếu số sê-ri đã tồn tại, cập nhật
                        DB::table('serial')
                            ->where('id', $existingSerial->id)
                            ->update([
                                'serial_number' => $serial_number,
                                'product_id' => $orderDetail->product_id,
                                'order_detail_id' => $orderdetail_id,
                            ]);
                    } else {
                        // Nếu số sê-ri chưa tồn tại, thêm mới
                        DB::table('serial')->insert([
                            'serial_number' => $serial_number,
                            'product_id' => $orderDetail->product_id,
                            'order_detail_id' => $orderdetail_id,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('admin.orders')->with('success', 'Đơn hàng đã được cập nhật thành công.');
    }

    public function buyInCart(Request $request)
    {
        $user = auth()->user();
        $cart = $user->cart;
        $allBrands = Brand::pluck('name', 'id')->toArray();

        $selectedProductIds = explode(',', $request->input('selected_products'));

        // Kiểm tra nếu không có sản phẩm nào được chọn
        if (empty($selectedProductIds[0])) {
            return redirect()->route('customer.cart')->with('error', 'Bạn chưa chọn sản phẩm nào!');
        }

        // Lấy chi tiết các sản phẩm được chọn từ giỏ hàng
        $selectedCartDetails = $cart->cartDetails()
            ->whereIn('product_id', $selectedProductIds)
            ->with('product')
            ->get();

        $totalAmount = 0;
        $hasInvalidQuantity = false;

        foreach ($selectedCartDetails as $cartDetail) {
            $quantity = $cartDetail->quantity;

            // Kiểm tra tồn kho
            if ($quantity > $cartDetail->product->quantity) {
                $hasInvalidQuantity = true;
                break;
            }

            // Sử dụng final_price (accessor đã có trong model Product)
            $totalAmount += $quantity * $cartDetail->product->final_price;
        }

        // Nếu vượt quá tồn kho
        if ($hasInvalidQuantity) {
            return redirect()->route('customer.cart')
                ->with('error', 'Số lượng sản phẩm chỉ còn ' . $cartDetail->product->quantity . ' sản phẩm.');
        }

        return view('customer.buy-inCart', compact(
            'selectedCartDetails',
            'totalAmount',
            'selectedProductIds',
            'allBrands'
        ));
    }

//hello
    public function cancelOrder2(Request $request, $id)
    {
        $order = Order::find($id);

        if ($order->status == 'Chờ xác nhận' || $order->status == 'Đã xác nhận') {
            $orderDetails = $order->orderDetails;
            foreach ($orderDetails as $orderDetail) {
                $product = Product::find($orderDetail->product_id);
                $product->quantity += $orderDetail->quantity;
                $product->save();
            }

            $order->status = 'Đã hủy';
            $order->note = 'Khách hàng: ' . $request->input('note');
            $order->save();

            return redirect()->back()->with('success', 'Đã hủy đơn hàng thành công!');
        } else {
            return redirect()->back()->with('error', 'Không thể hủy đơn hàng này!');
        }
    }

    public function doneOrder($id)
    {
        $order = Order::find($id);

        if ($order->status == 'Đang giao' || $order->status == 'Đã giao') {
            $order->status = 'Đã nhận hàng';
            $order->payment_status = 'Đã thanh toán';
            $order->prepay = $order->total;
            $order->postpaid = 0;
            $order->save();
            return redirect()->back()->with('success', 'Đã nhận đơn hàng thành công!');
        } else {
            return redirect()->back()->with('error', 'Không nhận đơn hàng này!');
        }
    }

    public function showProducts()
    {
        $allProducts = Product::with('category', 'brand')->get(); // ✅ Trả về Eloquent object
        $allCategories = \App\Models\Category::select('name')->get(); // Nên dùng model luôn

        return view('admin.create-order', compact('allProducts', 'allCategories'));
    }

    public function store(Request $request)
    {
        try {
            // Xác thực dữ liệu đầu vào
            $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_phone' => 'required|string|max:15',
                'customer_address' => 'required|string|max:255',
                'email' => 'required|email|max:255', // Thêm validation cho email
                'payment_method' => 'required|string|max:255',
                'total' => 'required|numeric', // Đổi từ integer sang numeric
                'cartItems' => 'required|string', // cartItems là string JSON
            ]);
            // Decode cartItems từ JSON string
            $cartItems = json_decode($request->cartItems, true);

            if (!$cartItems || !is_array($cartItems) || empty($cartItems)) {
                return response()->json(['error' => 'Giỏ hàng trống hoặc không hợp lệ!'], 400);
            }

            // Tạo bản ghi mới trong bảng orders
            $order = new Order();
            $order->name = $request->customer_name;
            $order->address = $request->customer_address;
            $order->phone = $request->customer_phone;
            $order->email = $request->email; // Sử dụng email từ form
            $order->order_date = now();
            $order->total = $request->total;
            $order->user_id = Auth::id();
            $order->payment = $request->payment_method;
            $order->note = $request->notes;
            $order->status = 'Đã nhận hàng';
            $order->payment_status = 'Đã thanh toán';
            $order->prepay = $request->total;
            $order->postpaid = 0;

            $order->save();

            // Lưu đơn hàng
            $order->save();
            // Lưu chi tiết đơn hàng
            foreach ($cartItems as $item) {
                // Kiểm tra dữ liệu item
                if (!isset($item['id']) || !isset($item['quantity']) || !isset($item['price'])) {
                    continue; // Bỏ qua item không hợp lệ
                }
                DB::table('orderdetails')->insert([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                // Cập nhật số lượng sản phẩm
                DB::table('products')
                    ->where('id', $item['id'])
                    ->decrement('quantity', $item['quantity']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Đơn hàng đã được tạo thành công!',
                'order_id' => $order->id,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Order creation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo đơn hàng: ' . $e->getMessage()
            ], 500);
        }
    }
}
