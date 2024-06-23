<?php

namespace App\Http\Api\Country;

use App\Http\Api\Base\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends BaseController
{
    private $countryRepository;

    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/country",
     *     tags={"Country"},
     *     summary="Get a countries list",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="limit", type="integer", example="25"),
     *             @OA\Property(property="page", type="integer", example="1"),
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
        if(!isset($this->countryRepository)) $this->countryRepository = new CountryRepository();
        $columns = $request->has('columns') ? $request->toArray('columns'):['*'];
        // Orden
        if($request->has('page')){
            $page = $request->integer('page');
            $limit = $request->has('limit') ? $request->integer('limit'): config('app.page_default_size');
            $data = $this->countryRepository->paginate($limit, $columns, 'page', $page );
            return $this->successResponse($data);
        }

        if($request->has('order')){
            $orderBys = $request->input('order');
            dd($orderBys);


        }

        $data = CountryResource::collection($this->countryRepository->all($columns));
        return $this->successResponse($data);
    }

    /**
     * @OA\Get(
     *     path="/api/country/{country_id}",
     *     tags={"Country"},
     *     summary="Get a country with country_id",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"country_id"},
     *            @OA\Property(property="country_id", type="integer", example="1"),
     *         )
     *     ),
     *     @OA\Response(response=200, description="Country get successfully"),
     *     @OA\Response(response=400, description="Bad Request"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function show($id)
    {
        //
        if(!isset($this->countryRepository)) $this->countryRepository = new CountryRepository();
        $data = new CountryResource($this->countryRepository->getById($id));
        return $this->successResponse($data);

    }

    public function store(Request $request)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
