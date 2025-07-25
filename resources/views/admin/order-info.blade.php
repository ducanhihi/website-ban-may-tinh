@extends('layout.app') <!-- hoặc layout của bạn -->

@section('content')
    <div class="container py-4">
        <h2 class="mb-4">Tra cứu đơn hàng GHN</h2>

        <!-- Form tra cứu -->
        <form method="POST" action="{{ route('admin.order-info') }}">
            @csrf
            <div class="mb-3">
                <label for="order_code" class="form-label">Mã đơn hàng (GHN)</label>
                <input type="text" class="form-control" name="order_code" id="order_code" required value="{{ old('order_code') }}">
            </div>
            <button type="submit" class="btn btn-primary">Tra cứu</button>
        </form>

        @if(session('data'))
            <hr>
            <h4 class="mt-4">Thông tin đơn hàng</h4>
            @php
                $order = session('data');
            @endphp

            <ul class="list-group mt-3">
                <li class="list-group-item"><strong>Mã đơn hàng:</strong> {{ $order['order_code'] }}</li>
                <li class="list-group-item"><strong>Trạng thái:</strong> {{ strtoupper($order['status']) }}</li>
                <li class="list-group-item"><strong>Người nhận:</strong> {{ $order['to_name'] }}</li>
                <li class="list-group-item"><strong>Địa chỉ:</strong> {{ $order['to_address'] }}</li>
                <li class="list-group-item"><strong>Số điện thoại:</strong> {{ $order['to_phone'] }}</li>
                <li class="list-group-item"><strong>COD:</strong> {{ number_format($order['cod_amount']) }}đ</li>
                <li class="list-group-item"><strong>Ngày tạo:</strong> {{ \Carbon\Carbon::parse($order['created_date'])->format('d/m/Y H:i') }}</li>
                <li class="list-group-item"><strong>Dự kiến giao hàng:</strong> {{ \Carbon\Carbon::parse($order['leadtime'])->format('d/m/Y H:i') }}</li>
            </ul>

            @if(!empty($order['log']))
                <h5 class="mt-4">Lịch sử vận chuyển</h5>
                <ul class="list-group">
                    @foreach($order['log'] as $log)
                        <li class="list-group-item">
                            <strong>{{ strtoupper($log['status']) }}</strong>
                            - {{ \Carbon\Carbon::parse($log['updated_date'])->format('d/m/Y H:i') }}
                        </li>
                    @endforeach
                </ul>
            @endif
        @endif

        @if(session('error'))
            <div class="alert alert-danger mt-4">
                {{ session('error') }}
            </div>
        @endif
    </div>
@endsection
