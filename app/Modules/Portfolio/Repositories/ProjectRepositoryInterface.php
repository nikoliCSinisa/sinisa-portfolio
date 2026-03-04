<?php

namespace App\Modules\Portfolio\Repositories;

use Illuminate\Support\Collection;

interface ProjectRepositoryInterface
{
    public function getPublishedOrdered(): Collection;
}