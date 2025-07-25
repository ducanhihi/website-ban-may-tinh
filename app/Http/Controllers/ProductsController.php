<?php
namespace App\Http\Controllers;
use App\Models\Customer;
use App\Models\Feedback;
use App\Models\Order;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use function Laravel\Prompts\select;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Image;

class ProductsController extends Controller
{
    function viewAdminProducts()
    {
        $allProducts = DB::table('products')
            ->select(['id', 'product_code', 'name', 'price_out', 'quantity', 'description', 'image', 'category_id', 'brand_id', 'created_at', 'updated_at'])
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->select('products.*', 'categories.name as category_name', 'brands.name as brand_name')
            ->get();

        // Lấy danh sách các cặp category_id và category_name từ $allProducts
        $categoryOptions = DB::table('categories')->pluck('name', 'id');
        $brandOptions = DB::table('brands')->pluck('name', 'id');
        return view('admin.products', ['allProducts' => $allProducts, 'categoryOptions' => $categoryOptions, 'brandOptions' => $brandOptions]);
    }

    public function showProducts()
    {
        $allProducts = Product::with(['category:id,name', 'brand:id,name'])->get();

        $allCategories = Category::select('name')->get();

        return view('customer.main-home', [
            'allProducts' => $allProducts,
            'allCategories' => $allCategories
        ]);
    }

