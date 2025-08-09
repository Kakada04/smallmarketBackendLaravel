<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
       // $products_last = Product::latest()->take(5)->get();
       // $products = Product::latest()->get();
       $products = Product::with('category')->latest()->get()->map(function ($product) {
        $product->full_banner_url = asset('storage/' . $product->banner_img);
        return $product;
    });


        return response()->json([
            'status' => true,
            'message'=>'Get data Success',
            'data'=> $products
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     //
    //    $data = $request->validate([
    //     "category_id"=>'required|exists:categories,id',
    //     "product_name"=>'required|string',
    //     "quantity"=>'required',
    //     "cost_price"=>'required',
    //     "sell_price"=>'required',
    //     "banner_img"=>'required',
    //     ]);

    //     if($request->hasFile('banner_img' )){
    //         $data['banner_img'] = $request->files("banner_img")->store("product_image","public");
    //     }
    //     Product::create($data);
    //     return response()->json([
    //         'status' => true,
            
    //         'message' => 'Item Add Success',
    //         'data' => $data
    //     ], 201);
    // }
   
public function store(Request $request)
{
    // Validate input, require 'category_type' instead of 'category_id'
    $data = $request->validate([
        "category_type" => 'required|string',
        "product_name" => 'required|string',
        "quantity" => 'required',
        "cost_price" => 'required',
        "sell_price" => 'required',
        "banner_img" => 'required',
        "description" =>'required',
    ]);

    // Find the category by type
    $category = Category::where('type', $data['category_type'])->first();

    if (!$category) {
        return response()->json([
            'status' => false,
            'message' => 'Category type not found',
            'data' => null
        ], 404);
    }

    // Replace 'category_type' with 'category_id'
    $data['category_id'] = $category->id;
    unset($data['category_type']);

    // if ($request->hasFile('banner_img')) {
    //     $data['banner_img'] = $request->file('banner_img')->store('product_image', 'public');
    // }
    if ($request->hasFile('banner_img')) {
    $path = $request->file('banner_img')->store('product_image', 'public');
    Log::info('Image stored at: ' . $path);
    $data['banner_img'] = $path;
}


    $product = Product::create($data);

    return response()->json([
        'status' => true,
        'message' => 'Item Add Success',
        'data' => $product
    ], 201);
}

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
        return response()->json([
            'status'=> true,
            'message'=> 'get data from ID success',
            'data'=> $product
        ]);
    }

    public function getProductById($productId)
{
    if (!is_numeric($productId) || $productId <= 0) {
        return response()->json([
            'status' => false,
            'message' => 'Invalid product ID',
            'data' => null
        ], 400);
    }

    try {
        $product = Product::with('category')->findOrFail($productId);
        
        $product->full_banner_url = $product->banner_img 
            ? asset('storage/' . $product->banner_img) 
            : null;
        
        return response()->json([
            'status' => true,
            'message' => 'Product retrieved successfully',
            'data' => $product
        ]);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json([
            'status' => false,
            'message' => 'Product not found',
            'data' => null
        ], 404);
    }
}
    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, Product $product)
    // {
    //     //
    //     $data = $request->validate([
    //     "category_id"=>'sometimes|exists:categories,id',
    //     "product_name"=>'sometimes|string',
    //     "quantity"=>'sometimes',
    //     "cost_price"=>'sometimes',
    //     "sell_price"=>'sometimes',
    //      "banner_img" => 'sometimes',
    //     "description" =>'sometimes',



    //     ]);

    //      $category = Category::where('type', $data['category_type'])->first();

    // if (!$category) {
    //     return response()->json([
    //         'status' => false,
    //         'message' => 'Category type not found',
    //         'data' => null
    //     ], 404);
    // }
    // $data['category_id'] = $category->id;
    // unset($data['category_type']);

    // if ($request->hasFile('banner_img')) {
    //     $data['banner_img'] = $request->file('banner_img')->store('product_image', 'public');
    // }
    //     $product->update($data);
    //     return response()->json([
    //         'status'=> true,
    //         'message'=> 'update success',
    //         'data'=> $data
    //     ]);
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function totalproduct (Request $request){


        
    }
