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

    /**
     * @OA\Get(
     *     path="/api/country",
     *     tags={"Country"},
     *     summary="Get a countries list",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="limit", type="integer", example="30", default="30"),
     *             @OA\Property(property="page", type="integer", example="1"),
     *             @OA\Property(property="order", type="array", example='"orders": [{"column": "phone_code","direction": "desc"},{"column": "name","direction": "desc"}]'),
     *
     *         )
     *     ),
     *     @OA\Response(response=200, description="Countries get successfully"),
     *     @OA\Response(response=400, description="Bad Request"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function index(Request $request)
    {
        return $this->service->list($request);
    }

    /**
     * @OA\Get(
     *     path="/api/country/{id}",
     *     tags={"Country"},
     *     summary="Get a country with id",
     *     @OA\Request(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id"},
     *            @OA\Property(property="id", type="integer", example="1"),
     *         )
     *     ),
     *     @OA\Response(response=200, description="Country get successfully"),
     *     @OA\Response(response=400, description="Bad Request"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden")
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function show($id)
    {
        return $this->service->show($id);
    }




}
