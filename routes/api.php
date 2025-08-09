<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductsController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\OrderListController;
use App\Http\Controllers\Api\ClothsDetailController;
use App\Http\Controllers\Api\OrderDetailController;
use App\Http\Controllers\Api\DrinksDetailController;
use App\Http\Controllers\Api\SkincareDetailController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\DataAnalystController;
use App\Http\Controllers\Auth\AuthJWTController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
// Route::apiResource("category", CategoryController::class);
// Route::apiResource("products",ProductsController::class);
// Route::apiResource("locations", LocationController::class);
// Route::apiResource("users", UsersController::class);
// Route::apiResource("orderlist", OrderListController::class);
// Route::apiResource("orderdetail", OrderDetailController::class);
// Route::apiResource("clothsdetail", ClothsDetailController::class);
// Route::apiResource("drinkdetail", DrinksDetailController::class);
// Route::apiResource("skincaredetail", SkincareDetailController::class);
// Route::apiResource("cart", CartController::class);
// Route::apiResource("favorite", FavoriteController::class);
// Route::apiResource("Dataanalyst", DataAnalystController::class);

// Keep all three if you might want these features later


 //categories allOrders
    Route::get('/new-arrivals', [ProductsController::class, 'newArrivals']);
    Route::get('/products-high-price-first', [ProductsController::class, 'highPriceFirst']);
    Route::get('/products-low-price-first', [ProductsController::class, 'lowPriceFirst']);
    Route::get('/products-sorted', [ProductsController::class, 'productsSorted']);
    Route::get('/products-by-price-range', [ProductsController::class, 'productsByPriceRange']);    
    Route::get('/top-sale-products', [DataAnalystController::class, 'topSaleProducts']);
    Route::get('/top-sale-products-by-category', [DataAnalystController::class, 'topSaleProductsByCategory']);
    Route::get('/top-products', [ProductsController::class, 'topProductsByPeriod']);

    Route::post('/register', [AuthJWTController::class, 'register']);
    Route::post('/login', [AuthJWTController::class, 'login']);
    //locations
    Route::get('category/{categoryID}/products', [CategoryController::class, 'showProductsByCategory']);
    Route::post('/locations', [LocationController::class, 'store']);
    Route::get('/locations', [LocationController::class, 'index']);
    //Products
    Route::get('/products', [ProductsController::class, 'index']);


    Route::get('/product/productname/{productName}', [ProductsController::class, 'searchProduct']);
    Route::get('/products/{product}', [ProductsController::class, 'show']);
    Route::get('/products/productid/{productId}',[ProductsController::class,'getProductbyId']);
   

    
    
    //Categories
    Route::get('/category', [CategoryController::class, 'index']);
    Route::get('/category/{category}', [CategoryController::class, 'show']);

    

