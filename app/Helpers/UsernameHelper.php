<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class UsernameHelper{
	static public function make(string $string) {
		$string		= Str::limit($string, 20, '');
		$string		= Str::slug($string, '.');
		$rules		= 'regex:/[a-z0-9\._]{5,15}/i|unique:users,username';
		$validator	= validator::make([$string], [0 => $rules]);
		$unique		= false;

		if($validator->fails())
			$string	= Str::limit($string, 15, '');
		else
			$unique = true;

		while(!$unique) {
			$newString	= $string . rand(0, 99999);
			$validator	= Validator::make([$string], [0 => $rules]);

			if($validator->fails()) continue;

			$string	= $newString;
			$unique	= true;
		};

		return $string;
	}
}