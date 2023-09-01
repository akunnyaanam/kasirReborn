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
        Schema::create('detail_stok_tokos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('stoktoko_id');
            $table->unsignedBigInteger('barang_id');
            $table->integer('stok');
            $table->timestamps();
            $table->unique(['stoktoko_id', 'barang_id']);

            $table->foreign('barang_id')->references('id')->on('total_stok_gudangs');
            $table->foreign('stoktoko_id')->references('id')->on('stok_tokos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_stok_tokos');
    }
};
