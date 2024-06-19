<?php

namespace App\Models\Scopes;

trait ModuleScope
{
    public function scopeFilters($query, $columns, $search = null): void
    {
        if (isset($search)) {
            $query->where(function ($query) use ($search, $columns) {
                foreach (collect($columns)->filter(function ($value) {
                    return $value != 'action';
                })->toArray() as $value) {
                    $query->orWhere($value, 'like', '%' . $search . '%');
                }
            });
        }
    }

    public function scopeFilterByRoute($query, $route): void
    {
        $query->whereHas('routes', function ($query) use ($route) {
            $query->where('name', $route);
        });
    }
}
