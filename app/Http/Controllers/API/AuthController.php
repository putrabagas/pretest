<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;

class AuthController extends Controller
{
    public function register(Request $request): Response
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 400,
                'errors' => $validator->errors()
            ], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $token = $user->createToken('AuthToken')->accessToken;

        return response([
            'code' => 201,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'token' => $token
            ]
        ], 201);
    }

    public function login(Request $request): Response
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 400,
                'errors' => $validator->errors()
            ], 400);
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $token = Auth::user()->createToken('AuthToken')->accessToken;
            return response([
                'code' => 200,
                'data' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'token' => $token
                ]
            ], 200);
        } 
        else{ 
            return response([
                'code' => 401,
                'errors' => [
                    'message' => 'Incorrect email or password'
                    ]
            ], 401);
        } 
    }

    public function logout(): Response
    {
        auth()->user()->tokens()->each(function ($token, $key) {
            $token->revoke();
        });

        return response([
            'code' => 200,
            'data' => [
                'message' => 'Logged out successfully'
                ]
        ], 200);
    }
}
