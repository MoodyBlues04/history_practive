<?php

namespace App\Http\Controllers;

use App\Models\Museum;
use App\Repositories\MuseumRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MapController extends Controller
{
    public function __construct(private readonly MuseumRepository $museumRepository)
    {
    }

    public function index(): View
    {
        $museum = $this->museumRepository->getOpticsMuseum(); // can be changed to any museum
        return view('map.index', compact('museum'));
    }

    public function load(Museum $museum)
    {
        return Storage::download($museum->map->path, 'museum-map.jpg', [
            'Content-Type' => 'image/jpeg',
            'Cache-Control' => 'no-store, no-cache, must-revalidate'
        ]);
    }
}
