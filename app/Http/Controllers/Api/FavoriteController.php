<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = Favorite::all();
        return response()->json([
            "status"=>true,
            "message"=>"success",
            "data"=> $data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validate = $request->validate([
             
             "product_id"=>'required|exists:products,id',
             "quality"=>'required'
        ]);
        $userId = Auth()->user()->id;
        $data=array_merge($validate,['user_id'=>$userId]);
       Favorite::create($data);
       return response()->json([
        'status'=>true,
        'message'=> 'success',
        'data'=> $data

       ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Favorite $favorite)
    {
        //
        return response()->json([
            'status'=>true,
            'message'=> 'success',
            'data'=> $favorite
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Favorite $favorite)
    {
        //
        $data = $request->validate([
             "user_id"=>'sometimes|exists:users,id',
             "product_id"=>'sometimes|exists:products,id',
             "quality"=>'sometimes'
        ]);

       $favorite->update($data);
       return response()->json([
        'status'=>true,
        'message'=> 'success',
        'data'=> $data
         ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Favorite $favorite)
    {
        //
        $favorite->delete();
        return response()->json([
            'status'=>true,
            'message'=> 'Success'
        ]);
    }
}
