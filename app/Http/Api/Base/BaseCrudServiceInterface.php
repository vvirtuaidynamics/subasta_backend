<?php

namespace App\Http\Api\Base;

use Illuminate\Http\Request;

/**
 * Interface RepositoryContract.
 */
interface BaseCrudServiceInterface
{

    public function repository(); //Return ModelRepository
    public function model(); //Return Model::class
    public function resource(); //Return Model::class
    public function storeRequest(); //Return StoreModelRequest
    public function updateRequest(); //Return UpdateModelRequest

}
