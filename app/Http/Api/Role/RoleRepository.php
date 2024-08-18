<?php

namespace App\Http\Api\Role;

use App\Http\Api\Base\BaseRepository;
use Spatie\Permission\Models\Role;

//use Your Model

/**
 * Class UserRepository.
 */
class RoleRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model(): string
    {
        return Role::class;
    }

    public function deleteById($id): bool
    {
        $role = Role::findById((int)$id);
        if ($role) {
            return $role->delete();
        } else {
            return false;
        }
    }

}
