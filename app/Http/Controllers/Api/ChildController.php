<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ErrorException;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\{Student, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChildController extends Controller
{
    public function index() {
        $userId = auth()->user()->id;
        $childs = User::with(['payer.children'])
            ->find($userId)
            ->payer
            ->children()
            ->get();

        return ResponseHelper::make($childs);
    }


    public function store(Request $request) {
        try{
            $validator  = Validator::make($request->all(), [
                'code'  => 'required|size:8|exists:students,code'
            ]);

            if($validator->fails()) {
                $errors = $validator->errors()->all();
                throw new ErrorException('Unprocessable', $errors, 422);
            }

            $code     = $request->code;
            $student  = Student::where('code', '=', $code)->first();
            $payer    = auth()->user()->payer;
            $child    = $payer->children()->firstOrCreate([
                'student_id'  => $student->id,
            ]);

            return ResponseHelper::make($child);
        }catch(ErrorException $err) {
            return ResponseHelper::error(
                $err->getErrors(),
                $err->getMessage(),
                $err->getCode(),
            );
        }
    }
}
