<?php

namespace App\Modules\Portfolio\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectImage extends Model
{
    protected $table = 'project_images';

    protected $fillable = [
        'project_id',
        'image_path',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'int',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}