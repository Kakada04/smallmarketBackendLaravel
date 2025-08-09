<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderList;
use App\Models\User;
use App\Models\Product;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;


class DataAnalystController extends Controller
{
    
public function totalsales(Request $request)
{
//     /api/total-sales?period=today
// /api/total-sales?period=last_7_days
// /api/total-sales?period=this_month
// /api/total-sales?period=jan
// /api/total-sales?period=year


    $period = $request->query('period', 'today'); // default to 'today'
    $query = OrderList::query();

    switch ($period) {
        case 'today':
            $query->whereDate('created_at', now()->toDateString());
            break;
        case 'last_7_days':
            $query->whereBetween('created_at', [now()->subDays(6)->startOfDay(), now()->endOfDay()]);
            break;
        case 'this_month':
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
            break;
        case 'year':
            $query->whereYear('created_at', now()->year);
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
            $query->whereMonth('created_at', $monthNum)
                  ->whereYear('created_at', now()->year);
            break;
        default:
            // If period is not recognized, return all time total
            break;
    }

    $totalSales = $query->sum('total_price');

    return response()->json([
        'period' => $period,
        'total_sales' => $totalSales,
    ]);
   }



   public function totalorders(Request $request){
    // /api/total-orders?period=today
    // /api/total-orders?period=last_7_days
    // /api/total-orders?period=this_month
    // /api/total-orders?period=jan
    // /api/total-orders?period=year

    $period = $request->query('period', 'today'); // default to 'today'
    $query = OrderList::query();

    switch ($period) {
        case 'today':
            $query->whereDate('created_at', now()->toDateString());
            break;
        case 'last_7_days':
            $query->whereBetween('created_at', [now()->subDays(6)->startOfDay(), now()->endOfDay()]);
            break;
        case 'this_month':
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
            break;
        case 'year':
            $query->whereYear('created_at', now()->year);
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
            $query->whereMonth('created_at', $monthNum)
                  ->whereYear('created_at', now()->year);
            break;
        default:
            // If period is not recognized, return all time total
            break;
    }

    $totalOrders = $query->count();

    return response()->json([
        'status' => true,
        'period' => $period,
        'data' => $totalOrders,
    ]);
   }

   public function allCustomer(){
    $data = User::where('is_admin', 0)->count();
    return response()->json([
        'status' => true,
        'message' => 'Total users',
        'data' => $data
    ], 200);
   }
   
   public function totalusers(Request $request){
    // /api/total-users?period=today
    // /api/total-users?period=last_7_days
    // /api/total-users?period=this_month
    // /api/total-users?period=jan
    // /api/total-users?period=year

    $period = $request->query('period', 'today'); // default to 'today'
    $query = User::query();

    switch ($period) {
        case 'today':
            $query->whereDate('created_at', now()->toDateString());
            break;
        case 'last_7_days':
            $query->whereBetween('created_at', [now()->subDays(6)->startOfDay(), now()->endOfDay()]);
            break;
        case 'this_month':
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
            break;
        case 'year':
            $query->whereYear('created_at', now()->year);
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
            $query->whereMonth('created_at', $monthNum)
                  ->whereYear('created_at', now()->year);
            break;
        default:
            // If period is not recognized, return all time total
            break;
    }

    $totalUsers = $query->where('is_admin', 0)->count();

    return response()->json([
        'period' => $period,
        'data' => $totalUsers
    ]);
   }

