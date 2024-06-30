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

    public function register(Request $request, $model = null): JsonResponse
    {
        $registerRequest = null;
        if (!$model) {
            $registerRequest = new RegisterUserRequest($request);
        }
        //TODO Add logic to register clients and carriers

        return $this->authService->register($registerRequest);
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

