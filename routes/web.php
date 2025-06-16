<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BcplController;
use App\Http\Controllers\BtpController;
use App\Http\Controllers\CPLController;
use App\Http\Controllers\CPMKController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\DpnaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KcplController;
use App\Http\Controllers\KcpmkController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\PLController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\RolesmkController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\RumusanAkhirMkController;
use App\Http\Controllers\RumusaAkhirCplController;
use App\Http\Controllers\DosenMataKuliahController;
use App\Http\Controllers\KetercapaianController;
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


Route::get('/', [AuthController::class, 'showFormLogin']);
Route::get('login', [AuthController::class, 'showFormLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('/login-dosen', [AuthController::class, 'showFormLoginDosen'])->name('login.dosen');
Route::post('/login-dosen', [AuthController::class, 'loginDosen'])->name('login.dosen.submit');
Route::get('/login-admin', [AuthController::class, 'showFormLoginAdmin'])->name('login.admin');
Route::post('/login-admin', [AuthController::class, 'loginAdmin'])->name('login_admin.submit');


Route::group(['middleware' => 'auth'], function () {
    Route::group(['middleware' => ['role:admin']], function () {
        // Admin

        Route::get('admin', [AdminController::class, 'index'])->name('admin');
        Route::get('admin/tambah/', [AdminController::class, 'tambahindex'])->name('tambahadmin');
        Route::post('admin/tambah/post', [AdminController::class, 'tambah'])->name('form-tambah-admin');
        Route::get('admin/hapus/{id}', [AdminController::class, 'hapus']);
        Route::get('admin/edit/{id}', [AdminController::class, 'editindex']);
        Route::post('admin/edit/{id}/post', [AdminController::class, 'edit']);
        // Dosen
        Route::get('dosen', [DosenController::class, 'index'])->name('dosen');
        Route::get('dosen/tambah/', [DosenController::class, 'tambahindex'])->name('tambahdosen');
        Route::post('dosen/tambah/post', [DosenController::class, 'tambah'])->name('form-tambah-dosen');
        Route::get('dosen/hapus/{id}', [DosenController::class, 'hapus']);
        Route::get('dosen/edit/{id}', [DosenController::class, 'editindex']);
        Route::post('dosen/edit/{id}/post', [DosenController::class, 'edit']);
        // Mahasiswa
        Route::prefix('mhs')->group(function () {
            Route::get('/', [MahasiswaController::class, 'index'])->name('mhs');
            Route::get('tambah', [MahasiswaController::class, 'tambahindex'])->name('tambahmhs');
            Route::post('tambah/post', [MahasiswaController::class, 'tambah'])->name('form-tambah-mahasiswa');
            Route::get('hapus/{id}', [MahasiswaController::class, 'hapus'])->name('mahasiswa.hapus');
            Route::get('edit/{id}', [MahasiswaController::class, 'editindex'])->name('mahasiswa.edit');
            Route::post('edit/{id}/post', [MahasiswaController::class, 'edit'])->name('mahasiswa.edit.post');
            Route::get('{id}', [MahasiswaController::class, 'show'])->name('mahasiswa.show');
            Route::get('{id}/matakuliah', [MahasiswaController::class, 'showMataKuliah'])->name('mahasiswa.mataKuliah');
            Route::post('/mahasiswa/import', [MahasiswaController::class, 'import'])->name('mahasiswa.import');
            Route::get('mhs/{id}/matakuliah', [MahasiswaController::class, 'detail'])->name('mahasiswa.detail');
            Route::post('mhs/{id}/matakuliah/tambah', [MahasiswaController::class, 'addMataKuliah'])->name('mahasiswa.mataKuliah.tambah');
            Route::delete('/mhs/{mahasiswa}/mata-kuliah/{mataKuliah}', [MahasiswaController::class, 'removeMataKuliah'])->name('mahasiswa.removeMataKuliah');
        });

        // Import and Mata Kuliah
        Route::get('/mahasiswa/{id}/mata-kuliah', [MahasiswaController::class, 'showMataKuliah'])->name('mahasiswa.mata-kuliah');
        Route::post('/mahasiswa/{id}/add-mata-kuliah', [MahasiswaController::class, 'addMataKuliah'])->name('mahasiswa.addMataKuliah');


        // Tahun Ajaran
        Route::get('ta', [TahunAjaranController::class, 'index'])->name('ta');
        Route::get('ta/tambah/', [TahunAjaranController::class, 'tambahindex'])->name('tambahta');
        Route::post('ta/tambah/post', [TahunAjaranController::class, 'tambah'])->name('form-tambah-ta');
        Route::get('ta/hapus/{id}', [TahunAjaranController::class, 'hapus']);
        Route::get('ta/edit/{id}', [TahunAjaranController::class, 'editindex']);
        Route::post('ta/edit/{id}/post', [TahunAjaranController::class, 'edit']);
        // Mata Kuliah
        Route::get('mk', [MataKuliahController::class, 'index'])->name('mk');
        Route::get('mk/tambah/', [MataKuliahController::class, 'tambahindex'])->name('tambahmk');
        Route::post('mk/tambah/post', [MataKuliahController::class, 'tambah'])->name('form-tambah-mk');
        Route::get('mk/hapus/{id}', [MataKuliahController::class, 'hapus']);
        Route::get('mk/edit/{id}', [MataKuliahController::class, 'editindex']);
        Route::post('mk/edit/{id}/post', [MataKuliahController::class, 'edit']);
        Route::post('/mk/import', [MataKuliahController::class, 'import'])->name('MataKuliah.import');

        //PL
        Route::get('pl', [PLController::class, 'index'])->name('pl');
        Route::get('pl/tambah/', [PLController::class, 'tambahindex'])->name('tambahpl');
        Route::post('pl/tambah/post', [PLController::class, 'tambah'])->name('form-tambah-pl');
        Route::get('pl/hapus/{id}', [PLController::class, 'hapus']);
        Route::get('pl/edit/{id}', [PLController::class, 'editindex']);
        Route::post('pl/edit/{id}/post', [PLController::class, 'edit']);

        // CPL
        Route::get('cpl', [CPLController::class, 'index'])->name('cpl');
        Route::get('cpl/tambah/', [CPLController::class, 'tambahindex'])->name('tambahcpl');
        Route::post('cpl/tambah/post', [CPLController::class, 'tambah'])->name('form-tambah-cpl');
        Route::get('cpl/hapus/{id}', [CPLController::class, 'hapus']);
        Route::get('cpl/edit/{id}', [CPLController::class, 'editindex']);
        Route::post('cpl/edit/{id}/post', [CPLController::class, 'edit']);
        Route::get('/cpl/{id}/detail', [CPLController::class, 'detail'])->name('cpl.detail');
        Route::post('/cpl/{id}/detail', [CPLController::class, 'detail']);
        Route::get('cpl/import', [CPLController::class, 'showImportForm'])->name('cpl.import.form');
        Route::post('cpl/import', [CPLController::class, 'import'])->name('cpl.import');

        // CPMK
        Route::get('cpmk', [CPMKController::class, 'index'])->name('cpmk.index');
        Route::get('cpmk/tambah/', [CPMKController::class, 'tambahindex'])->name('tambahcpmk');
        Route::post('cpmk/tambah/post', [CPMKController::class, 'tambah'])->name('form-tambah-cpmk');
        Route::get('cpmk/hapus/{id}', [CPMKController::class, 'hapus'])->name('cpmk.hapus');
        Route::get('cpmk/edit/{id}', [CPMKController::class, 'editindex']);
        Route::post('cpmk/edit/{id}/post', [CPMKController::class, 'edit'])->name('cpmk.edit');
        Route::post('cpmk/import', [CPMKController::class, 'import'])->name('cpmk.import');
        // Rumusan Akhir MK
        Route::get('rumusanAkhirMk', [RumusanAkhirMkController::class, 'index'])->name('rumusanAkhirMk.index');
        Route::get('rumusanAkhirMk/tambah', [RumusanAkhirMkController::class, 'create'])->name('tambah-rumusan_akhir_mk');
        Route::post('rumusanAkhirMk/tambah', [RumusanAkhirMkController::class, 'store'])->name('rumusanAkhirMk.store');
        Route::get('rumusanAkhirMk/hapus/{id}', [RumusanAkhirMkController::class, 'hapus']);
        Route::get('rumusanAkhirMk/{id}/edit', [RumusanAkhirMkController::class, 'edit'])->name('rumusanAkhirMk.edit');
        // Route::put('rumusanAkhirMk/{id}', [RumusanAkhirMkController::class, 'update'])->name('rumusanAkhirMk.update');
        Route::delete('rumusanAkhirMk/{id}', [RumusanAkhirMkController::class, 'destroy'])->name('rumusan_akhir_mk.destroy');
        Route::post('rumusanAkhirMk/import', [RumusanAkhirMkController::class, 'import'])->name('rumusanAkhirMk.import');

        // Fetch Mata Kuliah
        Route::get('/fetch-matakuliah', [MataKuliahController::class, 'fetchMataKuliah']);
        //rumusanAkhirCpl
        Route::get('rumusanAkhirCpl', [RumusaAkhirCplController::class, 'index'])->name('rumusanAkhirCpl.index');
        Route::get('/import-rumusan-akhir-cpl', [RumusaAkhirCplController::class, 'importDataFromRumusanAkhirMk'])->name('importRumusanAkhirCpl');
        
    });
    Route::group(['middleware' => ['role:admin|dosen']], function () {

        // Ketercapaian CPMK 
        Route::get('/ketercapaian', [KetercapaianController::class, 'index'])->name('ketercapaian.index');
        Route::get('ketercapaian/{id}', [KetercapaianController::class, 'show'])->name('ketercapaian.show');
        Route::get('/ketercapaian/{id}/cpl', [KetercapaianController::class, 'capaianCpl'])->name('ketercapaian.capaian-cpl');
        // Route::get('/ketercapaian/{id}/cpl', [KetercapaianController::class, 'capaianCpl'])->name('ketercapaian.cpl');

        // DPNA
        Route::get('dpna', [DpnaController::class, 'index'])->name('dpna');
        Route::get('dpna/cari', [DpnaController::class, 'cari'])->name('dpnacari');
        Route::get('dpna-cetak', [DpnaController::class, 'downloadPDF'])->name('dpna-cetak');
        // Nilai

        Route::get('/nilai', [NilaiController::class, 'index'])->name('nilai.index');
        Route::post('nilai-post', [NilaiController::class, 'store']);
        Route::get('/nilai/{mataKuliah}', [NilaiController::class, 'show'])->name('nilai.show');
        Route::post('/nilai/{mahasiswa}/update', [NilaiController::class, 'update'])->name('nilai.update');
        Route::post('/nilai/update', [NilaiController::class, 'updateNilai'])->name('nilai.update');
        Route::post('/nilai/update', [NilaiController::class, 'updateNilai'])->name('nilai.updateNilai');

    });
    Route::get('home', [HomeController::class, 'index'])->name('home');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
});
