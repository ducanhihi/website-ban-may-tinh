<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function viewCart()
    {
        {
            $user = auth()->user();
            $cart = $user->cart;
            $cartDetails = $cart->cartDetails()->with('product')->get();

            $totalAmount = 0;
            foreach ($cartDetails as $cartDetail) {
                $totalAmount += $cartDetail->quantity * $cartDetail->product->price;
            }

            return view('customer.cart', compact('cartDetails','totalAmount'));
        }
    }
    public function add(Product $product, Request $request)
    {
        // Chỉ xử lý AJAX requests
        if (!$request->expectsJson() && !$request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Invalid request'], 400);
        }

        if ($request->type === "ADD_TO_CART") {
            $quantity = $request->quantity ?: 1;

            // Kiểm tra số lượng
            if ($quantity > $product->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số lượng sản phẩm "' . $product->name . '" chỉ còn ' . $product->quantity . ' sản phẩm.'
                ], 422);
            }

            // Lấy hoặc tạo cart cho user
            $cart = Cart::firstOrCreate(['user_id' => auth()->user()->id]);

            // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
            $cartDetail = CartDetail::where([
                'cart_id' => $cart->id,
                'product_id' => $product->id
            ])->first();

            if ($cartDetail) {
                // Kiểm tra tổng số lượng sau khi thêm
                $newQuantity = $cartDetail->quantity + $quantity;
                if ($newQuantity > $product->quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Không thể thêm. Tổng số lượng sẽ vượt quá số lượng có sẵn (' . $product->quantity . ').'
                    ], 422);
                }

                $cartDetail->increment('quantity', $quantity);
            } else {
                CartDetail::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity
                ]);
            }

            // Tính tổng số lượng trong giỏ hàng
            $cartCount = CartDetail::where('cart_id', $cart->id)->sum('quantity');

            return response()->json([
                'success' => true,
                'message' => 'Đã thêm "' . $product->name . '" vào giỏ hàng',
                'cartCount' => $cartCount,
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'quantity' => $quantity
                ]
            ]);
        }

        if ($request->type === "DAT_HANG") {
            $quantity = $request->input('quantity', 1);

            if ($quantity > $product->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số lượng sản phẩm "' . $product->name . '" chỉ còn ' . $product->quantity . ' sản phẩm.'
                ], 422);
            }

            return response()->json([
                'success' => true,
                'redirect' => route('buy-now', ['product' => $product->id, 'quantity' => $quantity])
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid action'], 400);
    }

    public function update($product_id, Request $request)
    {
        try {
            $newQuantity = $request->input('quantity');

            // Validate quantity
            if ($newQuantity < 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số lượng phải lớn hơn 0'
                ], 400);
            }

            $cart = auth()->user()->cart;
            $cartDetail = $cart->cartDetails()->where('product_id', $product_id)->first();

            if ($cartDetail) {
                $cartDetail->update(['quantity' => $newQuantity]);

                return response()->json([
                    'success' => true,
                    'message' => 'Cập nhật số lượng thành công',
                    'new_quantity' => $newQuantity,
                    'product_id' => $product_id
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy sản phẩm trong giỏ hàng'
                ], 404);
            }

        } catch (\Exception $e) {
            Log::error('Cart update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    // Xóa sản phẩm - QUAN TRỌNG: method DELETE
    public function delete($product_id)
    {
        try {
            $cart = auth()->user()->cart;
            $deleted = $cart->cartDetails()->where('product_id', $product_id)->delete();

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Xóa sản phẩm thành công',
                    'product_id' => $product_id
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy sản phẩm để xóa'
                ], 404);
            }

        } catch (\Exception $e) {
            Log::error('Cart delete error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    // Xóa toàn bộ giỏ hàng
    public function clear()
    {
        try {
            $cart = auth()->user()->cart;
            $cart->cartDetails()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa toàn bộ giỏ hàng thành công'
            ]);

        } catch (\Exception $e) {
            Log::error('Cart clear error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }




}
