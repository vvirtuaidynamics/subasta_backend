<?php

namespace App\Http\Api\Base;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public  function successResponse($data, $message = '', $code = 200, )
    {
        return response([
            'success' => true,
            'data' => $data,
            'message' => $message
        ], $code);
    }

    public function errorResponse($message, $code, $data = [])
    {
        return response([
            'success' => false,
            'message' => $message,
            'data' => $data

        ], $code);
    }
}
