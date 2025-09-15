<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Actions\Action;
use App\Models\Service;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapLayananExport;
use Illuminate\Support\Facades\DB;

class MonitoringDashboard extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon  = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Monitoring Dashboard';
    protected static ?string $navigationGroup = 'Laporan & Monitoring';
    protected static string $view             = 'filament.pages.monitoring-dashboard';

    // filter tanggal sederhana
    public ?string $from = null;
    public ?string $to   = null;

    public function mount(): void
    {
        $this->from = now()->toDateString();
        $this->to   = now()->toDateString();

        $this->form->fill([
            'from' => $this->from,
            'to'   => $this->to,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(3)->schema([
                Forms\Components\DatePicker::make('from')
                    ->label('Dari Tanggal')
                    ->reactive()
                    ->afterStateUpdated(fn ($state) => $this->from = $state),

                Forms\Components\DatePicker::make('to')
                    ->label('Sampai Tanggal')
                    ->reactive()
                    ->afterStateUpdated(fn ($state) => $this->to = $state),

                Forms\Components\Placeholder::make('info')
                    ->content('Pilih tanggal untuk filter & export'),
            ]),
        ])->statePath('data'); // bebas, hanya untuk simpan state form
    }

    /**
     * Data yang dipakai di Blade (tabel rekap di halaman)
     */
    public function getViewData(): array
    {
        $rekapan = Service::query()
            ->withCount(['queues as queues_count' => function ($q) {
                $q->whereBetween('created_at', [
                    now()->parse($this->from)->startOfDay(),
                    now()->parse($this->to)->endOfDay(),
                ]);
            }])
            ->orderBy('name')
            ->get();

        return [
            'rekapan' => $rekapan,
        ];
    }

    /**
     * Tombol-tombol di header page Filament
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('export')
                ->label('Export Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                // arahkan ke route export sambil bawa query from & to dari form
                ->url(fn () => route('export.rekap-layanan', [
                    'from' => $this->from,
                    'to'   => $this->to,
                ]), shouldOpenInNewTab: false),
        ];
    }
    public function getMonitoringRealTime()
    {
        return Service::withCount([
            // jumlah antrian menunggu per layanan
            'queues as menunggu_count' => function ($q) {
                $q->where('status', 'menunggu');
            },
            // jumlah antrian dipanggil (sekarang)
            'queues as sekarang_count' => function ($q) {
                $q->where('status', 'dipanggil');
            },
            // jumlah antrian selesai
            'queues as selesai_count' => function ($q) {
                $q->where('status', 'selesai');
            },
            // jumlah antrian skip
            'queues as skip_count' => function ($q) {
                $q->where('status', 'skip');
            },
        ])->orderBy('name')->get(['id', 'name']);
    }

    public function getRekapJumlahPemohon()
    {
        $from = now()->parse($this->from)->startOfDay();
        $to   = now()->parse($this->to)->endOfDay();

        return DB::table('instansis as i')
            ->select('i.instansi_id', 'i.nama_instansi as name', DB::raw('COUNT(q.id) as total_pemohon'))
            ->leftJoin('services as s', 's.instansi_id', '=', 'i.instansi_id')
            ->leftJoin('queues as q', function ($join) use ($from, $to) {
                $join->on('q.service_id', '=', 's.id')
                    ->whereBetween('q.created_at', [$from, $to]);
            })
            ->groupBy('i.instansi_id', 'i.nama_instansi')
            ->orderBy('i.nama_instansi')
            ->get();
    }


    public function exportExcel()
    {
        return Excel::download(
            new RekapLayananExport($this->from, $this->to),
            'rekap_layanan.xlsx'
        );
    }    

}