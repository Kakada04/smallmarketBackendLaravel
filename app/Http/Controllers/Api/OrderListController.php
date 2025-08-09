<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderList;
use SimpleSoftwareIO\QrCode\Facades\QrCode; // Note: This may not be needed
use Illuminate\Support\Facades\DB;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;








class OrderListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
       $data = OrderList::orderBy("created_at","desc")->get();
        return response()->json([
            'status'=>true,
            'message'=>'success',
            'data'=> $data,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     //
    //     $data = $request->validate([
    //     "user_id" => "required|exists:users,id",
    //     "total_price" => "required|numeric",
    //     "created_at" => "required",
    //     "order_details" => "required|array",
    //     "order_details.*.product_id" => "required|exists:products,id",
    //     "order_details.*.quantity" => "required|integer|min:1",
    //     "order_details.*.price" => "required|numeric|min:0"
    // ]);

    // $orderList = OrderList::create([
    //     "user_id" => $data["user_id"],
    //     "total_price" => $data["total_price"],
    //     "created_at" => $data["created_at"]
    // ]);

    // foreach ($data["order_details"] as $detail) {
    //     $orderList->orderDetails()->create([
    //         "product_id" => $detail["product_id"],
    //         "quantity" => $detail["quantity"],
    //         "price" => $detail["price"]
    //     ]);
    // }

    // return response()->json([
    //     "status" => true,
    //     "message" => "Order created with details",
    //     "data" => $orderList->load('orderDetails'),
    // ]);
    // }
public function store(Request $request)
{
    try {
        $data = $request->validate([
            "total_price" => "required|numeric",
            "order_details" => "required|array",
            "order_details.*.product_id" => "required|exists:products,id",
            "order_details.*.quantity" => "required|integer|min:1",
            "order_details.*.price" => "required|numeric|min:0"
        ]);

        $orderList = OrderList::create([
            "user_id" => auth()->user()->id,
            "total_price" => $data["total_price"],
            "payment_status" => 'pending'
        ]);

        foreach ($data["order_details"] as $detail) {
            $orderList->orderDetails()->create($detail);
        }

        // Generate QR code after order is saved
        //10&amount=99.99

        $qrData = "http://192.168.100.10:8000/api/pay?order_id={$orderList->id}&amount={$orderList->total_price}";

        
$result = Builder::create()
    ->writer(new PngWriter())
    ->data($qrData)
    ->encoding(new Encoding('UTF-8'))
    ->errorCorrectionLevel(ErrorCorrectionLevel::High)
    ->size(300)
    ->build();

        $qrBase64 = base64_encode($result->getString());

        return response()->json([
            "status" => true,
            "message" => "Order created with QR",
            "data" => [
                "order" => $orderList->load('orderDetails'),
                "qr_code" => $qrBase64
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            "status" => false,
            "message" => "Failed to create order: " . $e->getMessage()
        ], 500);
    }
}

public function simulatePayment(Request $request)
{
    $request->validate([
        'order_id' => 'required|exists:order_lists,id'
    ]);

    OrderList::where('id', $request->order_id)->update([
        'payment_status' => 'paid'
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Payment simulated successfully'
    ]);
}
public function checkStatus($orderId)
{
    $order = OrderList::find($orderId);

    if (!$order) {
        return response()->json([
            'status' => false,
            'message' => 'Order not found'
        ], 404);
    }

    return response()->json([
        'status' => true,
        'payment_status' => $order->payment_status
    ]);
}

public function handleQrPayment(Request $request)
{
    $orderId = $request->query('order_id');
    $amount = $request->query('amount');

    $order = OrderList::find($orderId);

    if (!$order) {
        return response()->json([
            'status' => false,
            'message' => 'Order not found'
        ], 404);
    }

    // Optional: Confirm the amount matches
    if ((float)$amount !== (float)$order->total_price) {
        return response()->json([
            'status' => false,
            'message' => 'Amount mismatch'
        ], 400);
    }

    $order->update(['payment_status' => 'paid']);

    return response()->json([
        'status' => true,
        'message' => 'Payment successful'
    ]);
}

    /**
     * Display the specified resource.
     */
    public function show(OrderList $orderlist)
    {
        //
        
        return response()->json([
            'status'=>true,
            'message'=> 'success',
            'data'=>$orderlist
        ]);
    }

public function userOrderById()
{
    $userId = auth()->id();

    $orders = OrderList::with('orderDetails.product')->where('user_id', $userId)->get();

    if ($orders->isEmpty()) {
        return response()->json([
            'status' => false,
            'message' => 'No orders found for this user'
        ], 404);
    }

    return response()->json([
        'status' => true,
        'message' => 'Success',
        'data' => $orders
    ]);
}


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrderList $orderlist)
    {
        //
        $data = $request->validate([
            "user_id"=>"sometimes|exists:users,id",
            "total_price"=>"sometimes|numeric",
            "created_at"=> "sometimes"
        ]);
        $orderlist->update($data);
        return response()->json([
            "status"=>true,
            "message"=> "success",
            'data'=>$data,
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderList $orderlist)
    {
        //
        $orderlist->delete();
        return response()->json([
            'status'=>true,
            'message'=>'success',
            

        ]);
    }


    public function userOrders($userId)
{
    $orders = OrderList::with(['orderDetails', 'user'])
        ->where('user_id', $userId)
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($order) {
            $order->total_product = $order->orderDetails->count('id');
            return $order;
        });


    

    return response()->json([
        'status' => true,
        'message' => 'User orders',
        'data' => $orders->map(function ($order) {
            $orderArray = $order->toArray();
            unset($orderArray['total_products']); // Remove total_products if it exists
            return $orderArray;
        }),
        
    ]);
}

public function searchByuserName($username){
 $data = OrderList::with('user')
        ->whereHas('user', function ($query) use ($username) {
            $query->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$username}%"]);
        })
        ->get()->map(function ($order) {
            $order->total_product = $order->orderDetails->count('id');
            return $order;
        });

     return response()->json([
        'status' => true,
        'message' => 'User orders',
        'data' => $data
        
    ]);
}
public function allOrders()
{
    $orders = OrderList::with(['user', 'orderDetails']) // ✅ load orderDetails too
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($order) {
            $order->total_product = $order->orderDetails->count('id'); // ✅ will now work
            return $order;
        });

    return response()->json([
        'status' => true,
        'message' => 'All orders',
        'data' => $orders
    ]);
}

public function getUniqueCustomerCount()
    {
        $uniqueCustomerCount = OrderList::distinct('user_id')->count('user_id');
        
        return response()->json([
            'data' => $uniqueCustomerCount,
            'message' => 'Unique customer count retrieved successfully'
        ], 200);
    }







    public function ordersByPeriod(Request $request)
{
    //orders-by-period?period=today

    $period = $request->query('period', 'today'); // default to 'today'
    $query = OrderList::with('user');

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
            // If period is not recognized, no date filter is applied
            break;
    }

    $data = $query->get()->map(function ($order) {
            $order->total_product = $order->orderDetails->count('id');
            return $order;
        });

    return response()->json([
        'status' => true,
        'message' => "Orders for period: $period",
        'period' => $period,
        'data' => $data
    ]);
}
}
