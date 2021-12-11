<?php

namespace App\Helpers;

class ResponseHelper {
    static public function make($data=[], string $message='OK', $status=200) {
        return response()->json([
            'status'  => $status,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }


    static public function error($errors=[], string $message='Failed', $status=500) {
        return response()->json([
            'status'    => $status,
            'message'   => $message,
            'errors'    => $errors,
        ], $status);
    }
}