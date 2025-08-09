<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class AuthJWTController extends Controller
{
    //
    public function register(Request $request)
{
    // Validate the request data
    $data = $request->validate([
        'first_name'=>'required|string|max:255',
        'last_name'=>'required|string|max:255',
        'gender'=>'required|string',
        'gmail'=>'required|email|max:255|unique:users,gmail',
        'password'=>'required',
        'phone_number'=>'required',
        'is_admin'=>'boolean',
        'location_id'=>'required|exists:locations,id',
    ]);

    // Hash the password before saving
    $data['password'] = bcrypt($data['password']);

    // Create the user
    $user = User::create($data);

    // Attempt to log the user in and generate a token
    $token = auth()->attempt([
        'gmail' => $data['gmail'],
        'password' => $request->password
    ]);

    return response()->json([
        'status'=> true,
        'message'=> 'User Added Success',
        'data' => $user,
        'token' => $token
    ]);
}
    public function login(){
        // Validate the request data
        $credentials = request(['gmail', 'password']);
        
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'token' => $token,
            'is_admin' => auth()->user()->is_admin,
        ]);
        
    }
    public function me(){
        // Get the authenticated user
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'status' => true,
            'message' => 'User data retrieved successfully',
            'data' => $user
        ]);
        
    }
    public function update(Request $request)
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $data = $request->validate([
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'gender' => 'sometimes|string|in:male,female,other',
            'gmail' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('users', 'gmail')->ignore($user->id)
            ],
            'password' => 'sometimes|string|min:8',
            'phone_number' => 'sometimes|string|max:20',
            'is_admin' => 'sometimes|boolean',
            'location_id' => 'sometimes|exists:locations,id'
        ]);

        // Hash password if provided
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return response()->json([
            "status" => true,
            "message" => "Profile updated successfully",
            "data" => $user->fresh()
        ]);
    }

    public function logout(){
        // Invalidate the token
        auth()->logout();
        
        return response()->json([
            'status' => true,
            'message' => 'Logout successful'
        ]);
        
    }
}
