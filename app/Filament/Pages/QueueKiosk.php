<?php

namespace App\Filament\Pages;

use App\Models\Counter;
use App\Models\Service;
use Filament\Pages\Page;

class QueueKiosk extends Page
{
    protected static string $view = 'filament.pages.queue-kiosk';
    protected static ?string $title = 'Cetak Antrian';
    protected static ?string $navigationLabel = 'Kiosk Cetak Antrian';
    protected static ?string $navigationGroup = 'Display Kiosk';
    protected static ?string $navigationIcon = 'heroicon-o-printer';

    public $selectedCounter = null;
    public $selectedService = null;
    public $services = [];

    // Daftar zona + deskripsi instansi
    public $counters = [
        1 => [
            'name' => 'Zona 1',
            'services' => [
                'Unit Pelayanan Pelayanan Terpadu Satu Pintu (UPTSP)',
            ],
        ],
        2 => [
            'name' => 'Zona 2',
            'services' => [
                'Kepolisian Resor Kota Besar',
                'Badan Narkotika Surabaya',
                'Bagian Pengadaan',
                'Bagian Pengadaan Barang/Jasa & Administrasi Pembangunan Kota Surabaya',
                'PT Pos Indonesia',
                'Badan Pendapatan Daerah',
            ],
        ],
        3 => [
            'name' => 'Zona 3',
            'services' => [
                'Dinas Kependudukan dan Pencatatan Sipil',
                'Pengadilan Negeri Surabaya',
                'Pengadilan Tata Usaha Negeri Surabaya',
                'Dinas Lingkungan Hidup',
                'Dinas Perumahan Rakyat Kawasan Permukiman serta Tanaman (DPRKPP)',
            ],
        ],
        4 => [
            'name' => 'Zona 4',
            'services' => [
                'BPJS Kesehatan',
                'BPJS Ketenagakerjaan',
                'Bursa Tenaga Kerja',
                'Perumda Air Minum Surya Sembada',
                'Direktorat Jenderal Pajak',
                'Pengadilan Agama',
                'Kantor Pertanahan Kota Surabaya I',
                'Kantor Pertanahan Kota Surabaya II',
            ],
        ],
        5 => [
            'name' => 'Zona 5',
            'services' => [
                'Kejaksaan Negeri Tanjung Perak',
                'Kejaksaan Negeri Kota Surabaya',
                'Klinik Investasi'
            ],
        ],
    ];

    public function getCountersProperty()
    {
        return Counter::with('service')->get();
    }

    protected function getViewData(): array
    {
        return [
            // Pastikan relasi 'instansi' ada di model Counter
            'counters' => Counter::with('instansi', 'services')->get(),
        ];
    }


    public function selectCounter($counterId)
    {
        $this->selectedCounter = $counterId;
    }

    public function selectService($serviceId)
    {
        $this->selectedService = Service::find($serviceId);
    }

    public function resetSelection()
    {
        $this->selectedCounter = null;
    }

    public function printStruk($serviceId)
    {
        // TODO: isi logic cetak struk
        $this->dispatchBrowserEvent('print-start', ['text' => "Cetak Struk untuk Service ID: {$serviceId}"]);
    }

    public function printBarcode($serviceId)
    {
        // TODO: isi logic cetak barcode
        $this->dispatchBrowserEvent('print-start', ['text' => "Cetak Barcode untuk Service ID: {$serviceId}"]);
    }

    // Method untuk "cetak antrian"
    public function printTicket(Service $service)
    {
        // logika cetak antrian (misal simpan ke tabel antrian)
        // Queue::create([...]);

        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => "Tiket untuk layanan {$service->name} berhasil dicetak!"
        ]);
    }
}