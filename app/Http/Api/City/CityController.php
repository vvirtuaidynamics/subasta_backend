<?php

namespace App\Http\Api\City;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CityController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new CityService();
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
