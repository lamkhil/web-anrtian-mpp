<?php

namespace App\Exports;

use App\Models\Service;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RekapLayananExport implements FromCollection, WithHeadings
{
    protected $from;
    protected $to;

    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to   = $to;
    }

    public function collection()
    {
        return Service::query()
            ->withCount(['queues as total' => function ($q) {
                $q->whereBetween('created_at', [
                    now()->parse($this->from)->startOfDay(),
                    now()->parse($this->to)->endOfDay(),
                ]);
            }])
            ->get(['name']); // hanya ambil kolom name
    }

    public function headings(): array
    {
        return [
            'Layanan',
            'Jumlah Pemohon',
        ];
    }
}