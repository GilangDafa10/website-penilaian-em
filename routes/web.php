<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\UserProfilController;
use App\Http\Controllers\HasilNilaiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Inertia\Inertia;


Route::get('/', function () {
    return Inertia::render('Login');
});
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
// Logout (POST untuk keamanan)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route yang dilindungi autentikasi
Route::middleware('role:Admin')->group(function () {
    Route::resource('usercontrol', UserController::class)->names([
        'index' => 'userControl.index',
        'create' => 'userControl.create',
        'store' => 'userControl.store',
        'show' => 'userControl.show',
        'edit' => 'userControl.edit',
        'update' => 'userControl.update',
        'destroy' => 'userControl.destroy',
    ]);
    Route::get('/hasilnilai', [HasilNilaiController::class, 'index'])->name('hasilnilai.index');
});

Route::get('/dashboard', [AuthController::class, 'showDashboard'])->name('dashboard');

Route::group(['middleware' => 'role:Kahim,Wakahim,Kabid,Kadiv,Staff'], function () {
    Route::get('/penilaian', [PenilaianController::class, 'index'])->name('penilaian.index');
    Route::get('/penilaian/create/{nim}', [PenilaianController::class, 'create'])->name('penilaian.create');
    Route::post('/penilaian', [PenilaianController::class, 'store'])->name('penilaian.store');
    Route::get('/penilaian/{nim}', [PenilaianController::class, 'show'])->name('penilaian.show');
    Route::get('/profil', [UserProfilController::class, 'show'])->name('userProfil.show');
    Route::post('/profil', [UserProfilController::class, 'updatePassword'])->name('userProfil.update-password');
});
