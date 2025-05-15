<?php

namespace App\Http\Controllers;

use App\Models\Exhibit;
use App\Repositories\ExhibitRepository;
use App\Repositories\MuseumRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExhibitController extends Controller
{
    public function __construct(private readonly MuseumRepository $museumRepository)
    {
    }

    public function index(): View
    {
        $museum = $this->museumRepository->getOpticsMuseum();
        return view('exhibit.index', compact('museum'));
    }

    public function show(Exhibit $exhibit): View
    {
        return view('exhibit.show', compact('exhibit'));
    }
}
