<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HistorialController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('map');
});
Route::get('/map', function () {
    return view('map');
});

Route::get('/map', [HistorialController::class, 'map']);

Route::get('/map/{ciudad}', [HistorialController::class, 'showMap']);
Route::get('/humedad/{ciudad}', [HistorialController::class, 'getHumedad']);



