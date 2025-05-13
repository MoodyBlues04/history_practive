<?php

namespace App\Repositories;

use App\Models\Museum;

class MuseumRepository extends Repository
{
    public function __construct()
    {
        parent::__construct(Museum::class);
    }

    public function getOpticsMuseum(): ?Museum
    {
        return $this->query->first();
    }
}
