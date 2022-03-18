<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait SortBy
{
    /**
     * @param Builder $builder
     * @param array $sort
     * @return Builder
     */
    public function scopeSortBy(Builder $builder, array $sort = []): Builder
    {
        foreach ($sort as $field => $direction) {
            $builder->orderBy($field, $direction);
        }
        return $builder;
    }
}
