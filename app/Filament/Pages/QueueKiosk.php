<?php

namespace App\Filament\Pages;

use App\Models\Counter;
use App\Models\Service;
use Filament\Pages\Page;

class QueueKiosk extends Page
{
    protected static string $view = 'filament.pages.queue-kiosk';
    protected static ?string $title = 'Kiosk Cetak Antrian';
    protected static ?string $navigationLabel = 'Kiosk Cetak Antrian';
    protected static ?string $navigationGroup = 'Display Kiosk';
    protected static ?string $navigationIcon = 'heroicon-o-printer';

    public $selectedCounter = null;
    public $selectedService = null;

    public function getCountersProperty()
    {
        return Counter::with('service')->get();
    }

    public function selectCounter($counterId)
    {
        $this->selectedCounter = Counter::with('service')->find($counterId);
        $this->selectedService = null;
    }

    public function selectService($serviceId)
    {
        $this->selectedService = Service::find($serviceId);
    }

    public function resetSelection()
    {
        $this->selectedCounter = null;
        $this->selectedService = null;
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
}