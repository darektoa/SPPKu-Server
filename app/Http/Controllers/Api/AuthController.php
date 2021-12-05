<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request) {
        try{
            

            return response()->json([
                'status'    => 200,
                'message'   => 'OK',
                'data'      => []
            ]);
        }catch(Exception $err) {
            $errCode    = $err->getCode() ?: 400;
            $errMessage = $err->getMessage();
            
            return response()->json([
                'status'    => $errCode,
                'message'   => $errMessage
            ], $errCode);
        }
    }
}
