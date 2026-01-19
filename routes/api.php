<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ThingController;
use App\Http\Controllers\PlaceController;

// Текущий пользователь (проверка токена)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API для вещей — защищён токеном
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('things', ThingController::class);
    Route::get('things/my', [ThingController::class, 'myThings']);
    Route::get('things/all', [ThingController::class, 'allThings'])->middleware('admin');
});

// API для мест — только админ
Route::middleware(['auth:sanctum', 'admin'])->apiResource('places', PlaceController::class);

