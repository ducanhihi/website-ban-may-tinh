<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đơn hàng mới #{{ $order->id }}</title>
</head>
<body>
<h2>📦 Đơn hàng mới từ khách hàng</h2>

<p><strong>Mã đơn hàng:</strong> #{{ $order->id }}</p>
<p><strong>Ngày đặt:</strong> {{ \Carbon\Carbon::parse($order->order_date)->format('H:i d/m/Y') }}</p>

<h3>👤 Thông tin khách hàng</h3>
<p><strong>Họ tên:</strong> {{ $order->name }}</p>
<p><strong>Số điện thoại:</strong> {{ $order->phone }}</p>
<p><strong>Email:</strong> {{ $order->email }}</p>
<p><strong>Địa chỉ:</strong> {{ $order->address }}</p>

<h3>💰 Thông tin thanh toán</h3>
<p><strong>Tổng tiền:</strong> {{ number_format($order->total) }} VND</p>
<p><strong>Phương thức thanh toán:</strong> {{ $order->payment }}</p>

<p><strong>Trạng thái thanh toán:</strong>
    @if ($order->payment === 'Chuyển khoản')
        Đã thanh toán
    @elseif ($order->payment === 'Thanh toán khi nhận hàng')
        Đã cọc {{ number_format($order->prepay) }} VND,
        còn lại: {{ number_format($order->postpaid) }} VND
    @else
        {{ $order->payment_status }}
    @endif
</p>

<h3>📝 Chi tiết sản phẩm</h3>
<table border="1" cellpadding="6" cellspacing="0">
    <thead>
    <tr>
        <th>Tên sản phẩm</th>
        <th>Số lượng</th>
        <th>Đơn giá (VND)</th>
        <th>Thành tiền (VND)</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($order->orderDetails as $detail)
        <tr>
            <td>{{ $detail->product->name }}</td>
            <td>{{ $detail->quantity }}</td>
            <td>{{ number_format($detail->price) }}</td>
            <td>{{ number_format($detail->quantity * $detail->price) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<br>
<p>🕓 Thời gian gửi: {{ now()->format('H:i d/m/Y') }}</p>
<p>
    <strong>Link xác nhận đơn hàng:</strong><br>
    <a href="{{ url('/admin/order-detail/' . $order->id) }}">
        {{ url('/admin/order-detail/' . $order->id) }}
    </a>
</p>
<p>📧 Đây là email tự động từ hệ thống Shark Tech. Vui lòng không trả lời email này.</p>
</body>
</html>
