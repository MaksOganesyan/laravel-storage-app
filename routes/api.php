<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ThingController;
use App\Http\Controllers\PlaceController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    // CRUD вещей
    Route::apiResource('things', ThingController::class);

    // Мои вещи
    Route::get('things/my', [ThingController::class, 'myThings'])->name('things.my');

    // В ремонте
    Route::get('things/repair', [ThingController::class, 'repairThings'])->name('things.repair');

    // В работе
    Route::get('things/work', [ThingController::class, 'workThings'])->name('things.work');

    // Переданные мной
    Route::get('things/used', [ThingController::class, 'usedThings'])->name('things.used');

    // Все вещи (только админ)
    Route::get('things/all', [ThingController::class, 'allThings'])->name('things.all')->middleware('admin');

    // Передача вещи
    Route::get('things/{thing}/transfer', [ThingController::class, 'transfer'])->name('things.transfer');
    Route::post('things/{thing}/transfer', [ThingController::class, 'transferStore'])->name('things.transfer.store');

    // Вещи, которые мне передали
    Route::get('received-things', [ThingController::class, 'received'])->name('received.things');

    // Возврат вещи
    Route::post('received-things/{thing}/return', [ThingController::class, 'returnThing'])->name('received.return');
});

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::apiResource('places', PlaceController::class);
});
