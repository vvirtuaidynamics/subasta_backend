<?php

namespace App\Http\Api\Base;

use Illuminate\Http\Request;

/**
 * Interface RepositoryContract.
 */
interface BaseServiceInterface
{

    public function successResponse($data, $message = '', $code = 200);

    public function errorResponse($message, $code, $data = []);

}
