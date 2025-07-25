<?php

namespace App\Http\Controllers;

use App\Mail\CustomerOrderConfirmation;
use App\Mail\NewOrderNotification;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function vnpay_payment(Request $request)
    {
        $name = $request->get('name');
        $phone = $request->get('phone');
        $email = $request->get('email');
        $address = $request->get('address');
        $payment = $request->get('payment');
        $payment_status = $request->get('payment_status');
        $prepay = $request->get('prepay');
        $postpaid = $request->get('postpaid');
        $user_id = auth()->user()->id;
        $selectedProductIds = explode(',', $request->input('selected_products'));

        // Lấy giỏ hàng của user
        $cart = \App\Models\Cart::where('user_id', $user_id)->first();

        $totalAmount = 0;
        $cartDetails = collect();

        if ($cart) {
            // Lấy các cartdetails đã chọn, kèm product
            $cartDetails = \App\Models\CartDetail::with('product')
                ->where('cart_id', $cart->id)
                ->whereIn('product_id', $selectedProductIds)
                ->get();

            // Tính tổng tiền theo giá sau giảm (final_price)
            foreach ($cartDetails as $cartDetail) {
                $totalAmount += $cartDetail->quantity * $cartDetail->product->final_price;
            }
        }

        // Xác định số tiền cần thanh toán dựa trên phương thức thanh toán
        if ($payment === 'Chuyển khoản') {
            $amountToPay = $totalAmount; // 100% tổng cộng
            $payment_status = "Chưa thanh toán";
            $prepay = 0;
            $postpaid = $totalAmount;
        } elseif ($payment === 'Thanh toán khi nhận hàng') {
            if ($totalAmount > 1000000) {
                $amountToPay = $totalAmount * 0.4; // 40% tổng cộng
                $payment_status = "Chưa thanh toán";
                $prepay = 0;
                $postpaid = $totalAmount;
            } else {
                $amountToPay = $totalAmount; // Nếu không thì 100%
            }
        } else {
            $amountToPay = $totalAmount; // Mặc định 100%
        }

        // Tạo đơn hàng
        $order = \App\Models\Order::create([
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'address' => $address,
            'order_date' => now(),
            'user_id' => $user_id,
            'total' => $totalAmount,
            'payment' => $payment,
            'status' => 'Chờ thanh toán',
            'payment_status' => $payment_status,
            'prepay' => $prepay,
            'postpaid' => $postpaid,
        ]);

        // Lưu chi tiết đơn hàng
        if ($cart) {
            foreach ($cartDetails as $cartDetail) {
                \App\Models\OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $cartDetail->product_id,
                    'quantity' => $cartDetail->quantity,
                    'price' => $cartDetail->product->final_price,
                ]);
            }

            // Cập nhật số lượng sản phẩm trong kho
            foreach ($cartDetails as $cartDetail) {
                $product = $cartDetail->product;
                $product->quantity = max(0, $product->quantity - $cartDetail->quantity);
                $product->save();
            }

            // Xóa cartdetails đã đặt
            \App\Models\CartDetail::whereIn('product_id', $selectedProductIds)
                ->where('cart_id', $cart->id)
                ->delete();

            $order->load('orderDetails.product'); // nạp dữ liệu quan hệ để hiển thị tên sản phẩm trong email

        }

        // Tạo link thanh toán VNPAY
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('vnpay.return.existing');
        $vnp_TmnCode = "KEFQ4YHH";
        $vnp_HashSecret = "6UITGT2UZZ5TI5BEMKC197ZU2FYTRCC7";

        $vnp_TxnRef = $order->id;
        $vnp_OrderInfo = "Thanh toán hóa đơn #{$order->id}";
        $vnp_OrderType = "Shark Tech";
        $vnp_Amount = $amountToPay * 100;
        $vnp_Locale = "VN";
        $vnp_BankCode = "NCB";
        $vnp_IpAddr = request()->ip() ?? "127.0.0.1";

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];

        if (!empty($vnp_BankCode)) {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = [];
        $hashData = [];

        foreach ($inputData as $key => $value) {
            $hashData[] = urlencode($key) . "=" . urlencode($value);
            $query[] = urlencode($key) . "=" . urlencode($value);
        }

        $vnp_Url .= '?' . implode('&', $query);
        $vnp_SecureHash = hash_hmac('sha512', implode('&', $hashData), $vnp_HashSecret);
        $vnp_Url .= '&vnp_SecureHash=' . $vnp_SecureHash;

        return redirect($vnp_Url);
    }



    public function vnpay_return(Request $request) {
        $order_id = $request->get('vnp_TxnRef');
        $response_code = $request->get('vnp_ResponseCode');



        if (!$order_id) {
            return redirect()->route('customer.main-home')->with('error', 'Không tìm thấy đơn hàng.');
        }
        $order = Order::with('orderDetails.product')->find($order_id);

        if ($response_code === '00') {
            // Thanh toán thành công, cập nhật đơn hàng
            if ($order->payment === 'Chuyển khoản') {
                // Nếu phương thức thanh toán là Chuyển khoản
                DB::table('orders')->where('id', $order_id)->update([
                    'status' => 'Chờ xác nhận',
                    'payment_status' => 'Đã thanh toán', // Cập nhật trạng thái thanh toán
                    'prepay' => $order->total,
                    'postpaid' => 0,
                ]);
                // gui mail admin
                // Lấy lại bản ghi mới nhất có các giá trị đã cập nhật
                $order = Order::with('orderDetails.product')->find($order_id);
                Mail::to('daoduccanhh@gmail.com')->send(new NewOrderNotification($order));
                // Gửi mail cho khách hàng
                Mail::to($order->email)->send(new CustomerOrderConfirmation($order));
                // Có thể gửi mail, thông báo ở đây
                return redirect()->route('customer.main-home')->with('success', 'Thanh toán thành công.');


            } elseif ($order->payment === 'Thanh toán khi nhận hàng') {
                // Nếu phương thức thanh toán là Chuyển khoản
                DB::table('orders')->where('id', $order_id)->update([
                    'status' => 'Chờ xác nhận',
                    'payment_status' => 'Đã cọc', // Cập nhật trạng thái thanh toán
                    'prepay' => $order->total * 0.4,
                    'postpaid' => $order->total - $order->total * 0.4,
                ]);
                // gui mail admin
                // Lấy lại bản ghi mới nhất có các giá trị đã cập nhật
                $order = Order::with('orderDetails.product')->find($order_id);
                Mail::to('daoduccanhh@gmail.com')->send(new NewOrderNotification($order));
                // Gửi mail cho khách hàng
                Mail::to($order->email)->send(new CustomerOrderConfirmation($order));
                return redirect()->route('customer.main-home')->with('success', 'Thanh toán thành công.');
            }



        } else {
            // Thanh toán thất bại hoặc hủy
            DB::table('orders')->where('id', $order_id)->update([
                'status' => 'Chờ thanh toán',


            ]);

            return redirect()->route('customer.main-home')->with('error', 'Thanh toán thất bại hoặc bị hủy.');
        }
    }




    public function vnpay_payment_existing_order(Request $request) {
        $order_id = $request->get('order_id');

        if (!$order_id) {
            return redirect()->back()->with('error', 'Không tìm thấy đơn hàng.');
        }

        // Lấy thông tin đơn hàng
        $order = DB::table('orders')->where('id', $order_id)->first();

        if (!$order) {
            return redirect()->back()->with('error', 'Đơn hàng không tồn tại.');
        }

        // Kiểm tra quyền truy cập (chỉ chủ đơn hàng mới được thanh toán)
        if (auth()->user()->id != $order->user_id) {
            return redirect()->back()->with('error', 'Bạn không có quyền thanh toán đơn hàng này.');
        }

        // Kiểm tra trạng thái đơn hàng
        if (!in_array($order->status, ['Chờ thanh toán', 'Chờ xác nhận']) ||
            !in_array($order->payment_status, ['Chưa thanh toán'])) {
            return redirect()->back()->with('error', 'Đơn hàng này không thể thanh toán.');
        }

        // Xác định số tiền cần thanh toán dựa trên phương thức thanh toán
        $amountToPay = 0;

        if ($order->payment === 'Chuyển khoản') {
            $amountToPay = $order->total; // 100% tổng giá trị
        } elseif ($order->payment === 'Thanh toán khi nhận hàng') {
            $amountToPay = $order->total * 0.4; // 40% tổng giá trị
        } else {
            return redirect()->back()->with('error', 'Phương thức thanh toán không hợp lệ.');
        }

        // Cập nhật trạng thái đơn hàng thành "Đang xử lý thanh toán"
        DB::table('orders')->where('id', $order_id)->update([
            'updated_at' => now(),
        ]);

        // Tạo link thanh toán VNPAY
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('vnpay.return.existing'); // Route mới cho callback đơn hàng đã tồn tại
        $vnp_TmnCode = "KEFQ4YHH";
        $vnp_HashSecret = "6UITGT2UZZ5TI5BEMKC197ZU2FYTRCC7";

        $vnp_TxnRef = $order_id . '_EXISTING'; // Thêm suffix để phân biệt
        $vnp_OrderInfo = "Thanh toán đơn hàng #$order_id";
        $vnp_OrderType = "Shark Tech";
        $vnp_Amount = $amountToPay * 100;
        $vnp_Locale = "VN";
        $vnp_BankCode = "NCB";
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];

        if (!empty($vnp_BankCode)) {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = [];
        $hashData = [];

        foreach ($inputData as $key => $value) {
            $hashData[] = urlencode($key) . "=" . urlencode($value);
            $query[] = urlencode($key) . "=" . urlencode($value);
        }

        $vnp_Url .= '?' . implode('&', $query);
        $vnp_SecureHash = hash_hmac('sha512', implode('&', $hashData), $vnp_HashSecret);
        $vnp_Url .= '&vnp_SecureHash=' . $vnp_SecureHash;

        return redirect($vnp_Url);
    }

    /**
     * Xử lý callback thanh toán cho đơn hàng đã tồn tại
     */
    public function vnpay_return_existing_order(Request $request) {
        $vnp_TxnRef = $request->get('vnp_TxnRef');
        $response_code = $request->get('vnp_ResponseCode');
        $vnp_Amount = $request->get('vnp_Amount');
//        $vnp_TransactionNo = $request->get('vnp_TransactionNo');

        if (!$vnp_TxnRef) {
            return redirect()->route('customer.view-orders')->with('error', 'Không tìm thấy thông tin giao dịch.');
        }

        // Lấy order_id từ vnp_TxnRef (loại bỏ suffix '_EXISTING')
        $order_id = str_replace('_EXISTING', '', $vnp_TxnRef);

        $order = DB::table('orders')->where('id', $order_id)->first();

        if (!$order) {
            return redirect()->route('customer.view-orders')->with('error', 'Không tìm thấy đơn hàng.');
        }

        if ($response_code === '00') {
            // Thanh toán thành công
            $paid_amount = $vnp_Amount / 100; // VNPay trả về số tiền * 100

            if ($order->payment === 'Chuyển khoản') {
                // Thanh toán 100% - Đã thanh toán
                DB::table('orders')->where('id', $order_id)->update([
                    'status' => 'Chờ xác nhận',
                    'payment_status' => 'Đã thanh toán',
                    'prepay' => $order->total,
                    'postpaid' => 0,
//                    'transaction_no' => $vnp_TransactionNo,
                    'updated_at' => now(),
                ]);
                $order = Order::with('orderDetails.product')->find($order_id);
                Mail::to('daoduccanhh@gmail.com')->send(new NewOrderNotification($order));
                // Gửi mail cho khách hàng
                Mail::to($order->email)->send(new CustomerOrderConfirmation($order));
                $message = 'Thanh toán thành công! Đơn hàng của bạn đang chờ xác nhận.';

            } elseif ($order->payment === 'Thanh toán khi nhận hàng') {
                // Thanh toán 40% - Đã cọc
                $prepay_amount = $order->total * 0.4;
                $postpaid_amount = $order->total - $prepay_amount;

                DB::table('orders')->where('id', $order_id)->update([
                    'status' => 'Chờ xác nhận',
                    'payment_status' => 'Đã cọc',
                    'prepay' => $prepay_amount,
                    'postpaid' => $postpaid_amount,
//                    'transaction_no' => $vnp_TransactionNo,
                    'updated_at' => now(),
                ]);
                $order = Order::with('orderDetails.product')->find($order_id);
                Mail::to('daoduccanhh@gmail.com')->send(new NewOrderNotification($order));
                // Gửi mail cho khách hàng
                Mail::to($order->email)->send(new CustomerOrderConfirmation($order));
                $message = 'Đặt cọc thành công! Bạn sẽ thanh toán số tiền còn lại khi nhận hàng.';
            }


            return redirect()->route('customer.order-detail', ['order_id' => $order_id])
                ->with('success', $message);

        } else {
            // Thanh toán thất bại hoặc bị hủy
            DB::table('orders')->where('id', $order_id)->update([
                'status' => 'Chờ thanh toán',
                'updated_at' => now(),
            ]);


            $error_message = match($response_code) {
                '24' => 'Giao dịch bị hủy bởi người dùng.',
                '09' => 'Thẻ/Tài khoản của khách hàng chưa đăng ký dịch vụ InternetBanking tại ngân hàng.',
                '10' => 'Khách hàng xác thực thông tin thẻ/tài khoản không đúng quá 3 lần.',
                '11' => 'Đã hết hạn chờ thanh toán.',
                '12' => 'Thẻ/Tài khoản của khách hàng bị khóa.',
                '13' => 'Quý khách nhập sai mật khẩu xác thực giao dịch (OTP).',
                '51' => 'Tài khoản của quý khách không đủ số dư để thực hiện giao dịch.',
                '65' => 'Tài khoản của Quý khách đã vượt quá hạn mức giao dịch trong ngày.',
                default => 'Thanh toán thất bại. Vui lòng thử lại sau.'
            };

            return redirect()->route('customer.order-detail', ['order_id' => $order_id])
                ->with('error', $error_message);
        }
    }
}
