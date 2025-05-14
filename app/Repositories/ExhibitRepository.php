<?php

namespace App\Repositories;

use App\Models\Exhibit;

class ExhibitRepository extends Repository
{
    public function __construct()
    {
        parent::__construct(Exhibit::class);
    }
}
