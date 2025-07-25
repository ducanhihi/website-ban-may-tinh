<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        return $this->searchByName($request);
    }

    public function searchByName(Request $request)
    {
        $query = $request->input('query', '');
        $brandId = $request->input('brand');
        $categoryId = $request->input('category');
        $priceRange = $request->input('price');
        $discountFilter = $request->input('discount');
        $sort = $request->input('sort', 'name_asc');

        $productsQuery = Product::with(['category', 'brand']);

        // Search query
        if ($query) {
            $productsQuery->where(function($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                    ->orWhere('description', 'like', '%' . $query . '%')
                    ->orWhere('product_code', 'like', '%' . $query . '%');
            });
        }

        // Filter by brand
        if ($brandId) {
            $productsQuery->where('brand_id', $brandId);
        }

        // Filter by category
        if ($categoryId) {
            $productsQuery->where('category_id', $categoryId);
        }

        // Filter by price range
        if ($priceRange) {
            $priceRanges = explode('-', $priceRange);
            if (count($priceRanges) == 2) {
                $minPrice = (int)$priceRanges[0];
                $maxPrice = (int)$priceRanges[1];
                $productsQuery->whereBetween('price_out', [$minPrice, $maxPrice]);
            }
        }

        // Filter by discount
        if ($discountFilter) {
            switch ($discountFilter) {
                case 'has_discount':
                    $productsQuery->whereNotNull('discount_percent')
                        ->where('discount_percent', '>', 0);
                    break;
                case 'no_discount':
                    $productsQuery->where(function($q) {
                        $q->whereNull('discount_percent')
                            ->orWhere('discount_percent', 0);
                    });
                    break;
                case '10_plus':
                    $productsQuery->where('discount_percent', '>=', 10);
                    break;
                case '20_plus':
                    $productsQuery->where('discount_percent', '>=', 20);
                    break;
                case '30_plus':
                    $productsQuery->where('discount_percent', '>=', 30);
                    break;
                case '50_plus':
                    $productsQuery->where('discount_percent', '>=', 50);
                    break;
            }
        }

        // Apply sorting
        switch ($sort) {
            case 'name_asc':
                $productsQuery->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $productsQuery->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $productsQuery->orderBy('price_out', 'asc');
                break;
            case 'price_desc':
                $productsQuery->orderBy('price_out', 'desc');
                break;
            case 'discount_desc':
                $productsQuery->orderByRaw('COALESCE(discount_percent, 0) DESC');
                break;
            case 'newest':
                $productsQuery->orderBy('created_at', 'desc');
                break;
            default:
                $productsQuery->orderBy('name', 'asc');
        }

        $products = $productsQuery->paginate(20);

        // Get all brands and categories for filters
        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('customer.search-results', compact('products', 'query', 'brands', 'categories'));
    }

    public function apiSearch(Request $request)
    {
        $query = $request->input('query');
        $limit = $request->input('limit', 5);

        $products = Product::with(['category', 'brand'])
            ->where('name', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->orderBy('name')
            ->limit($limit)
            ->get();

        return response()->json($products);
    }

    public function filterProducts(Request $request)
    {
        return $this->searchByName($request);
    }
}
