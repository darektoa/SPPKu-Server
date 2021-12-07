<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\{Hash, Validator};

class AuthController extends Controller
{
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'register_as' => 'required|alpha_dash',
        ]);

        if($validator->fails()) return response()->json([
			'status'    => 422,
			'message'   => 'Unprocessable',
			'errors'    => $validator->errors()->all(),
		]);

        $registerAs = Str::lower($request->register_as);

        if($registerAs === 'user') return $this->payerRegister($request);
        if($registerAs === 'school') return '';

        return response()->json([
            'status'    => 422,
            'message'   => 'Unprocessable',
            'errors'    => ['Unknown value of register_as field']
        ]);
    }


    public function payerRegister(Request $request) {
		try{
			$validator  = Validator::make($request->all(), [
				'name'      => 'required|min:3|max:50',
				'email'     => 'required|email',
				'password'  => 'required|min:8|max:50',
			]);
	
			if($validator->fails()) return response()->json([
				'status'    => 422,
				'message'   => 'Unprocessable, Invalid field',
				'errors'    => $validator->errors()->all(),
			]);
	
			$user = User::create([
				'name'      => $request->name,
				'email'     => $request->email,
				'username'	=> 'Test',
				'password'  => Hash::make($request->password)
			]);
	
			return response()->json([
				'status'    => 200,
				'message'   => 'OK',
				'data'      => $user
			]);
		}catch(Exception $err) {
			$errCode	= $err->getCode() ?: 400;
			$errMessage	= $err->getMessage();

			return response()->json([
				'status'	=> $errCode,
				'message'	=> $errMessage,
			], $errCode);
		}
    }
}
