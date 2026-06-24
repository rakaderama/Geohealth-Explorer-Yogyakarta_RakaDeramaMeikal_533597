<?php
use App\Http\Controllers\PointsController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

// Home route - render landing page
Route::get('/', [PageController::class, 'landingpage'])->name('home');

Route::get('/peta', [PageController::class, 'peta'])
->middleware(['auth', 'verified'])
->name('peta');

Route::get('/tabel', [PageController::class, 'tabel'])->name('tabel');

// Backward-compatible route: support older '/points' URL to show the same table view
Route::get('/points', [PageController::class, 'tabel'])->name('points');

// Tentang (About) page
Route::get('/tentang', [PageController::class, 'tentang'])->name('tentang');

//Points
Route::post('/store-points', [\App\Http\Controllers\PointsController::class, 'store'])->name('points.store');

//Route untuk menghapus point berdasarkan ID
Route::delete('/delete-points/{id}', [PointsController::class, 'destroy'])->name('points.delete');

//Route untuk edit point berdasarkan ID
Route::get('/edit-points/{id}', [PointsController::class, 'edit'])->name('points.edit');

//Route untuk update point berdasarkan ID
Route::patch('/update-points/{id}', [PointsController::class, 'update'])->name('points.update');


Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/settings.php';
