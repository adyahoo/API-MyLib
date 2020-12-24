<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function editProfile(Request $request){
        $user = User::find(Auth::user()->id);

        try{
            $user->email = $request->email;
            $user->name = $request->name;
            $user->update();

            return response()->json([
                'success' => true,
                'message' => "Update Profile Success"
            ]);
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => ''.$e
            ]);
        }        
    }
}
