<?php

namespace App\Repositories;

use App\Models\ExhibitGroup;

class ExhibitGroupRepository extends Repository
{
    public function __construct()
    {
        parent::__construct(ExhibitGroup::class);
    }
}
