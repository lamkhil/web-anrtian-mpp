<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->unsignedBigInteger('instansi_id')->nullable()->after('id');
            $table->foreign('instansi_id')->references('instansi_id')->on('instansis')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['instansi_id']);
            $table->dropColumn('instansi_id');
        });
    }
};
