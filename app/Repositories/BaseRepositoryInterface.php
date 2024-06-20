<?php

namespace App\Repositories;

interface BaseRepositoryInterface
{
    public function __construct($model);
    public function count();
    public function all();
    public function store($data);
    public function update($id, $data);
    public function delete($id);
    public function getById($id);
}