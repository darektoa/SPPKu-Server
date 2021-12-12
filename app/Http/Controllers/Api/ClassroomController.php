<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ErrorException;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClassroomController extends Controller
{
    public function index() {
        $userId     = auth()->user()->id;
        $classroom  = User::with(['school.classrooms'])
            ->find($userId)
            ->school
            ->classrooms()
            ->get();

        return ResponseHelper::make($classroom);
    }


    public function store(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'name'  => 'required|max:50',
                'batch' => 'required|numeric',
            ]);

            if($validator->fails()){
                $errors = $validator->errors()->all();
                throw new ErrorException('Unprocessable', $errors, 422);
            }

            $school     = auth()->user()->school;
            $classroom  = $school->classrooms()->create([
                'name'  => $request->name,
                'batch' => $request->batch,
            ]);

            return ResponseHelper::make($classroom);
        }catch(ErrorException $err) {
            return ResponseHelper::error(
                $err->getErrors(),
                $err->getMessage(),
                $err->getCode(),
            );
        }
    }
}
