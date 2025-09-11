<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Counter;
use App\Models\Service;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
public function run(): void
{
    $counter1 = Counter::create(['name' => 'ZONA 1', 'is_active' => true]);
    $counter2 = Counter::create(['name' => 'ZONA 2', 'is_active' => true]);

    Service::create(['name' => 'Pengambilan Antri', 'prefix' => 'A', 'padding' => 3, 'is_active' => true, 'counter_id' => $counter1->id]);
    Service::create(['name' => 'Konsultasi FTSP', 'prefix' => 'K', 'padding' => 3, 'is_active' => true, 'counter_id' => $counter1->id]);
    Service::create(['name' => 'Konsultasi BPAD', 'prefix' => 'B', 'padding' => 3, 'is_active' => true, 'counter_id' => $counter2->id]);
}
}
