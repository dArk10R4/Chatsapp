<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register','logout']]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        if (auth()->attempt($credentials)) {
            $user = Auth::user();
            $user['token'] = $user->createToken('Laravelia')->accessToken;
            return response()->json([
                'user' => $user
            ], 200);
        }
        return response()->json([
            'message' => 'Invalid credentials'
        ], 402);
    }

    public function register(Request $request)
    {
        // return $request;
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
        if (User::userExistsWithEmail($request->email)) {
            // User already exists, handle accordingly
            return response()->json(['message' => 'User already exists'], 409); // You can customize the response as needed
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ]);
        // dd(31);
    }

    public function logout(Request $request)
    {
        // dd(Auth::check());
        // if (!Auth::check()) {
        //     // User is not authenticated, handle it accordingly
        //     return response()->json(['message' => 'Unauthorized'], 401);
        // }
        // echo "salam";
        // Auth::user()->tokens()->revoke();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }
}
