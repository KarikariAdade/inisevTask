<?php

namespace App\Helpers;

class HelperFunctions
{
    public function successResponse($data)
    {
        return response()->json([
            'code' => 200,
            'data' => $data
        ]);
    }


    public function failResponse($data)
    {
        return response()->json([
            'code' => 401,
            'data' => $data
        ]);
    }
}
