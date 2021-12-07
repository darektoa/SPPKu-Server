<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ErrorException;
use App\Helpers\UsernameHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\{Hash, Validator};

class AuthController extends Controller
{
    public function register(Request $request) {
		try{
			$registerAs = Str::lower($request->register_as);
			$validator	= Validator::make($request->all(), [
				'register_as' => 'required|alpha_dash',
			]);
	
			if($validator->fails()) 
				throw new ErrorException('Unprocessable', 422, $validator->errors()->all());
			if($registerAs === 'user')
				return $this->payerRegister($request);
			if($registerAs === 'school')
				return '';
	
			throw new ErrorException('Unprocessable', 422, [
				'Unknown value of register_as field'
			]);
		}catch(ErrorException $err) {
			$errCode	= $err->getCode() ?: 400;
			$errMessage = $err->getMessage();
			$errData	= $err->getErrors();

			return response()->json([
				'status'	=> $errCode,
				'message'	=> $errMessage,
				'errors'	=> $errData,
			], $errCode);
		}
    }


    public function payerRegister(Request $request) {
		try{
			$validator  = Validator::make($request->all(), [
				'name'      => 'required|min:3|max:50',
				'email'     => 'required|email|unique:users,email',
				'password'  => 'required|min:8|max:50',
			]);
	
			if($validator->fails())
				throw new ErrorException('Unprocessable', 422, $validator->errors()->all());
	
			$user = User::create([
				'name'      => $request->name,
				'email'     => $request->email,
				'username'	=> UsernameHelper::make($request->email),
				'password'  => Hash::make($request->password)
			]);
	
			return response()->json([
				'status'    => 200,
				'message'   => 'OK',
				'data'      => $user
			]);
		}catch(ErrorException $err) {
			$errCode	= $err->getCode() ?: 400;
			$errMessage	= $err->getMessage();
			$errData	= $err->getErrors();

			return response()->json([
				'status'	=> $errCode,
				'message'	=> $errMessage,
				'errors'	=> $errData,
			], $errCode);
		}
    }
}
