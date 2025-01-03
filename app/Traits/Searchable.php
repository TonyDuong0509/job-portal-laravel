<?php

namespace App\Traits;

trait Searchable
{
    public function search($query, array $searchableFields)
    {
        if (request()->has('search')) {
            return $query->where(function ($subquery) use ($searchableFields) {
                foreach ($searchableFields as $field) {
                    $subquery->orWhere($field, 'LIKE', '%' . request('search') . '%');
                }
            });
        }
        return;
    }
}
