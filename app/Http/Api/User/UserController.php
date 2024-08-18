<?php

namespace App\Http\Api\User;


use App\Http\Api\Base\BaseController;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    private UserService $service;

    public function __construct()
    {
        $this->service = new UserService();
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

    public function delete($id)
    {
        return $this->service->delete($id);
    }

    public function roles($id, Request $request)
    {
        return $this->service->roles($id, $request);
    }

    public function permissions($id, Request $request)
    {
        return $this->service->permissions($id, $request);
    }


}
