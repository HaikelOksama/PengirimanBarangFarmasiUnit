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
        Schema::table('obat_expireds', function (Blueprint $table) {
            $table->foreignId('unit_id')->constrained('units', 'id')->cascadeOnDelete();
            $table->string('keterangan')->default("-");
            $table->ulid('remote_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('obat_expireds', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropColumn('unit_id');
            $table->dropColumn('remote_id');
            $table->dropColumn('keterangan');
        });
    }
};
