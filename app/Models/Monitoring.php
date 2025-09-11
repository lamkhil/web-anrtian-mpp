<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Monitoring extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',   // contoh kolom
        'status', // contoh kolom
        'tanggal' // contoh kolom
    ];

}
