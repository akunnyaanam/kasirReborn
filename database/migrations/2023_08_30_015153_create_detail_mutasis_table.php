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
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mutasi_id');
            $table->unsignedBigInteger('barang_id');
            $table->string('gudang_awal');
            $table->unsignedBigInteger('gudang_tujuan_id');
            $table->integer('jumlah_barang');
            $table->timestamps();

            $table->foreign('mutasi_id')->references('id')->on('mutasis');
            $table->foreign('barang_id')->references('id')->on('total_stok_gudangs');
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
