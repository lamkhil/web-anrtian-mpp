<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services';

    public function counter()
    {
        return $this->belongsTo(Counter::class, 'service_id');
    }
        // relasi ke Queue
    public function queues()
    {
        return $this->hasMany(Queue::class, 'service_id');
    }   
}