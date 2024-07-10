<?php

namespace App\Http\Api\DocumentCarrier;


use App\Http\Api\Base\BaseController;
use Illuminate\Http\Request;

class DocumentCarrierController extends BaseController
{
    private DocumentCarrierService $service;

    public function __construct()
    {
        $this->service = new DocumentCarrierService();
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
