<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request){
        
        $user = User::create([
            'username' => $request->username,
            'role' => $request->role,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('myapptoken')->accessToken;

        $response = [
            'user' => $user,
            'token' => $token,
            'message' => 'User has been registered'
        ];

        return response()->json($response,201);

    }

    public function login(LoginRequest $request){

        $user = User::where('username' , $request->username)->first();

        if(!$user || !Hash::check($request->password , $user->password)){
            return response([
                'message' => 'user not found!'
            ], 401);
        }

        $token = $user->createToken('myapptoken')->accessToken;

        $response = [
            'user' => $user,
            'token' => $token,
            'message' => 'User has logged in'
        ];

        return response()->json($response,200);
    }
    
    public function logout(){
        auth()->user()->tokens()->delete();

        return response()->json(['message' => 'User has logged out'],200);
    }


}