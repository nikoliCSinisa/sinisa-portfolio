<?php

namespace App\Modules\Portfolio\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';

    protected $fillable = [
        'title',
        'client',
        'slug',
        'summary',
        'description',
        'cover_image_path',
        'project_url',
        'case_study_url',
        'sort_order',
        'is_featured',
        'is_published',
    ];

    protected $casts = [
        'is_featured' => 'bool',
        'is_published' => 'bool',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}