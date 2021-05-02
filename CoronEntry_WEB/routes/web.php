<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\StatsController;

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

Route::get('/', [PagesController::class, 'index'] )->name('home');
Route::get('/login', [LoginController::class, 'index'] )->name('login');
Route::post('/login', [LoginController::class, 'store']);
Route::get('/stats', [StatsController::class, 'makeChart'] )->name('stats');