   public function allProductsInStock()
{
    try {
        // Assuming 'stock' is a column indicating quantity > 0 for in-stock products
        $data = Product::count();

        return response()->json([
            'status' => true,
            'message' => 'Total products in stock',
            'data' => $data
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Failed to retrieve products in stock',
            'error' => $e->getMessage()
        ], 500);
    }
}
public function totalAllSaleProducts(){
    $data = OrderDetail::count();
 try{
    return response()->json([
        'status' => true,
        'message' => 'Total sale products',
        'data' => $data
    ], 200);
}catch (\Exception $e) {
    return response()->json([
        'status' => false,
        'message' => 'Failed to retrieve total sale products',
        'error' => $e->getMessage()
    ], 500);
}
}
   public function totalproducts(Request $request){
    // /api/total-products?period=today
    // /api/total-products?period=last_7_days
    // /api/total-products?period=this_month
    // /api/total-products?period=jan
    // /api/total-products?period=year

    $period = $request->query('period', 'today'); // default to 'today'
    $query = Product::query();

    switch ($period) {
        case 'today':
            $query->whereDate('created_at', now()->toDateString());
            break;
        case 'last_7_days':
            $query->whereBetween('created_at', [now()->subDays(6)->startOfDay(), now()->endOfDay()]);
            break;
        case 'this_month':
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
            break;
        case 'year':
            $query->whereYear('created_at', now()->year);
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
            $query->whereMonth('created_at', $monthNum)
                  ->whereYear('created_at', now()->year);
            break;
        default:
            // If period is not recognized, return all time total
            break;
    }

    $totalProducts = $query->count();

    return response()->json([
        'period' => $period,
        'total_products' => $totalProducts,
    ]);
   }

   public function totalsaleproducts(Request $request){
    // /api/total-sale-products?period=today
    // /api/total-sale-products?period=last_7_days
    // /api/total-sale-products?period=this_month
    // /api/total-sale-products?period=jan
    // /api/total-sale-products?period=year

    $period = $request->query('period', 'today'); // default to 'today'
    $query = OrderList::query();

    switch ($period) {
        case 'today':
            $query->whereDate('created_at', now()->toDateString());
            break;
        case 'last_7_days':
            $query->whereBetween('created_at', [now()->subDays(6)->startOfDay(), now()->endOfDay()]);
            break;
        case 'this_month':
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
            break;
        case 'year':
            $query->whereYear('created_at', now()->year);
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
            $query->whereMonth('created_at', $monthNum)
                  ->whereYear('created_at', now()->year);
            break;
        default:
            // If period is not recognized, return all time total
            break;
    }

    $totalSaleProducts = $query->withCount('orderDetails')->get();
    $totalSaleProductsQty = $totalSaleProducts->sum(function ($order) {
        return $order->orderDetails->sum('quantity');
    });
    $totalSaleProductsItems = $totalSaleProducts->count();

    return response()->json([
        'period' => $period,
        'total_sale_products_items' => $totalSaleProductsItems,
        'total_sale_products_Qty' => $totalSaleProductsQty,
        'total_sale_products' => $totalSaleProducts,
    ]);
   }


  public function totalRevenue(Request $request)
{
    try {
        // Validate period parameter
        $validPeriods = ['today', 'last_7_days', 'this_month', 'year', 'jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];
        $period = $request->query('period', 'today');

        if (!in_array($period, $validPeriods)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid period. Use: ' . implode(', ', $validPeriods)
            ], 400);
        }

        // Initialize the query
        $query = OrderList::query();

        // Map month names to numbers
        $monthMap = [
            'jan' => 1, 'feb' => 2, 'mar' => 3, 'apr' => 4, 'may' => 5, 'jun' => 6,
            'jul' => 7, 'aug' => 8, 'sep' => 9, 'oct' => 10, 'nov' => 11, 'dec' => 12
        ];

        // Apply date filter based on period
        switch ($period) {
            case 'today':
                $query->whereDate('created_at', now()->toDateString());
                break;
            case 'last_7_days':
                $query->whereBetween('created_at', [
                    now()->subDays(6)->startOfDay(),
                    now()->endOfDay()
                ]);
                break;
            case 'this_month':
                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                break;
            default:
                // Handle month-specific cases (jan, feb, etc.)
                if (isset($monthMap[$period])) {
                    $query->whereMonth('created_at', $monthMap[$period])
                          ->whereYear('created_at', now()->year);
                }
                break;
        }

        // Calculate total revenue
        $totalRevenue = $query->sum('total_price');

        return response()->json([
            'status' => true,
            'message' => "Total revenue for the period: $period",
            'period' => $period,
            'data' => (float) $totalRevenue
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Failed to calculate total revenue',
            'error' => $e->getMessage()
        ], 500);
    }
}




