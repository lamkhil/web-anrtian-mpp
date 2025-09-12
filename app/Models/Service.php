<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services';
    protected $fillable = ['counter_id', 'name'];

    public function counter()
    {
        return $this->belongsTo(Counter::class, 'service_id');
    }
        // relasi ke Queue
    public function queues()
    {
        return $this->hasMany(Queue::class, 'service_id');
    }   
    public function instans()
    {
        return $this->belongsTo(Instansi::class);
    }
}