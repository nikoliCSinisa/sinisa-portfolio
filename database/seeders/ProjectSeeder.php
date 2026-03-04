<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Portfolio\Models\Project;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        Project::query()->delete();

        Project::create([
            'title' => 'HSFK – Enterprise Website',
            'client' => 'HSFK',
            'slug' => 'hsfk-enterprise-website',
            'summary' => 'Enterprise CMS build with modular components and accessibility-first approach.',
            'sort_order' => 1,
            'is_featured' => true,
            'is_published' => true,
        ]);

        Project::create([
            'title' => 'BCLP – Content Platform',
            'client' => 'BCLP',
            'slug' => 'bclp-content-platform',
            'summary' => 'Scalable templates, performance optimizations and UI/UX improvements.',
            'sort_order' => 2,
            'is_featured' => true,
            'is_published' => true,
        ]);

        Project::create([
            'title' => 'Glenny – Property Experience',
            'client' => 'Glenny',
            'slug' => 'glenny-property-experience',
            'summary' => 'Modern responsive UI with reusable design system patterns.',
            'sort_order' => 3,
            'is_featured' => true,
            'is_published' => true,
        ]);
    }
}