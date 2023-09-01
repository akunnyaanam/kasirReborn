<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TotalStokToko extends Model
{
    use HasFactory;
    protected $fillable = ['barang_id', 'toko_id', 'gudang_id_asal', 'total_stok'];

    public function totalStokGudang()
    {
        return $this->belongsTo(TotalStokGudang::class, 'barang_id');
    }

    public function toko()
    {
        return $this->belongsTo(Toko::class, 'toko_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function stokToko()
    {
        return $this->belongsTo(StokToko::class, 'stok_toko_id');
    }

    public function transaksi()
    {
        return $this->belongsTo(DetailTransaksi::class); // Ganti sesuai dengan model yang sesuai
    }
}
