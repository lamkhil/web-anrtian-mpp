<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instansi extends Model
{
    protected $table = 'instansis';

    protected $primaryKey = 'instansi_id';

    public $incrementing = true;

    protected $keyType = 'int';
    
    protected $fillable = ['nama_instansi', 'deskripsi'];
    
    public function counter()
    {
        return $this->belongsTo(Counter::class);
    }

    public function services()
    {
        return $this->hasMany(\App\Models\Service::class, 'instansi_id', 'instansi_id');
    }
}
