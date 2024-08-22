<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUser;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    function login(LoginUserRequest $request) {
        $user = User::where('email',$request->email)->first();
        if(!$user || !Hash::check($request->password,$user->password)){
            return response()->json([
                'status' => false,
                'message' => 'Invalid Credentials'
            ],401);
        }
        $token = $user->createToken($user->name.'-AuthToken')->plainTextToken;
        return response()->json([
            'status' => true,
            'access_token' => $token,
        ]);
    }
    function register(RegisterUser $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'photo' => $request->photo->store('image/user', 'public'),
            ]);
            return response()->json([
                'status' => true,
                'message' => 'User Created ',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Server Error',
            ], 500);
        }
    }


}
