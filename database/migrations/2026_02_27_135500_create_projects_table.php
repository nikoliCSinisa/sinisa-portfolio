<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('client')->nullable();          // HSFK, BCLP, Glenny...
            $table->string('slug')->unique();

            $table->text('summary')->nullable();
            $table->longText('description')->nullable();

            $table->string('cover_image_path')->nullable(); // storage path
            $table->string('project_url')->nullable();
            $table->string('case_study_url')->nullable();

            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};