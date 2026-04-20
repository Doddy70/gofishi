<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $rules = [
            'username' => 'required|unique:users|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
            'dob' => 'required|date|before:-17 years',
            'age_agreement' => 'required|accepted'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 1,
            'email_verified_at' => now(), // Bypass verification for MVP
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'data' => [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // Check for User
        if (Auth::guard('web')->attempt($credentials)) {
            $user = Auth::guard('web')->user();
            
            if ($user->status == 0) {
                Auth::guard('web')->logout();
                return response()->json(['success' => false, 'message' => 'Your account is deactivated'], 403);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Logged in successfully',
                'data' => [
                    'user' => $user,
                    'role' => 'user',
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ]
            ]);
        }

        // Check for Vendor (Host)
        if (Auth::guard('vendor')->attempt($credentials)) {
            $vendor = Auth::guard('vendor')->user();

            if ($vendor->status == 0) {
                Auth::guard('vendor')->logout();
                return response()->json(['success' => false, 'message' => 'Your vendor account is deactivated'], 403);
            }

            $token = $vendor->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Logged in successfully',
                'data' => [
                    'vendor' => $vendor,
                    'role' => 'host',
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ]
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
    }

    public function loginAdmin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            $admin = Auth::guard('admin')->user();

            if ($admin->status == 0) {
                Auth::guard('admin')->logout();
                return response()->json(['success' => false, 'message' => 'Your admin account is deactivated'], 403);
            }

            $token = $admin->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Admin logged in successfully',
                'data' => [
                    'admin' => $admin,
                    'role' => $admin->role ? $admin->role->name : 'Admin',
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ]
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid admin credentials'], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}