<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Antrian; // model antrian
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapExport;

class MonitoringPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static string $view = 'filament.pages.monitoring-dashboard';
    protected static ?string $title = 'Monitoring Antrian';

    public $tanggal;

    public function mount()
    {
        $this->tanggal = now()->format('Y-m-d'); // default hari ini
    }

    public function getMonitoringRealTime()
    {
        return [
            'menunggu' => Antrian::where('status', 'menunggu')->get(),
            'sekarang' => Antrian::where('status', 'sekarang')->get(),
            'selesai'  => Antrian::where('status', 'selesai')->get(),
            'skip'     => Antrian::where('status', 'skip')->get(),
        ];
    }

    public function getRekapHarian()
    {
        return Antrian::select('layanan', DB::raw('count(*) as total'))
            ->whereDate('created_at', $this->tanggal)
            ->groupBy('layanan')
            ->get();
    }
        public function exportExcel()
    {
        return Excel::download(new RekapExport($this->tanggal), 'rekap-'.$this->tanggal.'.xlsx');
    }
        public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}