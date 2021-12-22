<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

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
}
