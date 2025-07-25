<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // GET: /login
    function viewLogin()
    {
        // Kiem tra xem da dang nhap hay chua ?
        if (Auth::check()) {
            // Da dang nhap
            $user = Auth::user();
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.home');
                    break;
                case 'customer':
                    return redirect()->route('customer.main-home');
                    break;
            }
        }
        // view dang nhap
        return view('auth.login');
    }

    // POST: /login
    function login(Request $request)
    {
        // Xu ly dang nhap
        $email = $request->get('email');
        $password = $request->get('password');
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            // Xem vai tro cua nguoi nay
            $user = Auth::user();
            switch ($user->role) {
                case 'admin':
                    return redirect()->intended(route('admin.home'));
                    break;
                case 'customer':
                    return redirect()->route('customer.main-home');
                    break;
            }
        } else {
            // Chuyen huong ve login
            sweetalert()->addWarning('Sai mật khẩu');
            return redirect()->back();
        }
    }

    // POST: /dang xuat
    function logout()
    {
        // Kiểm tra xem người dùng có đang đăng nhập hay không
        if (Auth::check()) { // Nếu có người dùng đang đăng nhập
            $role = Auth::user()->role;

            // Đăng xuất người dùng
            Auth::logout();

            // Chuyển hướng dựa trên vai trò
            if ($role == 'customer') {
                return redirect()->route('customer.main-home');
            } else if ($role == 'admin') {
                return redirect()->route('customer.main-home'); // Bạn có thể thay đổi route cho admin nếu cần
            } else {
                return redirect()->route('customer.main-home');
            }
        } else {
            // Nếu không có người dùng nào đang đăng nhập, chuyển hướng đến trang chính
            return redirect()->route('customer.main-home');
        }
    }



//    function register(Request $request)
//    {
//        $request->validate([
//            'name' => 'required|string|max:255',
//            'email' => 'required|string|email|max:255|unique:users',
//            'password' => 'required|string|min:8',
//        ]);
//
//        $user = new User();
//        $user->name = $request->name;
//        $user->email = $request->email;
//        $user->password = Hash::make($request->password);
//        $user->save();
//
//        return response()->json(['message' => 'User registered successfully'], 201);
//    }

    public function indexvchat()
    {
        $user = Auth::user();

        return view('vchat.index', [
            'userId' => $user->id,
            'userName' => $user->name,
            'userPhone' => $user->phone,
        ]);
    }



}
