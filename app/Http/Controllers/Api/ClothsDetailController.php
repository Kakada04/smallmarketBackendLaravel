<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClothDetail;

class ClothsDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = ClothDetail::all();	

        return response()->json([
            "status"=>true,
            "message"=>"success",
            "data"=> $data,
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
        "size"=>"required",
        "description"=>"required",
        ]);
        ClothDetail::create($data);
        return response()->json([
            "status"=>true,
            "message"=> "success",
            "data"=> $data,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ClothDetail $clothsdetail)
    {
        //
           
        return response()->json([
            "status"=>true,
            "message"=> "success",
            "data"=> $clothsdetail
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClothDetail $clothsdetail)
    {
        //
        $data = $request->validate([
            "product_id"=>"required|exists:products,id",
        "size"=>"required",
        "description"=>"required",
        ]);
        $clothsdetail->update($data);
        return response()->json([
            "status"=>true,
            "message"=> "success",
            "data"=> $data,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClothDetail $clothsdetail)
    {
        //
        $clothsdetail->delete();
        return response()->json([
            "status"=>true,
            "message"=> "deleted"
        ]);
    }
}
