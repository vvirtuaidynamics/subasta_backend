<?php

namespace App\Http\Api\Auth;

use App\Enums\ApiStatus;
use App\Enums\ApiResponseMessages;
use App\Enums\ApiResponseCodes;
use App\Http\Api\Auth\Requests\LoginRequest;
use App\Http\Api\Auth\Requests\RegisterUserRequest;
use App\Models\User;
use App\Traits\ApiResponseFormatTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use LaravelLang\Publisher\Concerns\Has;
use Symfony\Component\HttpFoundation\Response;

class AuthService
{
    use ApiResponseFormatTrait;

    public function login(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $firstCredentialValue = $validated['identity'];
        $firstCredentialValueType = filter_var($firstCredentialValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $user = User::where("$firstCredentialValueType", $validated['identity'])
            ->where("active", true)
            ->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return $this->sendError(
                ApiResponseMessages::INVALID_CREDENTIALS,
                ApiResponseCodes::HTTP_UNAUTHORIZED
            );
        }

        $permissions = $user->hasRole('super-admin') ? ['*'] : collect($user->getAllPermissions())->pluck('name')->toArray();

        $token = $user->createToken(config('app.name', 'Backend'),
            [...$permissions],
        )->plainTextToken;
        $data = array(
            'user' => $user,
            'token' => $token,
            'permissions' => $permissions
        );
        return $this->sendResponse(
            $data,
            ApiResponseMessages::LOGIN_SUCCESSFUL,
            ApiResponseCodes::HTTP_SUCCESS
        );

    }

    public function register(Request $request, $model = null)
    {
        $user_validated_data = null;
        try {
            $userRequest = new RegisterUserRequest();
            $validator = validator($request->all(), $userRequest->rules(), $userRequest->messages());
            $user_validated_data = $validator->validate();

        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->sendError(
                ApiResponseMessages::UNPROCESSABLE_CONTENT,
                ApiResponseCodes::HTTP_UNPROCESSABLE_CONTENT,
                $e->errors()
            );
        }

        $user = User::create($user_validated_data);
        $permissions = $user->hasRole('super-admin') ? ['*'] : collect($user->getAllPermissions())->pluck('name')->toArray();
        $token = $user->createToken(config('app.name', 'Backend'),
            [...$permissions],
        )->plainTextToken;
        $data = array(
            'user' => $user,
            'token' => $token,
        );
        return $this->sendResponse(
            $data,
            ApiResponseMessages::LOGIN_SUCCESSFUL,
            ApiResponseCodes::HTTP_SUCCESS
        );
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();
        return $this->sendResponse(null, ApiResponseMessages::LOGGED_OUT_SUCCESSFULLY);
    }

    public function refresh(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        $user = auth()->user();
        $permissions = $user->hasRole('super-admin') ? ['*'] : collect($user->getAllPermissions())->pluck('name')->toArray();

        $token = $user->createToken(config('app.name', 'Backend'),
            [...$permissions],
        )->plainTextToken;
        $data = array(
            'user' => $user,
            'token' => $token,
        );
        return $this->sendResponse(
            $data,
            ApiResponseMessages::LOGIN_SUCCESSFUL,
            ApiResponseCodes::HTTP_SUCCESS
        );

    }

    public function profile(Request $request): JsonResponse
    {
        return $this->sendResponse($request->user(), ApiResponseMessages::FETCHED_SUCCESSFULLY);
    }


}
