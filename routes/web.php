<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\ThingController;
use App\Http\Controllers\PlaceController;
// Регистрация и вход 
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'store']);

Route::post('/logout', [LogoutController::class, 'destroy'])->name('logout');

// Главная страница
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::resource('things', ThingController::class)->except(['show']);
    Route::resource('places', PlaceController::class)->middleware('admin');
    Route::get('things/{thing}/transfer', [ThingController::class, 'transfer'])->name('things.transfer');
    Route::post('things/{thing}/transfer', [ThingController::class, 'transferStore'])->name('things.transfer.store');
    Route::get('/received-things', [ThingController::class, 'received'])->name('received.things');
    Route::post('/received-things/{thing}/return', [ThingController::class, 'returnThing'])->name('received.return');
    Route::get('/things/my', [ThingController::class, 'myThings'])->name('things.my');
    Route::get('/things/repair', [ThingController::class, 'repairThings'])->name('things.repair');
    Route::get('/things/work', [ThingController::class, 'workThings'])->name('things.work');
    Route::get('/things/used', [ThingController::class, 'usedThings'])->name('things.used');
    Route::get('/things/all', [ThingController::class, 'allThings'])->name('things.all')->middleware('admin');
    Broadcast::routes(['middleware' => ['auth']]);
});
