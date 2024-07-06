<?php

namespace App\Http\Api\State;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StateController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new StateService();
    }

    public function list(Request $request)
    {
        return $this->service->list($request);
    }

    public function view($id)
    {
        return $this->service->view($id);
    }


}