public function topSaleProducts(Request $request)
{
    try {
        // /api/top-sale-products?period=today&limit=10&sort_by=quantity
        // /api/top-sale-products?period=last_7_days&limit=5&sort_by=revenue
        // /api/top-sale-products?period=this_month&limit=20
        // /api/top-sale-products?period=jan&sort_by=orders_count
        // /api/top-sale-products?period=year

        $period = $request->query('period', 'today');
        $limit = $request->query('limit', 10); // default top 10
        $sortBy = $request->query('sort_by', 'quantity'); // quantity, revenue, orders_count

        // Validate parameters
        $validPeriods = ['today', 'last_7_days', 'this_month', 'year', 'jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];
        $validSortBy = ['quantity', 'revenue', 'orders_count'];
        
        if (!in_array($period, $validPeriods)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid period. Use: ' . implode(', ', $validPeriods)
            ], 400);
        }

        if (!in_array($sortBy, $validSortBy)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid sort_by. Use: ' . implode(', ', $validSortBy)
            ], 400);
        }

        // Start building the query
        $query = OrderDetail::join('products', 'order_details.product_id', '=', 'products.id')
                           ->join('order_lists', 'order_details.order_id', '=', 'order_lists.id');

        // Apply date filter based on period
        $this->applyDateFilter($query, $period, 'order_lists');

        // Group by product and calculate metrics
        $topProducts = $query->select([
                'products.id',
                'products.product_name as name',
                'products.sell_price as price',
                'products.banner_img as image',
                'products.category_id',
                DB::raw('SUM(order_details.quantity) as total_quantity'),
                DB::raw('SUM(order_details.quantity * order_details.price) as total_revenue'),
                DB::raw('COUNT(DISTINCT order_details.order_id) as orders_count'),
                DB::raw('AVG(order_details.price) as avg_price')
            ])
            ->groupBy('products.id', 'products.product_name', 'products.sell_price', 'products.banner_img', 'products.category_id');

        // Apply sorting
        switch ($sortBy) {
            case 'quantity':
                $topProducts = $topProducts->orderBy('total_quantity', 'desc');
                break;
            case 'revenue':
                $topProducts = $topProducts->orderBy('total_revenue', 'desc');
                break;
            case 'orders_count':
                $topProducts = $topProducts->orderBy('orders_count', 'desc');
                break;
        }

        $results = $topProducts->limit($limit)->get();

        // Format the results
        $formattedResults = $results->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float) $product->price,
                'image' => $product->image,
                'category_id' => $product->category_id,
                'total_quantity_sold' => (int) $product->total_quantity,
                'total_revenue' => (float) $product->total_revenue,
                'orders_count' => (int) $product->orders_count,
                'average_price' => (float) $product->avg_price
            ];
        });

        return response()->json([
            'status' => true,
            'message' => "Top $limit selling products for period: $period (sorted by $sortBy)",
            'period' => $period,
            'sort_by' => $sortBy,
            'limit' => $limit,
            'data' => $formattedResults
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Failed to retrieve top selling products',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function topSaleProductsWithCategories(Request $request)
{
    try {
        // /api/top-sale-products-with-categories?period=today&limit=10&sort_by=quantity
        
        $period = $request->query('period', 'today');
        $limit = $request->query('limit', 10);
        $sortBy = $request->query('sort_by', 'quantity');

        $query = OrderDetail::join('products', 'order_details.product_id', '=', 'products.id')
                           ->join('order_lists', 'order_details.order_id', '=', 'order_lists.id')
                           ->leftJoin('categories', 'products.category_id', '=', 'categories.id');

        $this->applyDateFilter($query, $period, 'order_lists');

        $topProducts = $query->select([
                'products.id',
                'products.product_name as name',
                'products.sell_price as price',
                'products.banner_img as image',
                'products.category_id',
                'categories.type as category_name',
                DB::raw('SUM(order_details.quantity) as total_quantity'),
                DB::raw('SUM(order_details.quantity * order_details.price) as total_revenue'),
                DB::raw('COUNT(DISTINCT order_details.order_id) as orders_count')
            ])
            ->groupBy('products.id', 'products.product_name', 'products.sell_price', 'products.banner_img', 'products.category_id', 'categories.type');

        switch ($sortBy) {
            case 'quantity':
                $topProducts = $topProducts->orderBy('total_quantity', 'desc');
                break;
            case 'revenue':
                $topProducts = $topProducts->orderBy('total_revenue', 'desc');
                break;
            case 'orders_count':
                $topProducts = $topProducts->orderBy('orders_count', 'desc');
                break;
        }

        $results = $topProducts->limit($limit)->get();

        $formattedResults = $results->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float) $product->price,
                'image' => $product->image,
                'category' => [
                    'id' => $product->category_id,
                    'name' => $product->category_name
                ],
                'total_quantity_sold' => (int) $product->total_quantity,
                'total_revenue' => (float) $product->total_revenue,
                'orders_count' => (int) $product->orders_count
            ];
        });

        return response()->json([
            'status' => true,
            'message' => "Top $limit selling products with categories for period: $period",
            'period' => $period,
            'sort_by' => $sortBy,
            'limit' => $limit,
            'data' => $formattedResults
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Failed to retrieve top selling products with categories',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function topSaleProductsByCategory(Request $request)
{
    try {
        // /api/top-sale-products-by-category?period=today&category_id=1&limit=5
        
        $period = $request->query('period', 'today');
        $categoryId = $request->query('category_id');
        $limit = $request->query('limit', 10);
        $sortBy = $request->query('sort_by', 'quantity');

        if (!$categoryId) {
            return response()->json([
                'status' => false,
                'message' => 'category_id parameter is required'
            ], 400);
        }

        $query = OrderDetail::join('products', 'order_details.product_id', '=', 'products.id')
                           ->join('order_lists', 'order_details.order_id', '=', 'order_lists.id')
                           ->where('products.category_id', $categoryId);

        $this->applyDateFilter($query, $period, 'order_lists');

        $topProducts = $query->select([
                'products.id',
                'products.product_name as name',
                'products.sell_price as price',
                'products.banner_img as image',
               DB::raw('SUM(order_details.quantity) as total_quantity'),
               DB::raw('SUM(order_details.quantity * order_details.price) as total_revenue'),
               DB::raw('COUNT(DISTINCT order_details.order_id) as orders_count')
            ])
            ->groupBy('products.id', 'products.product_name', 'products.sell_price', 'products.banner_img');

        switch ($sortBy) {
            case 'quantity':
                $topProducts = $topProducts->orderBy('total_quantity', 'desc');
                break;
            case 'revenue':
                $topProducts = $topProducts->orderBy('total_revenue', 'desc');
                break;
            case 'orders_count':
                $topProducts = $topProducts->orderBy('orders_count', 'desc');
                break;
        }

        $results = $topProducts->limit($limit)->get();

        return response()->json([
            'status' => true,
            'message' => "Top $limit selling products in category $categoryId for period: $period",
            'period' => $period,
            'category_id' => $categoryId,
            'sort_by' => $sortBy,
            'limit' => $limit,
            'data' => $results
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Failed to retrieve top selling products by category',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function productSalesAnalytics(Request $request)
{
    try {
        // /api/product-sales-analytics?product_id=1&period=this_month
        
        $productId = $request->query('product_id');
        $period = $request->query('period', 'this_month');

        if (!$productId) {
            return response()->json([
                'status' => false,
                'message' => 'product_id parameter is required'
            ], 400);
        }

        // Get product info
        $product = Product::find($productId);
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $query = OrderDetail::join('order_lists', 'order_details.order_id', '=', 'order_lists.id')
                           ->where('order_details.product_id', $productId);

        $this->applyDateFilter($query, $period, 'order_lists');

        $analytics = $query->select([
               DB::raw('SUM(order_details.quantity) as total_quantity_sold'),
               DB::raw('SUM(order_details.quantity * order_details.price) as total_revenue'),
               DB::raw('COUNT(DISTINCT order_details.order_id) as total_orders'),
               DB::raw('AVG(order_details.quantity) as avg_quantity_per_order'),
               DB::raw('MIN(order_details.price) as min_price'),
               DB::raw('MAX(order_details.price) as max_price'),
               DB::raw('AVG(order_details.price) as avg_price')
            ])
            ->first();

        return response()->json([
            'status' => true,
            'message' => "Sales analytics for product: {$product->name}",
            'period' => $period,
            'product' => [
                'id' => $product->id,
                'name' => $product->product_name,
                'current_price' => (float) $product->sell_price,
                'image' => $product->banner_img
            ],
            'analytics' => [
                'total_quantity_sold' => (int) ($analytics->total_quantity_sold ?? 0),
                'total_revenue' => (float) ($analytics->total_revenue ?? 0),
                'total_orders' => (int) ($analytics->total_orders ?? 0),
                'average_quantity_per_order' => (float) ($analytics->avg_quantity_per_order ?? 0),
                'price_range' => [
                    'min' => (float) ($analytics->min_price ?? 0),
                    'max' => (float) ($analytics->max_price ?? 0),
                    'average' => (float) ($analytics->avg_price ?? 0)
                ]
            ]
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Failed to retrieve product sales analytics',
            'error' => $e->getMessage()
        ], 500);
    }
}

// Helper method to apply date filters (add this private method)
private function applyDateFilter($query, $period, $tablePrefix = 'order_lists')
{
    $createdAtField = $tablePrefix . '.created_at';

    switch ($period) {
        case 'today':
            $query->whereDate($createdAtField, now()->toDateString());
            break;
        case 'last_7_days':
            $query->whereBetween($createdAtField, [
                now()->subDays(6)->startOfDay(),
                now()->endOfDay()
            ]);
            break;
        case 'this_month':
            $query->whereMonth($createdAtField, now()->month)
                  ->whereYear($createdAtField, now()->year);
            break;
        case 'year':
            $query->whereYear($createdAtField, now()->year);
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
            $query->whereMonth($createdAtField, $monthNum)
                  ->whereYear($createdAtField, now()->year);
            break;
        default:
            // If period is not recognized, return all time data
            break;
    }

    return $query;
}


public function weeklySalesChart(Request $request)
{
    try {
        $metric = $request->query('metric', 'revenue'); // revenue, quantity, orders
        $validMetrics = ['revenue', 'quantity', 'orders'];
        
        if (!in_array($metric, $validMetrics)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid metric. Use: ' . implode(', ', $validMetrics)
            ], 400);
        }

        // Get the last 7 days including today
        $chartData = [];
        $totalValue = 0;

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dayName = $date->format('l'); // Full day name (Monday, Tuesday, etc.)
            $dateString = $date->toDateString();

            // Query based on metric type
            switch ($metric) {
                case 'revenue':
                    $value = OrderList::whereDate('created_at', $dateString)
                                    ->sum('total_price');
                    break;
                    
                case 'quantity':
                    $value = OrderDetail::join('order_lists', 'order_details.order_id', '=', 'order_lists.id')
                                       ->whereDate('order_lists.created_at', $dateString)
                                       ->sum('order_details.quantity');
                    break;
                    
                case 'orders':
                    $value = OrderList::whereDate('created_at', $dateString)
                                    ->count();
                    break;
                    
                default:
                    $value = 0;
            }

            // Handle today specifically
            if ($i === 0) {
                $dayName = 'Today';
            }

            $chartData[] = [
                'x' => $dayName,
                'y' => (float) ($value ?? 0),
                'date' => $dateString
            ];

            $totalValue += (float) ($value ?? 0);
        }

        // Prepare chart format exactly like your frontend expects
        $chartResponse = [
            'series' => [
                [
                    'name' => $this->getSeriesName($metric),
                    'data' => $chartData
                ]
            ],
            'summary' => [
                'total' => $totalValue,
                'average' => $totalValue / 7,
                'metric' => $metric,
                'period' => 'Last 7 days'
            ]
        ];

        return response()->json([
            'status' => true,
            'message' => "Weekly {$metric} chart data retrieved successfully",
            'data' => $chartResponse
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Failed to retrieve weekly sales chart data',
            'error' => $e->getMessage()
        ], 500);
    }
}

/**
 * Get daily sales comparison (today vs yesterday vs last week same day)
 * /api/daily-sales-comparison?metric=revenue
 */
public function dailySalesComparison(Request $request)
{
    try {
        $metric = $request->query('metric', 'revenue');
        $validMetrics = ['revenue', 'quantity', 'orders'];
        
        if (!in_array($metric, $validMetrics)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid metric. Use: ' . implode(', ', $validMetrics)
            ], 400);
        }

        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();
        $lastWeekSameDay = now()->subDays(7)->toDateString();

        $comparisons = [];
        $dates = [
            'Today' => $today,
            'Yesterday' => $yesterday,
            'Last Week Same Day' => $lastWeekSameDay
        ];

        foreach ($dates as $label => $date) {
            switch ($metric) {
                case 'revenue':
                    $value = OrderList::whereDate('created_at', $date)
                                    ->sum('total_price');
                    break;
                    
                case 'quantity':
                    $value = OrderDetail::join('order_lists', 'order_details.order_id', '=', 'order_lists.id')
                                       ->whereDate('order_lists.created_at', $date)
                                       ->sum('order_details.quantity');
                    break;
                    
                case 'orders':
                    $value = OrderList::whereDate('created_at', $date)
                                    ->count();
                    break;
                    
                default:
                    $value = 0;
            }

            $comparisons[] = [
                'x' => $label,
                'y' => (float) ($value ?? 0),
                'date' => $date
            ];
        }

        $chartResponse = [
            'series' => [
                [
                    'name' => $this->getSeriesName($metric) . ' Comparison',
                    'data' => $comparisons
                ]
            ]
        ];

        return response()->json([
            'status' => true,
            'message' => "Daily {$metric} comparison retrieved successfully",
            'data' => $chartResponse
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Failed to retrieve daily sales comparison',
            'error' => $e->getMessage()
        ], 500);
    }
}

/**
 * Get custom period sales chart
 * /api/custom-period-sales-chart?start_date=2024-01-01&end_date=2024-01-07&metric=revenue
 */
public function customPeriodSalesChart(Request $request)
{
    try {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $metric = $request->query('metric', 'revenue');
        
        if (!$startDate || !$endDate) {
            return response()->json([
                'status' => false,
                'message' => 'start_date and end_date are required (format: YYYY-MM-DD)'
            ], 400);
        }

        // Validate dates
        try {
            $start = \Carbon\Carbon::parse($startDate);
            $end = \Carbon\Carbon::parse($endDate);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid date format. Use YYYY-MM-DD'
            ], 400);
        }

        if ($start > $end) {
            return response()->json([
                'status' => false,
                'message' => 'start_date cannot be greater than end_date'
            ], 400);
        }

        // Limit to prevent too much data
        $daysDiff = $start->diffInDays($end);
        if ($daysDiff > 31) {
            return response()->json([
                'status' => false,
                'message' => 'Date range cannot exceed 31 days'
            ], 400);
        }

        $chartData = [];
        $totalValue = 0;
        $currentDate = $start->copy();

        while ($currentDate <= $end) {
            $dateString = $currentDate->toDateString();
            $dayName = $currentDate->format('M d'); // Format: Jan 01, Jan 02, etc.

            switch ($metric) {
                case 'revenue':
                    $value = OrderList::whereDate('created_at', $dateString)
                                    ->sum('total_price');
                    break;
                    
                case 'quantity':
                    $value = OrderDetail::join('order_lists', 'order_details.order_id', '=', 'order_lists.id')
                                       ->whereDate('order_lists.created_at', $dateString)
                                       ->sum('order_details.quantity');
                    break;
                    
                case 'orders':
                    $value = OrderList::whereDate('created_at', $dateString)
                                    ->count();
                    break;
                    
                default:
                    $value = 0;
            }

            $chartData[] = [
                'x' => $dayName,
                'y' => (float) ($value ?? 0),
                'date' => $dateString
            ];

            $totalValue += (float) ($value ?? 0);
            $currentDate->addDay();
        }

        $chartResponse = [
            'series' => [
                [
                    'name' => $this->getSeriesName($metric),
                    'data' => $chartData
                ]
            ],
            'summary' => [
                'total' => $totalValue,
                'average' => $totalValue / max(1, count($chartData)),
                'metric' => $metric,
                'period' => "{$startDate} to {$endDate}",
                'days_count' => count($chartData)
            ]
        ];

        return response()->json([
            'status' => true,
            'message' => "Custom period {$metric} chart data retrieved successfully",
            'data' => $chartResponse
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Failed to retrieve custom period sales chart data',
            'error' => $e->getMessage()
        ], 500);
    }
}

