<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Actions\Action;
use App\Models\Service;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapLayananExport;

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
    return [
        'menunggu' => \App\Models\Queue::where('status', 'menunggu')->get(),
        'dipanggil' => \App\Models\Queue::where('status', 'dipanggil')->get(),
        'selesai' => \App\Models\Queue::where('status', 'selesai')->get(),
    ];
    }
    public function getRekapHarian()
    {
    return Service::query()
        ->withCount(['queues as total' => function ($q) {
            $q->whereBetween('created_at', [
                now()->parse($this->from)->startOfDay(),
                now()->parse($this->to)->endOfDay(),
            ]);
        }])
        ->orderBy('name')
        ->get(['id', 'name']); // ambil id & name
    }

    public function exportExcel()
    {
        return Excel::download(
            new RekapLayananExport($this->from, $this->to),
            'rekap_layanan.xlsx'
        );
    }    

    
}
