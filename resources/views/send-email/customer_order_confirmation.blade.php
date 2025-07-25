<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác nhận đơn hàng #{{ $order->id }}</title>
</head>
<body>
<h2>🎉 Cảm ơn bạn đã đặt hàng tại Shark Tech!</h2>

<p>Xin chào <strong>{{ $order->name }}</strong>,</p>
<p>Chúng tôi đã nhận được đơn hàng của bạn và sẽ xử lý trong thời gian sớm nhất.</p>

<h3>📋 Thông tin đơn hàng</h3>
<table>
    <tr>
        <td><strong>Mã đơn hàng:</strong></td>
        <td>#{{ $order->id }}</td>
    </tr>
    <tr>
        <td><strong>Ngày đặt:</strong></td>
        <td>{{ $order->order_date->format('H:i d/m/Y') }}</td>
    </tr>
    <tr>
        <td><strong>Điện thoại:</strong></td>
        <td>{{ $order->phone }}</td>
    </tr>
    <tr>
        <td><strong>Địa chỉ:</strong></td>
        <td>{{ $order->address }}</td>
    </tr>
    <tr>
        <td><strong>Phương thức thanh toán:</strong></td>
        <td>{{ $order->payment }}</td>
    </tr>
    <tr>
        <td><strong>Trạng thái thanh toán:</strong></td>
        <td>
            @if ($order->payment === 'Chuyển khoản')
                Đã thanh toán
            @elseif ($order->payment === 'Thanh toán khi nhận hàng')
                Đã cọc {{ number_format($order->prepay) }} VND,
                còn lại: {{ number_format($order->postpaid) }} VND
            @else
                {{ $order->payment_status }}
            @endif
        </td>
    </tr>
    <tr>
        <td><strong>Tổng tiền:</strong></td>
        <td>{{ number_format($order->total) }} VND</td>
    </tr>
</table>

<h3>📦 Chi tiết sản phẩm</h3>
<table border="1" cellpadding="6" cellspacing="0">
    <tr>
        <th>Sản phẩm</th>
        <th>Số lượng</th>
        <th>Đơn giá</th>
    </tr>
    @foreach ($order->orderDetails as $detail)
        <tr>
            <td>{{ $detail->product->name }}</td>
            <td>{{ $detail->quantity }}</td>
            <td>{{ number_format($detail->price) }} VND</td>
        </tr>
    @endforeach
</table>

<p>Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi qua email hoặc số hotline trên website.</p>
<p>Trân trọng,<br><strong>Shark Tech</strong></p>
</body>
</html>
