<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Modules\Portfolio\Controllers\HomeController;
use App\Modules\Admin\Controllers\AdminProjectController;
use App\Modules\Portfolio\Controllers\ProjectController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{project:slug}', [ProjectController::class, 'show'])->name('projects.show');
Route::prefix('admin')
    ->as('admin.')
    ->middleware(['auth'])
    ->group(function () {

        Route::resource('projects', AdminProjectController::class)
            ->except(['show']);

        Route::post('/projects/{project}/toggle-published', [AdminProjectController::class, 'togglePublished'])
            ->name('projects.togglePublished');

        Route::post('/projects/{project}/toggle-featured', [AdminProjectController::class, 'toggleFeatured'])
            ->name('projects.toggleFeatured');
        
        Route::post('/projects/reorder', [AdminProjectController::class, 'reorder'])
            ->name('projects.reorder');
    });

/*
|--------------------------------------------------------------------------
| Dashboard (Breeze)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Profile (Breeze)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
