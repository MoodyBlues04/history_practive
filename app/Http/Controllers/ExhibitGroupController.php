<?php

namespace App\Http\Controllers;

use App\Models\ExhibitGroup;
use App\Repositories\ExhibitGroupRepository;
use App\Repositories\MuseumRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExhibitGroupController extends Controller
{
    public function __construct(private readonly MuseumRepository $museumRepository)
    {
    }

    public function index(): View
    {
        $museum = $this->museumRepository->getOpticsMuseum();
        return view('exhibit_group.index', compact('museum'));
    }

    public function show(ExhibitGroup $exhibitGroup): View
    {
        return view('exhibit_group.show', compact('exhibitGroup'));
    }
}
