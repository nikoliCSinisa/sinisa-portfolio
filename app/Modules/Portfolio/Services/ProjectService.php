<?php

namespace App\Modules\Portfolio\Services;

use App\Modules\Portfolio\Repositories\ProjectRepositoryInterface;
use Illuminate\Support\Collection;

class ProjectService
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projects
    ) {}

    public function listForHomepage(): Collection
    {
        return $this->projects->getPublishedOrdered();
    }
}