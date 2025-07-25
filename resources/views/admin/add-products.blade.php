<?php
@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>Tạo RAM mới</h1>

        <form action="{{ route('admin.form-ram') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="capacity">Dung lượng RAM:</label>
                <input type="text" class="form-control" id="capacity" name="capacity" required>
            </div>

            <div class="form-group">
                <label for="speed">Tốc độ RAM:</label>
                <input type="text" class="form-control" id="speed" name="speed" required>
            </div>

            <div class="form-group">
                <label for="type">Loại RAM:</label>
                <input type="text" class="form-control" id="type" name="type" required>
            </div>

            <button type="submit" class="btn btn-primary">Tạo RAM</button>
        </form>
    </div>
@endsection
