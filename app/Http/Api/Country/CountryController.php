<?php

namespace App\Http\Api\Country;

use App\Http\Api\Base\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends BaseController
{
    private $service;

    public function __construct()
    {
        $this->service = new CountryService();
    }

    public function index(Request $request)
    {
        return $this->service->list($request);
    }

    public function show($id)
    {
        return $this->service->show($id);
    }


}
