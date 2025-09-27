<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
Route::get('/dashboard', [App\Http\Controllers\AuthController::class, 'showDashboard'])->middleware('auth')->name('admin.dashboard');

Route::resource('userControl', App\Http\Controllers\UserController::class)->middleware('auth')->names([
    'index' => 'admin.userControl.index',
    'create' => 'admin.userControl.create',
    'store' => 'admin.userControl.store',
    'show' => 'admin.userControl.show',
    'edit' => 'admin.userControl.edit',
    'update' => 'admin.userControl.update',
    'destroy' => 'admin.userControl.destroy',
]);

// Route::group(['middleware' => 'auth'], function () {
//     Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
//     Route::resource('posts', App\Http\Controllers\PostController::class);
// });
