<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\PcBuild;
use App\Models\PcBuildComponent;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class PcBuilderController extends Controller
{
    public function index()
    {
        try {
            // Lấy tất cả các danh mục linh kiện PC
            $categories = $this->getPcCategories();
            $brands = Brand::all();

            // Lấy thông tin build hiện tại từ session
            $currentBuild = Session::get('pc_build', []);

            return view('customer.pc-builder.main-builder-pc', compact('categories', 'brands', 'currentBuild'));
        } catch (\Exception $e) {
            Log::error('Error in PC Builder index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi tải trang');
        }
    }

    public function getProducts(Request $request)
    {
        try {
            $query = Product::with(['category', 'brand'])
                ->where('category_id', $request->category_id)
                ->where('quantity', '>', 0);

            // Tìm kiếm theo tên
            if ($request->has('search') && !empty($request->search)) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            // Lọc theo thương hiệu
            if ($request->has('brand_id') && !empty($request->brand_id)) {
                $query->where('brand_id', $request->brand_id);
            }

            // Lọc theo khoảng giá
            if ($request->has('price_range') && !empty($request->price_range)) {
                $priceRange = explode('-', $request->price_range);
                if (count($priceRange) == 2) {
                    $query->whereBetween('price_out', [$priceRange[0], $priceRange[1]]);
                }
            }

            // Lọc theo thông số kỹ thuật - Sửa lại logic lọc JSON
            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'spec_') === 0 && !empty($value)) {
                    $specKey = str_replace('spec_', '', $key);

                    // Sử dụng LIKE để tìm kiếm trong JSON, xử lý cả key và value
                    $query->where(function($q) use ($specKey, $value) {
                        // Tìm theo key chính xác
                        $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(details, '$.\"" . $specKey . "\"')) LIKE ?", ['%' . $value . '%'])
                            // Hoặc tìm theo key không có dấu ngoặc kép
                            ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(details, '$." . $specKey . "')) LIKE ?", ['%' . $value . '%'])
                            // Hoặc tìm trong toàn bộ JSON string
                            ->orWhere('details', 'LIKE', '%"' . $specKey . '":"' . $value . '"%')
                            ->orWhere('details', 'LIKE', '%"' . $specKey . '": "' . $value . '"%');
                    });
                }
            }

            // Phân trang
            $products = $query->orderBy('name')->paginate(10);

            // Xử lý image URL và details cho từng sản phẩm
            $products->getCollection()->transform(function ($product) {
                if ($product->image) {
                    $product->image_url = asset('storage/images/' . $product->image);
                } else {
                    $product->image_url = asset('images/no-image.png');
                }

                // Tính giá cuối cùng
                $product->final_price = $this->calculateFinalPrice($product);

                // Parse details JSON để hiển thị
                if ($product->details) {
                    $product->parsed_details = is_string($product->details)
                        ? json_decode($product->details, true)
                        : $product->details;
                } else {
                    $product->parsed_details = [];
                }

                return $product;
            });

            return response()->json([
                'success' => true,
                'products' => $products->items(),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getProducts: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tải sản phẩm'
            ], 500);
        }
    }

    public function getSpecFilters(Request $request)
    {
        try {
            $categoryId = $request->category_id;

// Lấy tất cả sản phẩm trong category và có details
            $products = Product::where('category_id', $categoryId)
                ->whereNotNull('details')
                ->where('quantity', '>', 0)
                ->get();

            $specs = [];

            foreach ($products as $product) {
                if ($product->details) {
                    $details = is_string($product->details) ? json_decode($product->details, true) : $product->details;

                    if (is_array($details)) {
                        foreach ($details as $key => $value) {
                            if (!isset($specs[$key])) {
                                $specs[$key] = [];
                            }

// Chỉ thêm giá trị nếu chưa tồn tại và không rỗng
                            if (!empty($value) && !in_array($value, $specs[$key])) {
                                $specs[$key][] = $value;
                            }
                        }
                    }
                }
            }

// Sắp xếp các giá trị trong mỗi spec
            foreach ($specs as $key => $values) {
                sort($specs[$key]);
            }

            return response()->json([
                'success' => true,
                'specs' => $specs
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getSpecFilters: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'specs' => []
            ]);
        }
    }

