<?php

namespace App\Http\Api\Base;

use Illuminate\Http\Request;

/**
 * Interface RepositoryContract.
 */
interface BaseServiceInterface
{

    public function repository();

    public function model();

    public function rules();

    public function list(Request $request);

    public function getByColumn(Request $request); //$request=> ['column'=> 'column_name', 'value'=> 'value to search']

    public function view($id);

    public function create(Request $request);

    public function update($id, Request $request);

    public function delete($id);


}
