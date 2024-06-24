<?php

namespace App\Http\Api\Base;

use App\Http\Api\Base\BaseServiceInterface;
use Illuminate\Http\Request;

abstract class BaseService implements BaseServiceInterface
{

    public function list(Request $request)
    {
        // TODO: Implement list() method.
    }
    public function show( $id)
    {
        // TODO: Implement show() method.
    }

    public  function successResponse($data, $message = '', $code = 200 )
    {
        return response([
            'success' => true,
            'message' => $message,
            'data' => $data,
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
