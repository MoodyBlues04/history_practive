<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::prefix('map')
    ->as('map.')
    ->controller(\App\Http\Controllers\MapController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/load/{museum}', 'load')->name('load');
});
Route::prefix('exhibit_group')
    ->as('exhibit_group.')
    ->controller(\App\Http\Controllers\ExhibitGroupController::class)->group(function () {
    Route::get('/{exhibitGroup}', 'show')->name('show'); // todo mb just resource
});
Route::prefix('exhibit')
    ->as('exhibit.')
    ->controller(\App\Http\Controllers\ExhibitController::class)->group(function () {
    Route::get('/{exhibit}', 'show')->name('show');
});

Route::view('/test', 'test')->name('test');
Route::view('/', 'main')->name('main');
