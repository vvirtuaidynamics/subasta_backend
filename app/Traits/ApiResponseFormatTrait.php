<?php

namespace App\Traits;

use App\Enums\ApiStatus;
use App\Enums\ApiResponseMessages;
use App\Enums\MySQLError;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

trait ApiResponseFormatTrait
{
    public function sendResponse($result, $message, $status = 200)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
            'timestamp' => Carbon::now()
        ];
        return response()->json($response, $status);
    }
    public function sendError($error, $code = 404, $errorMessages = [])
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];
        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }


    public function recordNotFoundResponse($exception)
    {
        return $this->sendError( ApiResponseMessages::NO_QUERY_RESULTS);
    }

    public function serverErrorResponse()
    {
        return $this->sendError( ApiResponseMessages::INTERNAL_SERVER_ERROR, Response::HTTP_INTERNAL_SERVER_ERROR,);
    }

    public function invalidIdDataTypeResponse()
    {
        return $this->sendError(  ApiResponseMessages::NON_NUMERIC_ID, Response::HTTP_BAD_REQUEST);
    }

    public function queryExceptionResponse($exception)
    {
        $errorCode = $exception->errorInfo[1];
        if ($errorCode == MySQLError::ER_DUP_ENTRY) {
            return $this->sendError($exception->errorInfo[2], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function forbiddenAccessResponse()
    {
        return $this->sendError(ApiResponseMessages::FORBIDDEN,  Response::HTTP_FORBIDDEN);
    }

    public function unauthorizedResponse()
    {
        return $this->sendError( ApiResponseMessages::UNAUTHORIZED,  Response::HTTP_UNAUTHORIZED);
    }

    public function logoutResponse()
    {
        return $this->sendResponse(null,ApiResponseMessages::LOGGED_OUT_SUCCESSFULLY);
    }

    public function preparedResponse($actionName, $result)
    {
        $actions = [
            'index'   => [ Response::HTTP_OK, ApiResponseMessages::RETRIEVED_SUCCESSFULLY],
            'store'   => [ Response::HTTP_CREATED, ApiResponseMessages::CREATED_SUCCESSFULLY],
            'show'    => [ Response::HTTP_OK, ApiResponseMessages::FETCHED_SUCCESSFULLY],
            'update'  => [ Response::HTTP_OK, ApiResponseMessages::UPDATED_SUCCESSFULLY],
            'destroy' => [ Response::HTTP_OK, ApiResponseMessages::TRASHED_SUCCESSFULLY]
        ];

        if (array_key_exists($actionName, $actions)) {
            return $this->sendResponse($result, $actions[$actionName][1],$actions[$actionName][0]);

       }
    }
//
//    public function recordException($e)
//    {
//        Log::error($e->getMessage() . ' in file ' . $e->getFile() . ' at line ' . $e->getLine());
//    }


}
