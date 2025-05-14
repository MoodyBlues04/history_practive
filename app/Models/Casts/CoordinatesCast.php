<?php

namespace App\Models\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class CoordinatesCast implements CastsAttributes
{
    private const COORDINATES_SEP = ',';

    public function get($model, $key, $value, $attributes)
    {
        return array_map(fn ($item) => (float)$item, explode(self::COORDINATES_SEP, $value));
    }

    public function set($model, $key, $value, $attributes)
    {
        return implode(self::COORDINATES_SEP, $value);
    }
}
