<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('counters', function (Blueprint $table) {
            // kalau instansi pakai primary key custom 'instansi_id', maka:
            $table->unsignedBigInteger('instansi_id')->nullable();

            // bikin foreign key manual
            $table->foreign('instansi_id')->references('instansi_id')->on('instansis')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('counters', function (Blueprint $table) {
            $table->dropForeign(['instansi_id']);
            $table->dropColumn('instansi_id');
        });
    }
};
