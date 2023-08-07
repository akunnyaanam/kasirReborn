<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode_jenis',
        'kode_barang',
        'kode_pemasok',
        'kode_gudang',
        'nama',
        'harga',
        'harga_jual',
        'stok'
    ];

    public function RjenisBarang()
    {
        return $this->belongsTo(JenisBarang::class, 'kode_jenis');
    }
    
    public function Rgudang()
    {
        return $this->belongsTo(Gudang::class, 'kode_gudang');
    }

    public function RRpemasok()
    {
        return $this->belongsTo(Pemasok::class, 'kode_pemasok');
    }

    public function Rbarang()
    {
        return $this->hasMany(Barang::class);
    }

}
