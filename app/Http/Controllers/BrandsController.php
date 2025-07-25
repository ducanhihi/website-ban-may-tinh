<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class BrandsController extends Controller
{
    public function viewAdminBrands() {
        $allBrands = DB::table('brands')
            ->select(['id', 'name','created_at', 'updated_at' ])
            ->get();
        return view('admin.brands', ['allBrands' => $allBrands]);
    }

    public function createBrand(Request $request) {
        $name = $request->get('name');

        try {
            DB::table('brands')->insert([
                'name' => $name,
                'created_at'=> now(),
                'updated_at'=> now(),
            ]);
            flash()->addSuccess('Thêm thương hiệu thành công!');
        } catch (Exception $e) {
            flash()->addError('Có lỗi xảy ra khi thêm thương hiệu!');
        }

        return redirect()->back();
    }

    public function deleteBrandById($id){
        try {
            // Kiểm tra xem thương hiệu có tồn tại không
            $brand = DB::table('brands')->find($id);
            if (!$brand) {
                flash()->addError('Thương hiệu không tồn tại!');
                return redirect()->back();
            }

            // Kiểm tra xem có sản phẩm nào đang sử dụng thương hiệu này không
            $productCount = DB::table('products')->where('brand_id', $id)->count();

            if ($productCount > 0) {
                flash()->addError('Không thể xóa thương hiệu này vì đang có ' . $productCount . ' sản phẩm sử dụng!');
                return redirect()->back();
            }

            // Thực hiện xóa
            $deleted = DB::table('brands')->where('id', $id)->delete();

            if ($deleted) {
                flash()->addSuccess('Xóa thương hiệu thành công!');
            } else {
                flash()->addError('Không thể xóa thương hiệu!');
            }

        } catch (\Illuminate\Database\QueryException $e) {
            // Xử lý lỗi khóa ngoại
            if ($e->getCode() == '23000') {
                flash()->addError('Không thể xóa thương hiệu này vì đang được sử dụng bởi các sản phẩm khác!');
            } else {
                flash()->addError('Có lỗi xảy ra khi xóa thương hiệu!');
            }
        } catch (Exception $e) {
            flash()->addError('Có lỗi hệ thống xảy ra!');
        }

        return redirect()->back();
    }

    public function editBrandById ($id, Request $request) {
        try {
            // Bước 1: kiểm tra xem thương hiệu có tồn tại hay không
            $brand = DB::table('brands')->find($id);
            if ($brand == null) {
                flash()->addError('Thương hiệu không tồn tại!');
                return redirect('/admin/brand');
            }

            // Bước 2: cập nhật thông tin
            $name = $request->get('name');

            // Bước 3: cập nhật
            $updated = DB::table('brands')->where('id', '=', $id)->update([
                'name' => $name,
                'updated_at'=> now(),
            ]);

            // Bước 4: thông báo -> chuyển hướng về home
            if ($updated) {
                flash()->addSuccess('Cập nhật thương hiệu thành công!');
            } else {
                flash()->addError('Không có thay đổi nào được thực hiện!');
            }

        } catch (Exception $e) {
            flash()->addError('Có lỗi xảy ra khi cập nhật thương hiệu!');
        }

        return redirect()->back();
    }
}
