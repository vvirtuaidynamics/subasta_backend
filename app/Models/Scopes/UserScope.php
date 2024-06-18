<?php

namespace App\Models\Scopes;

trait UserScope
{
    public function scopeFilters($query, $columns, $search = null): void
    {
        if (isset($search)) {
            $query->where(function ($query) use ($search, $columns) {

                foreach (collect($columns)->filter(function ($value) {
                    return $value != 'action';
                })->toArray() as $value) {
                    if($value!='role_name')
                    $query->orWhere($value, 'like', '%' . $search . '%');
                }
            });
        }
    }
}
