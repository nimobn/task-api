<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\LoginUserResource;
use App\Http\Resources\LogoutUserResource;
use App\Http\Resources\RegisterUserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class AuthController extends Controller
{
    public function register(RegisterRequest $request){
        
        $user = User::create([
            'username' => $request->username,
            'role' => $request->role,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('myapptoken')->accessToken;
        $user->token = $token;
  
        return new RegisterUserResource($user);
    }

    public function login(LoginRequest $request){

        $user = User::where('username' , $request->username)->first();

        if(!$user || !Hash::check($request->password , $user->password))
            return Response::error('user not found.' , 401);

        $token = $user->createToken('myapptoken')->accessToken;
        $user->token = $token;

        return new LoginUserResource($user);
    }
    
    public function logout(){
        auth()->user()->tokens()->delete();

        return new LogoutUserResource(auth()->user());
    }


}