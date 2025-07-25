<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class CategoriesController extends Controller
{
    public function viewAdminCategories() {
        $allCategories = DB::table('categories')
            ->select(['id', 'name','created_at', 'updated_at' ])
            ->paginate(5);
        return view('admin.categories', ['allCategories' => $allCategories]);
    }

    public function viewAdminCategoriesAndBrands() {
        $allCategories = DB::table('categories')
            ->select(['id', 'name', 'created_at', 'updated_at'])
            ->get();

        $allBrands = DB::table('brands')
            ->select(['id', 'name', 'created_at', 'updated_at'])
            ->get();

        return view('admin.categories-brands', [
            'allCategories' => $allCategories,
            'allBrands' => $allBrands
        ]);
    }


    public function createCategory(Request $request) {
        $name = $request->get('name');
        //tao products -> chuyen huong ve home
        DB::table('categories')->insert([
            'name' => $name,
            'created_at'=> now(),
            'updated_at'=> now(),
        ]);
        flash() -> addSuccess('Add category succesfully!');
        return redirect()->back();
    }
    public function deleteCategoryById($id){
        try {
            // Kiểm tra xem thương hiệu có tồn tại không
            $categoty = DB::table('brands')->find($id);
            if (!$categoty) {
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

    public function showForm()
    {
        $categories = Category::all(); // Lấy tất cả danh mục từ bảng category
        return view('admin.products', compact('categories')); // Truyền biến $categories đến view
    }



    public function storeCategory(Request $request)
    {
        // Xử lý lưu danh mục hoặc thực hiện hành động khác
        $categoryId = $request->input('category_id');
        // Logic xử lý với $categoryId
    }


    public function editCategoryById ($id, Request $request) {
        // Bước 1: kiểm tra xem bài viết có tồn tại hay không
        $category = DB::table('categories') -> find($id);
        if ($category == null) {
            return redirect('/admin/products');
        }
        //buowc2 capnhat thong tin
        $name = $request->get('name');
        // buoc 3: cap nhat
        $category = DB::table('categories')->where('id', '=', $id) -> update(
            [
                'name' => $name,
                'created_at'=> now(),
                'updated_at'=> now(),
            ]
        );
        //bước 4L thông báo -> chuyen hương ve home
        if ($category == 0) {
            //cap nhat that bai
            flash() -> addError('Cap nhat that bai');
        } else {
            flash() -> addSuccess('Cap nhat thanh cong');
        }
        return redirect()->back();
    }



    public function viewEditCategory($id) {
        try {
            $category = DB::table('categories')->find($id);
            if (!$category) {
                flash()->addError('Danh mục không tồn tại!');
                return redirect()->back();
            }

            // Trả về JSON để sử dụng với modal
            return response()->json([
                'success' => true,
                'category' => $category
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tải thông tin danh mục!'
            ]);
        }
    }
}
