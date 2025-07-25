<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderDetailsController extends Controller
{
    public function viewOrderDetail($id)
    {
        $orders = DB::table('orders')
            ->join('Orderdetails', 'Orders.id', '=', 'Orderdetails.order_id')
            ->join('Products', 'Orderdetails.product_id', '=', 'Products.id')
            ->select(
                'Orders.id AS order_id',
                'Orders.name AS customer_name',
                'Orders.address',
                'Orders.phone',
                'Orders.email',
                'Orders.order_date',
                'Orders.total',
                'Orders.user_id',
                'Orders.landing_code',
                'Orders.shipping_unit',
                'Orders.payment',
                'Orders.payment_status',
                'Orders.prepay',
                'Orders.postpaid',
                'Orders.note',
                'Orders.status',
                'Orderdetails.price',
                'Orderdetails.quantity',
                'Products.id AS product_id',
                'Products.product_code',
                'Products.name AS product_name',
                'Products.price_out AS product_price',
                'Products.quantity AS product_quantity',
                'Products.description',
                'Products.image',
                'Products.category_id',
                'Products.brand_id'
            )
            ->where('orders.id',$id)
            ->get();

        $categoryOptions = DB::table('categories')->pluck('name','id');
        $brandOptions = DB::table('brands')->pluck('name','id');
        return view('admin.order-detail',['orders' => $orders, 'categoryOptions' => $categoryOptions, 'brandOptions' => $brandOptions]);
    }

    public function viewCustomerOrders()
    {
        $userId = auth()->id();

        $pendingOrders = $this->getPendingOrders1($userId);
        $canceledOrders = $this->getCanceledOrders1($userId);
        $shippingOrders = $this->getShippingOrders1($userId);
        $completedOrders = $this->getCompletedOrders1($userId);
        $confirmedOrders = $this->getConfirmedOrders1($userId);
        $receivedOrders = $this->getReceivedOrders1($userId);
        $wait_payment = $this->getWaitPaymet($userId);


        return view('customer.view-orders', compact(
            'pendingOrders',
            'canceledOrders',
            'shippingOrders',
            'completedOrders',
            'confirmedOrders',
            'receivedOrders',
            'wait_payment'
        ));
    }
    public function printInvoice($id)
    {
        $orders = DB::table('orders')
            ->join('Orderdetails', 'Orders.id', '=', 'Orderdetails.order_id')
            ->join('Products', 'Orderdetails.product_id', '=', 'Products.id')
            ->select(
                'Orders.id AS order_id',
                'Orders.name AS customer_name',
                'Orders.address',
                'Orders.phone',
                'Orders.email',
                'Orders.order_date',
                'Orders.total',
                'Orders.user_id',
                'Orders.landing_code',
                'Orders.shipping_unit',
                'Orders.payment',
                'Orders.payment_status',
                'Orders.prepay',
                'Orders.postpaid',
                'Orders.note',
                'Orders.status',
                'Orderdetails.price',
                'Orderdetails.quantity',
                'Products.id AS product_id',
                'Products.product_code',
                'Products.name AS product_name',
                'Products.price_out AS product_price',
                'Products.quantity AS product_quantity',
                'Products.description',
                'Products.image',
                'Products.category_id',
                'Products.brand_id'
            )
            ->where('orders.id', $id)
            ->get();

        if ($orders->isEmpty()) {
            abort(404, 'Không tìm thấy đơn hàng');
        }

        // Tính toán thông tin hóa đơn
        $orderInfo = $orders->first();
        $totalAmount = 0;
        $totalQuantity = 0;

        foreach ($orders as $item) {
            $totalAmount += $item->price * $item->quantity;
            $totalQuantity += $item->quantity;
        }

        $invoiceData = [
            'orders' => $orders,
            'orderInfo' => $orderInfo,
            'totalAmount' => $totalAmount,
            'totalQuantity' => $totalQuantity,
            'invoiceNumber' => 'INV-' . str_pad($id, 6, '0', STR_PAD_LEFT),
            'invoiceDate' => now()->format('d/m/Y'),
            'companyInfo' => [
                'name' => 'CÔNG TY TNHH CÔNG NGHỆ SHARK WARE',
                'address' => 'A17 Tạ Quang Bửu, Hai Bà Trưng, Hà Nội',
                'phone' => '(028) 1234 5678',
                'email' => 'info@company.com',
                'website' => 'www.company.com',
                'tax_code' => '0123456789'
            ]
        ];

        return view('admin.print-invoice', $invoiceData);
    }
    public function customerPrintInvoice($id)
    {
        $userId = auth()->id();

        $orders = DB::table('orders')
            ->join('Orderdetails', 'Orders.id', '=', 'Orderdetails.order_id')
            ->join('Products', 'Orderdetails.product_id', '=', 'Products.id')
            ->select(
                'Orders.id AS order_id',
                'Orders.name AS customer_name',
                'Orders.address',
                'Orders.phone',
                'Orders.email',
                'Orders.order_date',
                'Orders.total',
                'Orders.user_id',
                'Orders.landing_code',
                'Orders.shipping_unit',
                'Orders.payment',
                'Orders.payment_status',
                'Orders.prepay',
                'Orders.postpaid',
                'Orders.note',
                'Orders.status',
                'Orderdetails.price',
                'Orderdetails.quantity',
                'Products.id AS product_id',
                'Products.product_code',
                'Products.name AS product_name',
                'Products.price_out AS product_price',
                'Products.quantity AS product_quantity',
                'Products.description',
                'Products.image',
                'Products.category_id',
                'Products.brand_id'
            )
            ->where('orders.id', $id)
            ->where('orders.user_id', $userId) // Đảm bảo chỉ customer sở hữu mới xem được
            ->get();

        if ($orders->isEmpty()) {
            abort(404, 'Không tìm thấy đơn hàng hoặc bạn không có quyền truy cập');
        }

        // Tính toán thông tin hóa đơn
        $orderInfo = $orders->first();
        $totalAmount = 0;
        $totalQuantity = 0;

        foreach ($orders as $item) {
            $totalAmount += $item->price * $item->quantity;
            $totalQuantity += $item->quantity;
        }

        $invoiceData = [
            'orders' => $orders,
            'orderInfo' => $orderInfo,
            'totalAmount' => $totalAmount,
            'totalQuantity' => $totalQuantity,
            'invoiceNumber' => 'INV-' . str_pad($id, 6, '0', STR_PAD_LEFT),
            'invoiceDate' => now()->format('d/m/Y'),
            'companyInfo' => [
                'name' => 'CÔNG TY TNHH CÔNG NGHỆ ABC',
                'address' => '123 Đường ABC, Quận XYZ, TP. Hồ Chí Minh',
                'phone' => '(028) 1234 5678',
                'email' => 'info@company.com',
                'website' => 'www.company.com',
                'tax_code' => '0123456789'
            ]
        ];

        return view('customer.print-invoice', $invoiceData);
    }
    protected function getWaitPaymet($userId)
    {
        return DB::table('orders')
            ->where('user_id', $userId)
            ->whereIn('status', ['Chờ thanh toán'])
            ->get();
    }

    protected function getPendingOrders1($userId)
    {
        return DB::table('orders')
            ->where('user_id', $userId)
            ->whereIn('status', ['Chờ xác nhận'])
            ->get();
    }

    protected function getCanceledOrders1($userId)
    {
        return DB::table('orders')
            ->where('user_id', $userId)
            ->where('status', 'Đã hủy')
            ->get();
    }

    protected function getShippingOrders1($userId)
    {
        return DB::table('orders')
            ->where('user_id', $userId)
            ->where('status', 'Đang giao')
            ->get();
    }

    protected function getCompletedOrders1($userId)
    {
        return DB::table('orders')
            ->where('user_id', $userId)
            ->where('status', 'Đã giao')
            ->get();
    }

    protected function getConfirmedOrders1($userId)
    {
        return DB::table('orders')
            ->where('user_id', $userId)
            ->where('status', 'Đã xác nhận')
            ->get();
    }

    protected function getReceivedOrders1($userId)
    {
        return DB::table('orders')
            ->where('user_id', $userId)
            ->where('status', 'Đã nhận hàng')
            ->get();
    }

    public function customerOrderDetail($id)
    {
        $orders = DB::table('orders')
            ->join('Orderdetails', 'Orders.id', '=', 'Orderdetails.order_id')
            ->join('Products', 'Orderdetails.product_id', '=', 'Products.id')
            ->select(
                'Orders.id AS order_id',
                'Orders.name AS customer_name',
                'Orders.address',
                'Orders.phone',
                'Orders.email',
                'Orders.order_date',
                'Orders.total',
                'Orders.user_id',
                'Orders.landing_code',
                'Orders.shipping_unit',
                'Orders.payment',
                'Orders.payment_status',
                'Orders.prepay',
                'Orders.postpaid',
                'Orders.note',
                'Orders.status',
                'Orderdetails.price',
                'Orderdetails.quantity',
                'Products.id AS product_id',
                'Products.product_code',
                'Products.name AS product_name',
                'Products.price_out AS product_price',
                'Products.quantity AS product_quantity',
                'Products.description',
                'Products.image',
                'Products.category_id',
                'Products.brand_id'
            )
            ->where('orders.id',$id)
            ->get();

        $categoryOptions = DB::table('categories')->pluck('name','id');
        $brandOptions = DB::table('brands')->pluck('name','id');
        return view('customer.order-detail',['orders' => $orders, 'categoryOptions' => $categoryOptions, 'brandOptions' => $brandOptions]);
    }

    public function markAsReceived($orderId)
    {
        try {
            DB::table('orders')
                ->where('id', $orderId)
                ->where('user_id', auth()->id())
                ->update(['status' => 'Đã nhận hàng']);

            return redirect()->back()->with('success', 'Đã xác nhận nhận hàng thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra. Vui lòng thử lại!');
        }
    }
}
