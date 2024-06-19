<?php

namespace App\Models\Scopes;

trait BreadcrumbScope
{
    public function scopeFilters($query, $columns, $search = null)
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

    public function scopeFilterByUrl($query, $url)
    {
        $query->where('url', $url);

        $base = explode('/', $url)[0] ?? null;
        if ($base) {
            $query->orWhere('url', $base);
        }
    }

    public function scopeFilterByRouteName($query, $routeName)
    {
        $query->whereHas('module.routes', function ($query) use ($routeName) {
            $query->where('name', $routeName);
        });
    }
}
