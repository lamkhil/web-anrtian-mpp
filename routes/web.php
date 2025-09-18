<?php

use App\Filament\Pages\QueueStatus;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\QueueKioskController;
use App\Exports\RekapLayananExport;
use App\Filament\Pages\AntrianSkckBerjalanPage;
use App\Filament\Pages\AntrianSkckPage;
use Maatwebsite\Excel\Facades\Excel;

Route::get('queue-status', QueueStatus::class)->name('queue.status');

Route::middleware(['auth']) // atau middleware panel kamu sendiri jika pakai multi-panel
    ->get('/exports/rekap-layanan', [ExportController::class, 'rekapLayanan'])
    ->name('export.rekap-layanan');

Route::get('/export/rekap-jumlah-pemohon', function (\Illuminate\Http\Request $request) {
    $from = $request->query('from', now()->toDateString());
    $to   = $request->query('to', now()->toDateString());

    return Excel::download(new RekapLayananExport($from, $to), 'rekap_jumlah_pemohon.xlsx');
})->name('export.rekap-jumlah-pemohon');

Route::domain('skck.dpmptsp-surabaya.my.id')->group(function () {
    Route::get('/', AntrianSkckPage::class);
    Route::get('/terdaftar', AntrianSkckBerjalanPage::class);
    Route::get('/terdaftar/print',[ExportController::class, 'cetakRekap']);
    Route::get('/print/{id}',[ ExportController::class, 'cetakSkck']);
});

Route::prefix('skck')->group(function () {
    Route::get('/', AntrianSkckPage::class);
    Route::get('/terdaftar', AntrianSkckBerjalanPage::class);
    Route::get('/terdaftar/print',[ExportController::class, 'cetakRekap']);
    Route::get('/print/{id}',[ ExportController::class, 'cetakSkck']);
});