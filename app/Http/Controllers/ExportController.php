<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\RekapLayananExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function rekapLayanan(Request $request)
    {
        $from = $request->query('from', now()->toDateString());
        $to   = $request->query('to', now()->toDateString());

        $fileName = "rekap_layanan_{$from}_sd_{$to}.xlsx";

        return Excel::download(new RekapLayananExport($from, $to), $fileName);
    }
}