/**
 * Helper method to get series name based on metric
 */
private function getSeriesName($metric)
{
    switch ($metric) {
        case 'revenue':
            return 'Revenue';
        case 'quantity':
            return 'Products Sold';
        case 'orders':
            return 'Orders Count';
        default:
            return 'Sales';
    }
}











// Add this method to your existing DataAnalystController class

/**
 * Get top selling products data for pie chart
 * /api/pie-chart-products?limit=5&period=this_month
 * /api/pie-chart-products?limit=8&period=today&metric=quantity
 */
public function pieChartProducts(Request $request)
{
    try {
        $limit = $request->query('limit', 5); // default 5 products for pie chart
        $period = $request->query('period', 'this_month'); // default current month
        $metric = $request->query('metric', 'quantity'); // quantity, revenue, orders_count
        
        // Validate parameters
        $validPeriods = ['today', 'last_7_days', 'this_month', 'year', 'jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];
        $validMetrics = ['quantity', 'revenue', 'orders_count'];
        
        if (!in_array($period, $validPeriods)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid period. Use: ' . implode(', ', $validPeriods)
            ], 400);
        }

        if (!in_array($metric, $validMetrics)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid metric. Use: ' . implode(', ', $validMetrics)
            ], 400);
        }

        if (!is_numeric($limit) || $limit <= 0 || $limit > 10) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid limit. Must be between 1 and 10'
            ], 400);
        }

        // Build the query
        $query = OrderDetail::join('products', 'order_details.product_id', '=', 'products.id')
                           ->join('order_lists', 'order_details.order_id', '=', 'order_lists.id');

        // Apply date filter
        $this->applyDateFilter($query, $period, 'order_lists');

        // Select and group data based on metric
        switch ($metric) {
            case 'quantity':
                $products = $query->select([
                        'products.id',
                        'products.product_name as name',
                        DB::raw('SUM(order_details.quantity) as value')
                    ])
                    ->groupBy('products.id', 'products.product_name')
                    ->orderBy('value', 'desc')
                    ->limit($limit)
                    ->get();
                break;
                
            case 'revenue':
                $products = $query->select([
                        'products.id',
                        'products.product_name as name',
                        DB::raw('SUM(order_details.quantity * order_details.price) as value')
                    ])
                    ->groupBy('products.id', 'products.product_name')
                    ->orderBy('value', 'desc')
                    ->limit($limit)
                    ->get();
                break;
                
            case 'orders_count':
                $products = $query->select([
                        'products.id',
                        'products.product_name as name',
                        DB::raw('COUNT(DISTINCT order_details.order_id) as value')
                    ])
                    ->groupBy('products.id', 'products.product_name')
                    ->orderBy('value', 'desc')
                    ->limit($limit)
                    ->get();
                break;
        }

        if ($products->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No sales data found for the specified period',
                'data' => [
                    'series' => [],
                    'labels' => []
                ]
            ], 200);
        }

        // Format data for pie chart
        $series = [];
        $labels = [];
        $total = $products->sum('value');

        foreach ($products as $product) {
            $series[] = (float) $product->value;
            $labels[] = $product->name;
        }

        // Calculate percentages for additional info
        $percentages = [];
        foreach ($series as $value) {
            $percentages[] = $total > 0 ? round(($value / $total) * 100, 2) : 0;
        }

        $pieChartData = [
            'series' => $series,
            'labels' => $labels,
            'chart' => [
                'width' => 380,
                'type' => 'pie'
            ],
            'summary' => [
                'total' => $total,
                'period' => $period,
                'metric' => $metric,
                'products_count' => count($series),
                'percentages' => $percentages
            ]
        ];

        return response()->json([
            'status' => true,
            'message' => "Top $limit selling products for pie chart ($metric - $period)",
            'data' => $pieChartData
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Failed to retrieve pie chart products data',
            'error' => $e->getMessage()
        ], 500);
    }
}