// Các method khác giữ nguyên...
public function addToSession(Request $request)
{
    try {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'category_name' => 'required|string'
        ]);

        $product = Product::with(['category', 'brand'])->find($request->product_id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại'
            ]);
        }

        if ($product->quantity <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm đã hết hàng'
            ]);
        }

        $build = Session::get('pc_build', []);
        $categoryName = $request->category_name;

        // Xử lý image URL
        if ($product->image) {
            $product->image_url = asset('storage/images/' . $product->image);
        } else {
            $product->image_url = asset('images/no-image.png');
        }

        // Tính giá cuối cùng
        $product->final_price = $this->calculateFinalPrice($product);

        // Parse details
        if ($product->details) {
            $product->parsed_details = is_string($product->details)
                ? json_decode($product->details, true)
                : $product->details;
        } else {
            $product->parsed_details = [];
        }

        // Thêm sản phẩm vào build
        $build[$categoryName] = [
            'product' => $product,
            'quantity' => 1
        ];

        Session::put('pc_build', $build);

        // Tính tổng giá trị
        $totalPrice = $this->calculateTotalPrice($build);

        return response()->json([
            'success' => true,
            'build' => $build,
            'total_price' => $totalPrice,
            'message' => 'Đã thêm sản phẩm vào cấu hình'
        ]);
    } catch (\Exception $e) {
        Log::error('Error in addToSession: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Có lỗi xảy ra khi thêm sản phẩm'
        ], 500);
    }
}

public function removeFromSession(Request $request)
{
    try {
        $request->validate([
            'product_id' => 'required|integer',
            'category_name' => 'required|string'
        ]);

        $build = Session::get('pc_build', []);
        $categoryName = $request->category_name;

        // Xóa sản phẩm khỏi build
        if (isset($build[$categoryName])) {
            unset($build[$categoryName]);
            Session::put('pc_build', $build);
        }

        // Tính tổng giá trị
        $totalPrice = $this->calculateTotalPrice($build);

        return response()->json([
            'success' => true,
            'build' => $build,
            'total_price' => $totalPrice,
            'message' => 'Đã xóa sản phẩm khỏi cấu hình'
        ]);
    } catch (\Exception $e) {
        Log::error('Error in removeFromSession: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Có lỗi xảy ra khi xóa sản phẩm'
        ], 500);
    }
}

public function checkCompatibility()
{
    try {
        $build = Session::get('pc_build', []);

        // Kiểm tra tương thích cơ bản
        $compatible = true;
        $issues = [];

        // Kiểm tra CPU và Motherboard socket
        if (isset($build['CPU']) && isset($build['Motherboard'])) {
            $cpu = $build['CPU']['product'];
            $motherboard = $build['Motherboard']['product'];

            $cpuDetails = is_string($cpu->details) ? json_decode($cpu->details, true) : $cpu->details;
            $mbDetails = is_string($motherboard->details) ? json_decode($motherboard->details, true) : $motherboard->details;

            if (isset($cpuDetails['socket']) && isset($mbDetails['socket'])) {
                if ($cpuDetails['socket'] !== $mbDetails['socket']) {
                    $compatible = false;
                    $issues[] = 'CPU và Motherboard không tương thích socket';
                }
            }
        }

        // Kiểm tra RAM và Motherboard
        if (isset($build['RAM']) && isset($build['Motherboard'])) {
            $ram = $build['RAM']['product'];
            $motherboard = $build['Motherboard']['product'];

            $ramDetails = is_string($ram->details) ? json_decode($ram->details, true) : $ram->details;
            $mbDetails = is_string($motherboard->details) ? json_decode($motherboard->details, true) : $motherboard->details;

            if (isset($ramDetails['generation']) && isset($mbDetails['memory_support'])) {
                if ($ramDetails['generation'] !== $mbDetails['memory_support']) {
                    $compatible = false;
                    $issues[] = 'RAM và Motherboard không tương thích thế hệ bộ nhớ';
                }
            }
        }

        return response()->json([
            'compatible' => $compatible,
            'issues' => $issues
        ]);
    } catch (\Exception $e) {
        Log::error('Error in checkCompatibility: ' . $e->getMessage());
        return response()->json([
            'compatible' => true,
            'issues' => []
        ]);
    }
}

