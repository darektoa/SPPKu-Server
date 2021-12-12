<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ErrorException;
use App\Helpers\{UsernameHelper, ResponseHelper};
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\{Auth, Hash, Validator};

class AuthController extends Controller
{
	public function login(Request $request) {
		try{
			$email		= $request->email;
			$password	= $request->password;

			// ACCOUNT EXISTENCE VALIDATION
			if(
				!User::where('email', '=', $email)
				->orWhere('username', '=', $email)
				->first()
			) throw new ErrorException('Not found', ["Account doesn't exist"], 404);

			// CREDENTIAL VALIDATION
			if(!(
				Auth::attempt(['email' => $email, 'password' => $password]) ||
				Auth::attempt(['username' => $email, 'password' => $password ])
			)) throw new ErrorException('Unauthorized', ["Account doesn't match"], 401);
			
			// CREATE LOGIN TOKEN
			$userId	= auth()->user()->id;
			$user	= User::find($userId);
			$token 	= $user->tokens()->firstOrCreate(
				[],
				['token' => Hash::make($userId)],
			);
			$user->token = $token->token;

			return ResponseHelper::make(UserResource::make($user));
		}catch(ErrorException $err) {
			return ResponseHelper::error(
				$err->getErrors(),
				$err->getMessage(),
				$err->getCode(),
			);
		}
	}


    public function register(Request $request) {
		try{
			$registerAs = Str::lower($request->register_as);
			$validator	= Validator::make($request->all(), [
				'register_as' => 'required|alpha_dash',
			]);
	
			if($validator->fails()) {
				$errors	= $validator->errors()->all();	
				throw new ErrorException('Unprocessable', $errors, 422);
			}

			if($registerAs === 'user') return $this->payerRegister($request);
			if($registerAs === 'school') return $this->schoolRegister($request);
	
			throw new ErrorException('Unprocessable', [
				'Unknown value of register_as field'
			], 422);
		}catch(ErrorException $err) {
			return ResponseHelper::error(
				$err->getErrors(),
				$err->getMessage(),
				$err->getCode(),
			);
		}
    }


    public function payerRegister(Request $request) {
		try{
			$validator  = Validator::make($request->all(), [
				'name'      => 'required|min:3|max:50',
				'email'     => 'required|email|unique:users,email',
				'password'  => 'required|min:8|max:50',
			]);
	
			if($validator->fails()) {
				$errors = $validator->errors()->all();
				throw new ErrorException('Unprocessable', $errors, 422);
			}
	
			$user = User::create([
				'name'      => $request->name,
				'email'     => $request->email,
				'username'	=> UsernameHelper::email($request->email),
				'password'  => Hash::make($request->password)
			]);

			$user->payer()->create();

			return ResponseHelper::make(UserResource::make($user));
		}catch(ErrorException $err) {
			return ResponseHelper::error(
				$err->getErrors(),
				$err->getMessage(),
				$err->getCode(),
			);
		}
    }


	public function schoolRegister(Request $request) {
		try{
			$validator	= Validator::make($request->all(), [
				'name'		=> 'required|min:3|max:50',
				'email'		=> 'required|email|unique:users,email',
				'password'	=> 'required|min:8|max:50'
			]);

			if($validator->fails()) {
				$errors = $validator->errors()->all();
				throw new ErrorException('Unprocessable', $errors, 422);
			}

			$user = User::create([
				'name'		=> $request->name,
				'email'		=> $request->email,
				'username'	=> UsernameHelper::email($request->email),
				'password'	=> Hash::make($request->password),
			]);
			
			$user->school()->create();

			return ResponseHelper::make(UserResource::make($user));
		}catch(ErrorException $err) {
			return ResponseHelper::error(
				$err->getErrors(),
				$err->getMessage(),
				$err->getCode(),
			);
		}
	}
}
