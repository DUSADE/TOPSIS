<?php

namespace App\Http\Controllers;

use App\Support\CriteriaCatalog;

class GuideController extends Controller
{
    public function index()
    {
        return view('guide.index', [
            'criteriaDefinitions' => CriteriaCatalog::definitions(),
        ]);
    }
}
