<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchExhibitRequest;
use App\Models\Museum;
use App\Repositories\MuseumRepository;
use App\Services\SearchExhibitService;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MapController extends Controller
{
    public function __construct(
        private readonly MuseumRepository $museumRepository,
        private readonly SearchExhibitService $searchExhibitService
    ) {
    }

    public function index(SearchExhibitRequest $request): View
    {
        $museum = $this->museumRepository->getOpticsMuseum(); // can be changed to any museum
        $exhibitGroups = $this->searchExhibitService->search($request);
        $searched = $request->search;
        return view('map.index', compact('museum', 'exhibitGroups', 'searched'));
    }

    public function load(Museum $museum): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        return Storage::download($museum->map->path, 'museum-map.jpg', [
            'Content-Type' => 'image/jpeg',
            'Cache-Control' => 'no-store, no-cache, must-revalidate'
        ]);
    }
}
