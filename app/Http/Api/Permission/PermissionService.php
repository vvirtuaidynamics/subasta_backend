<?php

namespace App\Http\Api\Permission;

use App\Http\Api\Base\BaseService;
use Spatie\Permission\Models\Permission;


class PermissionService extends BaseService
{
    public function model(): string
    {
        return Permission::class;
    }

    public function repository(): string
    {
        return PermissionRepository::class;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:roles',
            'guard_name' => 'nullable|string'
        ];
    }


}
