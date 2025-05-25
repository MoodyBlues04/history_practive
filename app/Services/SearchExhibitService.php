<?php

namespace App\Services;

use App\Http\Requests\SearchExhibitRequest;
use App\Models\ExhibitGroup;
use App\Repositories\MuseumRepository;

class SearchExhibitService
{
    public function __construct(private readonly MuseumRepository $museumRepository)
    {
    }

    /**
     * @return ExhibitGroup[]
     */
    public function search(SearchExhibitRequest $request): array {
        $exhibitGroups = $this->museumRepository->getOpticsMuseum()->exhibitGroups();
        if (is_numeric($request->search)) {
            $exhibitGroups->where('number', $request->search);
        } else if (!empty($request->search)) {
            $exhibitGroups->where('name', 'like', "%$request->search%");
        }
        return $exhibitGroups->get()->all();
    }
}