/**
 * Get category-wise sales data for pie chart
 * /api/pie-chart-categories?limit=6&period=this_month&metric=revenue
 */
public function pieChartCategories(Request $request)
{
    try {
        $limit = $request->query('limit', 5);
        $period = $request->query('period', 'this_month');
        $metric = $request->query('metric', 'quantity');
        
        $query = OrderDetail::join('products', 'order_details.product_id', '=', 'products.id')
                           ->join('order_lists', 'order_details.order_id', '=', 'order_lists.id')
                           ->join('categories', 'products.category_id', '=', 'categories.id');

        $this->applyDateFilter($query, $period, 'order_lists');

        switch ($metric) {
            case 'quantity':
                $categories = $query->select([
                        'categories.id',
                        'categories.type as name',
                        DB::raw('SUM(order_details.quantity) as value')
                    ])
                    ->groupBy('categories.id', 'categories.type')
                    ->orderBy('value', 'desc')
                    ->limit($limit)
                    ->get();
                break;
                
            case 'revenue':
                $categories = $query->select([
                        'categories.id',
                        'categories.type as name',
                        DB::raw('SUM(order_details.quantity * order_details.price) as value')
                    ])
                    ->groupBy('categories.id', 'categories.type')
                    ->orderBy('value', 'desc')
                    ->limit($limit)
                    ->get();
                break;
                
            case 'orders_count':
                $categories = $query->select([
                        'categories.id',
                        'categories.type as name',
                        DB::raw('COUNT(DISTINCT order_details.order_id) as value')
                    ])
                    ->groupBy('categories.id', 'categories.type')
                    ->orderBy('value', 'desc')
                    ->limit($limit)
                    ->get();
                break;
        }

        if ($categories->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No category sales data found for the specified period',
                'data' => [
                    'series' => [],
                    'labels' => []
                ]
            ], 200);
        }

        $series = [];
        $labels = [];
        $total = $categories->sum('value');

        foreach ($categories as $category) {
            $series[] = (float) $category->value;
            $labels[] = $category->name;
        }

        $percentages = [];
        foreach ($series as $value) {
            $percentages[] = $total > 0 ? round(($value / $total) * 100, 2) : 0;
        }

        $pieChartData = [
            'series' => $series,
            'labels' => $labels,
            'chart' => [
                'width' => 380,
                'type' => 'pie'
            ],
            'summary' => [
                'total' => $total,
                'period' => $period,
                'metric' => $metric,
                'categories_count' => count($series),
                'percentages' => $percentages
            ]
        ];

        return response()->json([
            'status' => true,
            'message' => "Top $limit selling categories for pie chart ($metric - $period)",
            'data' => $pieChartData
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Failed to retrieve pie chart categories data',
            'error' => $e->getMessage()
        ], 500);
    }
}

