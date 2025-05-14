<?php

namespace App\Http\Controllers;

use App\Models\Exhibit;
use App\Repositories\ExhibitRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExhibitController extends Controller
{
    public function __construct(private readonly ExhibitRepository $exhibitRepository)
    {
    }

    public function show(Exhibit $exhibit): View
    {
        return view('exhibit.show', compact('exhibit'));
    }
}
