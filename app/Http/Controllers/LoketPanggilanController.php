<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Counter; // sesuaikan dengan model yang menyimpan nama zona

class LoketPanggilanController extends Controller
{
    public function showZona($zona)
    {
        // Cari data counter berdasarkan nama zona
        $counter = Counter::where('nama', $zona)->first();

        if (!$counter) {
            abort(404, 'Zona tidak ditemukan.');
        }

        return view('dashboard-call-kiosk', compact('counter'));
    }
}
