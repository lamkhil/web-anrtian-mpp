<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RekapLayananExport implements FromCollection, WithHeadings, ShouldAutoSize
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
        $from = now()->parse($this->from)->startOfDay();
        $to   = now()->parse($this->to)->endOfDay();

        return DB::table('instansis as i')
            ->select('i.nama_instansi as Jenis_Instansi', DB::raw('COUNT(q.id) as Jumlah_Pemohon'))
            ->leftJoin('services as s', 's.instansi_id', '=', 'i.instansi_id')
            ->leftJoin('queues as q', function ($join) use ($from, $to) {
                $join->on('q.service_id', '=', 's.id')
                     ->whereBetween('q.created_at', [$from, $to]);
            })
            ->groupBy('i.instansi_id', 'i.nama_instansi')
            ->orderBy('i.nama_instansi')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Jenis Instansi',
            'Jumlah Pemohon',
        ];
    }
}