public function saveBuild(Request $request)
{
    try {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'error' => 'Vui lòng đăng nhập để lưu cấu hình'
            ]);
        }

        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $build = Session::get('pc_build', []);

        if (empty($build)) {
            return response()->json([
                'success' => false,
                'error' => 'Vui lòng chọn ít nhất một linh kiện'
            ]);
        }

        DB::beginTransaction();

        try {
            // Tạo build mới
            $pcBuild = new PcBuild();
            $pcBuild->user_id = Auth::id();
            $pcBuild->name = $request->name;
            $pcBuild->save();

            // Thêm các linh kiện vào build
            foreach ($build as $component) {
                if (isset($component['product'])) {
                    $pcBuildComponent = new PcBuildComponent();
                    $pcBuildComponent->pc_build_id = $pcBuild->id;
                    $pcBuildComponent->product_id = $component['product']['id'];
                    $pcBuildComponent->quantity = $component['quantity'];
                    $pcBuildComponent->save();
                }
            }

            DB::commit();

            // Xóa build khỏi session
            Session::forget('pc_build');

            return response()->json([
                'success' => true,
                'message' => 'Lưu cấu hình thành công'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    } catch (\Exception $e) {
        Log::error('Error in saveBuild: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => 'Có lỗi xảy ra khi lưu cấu hình'
        ], 500);
    }
}

public function addToCart()
{
    try {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'error' => 'Vui lòng đăng nhập để thêm vào giỏ hàng'
            ]);
        }

        $build = Session::get('pc_build', []);

        if (empty($build)) {
            return response()->json([
                'success' => false,
                'error' => 'Vui lòng chọn ít nhất một linh kiện'
            ]);
        }

        DB::beginTransaction();

        try {
            // Lấy hoặc tạo giỏ hàng
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

            // Thêm các sản phẩm vào giỏ hàng
            foreach ($build as $component) {
                if (isset($component['product'])) {
                    $cartDetail = CartDetail::where('cart_id', $cart->id)
                        ->where('product_id', $component['product']['id'])
                        ->first();

                    if ($cartDetail) {
                        // Cập nhật số lượng nếu sản phẩm đã có trong giỏ hàng
                        $cartDetail->quantity += $component['quantity'];
                        $cartDetail->save();
                    } else {
                        // Thêm sản phẩm mới vào giỏ hàng
                        CartDetail::create([
                            'cart_id' => $cart->id,
                            'product_id' => $component['product']['id'],
                            'quantity' => $component['quantity']
                        ]);
                    }
                }
            }

            DB::commit();

            // Lấy số lượng sản phẩm trong giỏ hàng
            $cartCount = CartDetail::where('cart_id', $cart->id)->sum('quantity');

            return response()->json([
                'success' => true,
                'message' => 'Thêm vào giỏ hàng thành công',
                'cart_count' => $cartCount
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    } catch (\Exception $e) {
        Log::error('Error in addToCart: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => 'Có lỗi xảy ra khi thêm vào giỏ hàng'
        ], 500);
    }
}

public function clearBuild()
{
    try {
        Session::forget('pc_build');

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa tất cả linh kiện'
        ]);
    } catch (\Exception $e) {
        Log::error('Error in clearBuild: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Có lỗi xảy ra khi xóa cấu hình'
        ], 500);
    }
}

