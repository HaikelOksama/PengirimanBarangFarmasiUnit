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
        Schema::table('matreqs', function (Blueprint $table) {
            $table->timestamp('tgl_kirim')->nullable();
            $table->timestamp('tgl_terima')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matreqs', function (Blueprint $table) {
            $table->dropColumn('tgl_kirim');
            $table->dropColumn('tgl_terima');
        });
    }
};
