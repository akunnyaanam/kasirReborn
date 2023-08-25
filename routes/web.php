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
use App\Http\Controllers\MutasiController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\StoktokoController;

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
    
    // Route::get('/tambah-stok', 'StokGudangController@tambahStok')->name('tambah.stok');
    // Route::post('/simpan-stok', 'StokGudangController@simpanStok')->name('simpan.stok');
    // Route::get('/tambah-stok', [StokgudangController::class, 'tambahStok']);
    // Route::get('/simpan-stok', [StokgudangController::class, 'simpanStok']);
    // Route::get('/stokGudang', [StokgudangController::class, 'index']);

    // Route::get('/dashboard/stokGudang', [StokgudangController::class, 'index']);
    // Route::get('/dashboard/stokGudang/formtambahbanyak', [StokgudangController::class, 'formtambahbanyak']);
    // Route::post('/dashboard/stokGudang/simpandatabanyak', [StokgudangController::class, 'simpanStok']); 
    // Route::post('/simpan-banyak', 'StokgudangController@simpanBanyak')->name('simpan.banyak');
    // Route::get('/edit-stokGudang/{id}', [StokgudangController::class, 'edit']);
    // Route::put('/update-stokGudang', [StokgudangController::class, 'update']);
    // Route::delete('/delete-stokGudang', [StokgudangController::class, 'destroy']);

    Route::get('/dashboard/stokgudang' , [StokgudangController::class, 'index']);
    Route::post('/dashboard/stokgudang' , [StokgudangController::class, 'store']);

    Route::get('/dashboard/detail/stokgudang' , [StokgudangController::class, 'detail']);
    Route::get('/dashboard/detail/stokgudang/{id}' , [StokgudangController::class, 'detailStokgudang']);
    Route::get('/edit-detailstokgudang/{id}', [StokgudangController::class, 'edit']);
    Route::put('/update-detailstokgudang', [StokgudangController::class, 'update']);
    Route::get('/gudang/{gudang_id}/barang', [GudangController::class, 'showBarang'])->name('gudang.barang');
    
    
    Route::get('/dashboard/mutasi' , [MutasiController::class, 'index']);
    Route::get('/dashboard/mutasi/tambah' , [MutasiController::class, 'pembantu']);
    Route::post('/dashboard/mutasi/tambah' , [MutasiController::class, 'store']);
    Route::get('/dashboard/mutasi/{id}/cetak-pdf/{ukuran}', [MutasiController::class, 'cetakPdf'])->name('mutasi.cetakPdf');
    
    Route::get('/dashboard/stoktoko' , [StoktokoController::class, 'index']);
    Route::get('/dashboard/stoktoko/tambah' , [StoktokoController::class, 'pembantu']);
    Route::post('/dashboard/stoktoko/tambah' , [StoktokoController::class, 'store']);
    Route::get('/dashboard/stoktoko/{id}/cetak-pdf/{ukuran}', [StoktokoController::class, 'cetakPdf'])->name('cetak.pdf');
    // Route::get('', [StoktokoController::class, 'cetakPdf'])->name('stoktoko.cetakPdf');
    // Route::get('/dashboard/toko/{toko_id}/detailstok', [TokoController::class, 'showTokoDetailStok'])->name('toko.detailstok');
    Route::get('/dashboard/toko/{toko_id}/barang', [TokoController::class, 'showTokoBarang'])->name('toko.barang');

    Route::get('/dashboard/pengeluaran', [PengeluaranController::class, 'index']);
    Route::post('/add-pengeluaran', [PengeluaranController::class, 'store']);
    Route::get('/edit-pengeluaran/{id}', [PengeluaranController::class, 'edit']);
    Route::put('/update-pengeluaran', [PengeluaranController::class, 'update']);
    Route::delete('/delete-pengeluaran', [PengeluaranController::class, 'destroy']);
    Route::post('/verifikasi/{id}', [PengeluaranController::class, 'verifikasiPengeluaran'])->name('verifikasi.pengeluaran');
    // Route::get('/dashboard/pengeluaran/detail', [PengeluaranController::class, 'showDetail']);
    Route::get('/dashboard/pengeluaran/detail', [PengeluaranController::class, 'filterPengeluaran'])->name('filter.pengeluaran');
    Route::post('/dashboard/pengeluaran/detail', [PengeluaranController::class, 'filterPengeluaran'])->name('filter.pengeluaran');
    Route::post('/dashboard/pengeluaran/generate-pdf', [PengeluaranController::class, 'generatePDF'])->name('generate.pdf');


    Route::get('/dashboard/gudang/{id}', [GudangController::class, 'gudangBarang']);
    
});

