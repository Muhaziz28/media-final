<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\KdController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\Scor;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::redirect('/', '/login');


Route::middleware(['middleware' => 'PreventBackHistory'])->group(function () {
    Auth::routes();
});

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'admin', 'middleware' => ['isAdmin', 'auth', 'PreventBackHistory']], function () {
    Route::get('dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('kd', [KdController::class, 'index'])->name('admin.kd');
    Route::post('addKd', [KdController::class, 'add'])->name('admin.addKd');
    Route::get('get-kd', [KdController::class, 'getKd'])->name('admin.get-kd');

    Route::post('/detail-kd', [KdController::class, 'detailKd'])->name('admin.detail-kd');
    Route::post('/update-kd', [KdController::class, 'updateKd'])->name('admin.update-kd');

    Route::post('delete-kd', [KdController::class, 'deleteKd'])->name('admin.delete-kd');

    Route::get('siswa', [SiswaController::class, 'index'])->name('admin.siswa');
    Route::post('add-siswa', [SiswaController::class, 'addSiswa'])->name('admin.add-siswa');
    Route::get('get-siswa', [SiswaController::class, 'getSiswa'])->name('admin.get-siswa');
    Route::post('detail-siswa', [SiswaController::class, 'detailSiswa'])->name('admin.detail-siswa');
    Route::post('update-siswa', [SiswaController::class, 'updateSiswa'])->name('admin.update-siswa');
    Route::post('delete-siswa', [SiswaController::class, 'deleteSiswa'])->name('admin.delete-siswa');

    Route::get('materi', [MateriController::class, 'index'])->name('admin.materi');
    Route::get('get-materi', [MateriController::class, 'getMateri'])->name('admin.get-materi');
    Route::post('add-materi', [MateriController::class, 'addMateri'])->name('admin.add-materi');
    Route::post('delete-materi', [MateriController::class, 'deleteMateri'])->name('admin.delete-materi');

    Route::get('/scor', [Scor::class, 'index'])->name('admin.scor');
    Route::post('import', [Scor::class, 'import'])->name('admin.import');
    Route::get('get-nilai', [Scor::class, 'getNilai'])->name('admin.get-nilai');
    Route::post('detail-nilai', [Scor::class, 'detailNilai'])->name('admin.detail-nilai');
    Route::post('update-nilai', [Scor::class, 'updateNilai'])->name('admin.update-nilai');
    Route::post('delete-nilai', [Scor::class, 'deleteNilai'])->name('admin.delete-nilai');
});
