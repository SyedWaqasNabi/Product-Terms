<?php

namespace App\Services\Filters;

use App\Helpers\StringHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Class QueryFilter
 * @package App\Services\Filters
 */
abstract class QueryFilter
{
    /**
     * @var Request
     */
    protected Request $request;

    /**
     * @var Builder
     */
    protected Builder $builder;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param Builder $builder
     */
    public function apply(Builder $builder): void
    {
        $this->builder = $builder;
        $filter = $this->fields();
        if (array_key_exists('filter', $filter)) {
            foreach ($filter['filter'] as $field => $value) {
                $method = StringHelper::transformToCamelCase($field);
                if (method_exists($this, $method)) {
                    call_user_func_array([$this, $method], (array)$value);
                }
            }
        }
    }

    /**
     * @return array
     */
    protected function fields(): array
    {
        return  $this->request->all();
    }
}
