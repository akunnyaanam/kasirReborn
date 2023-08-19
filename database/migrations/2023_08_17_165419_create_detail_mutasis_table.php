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
        Schema::create('detail_mutasis', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('mutasi_id');
            $table->unsignedBigInteger('barang_id');
            $table->unsignedBigInteger('gudang_awal_id');
            $table->unsignedBigInteger('gudang_tujuan_id');
            $table->integer('jumlah_barang');

            $table->foreign('mutasi_id')->references('id')->on('mutasis');
            $table->foreign('barang_id')->references('id')->on('detail_stok_gudangs');
            $table->foreign('gudang_awal_id')->references('id')->on('stok_gudangs');
            $table->foreign('gudang_tujuan_id')->references('id')->on('gudangs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_mutasis');
    }
};
