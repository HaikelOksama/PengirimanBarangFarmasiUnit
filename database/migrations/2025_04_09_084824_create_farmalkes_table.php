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
            $table->string('kode');
            $table->string('nama');
            $table->integer('isi');
            $table->string('satuan');
            $table->string('kemasan');
            $table->string('pbf_kode');
            $table->foreign('pbf_kode')->references('kode')->on('pbfs');
            $table->unsignedBigInteger('hna');
            $table->integer('diskon')->default(0);
            $table->integer('ppn')->default(11);
            $table->timestamps();
        });
    }
    // $table->unsignedBigInteger('hna');
    // $table->unsignedInteger('diskon')->default(0);
    // $table->unsignedInteger('isi');
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmalkes');
    }
};
