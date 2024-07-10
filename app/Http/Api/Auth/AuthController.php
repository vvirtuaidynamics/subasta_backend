<?php

namespace App\Http\Api\Auth;

use App\Http\Api\Auth\Requests\LoginRequest;
use App\Http\Api\Auth\Requests\RegisterUserRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function register(Request $request): JsonResponse
    {
        if ($request->has('model') && $request->input('model') == "client")
            return $this->authService->registerClient($request);
        if ($request->has('model') && $request->input('model') == "carrier")
            return $this->authService->registerCarrier($request);
        return $this->authService->register($request);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        return $this->authService->login($request);
    }

    public function refresh(Request $request): JsonResponse
    {
        return $this->authService->refresh($request);
    }

    public function logout(Request $request): JsonResponse
    {
        return $this->authService->logout($request);
    }

    public function profile(Request $request): JsonResponse
    {
        return $this->authService->profile($request);
    }


}

