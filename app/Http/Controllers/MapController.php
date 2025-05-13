<?php

namespace App\Http\Controllers;

use App\Repositories\MuseumRepository;
use Illuminate\View\View;

class MapController extends Controller
{
    public function __construct(private readonly MuseumRepository $museumRepository)
    {
    }

    public function index(): View
    {
        $museum = $this->museumRepository->getOpticsMuseum();
        return view('map.index', compact('museum'));
    }
}
