<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\TarifSppController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\KwitansiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\Portal\PortalWaliController;
use App\Http\Controllers\TahunAjaranController;
use Illuminate\Support\Facades\Route;

/* ── Guest Routes ── */
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

/* ── Public ── */
Route::get('/kwitansi/verify/{nomor}', [KwitansiController::class, 'verify'])->name('kwitansi.verify');

/* ── Redirect root ── */
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

/* ── Authenticated Routes ── */
Route::middleware(['auth', 'check.active'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profil', [AuthController::class, 'profil'])->name('profil');
    Route::put('/profil', [AuthController::class, 'updateProfil'])->name('profil.update');
    Route::delete('/profil/avatar', [AuthController::class, 'deleteAvatar'])->name('profil.avatar.delete');
    Route::put('/profil/password', [AuthController::class, 'changePassword'])->name('profil.password');

    /* ── Dashboard (Admin, Kepala Sekolah) ── */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:admin,kepala_sekolah')
        ->name('dashboard');

    /* ── User Management (Admin only) ── */
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::patch('/users/{user}/toggle', [UserController::class, 'toggleStatus'])->name('users.toggle');
    });

    /* ── Master Data: Tahun Ajaran (Admin) ── */
    Route::middleware('role:admin')->group(function () {
        Route::resource('tahun-ajaran', TahunAjaranController::class)->except(['show']);
        Route::patch('/tahun-ajaran/{tahunAjaran}/toggle', [TahunAjaranController::class, 'toggleStatus'])->name('tahun-ajaran.toggle');
    });

    /* ── Master Data: Kelas (Admin) ── */
    Route::middleware('role:admin')->group(function () {
        Route::get('/kelas', [KelasController::class, 'index'])->name('kelas.index');
        Route::get('/kelas/create', [KelasController::class, 'create'])->name('kelas.create');
        Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store');
        Route::get('/kelas/{kela}/edit', [KelasController::class, 'edit'])->name('kelas.edit');
        Route::put('/kelas/{kela}', [KelasController::class, 'update'])->name('kelas.update');
        Route::delete('/kelas/{kela}', [KelasController::class, 'destroy'])->name('kelas.destroy');
    });

    /* ── Master Data: Tarif SPP ── */
    Route::get('/tarif-spp', [TarifSppController::class, 'index'])
        ->middleware('role:admin,kepala_sekolah')
        ->name('tarif.index');
    Route::middleware('role:admin')->group(function () {
        Route::get('/tarif-spp/create', [TarifSppController::class, 'create'])->name('tarif.create');
        Route::post('/tarif-spp', [TarifSppController::class, 'store'])->name('tarif.store');
        Route::get('/tarif-spp/{tarifSpp}/edit', [TarifSppController::class, 'edit'])->name('tarif.edit');
        Route::put('/tarif-spp/{tarifSpp}', [TarifSppController::class, 'update'])->name('tarif.update');
        Route::delete('/tarif-spp/{tarifSpp}', [TarifSppController::class, 'destroy'])->name('tarif.destroy');
    });

    /* ── Data Siswa ── */
    Route::get('/siswa', [SiswaController::class, 'index'])
        ->middleware('role:admin,kepala_sekolah')
        ->name('siswa.index');
    Route::get('/siswa/{siswa}', [SiswaController::class, 'show'])
        ->middleware('role:admin,kepala_sekolah,wali_murid')
        ->name('siswa.show');
    Route::middleware('role:admin')->group(function () {
        Route::get('/siswa-create', [SiswaController::class, 'create'])->name('siswa.create');
        Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
        Route::get('/siswa/{siswa}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
        Route::put('/siswa/{siswa}', [SiswaController::class, 'update'])->name('siswa.update');
        Route::delete('/siswa/{siswa}', [SiswaController::class, 'destroy'])->name('siswa.destroy');
    });

    /* ── Tagihan SPP ── */
    Route::get('/tagihan', [TagihanController::class, 'index'])
        ->middleware('role:admin,kepala_sekolah')
        ->name('tagihan.index');
    Route::middleware('role:admin,kepala_sekolah')->group(function () {
        Route::get('/tagihan/create', [TagihanController::class, 'create'])->name('tagihan.create');
        Route::post('/tagihan', [TagihanController::class, 'store'])->name('tagihan.store');
        Route::post('/tagihan/generate', [TagihanController::class, 'generateMassal'])->name('tagihan.generate');
        Route::get('/tagihan/{tagihan}/edit', [TagihanController::class, 'edit'])->name('tagihan.edit');
        Route::put('/tagihan/{tagihan}', [TagihanController::class, 'update'])->name('tagihan.update');
    });
    Route::delete('/tagihan/{tagihan}', [TagihanController::class, 'destroy'])
        ->middleware('role:admin')
        ->name('tagihan.destroy');

    /* ── Pembayaran ── */
    Route::get('/pembayaran', [PembayaranController::class, 'index'])
        ->middleware('role:admin,kepala_sekolah')
        ->name('pembayaran.index');
    Route::middleware('role:admin,kepala_sekolah')->group(function () {
        Route::get('/pembayaran/create/{tagihan}', [PembayaranController::class, 'create'])->name('pembayaran.create');
        Route::post('/pembayaran', [PembayaranController::class, 'store'])->name('pembayaran.store');
        Route::patch('/pembayaran/{pembayaran}/verifikasi', [PembayaranController::class, 'verifikasi'])->name('pembayaran.verifikasi');
    });
    Route::post('/pembayaran/{tagihan}/upload-bukti', [PembayaranController::class, 'uploadBukti'])
        ->middleware('role:wali_murid')
        ->name('pembayaran.upload');

    /* ── Kwitansi ── */
    Route::get('/kwitansi/{pembayaran}', [KwitansiController::class, 'download'])
        ->middleware('role:admin,wali_murid,kepala_sekolah')
        ->name('kwitansi.download');

    /* ── Laporan ── */
    Route::middleware('role:admin,kepala_sekolah')->group(function () {
        Route::get('/laporan/tunggakan', [LaporanController::class, 'tunggakan'])->name('laporan.tunggakan');
        Route::get('/laporan/tunggakan/export/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.tunggakan.pdf');
        Route::get('/laporan/tunggakan/export/excel', [LaporanController::class, 'exportExcel'])->name('laporan.tunggakan.excel');
        Route::get('/laporan/rekap-bulanan', [LaporanController::class, 'rekapBulanan'])->name('laporan.rekap');
        Route::get('/laporan/rekap-bulanan/export/pdf', [LaporanController::class, 'exportRekapPdf'])->name('laporan.rekap.pdf');
        Route::get('/laporan/rekap-bulanan/export/excel', [LaporanController::class, 'exportRekapExcel'])->name('laporan.rekap.excel');
    });

    /* ── Portal Wali Murid ── */
    Route::middleware('role:wali_murid')->prefix('portal')->name('portal.')->group(function () {
        Route::get('/', [PortalWaliController::class, 'index'])->name('index');
        Route::get('/tagihan', [PortalWaliController::class, 'tagihan'])->name('tagihan');
        Route::get('/riwayat', [PortalWaliController::class, 'riwayat'])->name('riwayat');
    });
});
