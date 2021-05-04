<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\EpManagementController;

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
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/user-select/{id}', [StatsController::class, 'singleUserStats'])->name('user-select');
Route::get('/stats', [StatsController::class, 'makeChart'] )->name('stats');
Route::get('/usermng', [UserManagementController::class, 'index'] )->name('usermng');
Route::post('/usermng/add', [UserManagementController::class, 'add'] )->name('usermng-add');
Route::put('/usermng/{id}/edit', [UserManagementController::class, 'edit'] )->name('usermng-edit');
Route::delete('/usermng/{id}/delete', [UserManagementController::class, 'delete'] )->name('usermng-delete');
Route::get('/epmng', [EpManagementController::class, 'index'] )->name('epmng');
Route::post('/epmng/add', [EpManagementController::class, 'add'] )->name('epmng-add');
Route::put('/epmng/{id}/edit', [EpManagementController::class, 'edit'] )->name('epmng-edit');
Route::delete('/epmng/{id}/delete', [EpManagementController::class, 'delete'] )->name('epmng-delete');