<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function viewAdminUsers() {
        $allUsers = DB::table("users")->get();
        return view('admin.users', ['allUsers' => $allUsers]);
    }

    public function showProfile()
    {
        $user = Auth::user();

        // Lấy thống kê người dùng (giả sử có bảng orders)
        $stats = [
            'total_orders' => 0, // Sẽ cập nhật khi có bảng orders
            'total_spent' => 0,  // Sẽ cập nhật khi có bảng orders
            'pending_orders' => 0, // Sẽ cập nhật khi có bảng orders
            'member_since' => $user->created_at->diffForHumans(),
        ];

        // Nếu có bảng orders, uncomment các dòng dưới:
        /*
        $stats = [
            'total_orders' => DB::table('orders')->where('customer_email', $user->email)->count(),
            'total_spent' => DB::table('orders')->where('customer_email', $user->email)->sum('total'),
            'pending_orders' => DB::table('orders')->where('customer_email', $user->email)->where('status', 'pending')->count(),
            'member_since' => $user->created_at->diffForHumans(),
        ];
        */

        // Lấy đơn hàng gần đây (nếu có bảng orders)
        $recentOrders = collect([]); // Empty collection for now
        /*
        $recentOrders = DB::table('orders')
            ->where('customer_email', $user->email)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        */

        return view('customer.show', compact('user', 'stats', 'recentOrders'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:100',
            'DOB' => 'nullable|date',
        ]);

        $user = Auth::user();

        // Cập nhật thông tin người dùng
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'DOB' => $request->DOB,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thông tin đã được cập nhật thành công.'
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Mật khẩu hiện tại không chính xác.'
            ], 422);
        }

        // Cập nhật mật khẩu mới
        $user->update(['password' => Hash::make($request->new_password)]);

        return response()->json([
            'success' => true,
            'message' => 'Mật khẩu đã được cập nhật thành công.'
        ]);
    }

    public function showChangePassword()
    {
        return view('customer.change-password');
    }


    public function deleteUser($id){
        try {
            // Kiểm tra xem thương hiệu có tồn tại không
            $user = DB::table('users')->find($id);
            if (!$user) {
                flash()->addError('người dùng không tồn tại!');
                return redirect()->back();
            }

            // Thực hiện xóa
            $deleted = DB::table('users')->where('id', $id)->delete();

            if ($deleted) {
                flash()->addSuccess('Xóa người dùng thành công!');
            } else {
                flash()->addError('Không thể xóa người dùng !');
            }

        } catch (\Illuminate\Database\QueryException $e) {
            // Xử lý lỗi khóa ngoại
            if ($e->getCode() == '23000') {
                flash()->addError('Không thể xóa người dùng này!');
            } else {
                flash()->addError('Có lỗi xảy ra khi xóa người dùng!');
            }
        } catch (Exception $e) {
            flash()->addError('Có lỗi hệ thống xảy ra!');
        }

        return redirect()->back();
    }
}