    public function viewDetailProduct($id)
    {
        $product = Product::with('images', 'category', 'brand')->findOrFail($id);
        $images = Image::where('product_id', $product->id)->get();
        $categoryOptions = Category::pluck('name', 'id');
        $brandOptions = Brand::pluck('name', 'id');

        try {
            $feedbacks = Feedback::where('product_id', $id)
                ->with(['user', 'order'])
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (\Exception $e) {
            Log::error('Lỗi khi lấy dữ liệu feedback: ' . $e->getMessage());
            $feedbacks = collect([]);
        }

        // Calculate average rating
        $averageRating = $feedbacks->avg('rating') ?: 0;

        // Count ratings by star level (for the rating bars)
        $ratingCounts = [
            5 => $feedbacks->where('rating', 5)->count(),
            4 => $feedbacks->where('rating', 4)->count(),
            3 => $feedbacks->where('rating', 3)->count(),
            2 => $feedbacks->where('rating', 2)->count(),
            1 => $feedbacks->where('rating', 1)->count(),
        ];

        return view('customer.view-detail', compact(
            'product',
            'categoryOptions',
            'brandOptions',
            'images',
            'feedbacks',
            'averageRating',
            'ratingCounts'
        ));
    }


    public function viewCreateRam(Request $request)
    {
        $categoryId = $request->query('category_id');
        $categoryOptions = DB::table('categories')->pluck('name', 'id');
        $brandOptions = DB::table('brands')->pluck('name', 'id');

        return view('admin.create-ram', compact('categoryOptions', 'brandOptions', 'categoryId'));
    }


    public function viewCreateProduct(Request $request)
    {
        $categoryId = $request->query('category_id');
        $category = DB::table('categories')->find($categoryId);
        $brandOptions = DB::table('brands')->pluck('name', 'id');

        // Nếu là RAM thì chuyển sang view khác
        if ($category && $category->name === 'RAM') {
            return redirect()->route('admin.create-ram', ['category_id' => $categoryId]);
        }
        elseif ( $category && $category->name === 'CPU') {
            return redirect()->route('admin.create-cpu', ['category_id' => $categoryId]);
        }

        return view('admin.create-product', compact('category', 'categoryId', 'brandOptions'));
    }



    public function createProduct(Request $request)
    {
        $product_code = $request->get('product_code');
        $name = $request->get('name');
        $price = $request->get('price_out');
        $quantity = $request->get('quantity');
        $description = $request->get('description');
        $ram_capacity = $request->get('ram_capacity');
        $ram_generation = $request->get('ram_generation');
        $ram_speed = $request->get('ram_speed');
        $image = "";
        if ($request->image != null) {
            $image = $request->image->getClientOriginalName();
            $request->image->move(public_path("image"), $image);
        }
        $categoryId = $request->get('category_id');
        $brandId = $request->get('brand_id');

        // Tạo mảng thông tin RAM
        $details = [
            'capacity' => $ram_capacity,
            'generation' => $ram_generation,
            'speed' => $ram_speed
        ];

        // Lưu thông tin sản phẩm và RAM vào bảng products
        $productId = DB::table('products')->insertGetId([
            'product_code' => $product_code,
            'name' => $name,
            'price_out' => $price,
            'quantity' => $quantity,
            'description' => $description,
            'details' => json_encode($details),
            'image' => $image,
            'category_id' => $categoryId,
            'brand_id' => $brandId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Xử lý ảnh phụ
        $images = $request->file('images');
        foreach ($images as $image) {
            $imageName = $image->getClientOriginalName();
            $image->move(public_path('image'), $imageName);

            DB::table('images')->insert([
                'url' => $imageName,
                'product_id' => $productId,
            ]);
        }

        flash()->addSuccess('Add product successfully!');
        return redirect()->route('admin.products');
    }

    public function viewCreateProductDynamic(Request $request)
    {
        $categoryId = $request->query('category_id');
        $categoryOptions = DB::table('categories')->pluck('name', 'id');
        $brandOptions = DB::table('brands')->pluck('name', 'id');

        return view('admin.create-product-dynamic', compact('categoryOptions', 'brandOptions', 'categoryId'));
    }



    public function viewEditProductDynamic($id)
    {
        $product = Product::with('images')->findOrFail($id);
        $categoryOptions = DB::table('categories')->pluck('name', 'id');
        $brandOptions = DB::table('brands')->pluck('name', 'id');

        // Parse existing details JSON - FIX: Check if it's already an array
        $existingSpecs = [];
        if (!empty($product->details)) {
            // Check if details is already an array or a string
            if (is_array($product->details)) {
                $existingSpecs = $product->details;
            } else {
                $details = json_decode($product->details, true);
                if (is_array($details)) {
                    $existingSpecs = $details;
                }
            }
        }

        return view('admin.edit-product-dynamic', compact('product', 'categoryOptions', 'brandOptions', 'existingSpecs'));
    }

    public function editProductDynamic($id, Request $request)
    {
        // Validate basic fields
        $request->validate([
            'product_code' => 'required|string|unique:products,product_code,' . $id,
            'name' => 'required|string|max:255',
            'price_out' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'spec_titles.*' => 'required|string|max:100',
            'spec_contents.*' => 'required|string|max:255'
        ]);

        $product = Product::findOrFail($id);

        $product_code = $request->get('product_code');
        $name = $request->get('name');
        $price = $request->get('price_out');
        $quantity = $request->get('quantity');
        $description = $request->get('description');
        $discount_percent = $request->get('discount_percent');
        $categoryId = $request->get('category_id');
        $brandId = $request->get('brand_id');

        // Handle main image
        $image = $product->image; // Keep existing image by default
        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image && file_exists(public_path('image/' . $product->image))) {
                unlink(public_path('image/' . $product->image));
            }

            $image = time() . '_' . $request->image->getClientOriginalName();
            $request->image->move(public_path("image"), $image);
        }

        // Process dynamic specifications - FIXED to preserve Vietnamese
        $details = [];
        $specTitles = $request->get('spec_titles', []);
        $specContents = $request->get('spec_contents', []);

        if (is_array($specTitles) && is_array($specContents)) {
            for ($i = 0; $i < count($specTitles); $i++) {
                if (isset($specTitles[$i]) && isset($specContents[$i]) &&
                    !empty(trim($specTitles[$i])) && !empty(trim($specContents[$i]))) {

                    // Keep original title as key (preserve Vietnamese characters and spaces)
                    $key = trim($specTitles[$i]);
                    $details[$key] = trim($specContents[$i]);
                }
            }
        }

        try {
            DB::beginTransaction();

            // Update product
            DB::table('products')
                ->where('id', $id)
                ->update([
                    'product_code' => $product_code,
                    'name' => $name,
                    'price_out' => $price,
                    'quantity' => $quantity,
                    'description' => $description,
                    'discount_percent' => $discount_percent,
                    'details' => json_encode($details, JSON_UNESCAPED_UNICODE),
                    'image' => $image,
                    'category_id' => $categoryId,
                    'brand_id' => $brandId,
                    'updated_at' => now(),
                ]);

            // Handle individual image deletions - FIXED: Use correct column name
            $imagesToDelete = $request->get('delete_images', []);
            if (!empty($imagesToDelete)) {
                foreach ($imagesToDelete as $imageId) {
                    // Make sure imageId is numeric
                    if (is_numeric($imageId)) {
                        $imageToDelete = DB::table('images')->where('id', $imageId)->first();
                        if ($imageToDelete) {
                            // FIX: Use uppercase URL instead of lowercase url
                            if (file_exists(public_path('image/' . $imageToDelete->URL))) {
                                unlink(public_path('image/' . $imageToDelete->URL));
                            }
                            // Delete record
                            DB::table('images')->where('id', $imageId)->delete();
                        }
                    }
                }
            }

            // Handle detail images - IMPROVED: Add new images instead of replacing all
            if ($request->hasFile('images')) {
                // Check if user wants to replace all images
                $replaceAllImages = $request->get('replace_all_images', false);

                if ($replaceAllImages) {
                    // Delete old detail images - FIX: Use correct column name
                    $oldImages = DB::table('images')->where('product_id', $id)->get();
                    foreach ($oldImages as $oldImage) {
                        // FIX: Use uppercase URL instead of lowercase url
                        if (file_exists(public_path('image/' . $oldImage->URL))) {
                            unlink(public_path('image/' . $oldImage->URL));
                        }
                    }
                    DB::table('images')->where('product_id', $id)->delete();
                }

                // Add new detail images - FIX: Use correct column name for insert
                foreach ($request->file('images') as $detailImage) {
                    $imageName = time() . '_' . uniqid() . '_' . $detailImage->getClientOriginalName();
                    $detailImage->move(public_path('image'), $imageName);

                    DB::table('images')->insert([
                        'URL' => $imageName, // FIX: Use uppercase URL
                        'product_id' => $id,
                    ]);
                }
            }

            DB::commit();

            flash()->addSuccess('Cập nhật sản phẩm thành công!');
            return redirect()->route('admin.products');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Lỗi khi cập nhật sản phẩm: ' . $e->getMessage());
            flash()->addError('Có lỗi xảy ra khi cập nhật sản phẩm: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    public function checkProductCode(Request $request)
    {
        $exists = Product::where('product_code', $request->code)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function createProductDynamic(Request $request)
    {
        // Validate basic fields
        $request->validate([
            'product_code' => 'required|string|unique:products,product_code',
            'name' => 'required|string|max:255',
            'price_out' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif',
            'spec_titles.*' => 'required|string|max:100',
            'spec_contents.*' => 'required|string|max:255'
        ]);

        $product_code = $request->get('product_code');
        $name = $request->get('name');
        $price = $request->get('price_out');
        $quantity = $request->get('quantity');
        $description = $request->get('description');
        $categoryId = $request->get('category_id');
        $brandId = $request->get('brand_id');

        // Handle main image
        $image = "";
        if ($request->hasFile('image')) {
            $image = time() . '_' . $request->image->getClientOriginalName();
            $request->image->move(public_path("image"), $image);
        }

        // Process dynamic specifications - FIXED to preserve Vietnamese
        $details = [];
        $specTitles = $request->get('spec_titles', []);
        $specContents = $request->get('spec_contents', []);

        if (is_array($specTitles) && is_array($specContents)) {
            for ($i = 0; $i < count($specTitles); $i++) {
                if (isset($specTitles[$i]) && isset($specContents[$i]) &&
                    !empty(trim($specTitles[$i])) && !empty(trim($specContents[$i]))) {

                    // Keep original title as key (preserve Vietnamese characters and spaces)
                    $key = trim($specTitles[$i]);
                    $details[$key] = trim($specContents[$i]);
                }
            }
        }

        try {
            DB::beginTransaction();

            // Insert product
            $productId = DB::table('products')->insertGetId([
                'product_code' => $product_code,
                'name' => $name,
                'price_out' => $price,
                'quantity' => $quantity,
                'description' => $description,
                'details' => json_encode($details, JSON_UNESCAPED_UNICODE),
                'image' => $image,
                'category_id' => $categoryId,
                'brand_id' => $brandId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Handle detail images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $detailImage) {
                    $imageName = time() . '_' . uniqid() . '_' . $detailImage->getClientOriginalName();
                    $detailImage->move(public_path('image'), $imageName);

                    DB::table('images')->insert([
                        'url' => $imageName,
                        'product_id' => $productId,

                    ]);
                }
            }

            DB::commit();

            flash()->addSuccess('Thêm sản phẩm thành công!');
            return redirect()->route('admin.products');

        } catch (\Exception $e) {
            DB::rollBack();

            // Delete uploaded main image if exists
            if ($image && file_exists(public_path("image/" . $image))) {
                unlink(public_path("image/" . $image));
            }

            flash()->addError('Có lỗi xảy ra khi thêm sản phẩm: ' . $e->getMessage());
            return back()->withInput();
        }
    }



    // Keep existing methods for backward compatibility
//    public function createRam(Request $request)
//    {
//        $product_code = $request->get('product_code');
//        $name = $request->get('name');
//        $price = $request->get('price_out');
//        $quantity = $request->get('quantity');
//        $description = $request->get('description');
//        $ram_capacity = $request->get('ram_capacity');
//        $ram_generation = $request->get('ram_generation');
//        $ram_speed = $request->get('ram_speed');
//        $image = "";
//        if ($request->image != null) {
//            $image = $request->image->getClientOriginalName();
//            $request->image->move(public_path("image"), $image);
//        }
//        $categoryId = $request->get('category_id');
//        $brandId = $request->get('brand_id');
//
//        // Tạo mảng thông tin RAM
//        $details = [
//            'capacity' => $ram_capacity,
//            'generation' => $ram_generation,
//            'speed' => $ram_speed
//        ];
//
//        // Lưu thông tin sản phẩm và RAM vào bảng products
//        $productId = DB::table('products')->insertGetId([
//            'product_code' => $product_code,
//            'name' => $name,
//            'price_out' => $price,
//            'quantity' => $quantity,
//            'description' => $description,
//            'details' => json_encode($details),
//            'image' => $image,
//            'category_id' => $categoryId,
//            'brand_id' => $brandId,
//            'created_at' => now(),
//            'updated_at' => now(),
//        ]);
//
//        // Xử lý ảnh phụ
//        $images = $request->file('images');
//        foreach ($images as $image) {
//            $imageName = $image->getClientOriginalName();
//            $image->move(public_path('image'), $imageName);
//
//            DB::table('images')->insert([
//                'url' => $imageName,
//                'product_id' => $productId,
//            ]);
//        }
//
//        flash()->addSuccess('Add product successfully!');
//        return redirect()->route('admin.products');
//    }

    public function deleteProductById($id)
    {
        try {
            $product = Product::findOrFail($id);

            // Kiểm tra xem sản phẩm có trong đơn hàng nào không
            $orderItemCount = DB::table('orderdetails')->where('product_id', $id)->count();

            if ($orderItemCount > 0) {
                flash()->addError('Không thể xóa sản phẩm này vì đã có trong ' . $orderItemCount . ' đơn hàng!');
                return redirect()->back();
            }

            // Xóa hình ảnh liên quan
            $product->images()->delete();

            // Xóa sản phẩm
            $product->delete();

            flash()->addSuccess('Xóa sản phẩm thành công!');

        } catch (\Illuminate\Database\QueryException $e) {
            // Xử lý lỗi khóa ngoại
            if ($e->getCode() == '23000') {
                flash()->addError('Không thể xóa sản phẩm này vì đang được sử dụng trong các đơn hàng hoặc bảng khác!');
            } else {
                flash()->addError('Có lỗi xảy ra khi xóa sản phẩm!');
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            flash()->addError('Sản phẩm không tồn tại!');
        } catch (Exception $e) {
            flash()->addError('Có lỗi hệ thống xảy ra!');
        }

        return redirect()->back();
    }

    function viewEditProduct($id)
    {
        $product = Product::findOrFail($id);


        // Lấy danh sách các cặp category_id và category_name từ $allProducts
        $categoryOptions = DB::table('categories')->pluck('name', 'id');
        $brandOptions = DB::table('brands')->pluck('name', 'id');

        return view('admin.edit-product', compact('product', 'categoryOptions', 'brandOptions'));

    }

    public function editProductById($id, Request $request)
    {
        // Bước 1: kiểm tra xem sản phẩm có tồn tại hay không
        $product = DB::table('products')->find($id);
        if ($product == null) {
            return redirect('/admin/products');
        }

        // Bước 2: cập nhật thông tin
        $product_code = $request->get('product_code');
        $name = $request->get('name');
        $price = $request->get('price_out');
        $quantity = $request->get('quantity');
        $description = $request->get('description');
        $discount_percent = $request->get('discount_percent');

        // Cập nhật ảnh chính của sản phẩm
        $image = $product->image; // Giữ nguyên tên ảnh hiện tại mặc định
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ trước khi cập nhật
            $oldImagePath = public_path('image') . '/' . $product->image;
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }

            // Lưu ảnh mới
            $image = $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path("image"), $image);
        }

        $categoryId = $request->get('category_id');
        $brandId = $request->get('brand_id');

        // Bước 3: cập nhật thông tin sản phẩm
        $productsRS = DB::table('products')
            ->where('id', '=', $id)
            ->update([
                'product_code' => $product_code,
                'name' => $name,
                'price_out' => $price,
                'quantity' => $quantity,
                'description' => $description,
                'discount_percent' => $discount_percent,
                'image' => $image,
                'category_id' => $categoryId,
                'brand_id' => $brandId,
                'updated_at' => now(),
            ]);

        // Cập nhật ảnh phụ - FIX: Use correct column name
        $images = $request->file('images');
        if ($images) {
            // Xóa ảnh phụ cũ trước khi cập nhật
            $oldImages = DB::table('images')->where('product_id', $id)->get();
            foreach ($oldImages as $oldImage) {
                // FIX: Use uppercase URL instead of lowercase url
                if (file_exists(public_path('image/' . $oldImage->URL))) {
                    unlink(public_path('image/' . $oldImage->URL));
                }
            }
            DB::table('images')->where('product_id', $id)->delete();

            // Lưu ảnh phụ mới
            foreach ($images as $image) {
                $imageName = $image->getClientOriginalName();
                $image->move(public_path('image'), $imageName);

                DB::table('images')->insert([
                    'URL' => $imageName, // FIX: Use uppercase URL
                    'product_id' => $id,
                ]);
            }
        }

        // Bước 4: thông báo và chuyển hướng
        if ($productsRS == 0) {
            flash()->addError('Cập nhật thất bại');
        } else {
            flash()->addSuccess('Cập nhật thành công');
        }
        return redirect()->route('admin.products');
    }
    public function getDashboardData(Request $request = null)
    {
        if ($request === null) {
            $request = new Request();
        }

        // Lấy tháng và năm từ request, mặc định là tháng hiện tại
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        $month = max(1, min(12, intval($month)));
        $year = max(2020, min(date('Y'), intval($year)));


        // Số đơn hàng trong tháng được chọn
        $totalOrdersInMonth = Order::whereMonth('order_date', $month)
            ->whereYear('order_date', $year)
            ->where('status', '!=', 'Đã hủy')
            ->count();


        // Doanh thu theo ngày, tuần, năm (mới)
        $todayRevenue = Order::whereDate('order_date', today())
            ->where('status', '!=', 'Đã hủy')
            ->sum('total');

        $thisWeekRevenue = Order::whereBetween('order_date', [now()->startOfWeek(), now()->endOfWeek()])
            ->where('status', '!=', 'Đã hủy')
            ->sum('total');

        $thisYearRevenue = Order::whereYear('order_date', $year)
            ->where('status', '!=', 'Đã hủy')
            ->sum('total');


        // Đơn hàng gần đây
        $recentOrders = Order::with('user')
            ->orderBy('order_date', 'desc')
            ->limit(10)
            ->get();

        // Sản phẩm bán chạy trong tháng được chọn
        $allProducts = DB::table('products')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->joinSub(
                DB::table('orderdetails')
                    ->join('orders', 'orderdetails.order_id', '=', 'orders.id')
                    ->whereMonth('orders.order_date', $month)
                    ->whereYear('orders.order_date', $year)
                    ->where('orders.status', '!=', 'Đã hủy')
                    ->selectRaw('product_id, SUM(orderdetails.quantity) as total_sold, SUM(orderdetails.quantity * orderdetails.price) as total_revenue')
                    ->groupBy('product_id'),
                'order_totals',
                'products.id', '=', 'order_totals.product_id'
            )
            ->selectRaw('products.id, products.name, products.image, products.price_out, products.quantity, categories.name as category_name, order_totals.total_sold, order_totals.total_revenue')
            ->where('order_totals.total_sold', '>', 0)
            ->orderByDesc('order_totals.total_sold')
            ->limit(5)
            ->get();

        // Doanh thu theo thể loại trong tháng được chọn
        $categoryRevenue = DB::table('categories')
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->join('orderdetails', 'products.id', '=', 'orderdetails.product_id')
            ->join('orders', 'orderdetails.order_id', '=', 'orders.id')
            ->whereMonth('orders.order_date', $month)
            ->whereYear('orders.order_date', $year)
            ->where('orders.status', '!=', 'Đã hủy')
            ->selectRaw('categories.name, SUM(orderdetails.quantity * orderdetails.price) as revenue')
            ->groupBy('categories.name')
            ->orderByDesc('revenue')
            ->get();

        return [


            // Dữ liệu mới
            'todayRevenue' => $todayRevenue,
            'thisWeekRevenue' => $thisWeekRevenue,
            'thisYearRevenue' => $thisYearRevenue,

            'categoryRevenue' => $categoryRevenue,

            // Dữ liệu thêm mới theo yêu cầu

        ];
    }

    // Route để lấy dữ liệu dashboard qua AJAX
    public function getDashboardDataAjax(Request $request)
    {
        return response()->json($this->getDashboardData($request));
    }



    public function show($id)
    {
        // Lấy thông tin sản phẩm
        $product = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
            ->select(
                'products.*',
                'categories.name as category_name',
                'brands.name as brand_name'
            )
            ->where('products.id', $id)
            ->first();

        if (!$product) {
            abort(404);
        }

        // Parse description để hiển thị dạng list
        $product->parsed_description = $this->parseDescription($product->description);

        // Lấy hình ảnh phụ của sản phẩm
        $product->images = DB::table('images')
            ->where('product_id', $id)
            ->get();

        // Lấy đánh giá của sản phẩm
        $feedbacks = DB::table('feedback')
            ->join('users', 'feedback.user_id', '=', 'users.id')
            ->leftJoin('orders', 'feedback.order_id', '=', 'orders.id')
            ->where('feedback.product_id', $id)
            ->select(
                'feedback.*',
                'users.name as user_name',
                'orders.created_at as order_date'
            )
            ->orderBy('feedback.created_at', 'desc')
            ->get();

        // Tính toán đánh giá trung bình
        $averageRating = $feedbacks->avg('rating') ?? 0;

        // Đếm số lượng đánh giá theo từng mức sao
        $ratingCounts = [];
        for ($i = 1; $i <= 5; $i++) {
            $ratingCounts[$i] = $feedbacks->where('rating', $i)->count();
        }

        // Chuyển đổi feedbacks thành collection với user object
        $feedbacks = $feedbacks->map(function ($feedback) {
            $feedback->user = (object) ['name' => $feedback->user_name];
            $feedback->created_at = \Carbon\Carbon::parse($feedback->created_at);
            if ($feedback->order_date) {
                $feedback->order = (object) ['created_at' => \Carbon\Carbon::parse($feedback->order_date)];
            }
            return $feedback;
        });

        return view('customer.view-detail', compact(
            'product',
            'feedbacks',
            'averageRating',
            'ratingCounts'
        ));
    }

    public function submitReview(Request $request, $productId)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập để đánh giá!'], 401);
        }

        $userId = auth()->id();

        // Kiểm tra xem user đã mua sản phẩm này và đã nhận hàng chưa
        $purchasedOrders = DB::table('orders')
            ->join('orderdetails', 'orders.id', '=', 'orderdetails.order_id')
            ->where('orders.user_id', $userId)
            ->where('orderdetails.product_id', $productId)
            ->where('orders.status', 'Đã nhận hàng') // Chỉ cho phép đánh giá khi đã nhận hàng
            ->orderBy('orders.created_at', 'desc')
            ->get();

        if ($purchasedOrders->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn chỉ có thể đánh giá sản phẩm sau khi đã nhận hàng!'
            ], 403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        try {
            DB::beginTransaction();

            // Kiểm tra xem đã có đánh giá chưa (dựa trên user_id và product_id)
            $existingReview = DB::table('feedback')
                ->where('user_id', $userId)
                ->where('product_id', $productId)
                ->first();

            if ($existingReview) {
                // Cập nhật đánh giá cũ - KHÔNG thay đổi order_id
                DB::table('feedback')
                    ->where('id', $existingReview->id)
                    ->update([
                        'rating' => $request->rating,
                        'comment' => $request->comment,
                        'updated_at' => now(),
                    ]);

                DB::commit();
                return response()->json(['success' => true, 'message' => 'Đánh giá của bạn đã được cập nhật!']);
            } else {
                // Tạo đánh giá mới - sử dụng đơn hàng đầu tiên đã nhận hàng
                $firstOrder = $purchasedOrders->first();

                DB::table('feedback')->insert([
                    'order_id' => $firstOrder->id-3,
                    'product_id' => $productId,
                    'user_id' => $userId,
                    'rating' => $request->rating,
                    'comment' => $request->comment,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::commit();
                return response()->json(['success' => true, 'message' => 'Cảm ơn bạn đã đánh giá sản phẩm!']);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi lưu đánh giá: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lưu đánh giá. Vui lòng thử lại!'
            ], 500);
        }
    }

    private function parseDescription($description)
    {
        if (empty($description)) {
            return '<li>Chưa có mô tả chi tiết</li>';
        }

        // Tách description thành các dòng
        $lines = explode("\n", $description);
        $parsed = '';

        foreach ($lines as $line) {
            $line = trim($line);
            if (!empty($line)) {
                // Nếu dòng không bắt đầu bằng dấu -, thêm vào
                if (!str_starts_with($line, '-') && !str_starts_with($line, '•')) {
                    $line = '- ' . $line;
                }
                // Chuyển đổi thành HTML list item
                $line = str_replace(['- ', '• '], '', $line);
                $parsed .= '<li>' . htmlspecialchars($line) . '</li>';
            }
        }

        return $parsed ?: '<li>Chưa có mô tả chi tiết</li>';
    }

}
