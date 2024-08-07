<?php

namespace App\Http\Api\Base;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use JasonGuru\LaravelMakeRepository\Exceptions\GeneralException;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;

    protected Builder $query;

    protected int $take;

    protected array $with = [];

    protected array $wheres = [];

    protected array $whereIns = [];

    protected array $whereBetweens = [];

    protected array $orderBys = [];

    protected array $scopes = [];

    public function __construct()
    {
        $this->makeModel();
    }

    abstract public function model(): string;

    public function makeModel(): Model
    {
        $model = app()->make($this->model());
        if (!$model instanceof Model) {
            throw new GeneralException("Class {$this->model()} must be an instance of " . Model::class);
        }
        return $this->model = $model;
    }

    public function all(array $columns = ['*']): Collection
    {
        $this->newQuery()->eagerLoad();

        $models = $this->query->get($columns);

        $this->unsetClauses();

        return $models;
    }

    public function count(): int
    {
        return $this->get()->count();
    }

    public function create(array $data): Model
    {
        $this->unsetClauses();

        return $this->model->create($data);
    }

    public function createMultiple(array $data): Collection
    {
        $models = new Collection();

        foreach ($data as $d) {
            $models->push($this->create($d));
        }

        return $models;
    }

    public function delete()
    {
        $this->newQuery()->setClauses()->setScopes();

        $result = $this->query->delete();

        $this->unsetClauses();

        return $result;
    }

    public function deleteById($id): bool
    {
        $this->unsetClauses();

        return $this->getById($id)->delete();
    }

    public function deleteMultipleById(array $ids): int
    {
        return $this->model->destroy($ids);
    }

    public function first(array $columns = ['*'])
    {
        $this->newQuery()->eagerLoad()->setClauses()->setScopes();

        $model = $this->query->firstOrFail($columns);

        $this->unsetClauses();

        return $model;
    }

    public function get(array $columns = ['*'])
    {
        $this->newQuery()->eagerLoad()->setClauses()->setScopes();

        $models = $this->query->get($columns);

        $this->unsetClauses();

        return $models;
    }

    public function getById($id, array $columns = ['*'])
    {
        $this->unsetClauses();

        $this->newQuery()->eagerLoad();
        if (!is_numeric($id)) {
            return $this->query->where('uuid', '=', "$id")->first($columns);
        }
        return $this->query->find($id, $columns);
    }

    public function getByColumn($item, $column, array $columns = ['*'])
    {
        $this->unsetClauses();

        $this->newQuery()->eagerLoad();

        return $this->query->where($column, $item)->first($columns);
    }

    public function paginate($limit = 30, array $columns = ['*'], $pageName = 'page', $page = null)
    {
        $this->newQuery()->eagerLoad()->setClauses()->setScopes();

        $models = $this->query->paginate($limit, $columns, $pageName, $page);

        $this->unsetClauses();

        return $models;
    }

    public function updateById($id, array $data, array $options = [])
    {
        $this->unsetClauses();

        $model = $this->getById($id);

        $model->update($data, $options);

        return $model;
    }

    public function limit($limit)
    {
        $this->take = $limit;

        return $this;
    }

    public function orderBy($column, $direction = 'asc')
    {
        $this->orderBys[] = compact('column', 'direction');

        return $this;
    }

    public function where($column, $value, $operator = '=')
    {
        $this->wheres[] = compact('column', 'value', 'operator');

        return $this;
    }

    public function whereIn($column, $values)
    {
        $values = is_array($values) ? $values : [$values];

        $this->whereIns[] = compact('column', 'values');

        return $this;
    }

    public function whereBetween($column, $values)
    {
        if (!is_array($values)) return null;

        $this->whereBetweens[] = compact('column', 'values');

        return $this;
    }

    public function with($relations)
    {
        if (is_string($relations)) {
            $relations = func_get_args();
        }

        $this->with = $relations;

        return $this;
    }

    protected function newQuery()
    {
        $this->query = $this->model->newQuery();

        return $this;
    }

    protected function eagerLoad()
    {
        foreach ($this->with as $relation) {
            $this->query->with($relation);
        }

        return $this;
    }

    protected function setClauses()
    {
        foreach ($this->wheres as $where) {
            $this->query->where($where['column'], $where['operator'], $where['value']);
        }

        foreach ($this->whereIns as $whereIn) {
            $this->query->whereIn($whereIn['column'], $whereIn['values']);
        }
        foreach ($this->whereBetweens as $whereBetween) {
            $this->query->whereBetween($whereBetween['column'], $whereBetween['values']);
        }

        foreach ($this->orderBys as $orders) {
            $this->query->orderBy($orders['column'], $orders['direction']);
        }

        if (isset($this->take) and !is_null($this->take)) {
            $this->query->take($this->take);
        }

        return $this;
    }

    public function setScopes()
    {
        foreach ($this->scopes as $method => $args) {
            $this->query->$method(implode(', ', $args));
        }

        return $this;
    }

    protected function unsetClauses()
    {
        $this->wheres = [];
        $this->whereIns = [];
        $this->whereBetweens = [];
        $this->scopes = [];
        $this->take = 0;

        return $this;
    }
}
