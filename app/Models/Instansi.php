<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instansi extends Model
{
          protected $primaryKey = 'instansi_id';
    public $incrementing = true;
    protected $keyType = 'int';
        protected $fillable = ['nama_instansi', 'deskripsi'];

}
