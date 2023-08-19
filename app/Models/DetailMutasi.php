<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailMutasi extends Model
{
    use HasFactory;
    protected $fillable = ['mutasi_id', 'barang_id', 'gudang_awal_id', 'gudang_tujuan_id', 'jumlah_barang'];

    public function mutasi()
    {
        return $this->belongsTo(Mutasi::class, 'mutasi_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function gudangAwal()
    {
        return $this->belongsTo(StokGudang::class, 'gudang_awal_id');
    }

    public function gudangTujuan()
    {
        return $this->belongsTo(Gudang::class, 'gudang_tujuan_id');
    }
}
