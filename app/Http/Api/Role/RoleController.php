<?php

namespace App\Http\Api\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new RoleService();
    }

    public function list(Request $request)
    {
        return $this->service->list($request);
    }

    public function view($id, Request $request)
    {
        return $this->service->view($id);
    }

    public function store(Request $request)
    {
        return $this->service->create($request);
    }

    public function update($id, Request $request)
    {
        return $this->service->update($id, $request);
    }

    public function delete($id, Request $request)
    {
        return $this->service->delete($id, $request);
    }

    public function addPermission($role_id, $permission_id, Request $request)
    {
        return $this->service->addPermission($role_id, $permission_id, $request);
    }

    public function syncPermissions($role_id, Request $request)
    {
        return $this->service->syncPermissions($role_id, $request);
    }

    public function removePermission($role_id, $permission_id, Request $request)
    {
        return $this->service->removePermission($role_id, $permission_id, $request);
    }

}
