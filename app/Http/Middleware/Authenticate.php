<?php

namespace App\Http\Middleware;

use App\Enums\ApiResponseMessages;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function unauthenticated($request, array $guards)
    {
        abort(response()->json(['success' => false, 'message' => ApiResponseMessages::UNAUTHORIZED], 401));
    }
}
