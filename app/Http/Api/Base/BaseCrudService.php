<?php

namespace App\Http\Api\Base;

use App\Enums\ApiResponseCodes;
use App\Enums\ApiResponseMessages;
use App\Http\Api\Base\BaseServiceInterface;
use App\Http\Api\Country\CountryRepository;
use App\Traits\ApiResponseFormatTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use JasonGuru\LaravelMakeRepository\Exceptions\GeneralException;
use Monolog\Handler\FingersCrossed\ActivationStrategyInterface;
use Nette\Schema\ValidationException;

abstract class BaseCrudService extends BaseService implements BaseCrudServiceInterface
{
    use ApiResponseFormatTrait;

    protected $repository;      //instancia de repositorio
    protected $model;           //instancia del modelo se hace aqui para generar permiso
    protected $resource;        //instancia de recurso de salida
    protected FormRequest $storeRequest;    //instancia del storeRequest
    protected FormRequest $updateRequest;    //instancia del storeRequest

    public function __construct()
    {
        $this->makeRepository();
        $this->makeModel();
        $this->makeResource();

    }

    abstract public function repository(); //Return ModelRepository

    abstract public function model(); //Return Model::class

    abstract public function resource(); //Return Model::class

    abstract public function storeRequest(); //Return StoreModelRequest

    abstract public function updateRequest(); //Return UpdateModelRequest

    public function makeRepository()
    {
        $repository = app()->make($this->repository());

        if (!$repository instanceof BaseRepository)
            throw new GeneralException("Class {$this->repository()} must be an instance of " . BaseRepository::class);
        return $this->repository = $repository;
    }

    public function makeModel()
    {
        $model = app()->make($this->model());
        if (!$model instanceof Model) {
            throw new GeneralException("Class {$this->model()} must be an instance of " . Model::class);
        }
        return $this->model = $model;
    }

    public function makeResource()
    {
        $resource = app()->make($this->resource());
        if (!$resource instanceof JsonResponse) {
            throw new GeneralException("Class {$this->model()} must be an instance of " . JsonResponse::class);
        }
        return $this->resource = $resource;
    }

    public function makeStoreRequest(): FormRequest
    {
        $storeRequest = app()->make($this->storeRequest());
        if (!$storeRequest instanceof FormRequest) {
            throw new GeneralException("Class {$this->model()} must be an instance of " . FormRequest::class);
        }
        return $this->storeRequest = $storeRequest;
    }

    public function makeUpdateRequest(): FormRequest
    {
        $updateRequest = app()->make($this->updateRequest());
        if (!$updateRequest instanceof FormRequest) {
            throw new GeneralException("Class {$this->model()} must be an instance of " . FormRequest::class);
        }
        return $this->updateRequest = $updateRequest;
    }

    public function getBaseModel()
    {
        return class_basename($this->model);
    }

    public function list(Request $request): JsonResponse
    {
        // Authorization check
        $user = auth()->user();
        $require_permission = strtolower($this->getBaseModel()) . ':list';
        if (!$user || (!$user->is_super_admin || !in_array($require_permission, $user->permission_names)))
            $this->sendError(ApiResponseMessages::FORBIDDEN, ApiResponseCodes::HTTP_FORBIDDEN);

        $columns = $request->has('columns') ? $request->toArray('columns') : ['*'];

        if ($request->has('order')) {
            $orders = $request->get('order');
            foreach ($orders as $order) {
                $this->repository->orderBy($order["column"], $order["direction"]);
            }
        }

        if ($request->has('filters')) {
            $filters = $request->get('filters'); //[{'column', 'value', 'operator'}]
            foreach ($filters as $filter) {
                if ($filter['type'] == 'where')
                    $this->repository->where($filter["column"], $filter["value"], isset($filter["operator"]) ? $filter["operator"] : '=');
                if ($filter['type'] == 'whereIn')
                    $this->repository->whereIn($filter["column"], $filter["values"]);
                if ($filter['type'] == 'whereBetween')
                    $this->repository->whereBetween($filter["column"], $filter["values"]);
            }
        }

        if ($request->has('scopes')) {
            $scopes = $request->get('scopes');
            $this->repository->setScopes($scopes);
        }

        if ($request->has('page')) {
            $page = (int)$request->get('page');
            $limit = $request->has('limit') ? (int)$request->get('limit') : (int)config('app.page_default_size');
            $data = $this->repository->paginate($limit, $columns, 'page', $page);
            return $this->sendResponse(new BaseCollection($data), ApiResponseMessages::FETCHED_SUCCESSFULLY);
        }

        $data = $this->repository->all($columns);
        return $this->sendResponse($this->resource::collection($data), ApiResponseMessages::FETCHED_SUCCESSFULLY);
    }


