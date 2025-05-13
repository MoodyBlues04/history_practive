<?php

namespace App\Http\Controllers;

use App\Models\ExhibitGroup;
use App\Repositories\ExhibitGroupRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExhibitGroupController extends Controller
{
    public function show(ExhibitGroup $exhibitGroup): View
    {
        return view('exhibit_group.show', compact('exhibitGroup'));
    }
}
