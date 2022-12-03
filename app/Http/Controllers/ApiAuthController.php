<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiAuthController extends Controller
{
    public function register(Request $request){
        $request->validate([
            "name" => "required|min:3",
            "email" => "required|unique:users|email",
            "password" => "required|min:6|confirmed"
        ]);

        User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password)
        ]);

        if(Auth::attempt($request->only(['email','password']))){
            $token = $request->user()->createToken($request->email)->plainTextToken;
            return response()->json($token);
        }

        return response()->json(['messsage','Register failed !'],401);
    }

    public function login(Request $request){
        $request->validate([
            "email" => "required|exists:users",
            "password" => "required|min:6"
        ]);

        if(Auth::attempt($request->only(['email','password']))){
            $token = $request->user()->createToken($request->email)->plainTextToken;
            return response()->json($token);
        }

        return response()->json(['messsage'=>'Register failed !'],401);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'logout successfully.'],204);
    }

    public function get_tokens(){

    }
}