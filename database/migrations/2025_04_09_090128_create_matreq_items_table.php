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
        Schema::create('matreq_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matreq_id')->constrained('matreqs')->cascadeOnDelete();
            $table->foreignId('farmalkes_id')->constrained('farmalkes')->cascadeOnDelete();
            $table->integer('pesan')->default(0);
            $table->integer('kirim')->default(0);
            $table->unsignedBigInteger('subtotal_harga'); //HNA * Jumlah
            $table->unsignedBigInteger('total_harga'); //HNA - disc% * Jumlah
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matreq_items');
    }
};