public function update(Request $request, Product $product)
{
   Log::info('Update request received', [
        'method' => $request->method(),
        'input' => $request->all(),
        'files' => $request->files->all(),
        'headers' => $request->headers->all(),
        'content_type' => $request->header('Content-Type'),
        'raw_body' => $request->getContent() // Log raw request body for debugging
    ]);

    $data = $request->validate([
        "category_type" => 'sometimes|string',
        "product_name" => 'sometimes|string',
        "quantity" => 'sometimes|numeric',
        "cost_price" => 'sometimes|numeric',
        "sell_price" => 'sometimes|numeric',
        "banner_img" => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        "description" => 'sometimes|string',
    ]);

   Log::info('Validated data', ['data' => $data]); // Fixed typo

    if (isset($data['category_type'])) {
        $category = Category::where('type', $data['category_type'])->first();
        if (!$category) {
           Log::error('Category not found', ['category_type' => $data['category_type']]);
            return response()->json([
                'status' => false,
                'message' => 'Category type not found',
                'data' => null
            ], 404);
        }
        $data['category_id'] = $category->id;
        unset($data['category_type']);
    }

    if ($request->hasFile('banner_img')) {
       Log::info('Banner image detected', [
            'file' => $request->file('banner_img')->getClientOriginalName(),
            'size' => $request->file('banner_img')->getSize(),
            'mime' => $request->file('banner_img')->getMimeType()
        ]);
        if ($product->banner_img) {
           Storage::disk('public')->delete($product->banner_img);
           Log::info('Old image deleted', ['path' => $product->banner_img]);
        }
        $data['banner_img'] = $request->file('banner_img')->store('product_image', 'public');
       Log::info('Banner image stored', ['path' => $data['banner_img']]);
    } else {
       Log::warning('No banner image provided or detected', [
            'has_file' => $request->hasFile('banner_img'),
            'file_exists' => $request->file('banner_img') !== null,
            'files_count' => count($request->files->all())
        ]);
    }

    $product->update($data);
    $product->refresh();

   Log::info('Product updated', ['product' => $product->toArray()]);

    return response()->json([
        'status' => true,
        'message' => 'Update success',
        'data' => $product
    ]);
}
    public function destroy(Product $product)

    {
        //
         if($product->banner_img){
            Storage::disk('public')->delete($product->banner_img);
        }
        $product->delete();
        return response()->json([
            'status'=> true,
            'message'=> 'Product has been delete'
        ]);

    }
public function searchProduct($productName){
  $products = Product::where('product_name', 'like', "%" . $productName . "%")
        ->get()
        ->map(function ($product) {
            $product->full_banner_url = asset('storage/' . $product->banner_img);
            return $product;
        });

    if ($products->isEmpty()) {
        return response()->json([
            'status' => false,
            'message' => 'No products found',
            'data' => null
        ], 200);
    }

    return response()->json([
        'status' => true,
        'message' => 'Products retrieved successfully', // Fixed message
        'data' => $products
    ]);
}

public function topProductsByPeriod(Request $request)
{
    try {
        $period = $request->query('period', 'today');
        $limit = $request->query('limit', 10);
        
        // Validate limit
        if (!is_numeric($limit) || $limit <= 0 || $limit > 100) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid limit. Must be between 1 and 100',
                'data' => null
            ], 400);
        }

        // Get top selling products with their sales count
        $products = Product::with('category')
            ->select('products.*')
            ->join('order_details', 'products.id', '=', 'order_details.product_id')
            ->join('order_lists', 'order_details.order_id', '=', 'order_lists.id')
            ->whereNotNull('order_lists.total_price') // Completed orders
            ->selectRaw('SUM(order_details.quantity) as total_sold')
            ->groupBy('products.id')
            ->orderByDesc('total_sold')
            ->limit($limit);

        // Apply period filtering
        switch ($period) {
            case 'today':
                $products->whereDate('order_lists.created_at', now()->toDateString());
                break;
            case 'last_7_days':
            case 'this_week':
                $products->whereBetween('order_lists.created_at', [now()->subDays(6)->startOfDay(), now()->endOfDay()]);
                break;
            case 'this_month':
                $products->whereMonth('order_lists.created_at', now()->month)
                      ->whereYear('order_lists.created_at', now()->year);
                break;
            case 'year':
                $products->whereYear('order_lists.created_at', now()->year);
                break;
            case 'jan':
            case 'feb':
            case 'mar':
            case 'apr':
            case 'may':
            case 'jun':
            case 'jul':
            case 'aug':
            case 'sep':
            case 'oct':
            case 'nov':
            case 'dec':
                $monthNum = date('n', strtotime($period));
                $products->whereMonth('order_lists.created_at', $monthNum)
                      ->whereYear('order_lists.created_at', now()->year);
                break;
            default:
                // All time if period not recognized
                break;
        }

        $products = $products->get()
            ->map(function ($product) {
                $product->full_banner_url = $product->banner_img 
                    ? asset('storage/' . $product->banner_img) 
                    : null;
                return $product;
            });

        return response()->json([
            'status' => true,
            'message' => "Top selling products ($period)",
            'period' => $period,
            'limit' => $limit,
            'count' => $products->count(),
            'data' => $products
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Failed to retrieve top products',
            'error' => $e->getMessage()
        ], 500);
    }
}


