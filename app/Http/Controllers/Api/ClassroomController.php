<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

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
}
