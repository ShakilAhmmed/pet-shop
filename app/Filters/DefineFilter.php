<?php

namespace App\Filters;

use Closure;

class DefineFilter
{
    public static function with($key, $value): Closure
    {
        return function ($query) use ($key, $value) {
            $query->where($key, $value);
        };
    }
}
