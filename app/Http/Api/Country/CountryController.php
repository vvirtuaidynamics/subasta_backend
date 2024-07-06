<?php

namespace App\Http\Api\Country;

use App\Http\Api\Base\BaseController;
use Illuminate\Http\Request;

class CountryController extends BaseController
{
    private $service;

    public function __construct()
    {
        $this->service = new CountryService();
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
