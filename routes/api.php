<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// GeoJSON API
Route::get('points', [ApiController::class, 'geojson_points'])
    ->name('geojson.points');

// GeoJSON API
Route::get('point/{id}', [ApiController::class, 'geojson_point'])
    ->name('geojson.point');
