<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LingoController;

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

Route::get('/', [LingoController::class, 'index']);

Route::get('/play', [LingoController::class, 'Play']);
Route::get('/end', [LingoController::class, 'End']);

Route::get('/session', [LingoController::class, 'GetSession']);

Route::post('/newword', [LingoController::class, 'NewWord']);
Route::post('/guess/{guess}', [LingoController::class, 'Guess']);