/**
 * Get mixed data for pie chart (products + other info)
 * /api/pie-chart-mixed?period=today
 */
public function pieChartMixed(Request $request)
{
    try {
        $period = $request->query('period', 'today');
        
        // Get top 3 products + total orders + total customers for today
        $query = OrderDetail::join('products', 'order_details.product_id', '=', 'products.id')
                           ->join('order_lists', 'order_details.order_id', '=', 'order_lists.id');

        $this->applyDateFilter($query, $period, 'order_lists');

        // Top 3 products by quantity
        $topProducts = $query->select([
                'products.product_name as name',
                DB::raw('SUM(order_details.quantity) as value')
            ])
            ->groupBy('products.id', 'products.product_name')
            ->orderBy('value', 'desc')
            ->limit(3)
            ->get();

        $series = [];
        $labels = [];

        // Add top products
        foreach ($topProducts as $product) {
            $series[] = (float) $product->value;
            $labels[] = $product->name;
        }

        // Add total orders and customers as additional segments
        $orderQuery = OrderList::query();
        $this->applyDateFilter($orderQuery, $period, 'order_lists');
        $totalOrders = $orderQuery->count();

        $userQuery = User::where('is_admin', 0);
        $this->applyDateFilter($userQuery, $period, 'users');
        $totalCustomers = $userQuery->count();

        if ($totalOrders > 0) {
            $series[] = (float) $totalOrders;
            $labels[] = 'Total Orders';
        }

        if ($totalCustomers > 0) {
            $series[] = (float) $totalCustomers;
            $labels[] = 'New Customers';
        }

        $pieChartData = [
            'series' => $series,
            'labels' => $labels,
            'chart' => [
                'width' => 380,
                'type' => 'pie'
            ],
            'summary' => [
                'period' => $period,
                'segments_count' => count($series)
            ]
        ];

        return response()->json([
            'status' => true,
            'message' => "Mixed pie chart data for $period",
            'data' => $pieChartData
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Failed to retrieve mixed pie chart data',
            'error' => $e->getMessage()
        ], 500);
    }


}
}
