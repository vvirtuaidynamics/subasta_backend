<?php


namespace App\Models\Scopes;


use App\Enums\ValidationStatus;
use App\Http\Repositories\Module\ValidationTask\ValidationTaskRepository;
use Matrix\Builder;

trait ValidationTaskScope
{
    public function scopePending($query)
    {
        return $query->where('status', ValidationStatus::PENDING->value);
    }
    public function scopeValidated($query)
    {
        return $query->where('status', ValidationStatus::VALIDATED->value);
    }
    public function scopeRejected($query)
    {
        return $query->where('status', ValidationStatus::REJECTED->value);
    }
    public function scopeDeleted($query)
    {
        return $query->where('status', ValidationStatus::DELETED->value);
    }

//    public function scopeFilters($query, $columns, $search = null): void
//    {
//        //dd($columns);
//        if (isset($search)) {
//            $base_query = ValidationTaskRepository::baseQuery()->getQuery();
//            $base_query->where(function ($query) use ($search, $columns) {
//                foreach (collect($columns)->filter(function ($value) {
//                    return $value != 'action';
//                })->toArray() as $value) {
//                    $query->orWhere($value, 'like', '%' . $search . '%');
//                }
//            });
//        }
//
//    }
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

}
