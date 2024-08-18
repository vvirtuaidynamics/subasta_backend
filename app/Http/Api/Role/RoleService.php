<?php

namespace App\Http\Api\Role;

use App\Enums\ApiResponseCodes;
use App\Enums\ApiResponseMessages;
use App\Http\Api\Base\BaseService;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;


class RoleService extends BaseService
{
    public function model(): string
    {
        return Role::class;
    }

    public function repository(): string
    {
        return RoleRepository::class;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:roles',
            'guard_name' => 'nullable|string'
        ];
    }


    public function delete($id, Request $request = null)
    {
        try {
            $user = auth()->user();
            $require_permission = strtolower($this->getBaseModel()) . ':delete';
            if (!$user || (!$user->super_admin || !in_array($require_permission, $user->permission_names)))
                $this->sendError(ApiResponseMessages::FORBIDDEN, ApiResponseCodes::HTTP_FORBIDDEN);
            $guard_name = $request && $request->has('guard_name') ? $request->input('guard_name') : 'api';
            $role = Role::findById($id, $guard_name);
            if ($role) {
                $data = $role->delete();
                if ($data)
                    return $this->sendResponse($data, ApiResponseMessages::DELETED_SUCCESSFULLY);
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
        return $this->sendError(ApiResponseMessages::NO_QUERY_RESULTS);
    }

    public function addPermission($role_id, $permission_id, Request $request)
    {
        try {
            $user = auth()->user();
            $require_permission = strtolower($this->getBaseModel()) . ':update';
            if (!$user || (!$user->super_admin || !in_array($require_permission, $user->permission_names)))
                $this->sendError(ApiResponseMessages::FORBIDDEN, ApiResponseCodes::HTTP_FORBIDDEN);
            $guard_name = $request && $request->has('guard_name') ? $request->input('guard_name') : 'api';
            $role = Role::findById($role_id, $guard_name);
            $permission = Permission::findById($permission_id, $guard_name);
            if ($role && $permission) {
                $data = $role->givePermissionTo($permission);
                $data['permissions'] = $role->getAllPermissions();
                if ($data)
                    return $this->sendResponse($data, ApiResponseMessages::UPDATED_SUCCESSFULLY);
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
        return $this->sendError(ApiResponseMessages::NO_QUERY_RESULTS);
    }

    public function removePermission($role_id, $permission_id, Request $request)
    {
        try {
            $user = auth()->user();
            $require_permission = strtolower($this->getBaseModel()) . ':update';
            if (!$user || (!$user->super_admin || !in_array($require_permission, $user->permission_names)))
                $this->sendError(ApiResponseMessages::FORBIDDEN, ApiResponseCodes::HTTP_FORBIDDEN);
            $guard_name = $request && $request->has('guard_name') ? $request->input('guard_name') : 'api';
            $role = Role::findById($role_id, $guard_name);
            $permission = Permission::findById($permission_id, $guard_name);
            if ($role && $permission) {
                $data = $role->revokePermissionTo($permission);
                $data['permissions'] = $role->getAllPermissions();

                if ($data)
                    return $this->sendResponse($data, ApiResponseMessages::UPDATED_SUCCESSFULLY);
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
        return $this->sendError(ApiResponseMessages::NO_QUERY_RESULTS);
    }

    public function syncPermissions($role_id, Request $request)
    {
        try {
            $user = auth()->user();
            $require_permission = strtolower($this->getBaseModel()) . ':update';
            if (!$user || (!$user->super_admin || !in_array($require_permission, $user->permission_names)))
                $this->sendError(ApiResponseMessages::FORBIDDEN, ApiResponseCodes::HTTP_FORBIDDEN);
            $guard_name = $request && $request->has('guard_name') ? $request->input('guard_name') : 'api';
            $role = Role::findById($role_id, $guard_name);
            $permissions = $request->has('permissions') ? explode(',', $request->input('permissions')) : [];
            $perms = [];
            foreach ($permissions as $p) {
                $perms[] = Permission::findById($p, $guard_name);
            }
            if ($role && count($perms) > 0) {
                $data = $role->syncPermissions($perms);
                $data['permissions'] = $role->getAllPermissions();
                if ($data)
                    return $this->sendResponse($data, ApiResponseMessages::UPDATED_SUCCESSFULLY);
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
        return $this->sendError(ApiResponseMessages::NO_QUERY_RESULTS);
    }


}