    public function show($id): JsonResponse
    {
        // Authorization check
        $user = auth()->user();
        $require_permission = strtolower($this->getBaseModel()) . ':show';
        if (!$user || (!$user->is_super_admin || !in_array($require_permission, $user->permission_names)))
            $this->sendError(ApiResponseMessages::FORBIDDEN, ApiResponseCodes::HTTP_FORBIDDEN);

        $id = (int)$id;
        if ($id) {
            $data = $this->repository->getById($id);
            if ($data)
                return $this->sendResponse($data, ApiResponseMessages::FETCHED_SUCCESSFULLY);
        }
        return $this->sendError(ApiResponseMessages::NO_QUERY_RESULTS);
    }

    public function create(Request $request): JsonResponse
    {
        // Authorization check
        $user = auth()->user();
        $require_permission = strtolower($this->getBaseModel()) . ':create';

        if (!$user || (!$user->is_super_admin || !in_array($require_permission, $user->permission_names)))
            $this->sendError(ApiResponseMessages::FORBIDDEN, ApiResponseCodes::HTTP_FORBIDDEN);

        try {
            $rules = $this->storeRequest->rules();
            $validatedData = $request->validate($rules);
            $result = $this->repository->create($validatedData);
            return $this->sendResponse($result, ApiResponseMessages::CREATED_SUCCESSFULLY);
        } catch (ValidationException $ex) {
            return $this->sendError(
                ApiResponseMessages::UNPROCESSABLE_CONTENT,
                ApiResponseCodes::HTTP_UNPROCESSABLE_CONTENT,
                ['errors' => $ex->errors()]
            );
        }
        return $this->sendError(ApiResponseMessages::BAD_REQUEST);
    }

    public function createMultiple(array $data): JsonResponse
    {
        // Authorization check
        $user = auth()->user();
        $require_permission = strtolower($this->getBaseModel()) . ':create';
        if (!$user || (!$user->is_super_admin || !in_array($require_permission, $user->permission_names)))
            $this->sendError(ApiResponseMessages::FORBIDDEN, ApiResponseCodes::HTTP_FORBIDDEN);

        if ($data) {
            $result = $this->repository->createMultiple($data);
            if ($result)
                return $this->sendResponse($result, ApiResponseMessages::CREATED_SUCCESSFULLY);
        }
        return $this->sendError(ApiResponseMessages::BAD_REQUEST);
    }

    public function update($id, Request $request): JsonResponse
    {
        //$id, $data, $options=[]
        // Authorization check
        $user = auth()->user();
        $require_permission = strtolower($this->getBaseModel()) . ':update';
        if (!$user || (!$user->is_super_admin || !in_array($require_permission, $user->permission_names)))
            $this->sendError(
                ApiResponseMessages::FORBIDDEN,
                ApiResponseCodes::HTTP_FORBIDDEN
            );
        try {
            $rules = $this->updateRequest->rules();
            $validatedData = $request->validate($rules);
            $result = $this->repository->updateById($id, $validatedData);
            return $this->sendResponse(
                $result,
                ApiResponseMessages::UPDATED_SUCCESSFULLY
            );

        } catch (ValidationException $ex) {
            return $this->sendError(
                ApiResponseMessages::UNPROCESSABLE_CONTENT,
                ApiResponseCodes::HTTP_UNPROCESSABLE_CONTENT,
                ['errors' => $ex->errors()]
            );
        }

        return $this->sendError(ApiResponseMessages::NO_QUERY_RESULTS);
    }

    public function delete($id): JsonResponse
    {
        // Authorization check
        $user = auth()->user();
        $require_permission = strtolower($this->getBaseModel()) . ':delete';
        if (!$user || (!$user->is_super_admin || !in_array($require_permission, $user->permission_names)))
            $this->sendError(ApiResponseMessages::FORBIDDEN, ApiResponseCodes::HTTP_FORBIDDEN);

        $id = (int)$id;
        if ($id) {
            $data = $this->repository->deleteById($id);
            if ($data)
                return $this->sendResponse($data, ApiResponseMessages::DELETED_SUCCESSFULLY);
        }
        return $this->sendError(ApiResponseMessages::NO_QUERY_RESULTS);
    }

    public function deleteMultipleById(array $ids): JsonResponse
    {
        // Authorization check
        $user = auth()->user();
        $require_permission = strtolower($this->getBaseModel()) . ':delete';
        if (!$user || (!$user->is_super_admin || !in_array($require_permission, $user->permission_names)))
            $this->sendError(ApiResponseMessages::FORBIDDEN, ApiResponseCodes::HTTP_FORBIDDEN);

        if ($ids) {
            $data = $this->repository->deleteMultipleById($ids);
            if ($data) {
                $model = strtolower($this->getBaseModel());
                
                return $this->sendResponse($data, ApiResponseMessages::DELETED_SUCCESSFULLY);

            }
        }
        return $this->sendError(ApiResponseMessages::NO_QUERY_RESULTS);
    }

    public function setActivityLog($logMsg, $eventType = 'created')
    {
        $causerBy = auth()->user();
        activity()
            ->by($causerBy)
            ->event($eventType)
            ->log($logMsg);
    }

}
