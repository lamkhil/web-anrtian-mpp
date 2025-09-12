<?php

use App\Filament\Pages\QueueStatus;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\QueueKioskController;

Route::get('queue-status', QueueStatus::class)->name('queue.status');

Route::middleware(['auth']) // atau middleware panel kamu sendiri jika pakai multi-panel
    ->get('/exports/rekap-layanan', [ExportController::class, 'rekapLayanan'])
    ->name('export.rekap-layanan');



