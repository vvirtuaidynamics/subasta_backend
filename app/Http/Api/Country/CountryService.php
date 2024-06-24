<?php

namespace App\Http\Api\Country;

use App\Http\Api\Base\BaseCollection;
use App\Http\Api\Base\BaseService;
use App\Http\Api\Country\Resources\CountryResource;
use Illuminate\Http\Request;

class CountryService extends BaseService
{
    private CountryRepository $repository;

    public function __construct()
    {
        $this->repository = new CountryRepository();
    }

    public function list(Request $request)
    {
       $columns = $request->has('columns') ? $request->toArray('columns'):['*'];

        if($request->has('order')){
            $orders = $request->get('order');
            foreach ($orders as $order){
                $this->repository->orderBy($order["column"],$order["direction"]);
            }
        }

        if($request->has('filters')){
            $filters = $request->get('filters'); //[{'column', 'value', 'operator'}]
            foreach ($filters as $filter){
                if($filter['type']=='where')
                    $this->repository->where($filter["column"],$filter["value"], isset( $filter["operator"] ) ? $filter["operator"] : '=');
                if($filter['type']=='whereIn')
                    $this->repository->whereIn($filter["column"],$filter["values"], );
            }
        }

        if($request->has('page')){
            $page = (int) $request->get('page');
            $limit =  $request->has('limit') ? (int) $request->get('limit'): (int) config('app.page_default_size');
            $data = $this->repository->paginate($limit, $columns, 'page', $page );
            return $this->successResponse(new BaseCollection($data));
        }

        $data =$this->repository->all($columns);
        return $this->successResponse(CountryResource::collection($data));
    }

    public function show($id)
    {
        $id = (int) $id;
        if($id){
            $data = $this->repository->getById($id);
            if($data)
                return $this->successResponse(new CountryResource($data));
        }
        return $this->errorResponse('Not found', 404);

    }
}
