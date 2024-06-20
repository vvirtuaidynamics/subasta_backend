<?php

namespace App\Repositories;

class BaseRepository implements BaseRepositoryInterface
{
    protected mixed $model;

    public function __construct($model)
    {
        $this->model = new $model;
    }

    public function count()
    {
        return $this->model->count();
    }

    public function all()
    {
        return $this->model->all();
    }
    public function store($data)
    {
        return $this->model->create($data);
    }
    public function update($id, $data)
    {
        return $this->model->updateOrCreate($id, $data);
    }
    public function delete($id)
    {
        return $this->model->delete($id);
    }
    public function getById($id)
    {
        return $this->model->find($id);
    }
}