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
        Schema::create('detail_stok_gudangs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('stokgudang_id');
            $table->unsignedBigInteger('barang_id');
            $table->integer('stok');
            $table->timestamps();
            $table->unique(['stokgudang_id', 'barang_id']);

            $table->foreign('barang_id')->references('id')->on('barangs');
            $table->foreign('stokgudang_id')->references('id')->on('stok_gudangs');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_stok_gudangs');
    }
};
