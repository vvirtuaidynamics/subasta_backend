<?php

namespace App\Http\Api\File;

use App\Http\Api\Base\BaseController;
use Illuminate\Http\Request;

class FileController extends BaseController
{
    private FileService $service;

    public function __construct()
    {
        $this->service = new FileService();
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