public function myBuilds()
{
    try {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $builds = PcBuild::with(['components.product.category', 'components.product.brand'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Tính tổng giá cho mỗi build
        foreach ($builds as $build) {
            $totalPrice = 0;
            foreach ($build->components as $component) {
                $finalPrice = $this->calculateFinalPrice($component->product);
                $totalPrice += $finalPrice * $component->quantity;
            }
            $build->total_price = $totalPrice;
        }

        return view('customer.pc-builder.my-build', compact('builds'));
    } catch (\Exception $e) {
        Log::error('Error in myBuilds: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Có lỗi xảy ra khi tải danh sách cấu hình');
    }
}

public function loadBuild($id)
{
    try {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $build = PcBuild::with(['components.product.category', 'components.product.brand'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$build) {
            return redirect()->route('pc-builder.my-builds')
                ->with('error', 'Không tìm thấy cấu hình');
        }

        // Chuyển đổi build từ database sang session
        $sessionBuild = [];

        foreach ($build->components as $component) {
            $categoryName = $component->product->category->name;

            // Xử lý image URL
            if ($component->product->image) {
                $component->product->image_url = asset('storage/images/' . $component->product->image);
            } else {
                $component->product->image_url = asset('images/no-image.png');
            }

            // Tính giá cuối cùng
            $component->product->final_price = $this->calculateFinalPrice($component->product);

            // Parse details
            if ($component->product->details) {
                $component->product->parsed_details = is_string($component->product->details)
                    ? json_decode($component->product->details, true)
                    : $component->product->details;
            } else {
                $component->product->parsed_details = [];
            }

            $sessionBuild[$categoryName] = [
                'product' => $component->product,
                'quantity' => $component->quantity
            ];
        }

        Session::put('pc_build', $sessionBuild);

        return redirect()->route('pc-builder.main-builder-pc')
            ->with('success', 'Đã tải cấu hình thành công');
    } catch (\Exception $e) {
        Log::error('Error in loadBuild: ' . $e->getMessage());
        return redirect()->route('pc-builder.my-builds')
            ->with('error', 'Có lỗi xảy ra khi tải cấu hình');
    }
}

public function deleteBuild($id)
{
    try {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $build = PcBuild::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$build) {
            return redirect()->route('pc-builder.my-builds')
                ->with('error', 'Không tìm thấy cấu hình');
        }

        DB::beginTransaction();

        try {
            // Xóa các components trước
            PcBuildComponent::where('pc_build_id', $build->id)->delete();

            // Sau đó xóa build
            $build->delete();

            DB::commit();

            return redirect()->route('pc-builder.my-builds')
                ->with('success', 'Xóa cấu hình thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    } catch (\Exception $e) {
        Log::error('Error in deleteBuild: ' . $e->getMessage());
        return redirect()->route('pc-builder.my-builds')
            ->with('error', 'Có lỗi xảy ra khi xóa cấu hình');
    }
}

private function calculateFinalPrice($product)
{
    $price = $product->price_out;

    if ($product->discount_percent) {
        $price = $price - ($price * $product->discount_percent / 100);
    }

    if ($product->discount_direct) {
        $price = $price - $product->discount_direct;
    }

    return max(0, $price);
}

private function calculateTotalPrice($build)
{
    $totalPrice = 0;

    foreach ($build as $component) {
        if (isset($component['product'])) {
            $price = $this->calculateFinalPrice($component['product']);
            $totalPrice += $price * $component['quantity'];
        }
    }

    return $totalPrice;
}

private function getPcCategories()
{
    // Danh sách các loại linh kiện PC
    $categoryNames = [
        'CPU', 'GPU', 'RAM', 'Motherboard', 'SSD', 'HDD', 'PSU', 'Case',
        'Màn hình', 'Bàn phím', 'Chuột', 'Tai nghe', 'Loa',
        'Tản nhiệt nước', 'Tản nhiệt khí'
    ];

    // Lấy các danh mục từ database
    $categories = Category::whereIn('name', $categoryNames)->get();

    // Nếu chưa có đủ danh mục, tạo thêm
    $existingNames = $categories->pluck('name')->toArray();
    $missingNames = array_diff($categoryNames, $existingNames);

    if (!empty($missingNames)) {
        foreach ($missingNames as $name) {
            $category = new Category();
            $category->name = $name;
            $category->save();

            $categories->push($category);
        }
    }

    return $categories;
}
}
