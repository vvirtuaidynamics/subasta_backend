<?php

namespace App\Http\Api\Base;

use App\Enums\ApiResponseCodes;
use App\Enums\ApiResponseMessages;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponseFormatTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use JasonGuru\LaravelMakeRepository\Exceptions\GeneralException;
use Illuminate\Validation\ValidationException;

abstract class BaseService implements BaseServiceInterface
{
    use ApiResponseFormatTrait;

    protected BaseRepository $repository;
    protected Model $model;
    protected array $validationRules;

    public function __construct()
    {
        $this->makeRepository();
        $this->makeModel();
        $this->makeRules();
    }

    abstract public function repository();

    abstract public function model();

    abstract public function rules();

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function makeValidator(array $data, $id = null)
    {
        $rules = $this->validationRules;
        if ($id) {
            foreach ($rules as $field => $rule) {
                if (is_string($rule) && str_contains($rule, 'unique:')) {
                    $rules[$field] .= ",$field," . intval($id);
                }
                if ($field === 'password' && $id) {
                    $password_rules = str_replace('required', 'nullable', $rule);
                    $rules[$field] = $password_rules;
                }
            }
        }
        //dd($rules);

        return Validator::make($data, $rules);

    }

    public function makeRepository()
    {
        $repository = app()->make($this->repository());
        if (!$repository instanceof BaseRepository)
            throw new GeneralException("Class {$this->repository()} must be an instance of " . BaseRepository::class);
        return $this->repository = $repository;
    }

    public function makeModel(): Model
    {
        $model = app()->make($this->model());
        if (!$model instanceof Model) {
            throw new GeneralException("Class {$this->model()} must be an instance of " . Model::class);
        }
        return $this->model = $model;
    }

    public function makeRules(): array
    {
        $rules = $this->rules();
        if (!is_array($rules)) {
            throw new GeneralException("{$this->rules()} must be a valid array");
        }
        return $this->validationRules = $rules;
    }

    public function getBaseModel(): string
    {
        return class_basename($this->model);
    }

    public function findById($id)
    {
        // Authorization check
        $user = auth()->user();
        $require_permission = strtolower($this->getBaseModel()) . ':list';
        if (!$user || (!$user->super_admin || !in_array($require_permission, $user->permission_names)))
            return null;
        return $this->repository->getById($id);
    }

    public function findByColumn($value, $column = 'id')
    {
        // Authorization check
        $user = auth()->user();
        $require_permission = strtolower($this->getBaseModel()) . ':list';
        if (!$user || (!$user->super_admin || !in_array($require_permission, $user->permission_names)))
            $this->sendError(ApiResponseMessages::FORBIDDEN, ApiResponseCodes::HTTP_FORBIDDEN);
        if (!isset($value)) return null;
        return $this->repository->getByColumn($value, $column);
    }

    public function list(Request $request)
    {
        // Authorization check
        $user = auth()->user();
        $require_permission = strtolower($this->getBaseModel()) . ':list';
        if (!$user || (!$user->super_admin || !in_array($require_permission, $user->permission_names)))
            $this->sendError(ApiResponseMessages::FORBIDDEN, ApiResponseCodes::HTTP_FORBIDDEN);

        $columns = $request->has('columns') ? explode(',', $request->get('columns')) : ['*'];

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
        return $this->sendResponse($data, ApiResponseMessages::FETCHED_SUCCESSFULLY);
    }

    public function getByColumn(Request $request)
    {
        if ($request->has('column') && $request->has('value')) {
            $column = $request->get('column');
            $value = $request->get('value');
            return $this->repository->getByColumn($value, $column);
        } else {
            return $this->sendError(ApiResponseMessages::UNPROCESSABLE_CONTENT, ApiResponseCodes::HTTP_UNPROCESSABLE_CONTENT);
        }
    }

    public function view($id, $getModel = false)
    {
        // Authorization check
        $user = auth()->user();
        $require_permission = strtolower($this->getBaseModel()) . ':show';
        if (!$user || (!$user->super_admin || !in_array($require_permission, $user->permission_names)))
            $this->sendError(ApiResponseMessages::FORBIDDEN, ApiResponseCodes::HTTP_FORBIDDEN);


        $data = $this->repository->getById($id);
        if ($data) {
            if ($getModel) return $data;
            return $this->sendResponse($data, ApiResponseMessages::FETCHED_SUCCESSFULLY);

        }
        return $this->sendError(ApiResponseMessages::NO_QUERY_RESULTS);
    }

    public function create(Request $request, $getModel = false)
    {
        // Authorization check
        $user = auth()->user();
        $require_permission = strtolower($this->getBaseModel()) . ':create';
        if (!$user || (!$user->super_admin || !in_array($require_permission, $user->permission_names)))
            $this->sendError(ApiResponseMessages::FORBIDDEN, ApiResponseCodes::HTTP_FORBIDDEN);
        try {
            if (!$request->has('active'))
                $request = $request->merge(['active' => 0]);
            $validator = $this->makeValidator($request->all());
            $validatedData = $validator->validate();
            $result = $this->repository->create($validatedData);
            if ($getModel) return $result;

            return $this->sendResponse($result, ApiResponseMessages::CREATED_SUCCESSFULLY);
        } catch (ValidationException $e) {
            return $this->sendError(
                ApiResponseMessages::UNPROCESSABLE_CONTENT,
                ApiResponseCodes::HTTP_UNPROCESSABLE_CONTENT,
                ['errors' => $e->errors()]
            );
        }

    }

    public function update($id, Request $request, $getModel = false)
    {
        $user = auth()->user();
        $require_permission = strtolower($this->getBaseModel()) . ':update';
        if (!$user || (!$user->super_admin || !in_array($require_permission, $user->permission_names)))
            $this->sendError(
                ApiResponseMessages::FORBIDDEN,
                ApiResponseCodes::HTTP_FORBIDDEN
            );
        try {
            $validator = $this->makeValidator($request->all(), $id);

            $validatedData = $validator->validate();

            $result = $this->repository->updateById($id, $validatedData);
            if ($getModel) return $result;
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

    }

    public function delete($id)
    {
        // Authorization check
        $user = auth()->user();
        $require_permission = strtolower($this->getBaseModel()) . ':delete';
        if (!$user || (!$user->super_admin || !in_array($require_permission, $user->permission_names)))
            $this->sendError(ApiResponseMessages::FORBIDDEN, ApiResponseCodes::HTTP_FORBIDDEN);

        if ($id) {
            $data = $this->repository->deleteById($id);
            if ($data)
                return $this->sendResponse($data, ApiResponseMessages::DELETED_SUCCESSFULLY);
        }
        return $this->sendError(ApiResponseMessages::NO_QUERY_RESULTS);
    }


}
