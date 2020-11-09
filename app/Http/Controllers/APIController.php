<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class APIController extends Controller
{
    function loginAndroid(Request $request){
        $login = DB::table('users')->where('username',$request->username)->where('password',$request->password)->get();

        if(count($login)>0){
            foreach($login as $log){
                $hasil["success"] = '1';
                $hasil["message"] = 'Login Success';
                //ambil data yg berhasil login
                $hasil["id"] = $log->id;
                $hasil["username"] = $log->username;
                $hasil["password"] = $log->password;
            }
            echo json_encode($hasil)
        }else{
            $hasil["success"] = '0';
            $hasil["message"] = 'Fail';
            echo json_encode($hasil);
        }
    }
}
