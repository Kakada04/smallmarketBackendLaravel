<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\skincareDetail;

class SkincareDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $skincaredetail = SkincareDetail::all();
        return response()->json([
            'status'=>true,
            'message'=>'success',
            'data'=>$skincaredetail
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $request->validate([
            "product_id"=>"required|exists:products,id",
        "description"=>"required",
        ]);
        SkincareDetail::create($data);
        return response()->json([
            "status"=>true,
            "message"=> "success",
            "data"=>$data

        ]);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(skincareDetail $skincaredetail)
    {
        //
        $data = $skincaredetail->get();
        return response()->json([
            "status"=>true,
            "message"=> "success",
            "data"=> $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, skincareDetail $skincaredetail)
    {
        //
        $data = $request->validate([
            "product_id"=>"sometimes|exists:products,id",
        "description"=>"sometimes",
        ]);
        $skincaredetail->update($data);
        return response()->json([
            "status"=>true,
            "message"=> "success",
            "data"=>$data

        ]);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(skincareDetail $skincaredetail)
    {
        //
        $skincaredetail->delete();
        return response()->json([
            "status"=>true,
            "message"=> "deleted"
        ]);
    }
}
