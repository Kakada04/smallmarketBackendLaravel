<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DrinkDetail;

class DrinksDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = DrinkDetail::all();
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
        
            "description"=>"required",
        ]);

        DrinkDetail::create($data);

        $data = DrinkDetail::all();
        return response()->json([
            "status"=>true,
            "message"=>"success",
            "data"=> $data,
        ]);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(DrinkDetail $drinkdetail)
    {
        //
        return response()->json([
            "status"=>true,
            "message"=> "success",
            "data"=> $drinkdetail
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DrinkDetail $drinkdetail)
    {
        //
        $data = $request->validate([
            "product_id"=>"sometimes|exists:products,id",
        
            "description"=>"sometimes",
        ]);

        $drinkdetail->update($data);

       
        return response()->json([
            "status"=>true,
            "message"=>"success",
            "data"=> $data,
        ]);


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DrinkDetail $drinkdetail)
    {
        //
        $drinkdetail->delete();
        return response()->json([
            "status"=>true,
            "message"=> "success",
        ]);
    }
}
