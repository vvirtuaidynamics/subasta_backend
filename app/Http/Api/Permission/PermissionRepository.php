<?php

namespace App\Http\Api\Permission;

use App\Http\Api\Base\BaseRepository;
use Spatie\Permission\Models\Permission;

//use Your Model

/**
 * Class UserRepository.
 */
class PermissionRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model(): string
    {
        return Permission::class;
    }
}
