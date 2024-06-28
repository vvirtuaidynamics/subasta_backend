<?php

namespace App\Http\Api\Auth;

use App\Enums\ApiStatus;
use App\Enums\ApiResponseMessages;
use App\Enums\ApiResponseCodes;
use App\Http\Api\Auth\Requests\LoginRequest;
use App\Models\User;
use App\Traits\ApiResponseFormatTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use LaravelLang\Publisher\Concerns\Has;
use Nette\Schema\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AuthService
{
    use ApiResponseFormatTrait;

    public function login(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $firstCredentialValue = $validated['identity'];
        $firstCredentialValueType = filter_var($firstCredentialValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $user = User::where("$firstCredentialValueType", $validated['identity'])->first();

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
            ,
            $data
        );

    }

    public function register(Request $request, $model = null)
    {
        try {
            $user_validator = $request->validate([
                'username' => 'required|string|min:3|unique:users,username',
                'name' => 'required|string',
                'surname' => 'required|string',
                'email' => 'required|string|email|unique:users,email',
                'password' => 'required|string|min:5',
                'avatar' => 'image|mimes:jpeg,png,jpg,gif|max:10240',
            ]);
            $user = User::create($user_validator);
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
                ,
                $data
            );

        } catch (ValidationException $ex) {
            return $this->sendError(
                ApiResponseMessages::UNPROCESSABLE_CONTENT,
                ApiResponseCodes::HTTP_UNPROCESSABLE_CONTENT,
                $ex->errors()
            );
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();
        return $this->sendResponse(null, ApiResponseMessages::LOGGED_OUT_SUCCESSFULLY);

    }

    public function refresh(Request $request)
    {

    }

    public function profile(Request $request): JsonResponse
    {
        return $this->sendResponse($request->user(), ApiResponseMessages::FETCHED_SUCCESSFULLY);
    }


}
