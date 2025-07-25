@extends('layout.app')

@section('content')
    <div class="container">
        <h1>Bộ PC</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(!$build)
            <h2>Tạo mới bộ PC</h2>
            <form action="{{ route('view_build_pc') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Tên bộ PC:</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Tạo bộ PC</button>
            </form>
        @else
            <h2>Bộ PC: {{ $build->name }}</h2>

            <h2>Thêm linh kiện vào bộ PC</h2>
            <form action="{{ route('add_component', $build->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="product_id">Chọn linh kiện:</label>
                    <select name="product_id" id="product_id" class="form-control" required>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} - {{ number_format($product->price, 0, ',', '.') }} VNĐ</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="quantity">Số lượng:</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" required>
                </div>
                <button type="submit" class="btn btn-primary">Thêm vào bộ PC</button>
            </form>


            <h2>Chi tiết linh kiện</h2>
            @if($components->isEmpty())
                <p>Chưa có linh kiện nào được thêm vào bộ PC.</p>
            @else
                <table class="table">
                    <thead>
                    <tr>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Giá</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($components as $component)
                        <tr>
                            <td>{{ $component->product_name }}</td>
                            <td>{{ $component->quantity }}</td>
                            <td>{{ number_format($component->product_price, 0, ',', '.') }} VNĐ</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        @endif
    </div>
@endsection
