<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait UUIDAble
{
    public static function bootUUIDAble(): void
    {
        static::creating(function ($model) {
            if (in_array('uuid', $model->fillable)) {
                $model->uuid = Str::uuid();
            }
        });
    }
}
