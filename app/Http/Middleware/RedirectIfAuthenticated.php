<?php

namespace App\Http\Middleware;

use App\Enums\ApiResponseMessages;
use App\Providers\RouteServiceProvider;
use App\Traits\ApiResponseFormatTrait;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    use ApiResponseFormatTrait;

    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = $request->user();
                return $this->sendResponse($user, ApiResponseMessages::LOGIN_SUCCESSFUL);
            }
        }

        return $next($request);
    }
}
