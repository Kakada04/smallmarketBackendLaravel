<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $cart = Cart::orderBy("id","desc")->get();
        return response()->json([
            "status"=> true,
            "message"=>"success",
            "data"=> $cart
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $userId = auth()->user()->id; // Get the authenticated user's ID

    $data = $request->validate([
        "product_id" => "required|exists:products,id",
        "quantity" => "required|numeric"
    ]);

    $data['user_id'] = $userId; // Add user_id to the data

    $cart = Cart::create($data);

    return response()->json([
        "status" => true,
        "message" => "success",
        "data" => $cart
    ]);
}
    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
        return response()->json([
            "status"=> true,
            "message"=> "success",
            "data"=> $cart
        ]);
    }

    public function getUserCartByProductId($productId)
    {
       $userId = auth()->user()->id;

    try {
        $cart = Cart::with('product')
            ->where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($cart && $cart->product) {
            $cart->product->full_banner_url = asset('storage/' . $cart->product->banner_img);
        }

        return response()->json([
            'status' => true,
            'message' => 'Cart retrieved successfully',
            'data' => $cart ?? null
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Error retrieving cart',
            'error' => $e->getMessage()
        ], 500);
    }
}
    public function userCartById()
    {
        $userId = auth()->user()->id; // Get the authenticated user's ID
        //
        $cart = Cart::with('product')->where("user_id", $userId)->get()->map(function ($item) {
        if ($item->product) {
            $item->product->full_banner_url = asset('storage/' . $item->product->banner_img);
        }
        return $item;
    });

        return response()->json([
            "status"=> true,
            "message"=>"success",
            "data"=> $cart
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, Cart $cart)
{
    $userId = auth()->user()->id;

    $data = $request->validate([
        "product_id" => "required|exists:products,id",
        "quantity" => "required|numeric"
    ]);

    // Find the cart item for this user and product
    $cart = Cart::where('user_id', $userId)
                ->where('product_id', $data['product_id'])
                ->first();

    if (!$cart) {
        return response()->json([
            "status" => false,
            "message" => "Cart item not found"
        ], 404);
    }

    $cart->update(['quantity' => $data['quantity']]);

    return response()->json([
        "status" => true,
        "message" => "Cart updated successfully",
        "data" => $cart
    ]);
}

    /**
     * Remove the specified resource from storage.
     */
public function destroy(Request $request, $product_id)
{
    $userId = auth()->user()->id;

    // Merge route parameter into request data for validation
    $request->merge(['product_id' => $product_id]);

    $data = $request->validate([
        'product_id' => 'required|numeric|exists:products,id',
    ]);

    // Find the cart item for this user and product
    $cart = Cart::where('user_id', $userId)
                ->where('product_id', $data['product_id'])
                ->first();

    if (!$cart) {
        return response()->json([
            'status' => false,
            'message' => 'Cart item not found'
        ], 404);
    }

    $cart->delete();

    return response()->json([
        'status' => true,
        'message' => 'Cart item deleted successfully'
    ]);
}





public function destroyByProductId(Request $request)
{
    $productId = $request->query('product_id');
    $userId = auth()->user()->id;

  $cart = Cart::where('user_id', $userId)->where('product_id', $productId)->first();

    if (!$cart) {
        return response()->json([
            "status" => false,
            "message" => "Cart item not found"
        ], 404);
    }

    $cart->delete();

    return response()->json([
        "status" => true,
        "message" => "Cart item deleted successfully"
    ]);
}
}