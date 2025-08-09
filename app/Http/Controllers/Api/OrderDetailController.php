<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\OrderDetail;

class OrderDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $orders = OrderDetail::orderBy("created_at","desc")->get();
        return response()->json([
            'status'=>true,
            'message'=>'success',
            'data'=>$orders
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $request->validate([
            "order_id"=>'required|exists:order_lists,id',
            "product_id"=>'required|exists:products,id',
            "quantity"=>'required',
            'price'=> 'required',
        ]);
        OrderDetail::create( $data );
        return response()->json([
            'status'=>true,
            'message'=>'success',
            'data'=>$data
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(OrderDetail $orderdetail)
    {
        //
        return response()->json([
            'status'=>true,
            'message'=> 'success',
            'data'=>$orderdetail
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrderDetail $orderdetail)
    {
        //
        $data = $request->validate([
            "order_id"=>'sometimes|exists:order_lists,id',
            "product_id"=>'sometimes|exists:products,id',
            "quantity"=>'sometimes',
            'price'=> 'sometimes',
        ]);
       $orderdetail->update( $data );
        return response()->json([
            'status'=>true,
            'message'=>'success',
            'data'=>$data
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderDetail $orderdetail)
    {
        //
        $orderdetail->delete();
        return response()->json([
            'status'=>true,
            'message'=>'deleted success'
        ]);
    }
}
