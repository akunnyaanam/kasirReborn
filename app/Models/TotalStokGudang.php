<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TotalStokGudang extends Model
{
    use HasFactory;
    protected $fillable = ['barang_id', 'gudang_id', 'total_stok'];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'gudang_id');
    }
    
    public function mutasi()
    {
        return $this->hasMany(DetailMutasi::class, 'barang_id');
    }

    public function totalStokToko()
    {
        return $this->hasMany(TotalStokToko::class, 'barang_id');
    }
    

    // public function mutasiAwal()
    // {
    //     return $this->hasMany(DetailMutasi::class, 'gudang_awal_id');
    // }
}
