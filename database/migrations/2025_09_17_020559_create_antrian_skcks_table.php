<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('antrian_skcks', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nik')->unique();
            $table->string('nomor_whatsapp')->unique();
            $table->integer('antrian');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antrian_skcks');
    }
};
