<?php

namespace App\Services\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait Filterable
 * @package App\Services\Filters
 */
trait Filterable
{
    /**
     * @param Builder $builder
     * @param QueryFilter $filter
     */
    public function scopeApplyFilters(Builder $builder, QueryFilter $filter)
    {
        $filter->apply($builder);
    }
}