// **
//  * Get new product arrivals
//  * /api/new-arrivals?limit=10&days=30
//  * /api/new-arrivals?limit=20&days=7
//  */
public function newArrivals(Request $request)
{
    try {
        $limit = $request->query('limit', 10); // default 10 products
        $days = $request->query('days', 30); // default last 30 days
        
        // Validate parameters
        if (!is_numeric($limit) || $limit <= 0 || $limit > 100) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid limit. Must be between 1 and 100',
                'data' => null
            ], 400);
        }

        if (!is_numeric($days) || $days <= 0) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid days. Must be greater than 0',
                'data' => null
            ], 400);
        }

        $products = Product::with('category')
            ->where('created_at', '>=', now()->subDays($days))
            ->latest('created_at')
            ->limit($limit)
            ->get()
            ->map(function ($product) {
                $product->full_banner_url = $product->banner_img 
                    ? asset('storage/' . $product->banner_img) 
                    : null;
                return $product;
            });

        return response()->json([
            'status' => true,
            'message' => "New arrivals from last $days days",
            'days' => $days,
            'limit' => $limit,
            'count' => $products->count(),
            'data' => $products
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Failed to retrieve new arrivals',
            'error' => $e->getMessage()
        ], 500);
    }
}

/**
 * Get products sorted by price (high to low)
 * /api/products-high-price-first?limit=20&min_price=100&max_price=1000
 * /api/products-high-price-first?category_id=1
 */
public function highPriceFirst(Request $request)
{
    try {
        $limit = $request->query('limit', 20); // default 20 products
        $categoryId = $request->query('category_id');
        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');
        
        $query = Product::with('category');

        // Apply category filter
        if ($categoryId) {
            if (!is_numeric($categoryId)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid category_id. Must be numeric',
                    'data' => null
                ], 400);
            }
            $query->where('category_id', $categoryId);
        }

        // Apply price range filters
        if ($minPrice && is_numeric($minPrice)) {
            $query->where('sell_price', '>=', $minPrice);
        }

        if ($maxPrice && is_numeric($maxPrice)) {
            $query->where('sell_price', '<=', $maxPrice);
        }

        // Validate price range
        if ($minPrice && $maxPrice && $minPrice > $maxPrice) {
            return response()->json([
                'status' => false,
                'message' => 'min_price cannot be greater than max_price',
                'data' => null
            ], 400);
        }

        $products = $query->orderBy('sell_price', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($product) {
                $product->full_banner_url = $product->banner_img 
                    ? asset('storage/' . $product->banner_img) 
                    : null;
                return $product;
            });

        return response()->json([
            'status' => true,
            'message' => 'Products sorted by price (high to low)',
            'filters' => [
                'category_id' => $categoryId,
                'min_price' => $minPrice,
                'max_price' => $maxPrice,
                'limit' => $limit
            ],
            'count' => $products->count(),
            'data' => $products
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Failed to retrieve products by high price',
            'error' => $e->getMessage()
        ], 500);
    }
}

/**
 * Get products sorted by price (low to high)
 * /api/products-low-price-first?limit=20&min_price=10&max_price=500
 * /api/products-low-price-first?category_id=2
 */
public function lowPriceFirst(Request $request)
{
    try {
        $limit = $request->query('limit', 20); // default 20 products
        $categoryId = $request->query('category_id');
        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');
        
        $query = Product::with('category');

        // Apply category filter
        if ($categoryId) {
            if (!is_numeric($categoryId)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid category_id. Must be numeric',
                    'data' => null
                ], 400);
            }
            $query->where('category_id', $categoryId);
        }

        // Apply price range filters
        if ($minPrice && is_numeric($minPrice)) {
            $query->where('sell_price', '>=', $minPrice);
        }

        if ($maxPrice && is_numeric($maxPrice)) {
            $query->where('sell_price', '<=', $maxPrice);
        }

        // Validate price range
        if ($minPrice && $maxPrice && $minPrice > $maxPrice) {
            return response()->json([
                'status' => false,
                'message' => 'min_price cannot be greater than max_price',
                'data' => null
            ], 400);
        }

        $products = $query->orderBy('sell_price', 'asc')
            ->limit($limit)
            ->get()
            ->map(function ($product) {
                $product->full_banner_url = $product->banner_img 
                    ? asset('storage/' . $product->banner_img) 
                    : null;
                return $product;
            });

        return response()->json([
            'status' => true,
            'message' => 'Products sorted by price (low to high)',
            'filters' => [
                'category_id' => $categoryId,
                'min_price' => $minPrice,
                'max_price' => $maxPrice,
                'limit' => $limit
            ],
            'count' => $products->count(),
            'data' => $products
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Failed to retrieve products by low price',
            'error' => $e->getMessage()
        ], 500);
    }
}

