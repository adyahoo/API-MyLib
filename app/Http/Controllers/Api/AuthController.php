<?php

namespace App\Http\Controllers\Api;

use App\User;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request){

        $creds = $request->only(['email','password']);

        if(!$token=auth()->attempt($creds)){
            return response()->json([
                'success' => false,
                'message' => 'invalid credentials'
            ]);
        } return response()->json([
            'success' => true,
            'token' => $token,
            'user' => Auth::user()
        ]);
    }

    public function register(Request $request){

        $encryptPass = Hash::make($request->password);

        $user = new User;
        $cekEmail = User::where('email',$request->email)->first();

        if($cekEmail){
            return response()->json([
                'success' => false,
                'message' => 'Email is Already Registered'
            ]);
        }else{
            try{
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = $encryptPass;
                $user->save();
                return $this->login($request);
    
            }catch(Exception $e){
                return response()->json([
                    'success' => false,
                    'message' => ''.$e
                ]);
            }
        }        
    }

    public function logout(Request $request){
        try{
            JWTAuth::invalidate(JWTAuth::parseToken($request->token));
            return response()->json([
                'success' => true,
                'message' => 'logout success'
            ]);
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => ''.$e
            ]);
        }
    }
}
