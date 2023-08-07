<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TokoController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\PemasokController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StokgudangController;
use App\Http\Controllers\JenisBarangController;

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

// Login 
Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
// Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Content
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/dashboard/jenisBarang', [JenisBarangController::class, 'index']);
    Route::post('/add-jenisBarang', [JenisBarangController::class, 'store']);
    Route::get('/edit-jenisBarang/{id}', [JenisBarangController::class, 'edit']);
    Route::put('/update-jenisBarang', [JenisBarangController::class, 'update']);
    Route::delete('/delete-jenisBarang', [JenisBarangController::class, 'destroy']);
    
    Route::get('/dashboard/pemasok', [PemasokController::class, 'index']);
    Route::post('/add-pemasok', [PemasokController::class, 'store']);
    Route::get('/edit-pemasok/{id}', [PemasokController::class, 'edit']);
    Route::put('/update-pemasok', [PemasokController::class, 'update']);
    Route::delete('/delete-pemasok', [PemasokController::class, 'destroy']);
    
    Route::get('/dashboard/toko', [TokoController::class, 'index']);
    Route::post('/add-toko', [TokoController::class, 'store']);
    Route::get('/edit-toko/{id}', [TokoController::class, 'edit']);
    Route::put('/update-toko', [TokoController::class, 'update']);
    Route::delete('/delete-toko', [TokoController::class, 'destroy']);
    
    Route::get('/dashboard/gudang', [GudangController::class, 'index']);
    Route::post('/add-gudang', [GudangController::class, 'store']);
    Route::get('/edit-gudang/{id}', [GudangController::class, 'edit']);
    Route::put('/update-gudang', [GudangController::class, 'update']);
    Route::delete('/delete-gudang', [GudangController::class, 'destroy']);
    
    Route::get('/dashboard/barang', [BarangController::class, 'index']);
    Route::post('/add-barang', [BarangController::class, 'store']);
    Route::get('/edit-barang/{id}', [BarangController::class, 'edit']);
    Route::put('/update-barang', [BarangController::class, 'update']);
    Route::delete('/delete-barang', [BarangController::class, 'destroy']);
    
    Route::get('/dashboard/stokGudang', [StokgudangController::class, 'index']);
    Route::post('/add-stokGudang', [StokgudangController::class, 'store']);
    Route::get('/edit-stokGudang/{id}', [StokgudangController::class, 'edit']);
    Route::put('/update-stokGudang', [StokgudangController::class, 'update']);
    Route::delete('/delete-stokGudang', [StokgudangController::class, 'destroy']);
});

