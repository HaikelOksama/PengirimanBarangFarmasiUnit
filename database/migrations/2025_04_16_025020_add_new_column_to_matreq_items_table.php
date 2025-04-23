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
        Schema::table('matreq_items', function (Blueprint $table) {
            $table->unsignedBigInteger('hna');
            $table->unsignedInteger('diskon')->default(0);
            $table->unsignedInteger('harga_beli')->default(0);
            $table->unsignedInteger('isi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matreq_items', function (Blueprint $table) {
            $table->dropColumn('hna');
            $table->dropColumn('diskon');
            $table->dropColumn('harga_beli');
            $table->dropColumn('isi');
        });
    }
};
