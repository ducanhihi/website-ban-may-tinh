@extends('layout.customerApp')

@section('content')
    <div class="container">
        <div class="mb-5">
            <h1 class="text-center">Đổi Mật Khẩu</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('profile.password.update') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="current_password">Mật Khẩu Hiện Tại</label>
                <input type="password" class="form-control" name="current_password" id="current_password" required>
            </div>

            <div class="form-group">
                <label for="new_password">Mật Khẩu Mới</label>
                <input type="password" class="form-control" name="new_password" id="new_password" required>
            </div>

            <div class="form-group">
                <label for="new_password_confirmation">Xác Nhận Mật Khẩu Mới</label>
                <input type="password" class="form-control" name="new_password_confirmation" id="new_password_confirmation" required>
            </div>

            <button type="submit" class="btn btn-primary">Cập Nhật Mật Khẩu</button>
        </form>
    </div>
@endsection
