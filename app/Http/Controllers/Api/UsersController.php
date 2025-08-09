<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
       $users = User::with('location')->latest()->where('is_admin', 0)->get();// Assuming you want to exclude admin users
        return response()->json([
            'status' => true,
            'message' => 'Get data Success',
            'data' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
       $data = $request ->validate([
            'first_name'=>'required',
            'last_name'=>'required',
            'gender'=>'required',
            'gmail'=>'required',
            'password'=>'required',
            'phone_number'=>'required',
            'location_id'=>'required|exists:locations,id',
        ]);
        User::create($data);
        return response()->json([
            'status'=> true,
            'message'=> 'User Added Success',
            'data' => $data
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
        $user = User::find($user->id);
        return response()->json([
            'status'=> true,
            'message'=> 'success',
            'data'=>$user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
        $data = $request -> validate([
            'first_name'=>'sometimes|string',
            'last_name'=>'sometimes|string',
            'gender'=>'sometimes|string',
            'gmail'=>'sometimes|email',
            'password'=>'sometimes',
            'phone_number'=>'sometimes|numeric',
            'location_id'=>'sometimes|exists:locations,id',
        ]);
        $user->update($data);
        return response()->json([
            'status'=> true,
            'message'=> 'User Update Success',
            'data'=> $data
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
        $user->delete();
        return response()->json([
            'status'=> true,
            'message'=> 'user delete'
        ]);

    }
    
}
