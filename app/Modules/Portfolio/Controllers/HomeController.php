<?php

namespace App\Modules\Portfolio\Controllers;

use Illuminate\Routing\Controller;
use App\Modules\Portfolio\Services\ProjectService;
use App\Modules\Portfolio\Models\Project;

class HomeController extends Controller
{
    public function index()
    {
        return theme_view('pages.home', [
            'title' => 'Siniša Nikolić',
            'projects' => Project::published()
                ->ordered()
                ->get(),

            'featuredProjects' => Project::published()
                ->featured()
                ->ordered()
                ->get(),
        ]);
    }
}