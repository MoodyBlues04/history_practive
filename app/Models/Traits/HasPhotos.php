<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Collection;

/**
 * @property Collection $photos
 */
trait HasPhotos
{
    public function getIconUrl(): string
    {
        return $this->photos->first()->getPublicUrl(); // todo default
    }
}
