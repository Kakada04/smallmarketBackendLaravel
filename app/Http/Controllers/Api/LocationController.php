<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $locations = Location::all();
        return response()->json([
            "data"=> $locations
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $request->validate([
            "location"=> 'required|string'
        ]);
        Location::create($data);
        return response()->json([
            'status'=>true,
            'message'=> 'added success',
            'data'=> $data
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Location $location)
    {
        //
        return response()->json([
            'status'=>true,
            'message'=> 'sucess',
            'data'=> $location
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Location $location)
    {
        //
        $data = $request->validate([
            "location"=> 'required|string'
        ]);
        $location->update($data);
        return response()->json([
            'status'=>true,
            'message'=> 'update success',
            'data'=> $data
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        //
        $location->delete();
        return response()->json([
            'status'=>true,
            'message'=> 'delete success'
        ]);
    }
}
