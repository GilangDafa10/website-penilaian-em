<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\UserProfilController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
Route::get('/dashboard', [App\Http\Controllers\AuthController::class, 'showDashboard'])->middleware('auth')->name('admin.dashboard');

Route::resource('userControl', App\Http\Controllers\UserController::class)->middleware('role:Admin')->names([
    'index' => 'admin.userControl.index',
    'create' => 'admin.userControl.create',
    'store' => 'admin.userControl.store',
    'show' => 'admin.userControl.show',
    'edit' => 'admin.userControl.edit',
    'update' => 'admin.userControl.update',
    'destroy' => 'admin.userControl.destroy',
]);

Route::group(['middleware' => 'role:Kahim,Wakahim,Kabid,Kadiv,Staff'], function () {
    Route::get('/penilaian', [PenilaianController::class, 'index'])->name('penilaian.index');
    Route::get('/penilaian/create/{nim}', [PenilaianController::class, 'create'])->name('penilaian.create');
    Route::post('/penilaian', [PenilaianController::class, 'store'])->name('penilaian.store');
    Route::get('/penilaian/{nim}', [PenilaianController::class, 'show'])->name('penilaian.show');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserProfilController::class, 'show'])->name('userProfil.show');
    Route::post('/profile', [UserProfilController::class, 'updatePassword'])->name('userProfil.update-password');
});



// Route::group(['middleware' => 'auth'], function () {
//     Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
//     Route::resource('posts', App\Http\Controllers\PostController::class);
// });
