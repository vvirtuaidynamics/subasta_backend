<?php

namespace App\Http\Api\Bearing;


use App\Http\Api\Base\BaseController;
use Illuminate\Http\Request;

class BearingController extends BaseController
{
    private BearingService $service;

    public function __construct()
    {
        $this->service = new BearingService();
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
}
