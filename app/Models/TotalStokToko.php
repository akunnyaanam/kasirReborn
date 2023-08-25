<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TotalStokToko extends Model
{
    use HasFactory;
    protected $fillable = ['barang_id', 'toko_id', 'total_stok'];

    public function detailStokGudang()
    {
        return $this->belongsTo(DetailStokGudang::class, 'barang_id');
    }

    public function toko()
    {
        return $this->belongsTo(Toko::class, 'toko_id');
    }
}
