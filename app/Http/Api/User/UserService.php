<?php

namespace App\Http\Api\User;

use App\Enums\ApiResponseMessages;
use App\Http\Api\Base\BaseService;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Class UserRepository.
 */
class UserService extends BaseService
{
    public function model(): string
    {
        return User::class;
    }

    public function repository(): string
    {
        return UserRepository::class;
    }

    public function rules(): array
    {
        return [
            'username' => 'required|string|max:50|unique:users',
            'name' => 'required|string|max:50',
            'surname' => 'nullable|string|max:50',
            'email' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed', // 'have to receive password_confirmation'
            'active' => 'boolean|nullable',
            'configuration' => 'text|nullable',
            'avatar' => 'image|nullable|mimes:jpeg,png,jpg,gif|max:2048'
        ];
    }

    public function mergeRequestBefore(Request $request): Request
    {
        if (!$request->has('active'))
            $request = $request->merge(['active' => 0]);
        return $request;
    }

    public function roles($id, Request $request)
    {
        try {
            $user = auth()->user();
            $require_permission = strtolower($this->getBaseModel()) . ':update';
            if (!$user || (!$user->super_admin || !in_array($require_permission, $user->permission_names)))
                $this->sendError(ApiResponseMessages::FORBIDDEN, ApiResponseCodes::HTTP_FORBIDDEN);

            $model_user = $this->repository->getById($id);
            if ($model_user) {
                $data = $model_user->getRoleNames();
                if ($data)
                    return $this->sendResponse($data, ApiResponseMessages::FETCHED_SUCCESSFULLY);

            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
        return $this->sendError(ApiResponseMessages::NO_QUERY_RESULTS);


    }

    public function permissions($id, Request $request)
    {
        try {
            $user = auth()->user();
            $require_permission = strtolower($this->getBaseModel()) . ':update';
            if (!$user || (!$user->super_admin || !in_array($require_permission, $user->permission_names)))
                $this->sendError(ApiResponseMessages::FORBIDDEN, ApiResponseCodes::HTTP_FORBIDDEN);

            $model_user = $this->repository->getById($id);
            if ($model_user) {
                $data = collect($model_user->getAllPermissions())->map(function ($permission) {
                    return [
                        'id' => $permission->id,
                        'name' => $permission->name,
                    ];
                });
                if ($data)
                    return $this->sendResponse($data, ApiResponseMessages::UPDATED_SUCCESSFULLY);

            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
        return $this->sendError(ApiResponseMessages::NO_QUERY_RESULTS);
    }


}
