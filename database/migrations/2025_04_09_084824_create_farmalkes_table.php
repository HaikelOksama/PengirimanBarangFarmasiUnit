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
        Schema::create('farmalkes', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('tipe');
            $table->string('kategori');
            $table->string('satuan');
            $table->unsignedBigInteger('hna');
            $table->integer('diskon');
            $table->integer('ppn');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmalkes');
    }
};
