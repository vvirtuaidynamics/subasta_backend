<?php

namespace App\Http\Middleware;

use App\Enums\ApiResponseCodes;
use App\Enums\ApiResponseMessages;
use App\Enums\ApiStatus;
use App\Traits\ApiResponseFormatTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenIsValid
{
    use ApiResponseFormatTrait;

    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = $request->route()->getName();

        $isPublic = in_array($routeName, config('app.public_routes', ['home', 'login', 'register', 'dev']));
        if (!$isPublic && Auth::guard('sanctum')->guest()) {
            return $this->sendError(ApiResponseMessages::UNAUTHORIZED, ApiResponseCodes::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