/**
 * Get products with multiple sorting options
 * /api/products-sorted?sort_by=price_high&limit=15&category_id=1
 * /api/products-sorted?sort_by=newest&days=7&limit=10
 * /api/products-sorted?sort_by=name_asc&search=shirt
 */
public function productsSorted(Request $request)
{
    try {
        $sortBy = $request->query('sort_by', 'newest'); // newest, price_high, price_low, name_asc, name_desc
        $limit = $request->query('limit', 20);
        $categoryId = $request->query('category_id');
        $search = $request->query('search');
        $days = $request->query('days', 30); // for newest filter
        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');

        $validSortBy = ['newest', 'price_high', 'price_low', 'name_asc', 'name_desc'];
        
        if (!in_array($sortBy, $validSortBy)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid sort_by. Use: ' . implode(', ', $validSortBy),
                'data' => null
            ], 400);
        }

        $query = Product::with('category');

        // Apply filters
        if ($categoryId && is_numeric($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        if ($search) {
            $query->where('product_name', 'like', '%' . $search . '%');
        }

        if ($minPrice && is_numeric($minPrice)) {
            $query->where('sell_price', '>=', $minPrice);
        }

        if ($maxPrice && is_numeric($maxPrice)) {
            $query->where('sell_price', '<=', $maxPrice);
        }

        // Apply sorting
        switch ($sortBy) {
            case 'newest':
                $query->where('created_at', '>=', now()->subDays($days))
                      ->orderBy('created_at', 'desc');
                break;
            case 'price_high':
                $query->orderBy('sell_price', 'desc');
                break;
            case 'price_low':
                $query->orderBy('sell_price', 'asc');
                break;
            case 'name_asc':
                $query->orderBy('product_name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('product_name', 'desc');
                break;
        }

        $products = $query->limit($limit)
            ->get()
            ->map(function ($product) {
                $product->full_banner_url = $product->banner_img 
                    ? asset('storage/' . $product->banner_img) 
                    : null;
                return $product;
            });

        return response()->json([
            'status' => true,
            'message' => "Products sorted by $sortBy",
            'sort_by' => $sortBy,
            'filters' => [
                'category_id' => $categoryId,
                'search' => $search,
                'min_price' => $minPrice,
                'max_price' => $maxPrice,
                'days' => $sortBy === 'newest' ? $days : null,
                'limit' => $limit
            ],
            'count' => $products->count(),
            'data' => $products
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Failed to retrieve sorted products',
            'error' => $e->getMessage()
        ], 500);
    }
}

/**
 * Get products by price range
 * /api/products-by-price-range?min_price=100&max_price=500&sort=asc
 */
public function productsByPriceRange(Request $request)
{
    try {
        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');
        $sort = $request->query('sort', 'asc'); // asc or desc
        $limit = $request->query('limit', 20);
        $categoryId = $request->query('category_id');

        if (!$minPrice || !$maxPrice) {
            return response()->json([
                'status' => false,
                'message' => 'Both min_price and max_price are required',
                'data' => null
            ], 400);
        }

        if (!is_numeric($minPrice) || !is_numeric($maxPrice)) {
            return response()->json([
                'status' => false,
                'message' => 'min_price and max_price must be numeric',
                'data' => null
            ], 400);
        }

        if ($minPrice > $maxPrice) {
            return response()->json([
                'status' => false,
                'message' => 'min_price cannot be greater than max_price',
                'data' => null
            ], 400);
        }

        $query = Product::with('category')
            ->whereBetween('sell_price', [$minPrice, $maxPrice]);

        if ($categoryId && is_numeric($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->orderBy('sell_price', $sort === 'desc' ? 'desc' : 'asc')
            ->limit($limit)
            ->get()
            ->map(function ($product) {
                $product->full_banner_url = $product->banner_img 
                    ? asset('storage/' . $product->banner_img) 
                    : null;
                return $product;
            });

        return response()->json([
            'status' => true,
            'message' => "Products in price range $minPrice - $maxPrice",
            'price_range' => [
                'min' => (float) $minPrice,
                'max' => (float) $maxPrice
            ],
            'sort' => $sort,
            'category_id' => $categoryId,
            'count' => $products->count(),
            'data' => $products
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Failed to retrieve products by price range',
            'error' => $e->getMessage()
        ], 500);
    }
}
}