<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ErrorException;
use App\Helpers\{RandomCodeHelper, ResponseHelper};
use App\Http\Controllers\Controller;
use App\Models\Classroom;
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


    public function indexStudent(Classroom $classroom) {
        try{
            $students = $classroom->students()->get();

            return ResponseHelper::make($students);
        }catch(ErrorException $err) {
            return ResponseHelper::error(
                $err->getErrors(),
                $err->getMessage(),
                $err->getCode(),
            );
        }
    }


    public function storeStudent(Request $request, Classroom $classroom) {
        try{
            $validator  = Validator::make($request->all(), [
                'name'          => 'required|max:100',
                'id_number'     => 'required|numeric|digits_between:1,20',
                'birth_date'    => 'nullable|date',
                'birth_place'   => 'nullable|max:50',
                'address'       => 'nullable|max:300',
            ]);

            if($validator->fails()) {
                $errors = $validator->errors()->all();
                throw new ErrorException('Unprocessable', $errors, 422);
            }

            $student = $classroom->students()->create([
                'code'          => RandomCodeHelper::make(),
                'name'          => $request->name,
                'id_number'     => $request->id_number,
                'birth_date'    => $request->birth_date,
                'birth_place'   => $request->birth_place,
                'address'       => $request->address,
            ]);

            return ResponseHelper::make($student);
        }catch(ErrorException $err) {
            return ResponseHelper::error(
                $err->getErrors(),
                $err->getMessage(),
                $err->getCode(),
            );
        }
    }
}
