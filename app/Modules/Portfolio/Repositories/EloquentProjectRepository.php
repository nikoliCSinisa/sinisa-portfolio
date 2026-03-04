<?php

namespace App\Modules\Portfolio\Repositories;

use App\Modules\Portfolio\Models\Project;
use Illuminate\Support\Collection;

class EloquentProjectRepository implements ProjectRepositoryInterface
{
    public function getPublishedOrdered(): Collection
    {
        return Project::query()
            ->where('is_published', true)
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();
    }
}