//----------------------------------Protected Routes-------------------------------------------------// 
    
    Route::middleware('auth:api')->group(function () {
        //OrderController
    Route::get('user/{userId}/orders', [OrderListController::class, 'userOrders']);
        //User Profile
    Route::get('/me', [AuthJWTController::class, 'me']);
    Route::put('/update', [AuthJWTController::class, 'update']);
    Route::delete('/logout', [AuthJWTController::class, 'logout']);
   
    // CartController
    Route::get('/cart/usercard/{productId}',[CartController::class,'getUserCartByProductId']);
   
    //orderlist
    Route::get('/userorder', [OrderListController::class, 'userOrderById']);

    Route::post('/orderlist', [OrderListController::class, 'store']);
    Route::get('/checkstatus/{orderId}', [OrderListController::class, 'checkStatus']);
    Route::post('simulatepayment', [OrderListController::class, 'simulatePayment']);
    Route::get('/pay', [OrderListController::class, 'handleQrPayment']);
    Route::get('/orderlist', [OrderListController::class, 'index']);
    
    Route::get('/orderlist/{orderlist}', [OrderListController::class, 'show']);
    //orderdetail
    Route::get('/orderdetail', [OrderDetailController::class, 'index']);
    Route::get('/orderdetail/{orderlist}', [OrderDetailController::class, 'show']);
    //add to cart and favorite
    Route::get('/usercart', [CartController::class, 'userCartById']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::put('/cartupdate', [CartController::class, 'update']);
    Route::delete('/cartdelete/{product_id}', [CartController::class, 'destroy']);


    Route::apiResource('favorite', FavoriteController::class);

//-------------------------------Protected Routes Admin Only-----------------------------------------------//
    Route::middleware(\App\Http\Middleware\IsAdmin::class)->group(function () {
       
        // //categories
        Route::post('/category', [CategoryController::class, 'store']);
        Route::put('/category/{category}', [CategoryController::class, 'update']);
        Route::delete('/category/{category}', [CategoryController::class, 'destroy']);
        // Route::post('/category', [CategoryController::class, 'store']);
        // Route::patch('/category/{category}', [CategoryController::class, 'update']);
        // Route::delete('/category/{category}', [CategoryController::class, 'destroy']);
       //prodcuts
       
        Route::get('/searchorderbyname/{username}',[OrderListController::class,'searchByuserName']);
        Route::get('/orders-by-period', [OrderListController::class, 'ordersByPeriod']);
        



       //locations
       
        Route::put('/locations/{location}', [LocationController::class, 'update']);
        Route::delete('/locations/{location}', [LocationController::class, 'destroy']);


        //users
        Route::apiResource('users', UsersController::class);

        //orderlist
        Route::get('orders/all', [OrderListController::class, 'allOrders']);
        Route::patch('/orderlist/{orderlist}', [OrderListController::class, 'update']);
        Route::delete('/orderlist/{orderlist}', [OrderListController::class, 'destroy']);


        //orderdetail
        
        Route::post('/orderdetail', [OrderDetailController::class, 'store']);
        Route::patch('/orderdetail/{orderdetail}', [OrderDetailController::class, 'update']);
        Route::delete('/orderdetail/{orderdetail}', [OrderDetailController::class, 'destroy']);


        Route::apiResource('clothsdetail', ClothsDetailController::class)->except(['index', 'show']);
        Route::apiResource('drinkdetail', DrinksDetailController::class)->except(['index', 'show']);
        Route::apiResource('skincaredetail', SkincareDetailController::class)->except(['index', 'show']);
        

        //Data Analyst
      //  Route::get('/dataanalyst/totalsales', [DataAnalystController::class, 'totalsales']);
      //  Route::get('/dataanalyst/totalorders', [DataAnalystController::class, 'totalorders']);
        //Route::get('/dataanalyst/totalusers', [DataAnalystController::class, 'totalusers']);
      //  Route::get('/dataanalyst/totalproducts', [DataAnalystController::class, 'totalproducts']);
      Route::post('/products', [ProductsController::class, 'store']);
Route::delete('/products/{product}', [ProductsController::class, 'destroy']);
Route::post('/products/{product}', [ProductsController::class, 'update']);
Route::get('/dataanalyst/totalproducts', [DataAnalystController::class, 'totalproducts']);
Route::get('/dataanalyst/allproductinstock', [DataAnalystController::class, 'allProductsInStock']);
Route::get('/dataanalyst/totalallsaleproducts', [DataAnalystController::class, 'totalAllSaleProducts']);
Route::get('/dataanalyst/totalorders', [DataAnalystController::class, 'totalorders']);
Route::get('/dataanalyst/totalrevenue', [DataAnalystController::class, 'totalRevenue']);
Route::get('/dataanalyst/totalusers', [DataAnalystController::class, 'totalusers']);
Route::get('/dataanalyst/allcustomer', [DataAnalystController::class, 'allCustomer']);
Route::get('/dataanalyst/totalusers', [DataAnalystController::class, 'totalusers']);
Route::get('/dataanalyst/buyer', [OrderListController::class, 'getUniqueCustomerCount']);


// /api/top-sale-products?period=today&limit=10&sort_by=quantity
// /api/top-sale-products?period=last_7_days&limit=5&sort_by=revenue
// /api/top-sale-products-with-categories?period=this_month&limit=20
// /api/top-sale-products-by-category?period=jan&category_id=1&limit=5
// /api/product-sales-analytics?product_id=1&period=this_month

Route::get('/weekly-sales-chart', [DataAnalystController::class, 'weeklySalesChart']);
Route::get('/daily-sales-comparison', [DataAnalystController::class, 'dailySalesComparison']); // Optional
Route::get('/custom-period-sales-chart', [DataAnalystController::class, 'customPeriodSalesChart']); // Optional
Route::get('/pie-chart-products', [DataAnalystController::class, 'pieChartProducts']);
Route::get('/pie-chart-categories', [DataAnalystController::class, 'pieChartCategories']);
Route::get('/pie-chart-mixed', [DataAnalystController::class, 'pieChartMixed']);


Route::get('/top-sale-products-with-categories', [DataAnalystController::class, 'topSaleProductsWithCategories']);

Route::get('/product-sales-analytics', [DataAnalystController::class, 'productSalesAnalytics']);

Route::get('/user', [UsersController::class, 'index']);
        Route::get('/dataanalyst/totalsaleproducts', [DataAnalystController::class, 'totalsaleproducts']);
       
    });
   
});

