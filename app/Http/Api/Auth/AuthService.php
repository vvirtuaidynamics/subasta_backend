<?php

namespace App\Http\Api\Auth;

use App\Enums\ApiResponseMessages;
use App\Enums\ApiResponseCodes;
use App\Enums\ApiStatus;
use App\Enums\ValidationStatus;
use App\Http\Api\Auth\Requests\LoginRequest;
use App\Http\Api\Auth\Requests\RegisterCarrierRequest;
use App\Http\Api\Auth\Requests\RegisterClientRequest;
use App\Http\Api\Auth\Requests\RegisterUserRequest;
use App\Http\Api\DocumentCarrier\DocumentCarrierRepository;
use App\Http\Api\Client\ClientRepository;
use App\Http\Api\State\Resources\StateResource;
use App\Http\Api\User\UserRepository;
use App\Http\Api\ValidationTask\ValidationTaskRepository;
use App\Models\Client;
use App\Models\Configuration;
use App\Models\User;
use App\Traits\ApiResponseFormatTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    use ApiResponseFormatTrait;

    protected UserRepository $userRepository;
    protected ClientRepository $clientRepository;
    protected DocumentCarrierRepository $carrierRepository;
    protected ValidationTaskRepository $validationTaskRepository;

    public function __construct()
    {
        $this->userRepository = new userRepository();
        $this->clientRepository = new ClientRepository();
        $this->carrierRepository = new DocumentCarrierRepository();
        $this->validationTaskRepository = new ValidationTaskRepository();

    }

    public function login(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $firstCredentialValue = $validated['identity'];
        $firstCredentialValueType = filter_var($firstCredentialValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $user = $this->userRepository->getByColumn($firstCredentialValue, $firstCredentialValueType);
        if (!isset($user) || !$user->active) {
            return $this->sendError(
                ApiResponseMessages::USER_NOT_ACTIVE,
                ApiResponseCodes::HTTP_UNAUTHORIZED
            );
        }

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return $this->sendError(
                ApiResponseMessages::INVALID_CREDENTIALS,
                ApiResponseCodes::HTTP_UNAUTHORIZED
            );
        }

        $permissions = $user->hasRole('super-admin') ? ['*'] : collect($user->getAllPermissions())->pluck('name')->toArray();
        $user_to_return = $user;
        $user->last_login_at = now();
        $user->save();

        $modules = get_user_modules($user);

        $modules = [get_user_modules($user_to_return)];
        $token = $user_to_return->createToken(config('app.name', 'Backend'),
            [...$permissions],
        )->plainTextToken;

        $data = array(
            'user' => $user_to_return,
            'token' => $token,
            'modules' => $modules
        );
        return $this->sendResponse(
            $data,
            ApiResponseMessages::LOGIN_SUCCESSFUL,
            ApiResponseCodes::HTTP_SUCCESS
        );
    }

    public function registerClient(Request $request)
    {
        try {
            $request->merge(['active' => false]);
            $clientRequest = new RegisterClientRequest();
            $userRequest = new RegisterUserRequest();
            $userValidator = validator($request->all(), $userRequest->rules(), $userRequest->messages());
            $user_validated_data = $userValidator->validate();
            $user = $this->userRepository->create($user_validated_data);
            $request->merge(['user_id' => $user->id]);
            $clientValidator = validator($request->all(), $clientRequest->rules(), $clientRequest->messages());
            $client_validated_data = $clientValidator->validate();
            $client = $this->clientRepository->create($client_validated_data);
            $validationtask = null;
            if ($client) {
                $validationtask = $this->validationTaskRepository->create([
                    'validationable_type' => Client::class,
                    'validationable_id' => $client->id,
                    'user_id' => $client->user_id,
                    'status' => ValidationStatus::PENDING->value,
                    'notes' => 'Client registration validation task for user: ' . $user->full_name,
                ]);
            }
            $permissions = $user->hasRole('super-admin') ? ['*'] : collect($user->getAllPermissions())->pluck('name')->toArray();

            $token = $user->createToken(config('app.name', 'Backend'),
                [...$permissions],
            )->plainTextToken;
            $data = array(
                'user' => $user,
                'client' => $client,
                'validationtask' => $validationtask,
                'token' => $token,
            );
            return $this->sendResponse(
                $data,
                ApiResponseMessages::LOGIN_SUCCESSFUL,
                ApiResponseCodes::HTTP_SUCCESS
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->sendError(
                ApiResponseMessages::UNPROCESSABLE_CONTENT,
                ApiResponseCodes::HTTP_UNPROCESSABLE_CONTENT,
                $e->errors()
            );
        }

    }

    public function registerCarrier(Request $request)
    {
        try {
            $request->merge(['active' => false]);
            $userRequest = new RegisterUserRequest();
            $validator = validator($request->all(), $userRequest->rules(), $userRequest->messages());
            $user_validated_data = $validator->validate();
            $user = $this->userRepository->create($user_validated_data);
            $request->merge(['user_id' => $user->id]);
            $carrierRequest = new RegisterCarrierRequest();
            $carrierValidator = validator($request->all(), $carrierRequest->rules(), $carrierRequest->messages());
            $carrier_validated_data = $carrierValidator->validate();
            $carrier = $this->clientRepository->create($carrier_validated_data);
            $validationtask = null;
            if ($carrier) {
                $validationtask = $this->validationTaskRepository->create([
                    'validationable_type' => Client::class,
                    'validationable_id' => $carrier->id,
                    'user_id' => $carrier->user_id,
                    'status' => ValidationStatus::PENDING->value,
                    'notes' => 'Client registration validation task for user: ' . $user->full_name,
                ]);
            }
            $permissions = $user->hasRole('super-admin') ? ['*'] : collect($user->getAllPermissions())->pluck('name')->toArray();

            $token = $user->createToken(config('app.name', 'Backend'),
                [...$permissions],
            )->plainTextToken;
            $data = array(
                'user' => $user,
                'carrier' => $carrier,
                'validationtask' => $validationtask,
                'token' => $token,
            );
            return $this->sendResponse(
                $data,
                ApiResponseMessages::LOGIN_SUCCESSFUL,
                ApiResponseCodes::HTTP_SUCCESS
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->sendError(
                ApiResponseMessages::UNPROCESSABLE_CONTENT,
                ApiResponseCodes::HTTP_UNPROCESSABLE_CONTENT,
                $e->errors()
            );
        }

    }

    public function register(Request $request)
    {
        try {
            $request->merge(['active' => false]);
            $userRequest = new RegisterUserRequest();
            $validator = validator($request->all(), $userRequest->rules(), $userRequest->messages());
            $user_validated_data = $validator->validate();
            $user = $this->userRepository->create($user_validated_data);


            if (!$request->has('model')) {
            }
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
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->sendError(
                ApiResponseMessages::UNPROCESSABLE_CONTENT,
                ApiResponseCodes::HTTP_UNPROCESSABLE_CONTENT,
                $e->errors()
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

    public function setUserConfig(Request $request): JsonResponse
    {
        $user = $this->userRepository->getById(auth()->user()->getAuthIdentifier());
        $request->validate([
            'configuration' => 'required|string',
        ]);
        $configuration = $request->input('configuration');
        $data = ['configurationable_type' => User::class, 'configurationable_id' => $user->id, 'configuration' => $configuration];
        $user_config = $user->configuration()->updateOrCreate([], $data);
        return $this->sendResponse($user_config, ApiResponseMessages::UPDATED_SUCCESSFULLY, ApiResponseCodes::HTTP_SUCCESS);
    }

    public function getUserConfig(Request $request): JsonResponse
    {
        $user = $request->user();
        dd($user);
        $configuration = $user->configuration();
        return $this->sendResponse($configuration, ApiResponseMessages::FETCHED_SUCCESSFULLY, ApiResponseCodes::HTTP_SUCCESS);

    }


}
