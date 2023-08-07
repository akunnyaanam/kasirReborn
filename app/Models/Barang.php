<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode_barang',
        'id_jenis_barang',
        'id_pemasok',
        'id_gudang',
        'nama',
        'harga_beli',
        'harga_jual',
        'stok'
    ];

    public function RjenisBarang()
    {
        return $this->belongsTo(JenisBarang::class, 'id_jenis_barang');
    }
    
    public function Rgudang()
    {
        return $this->belongsTo(Gudang::class, 'id_gudang');
    }

    public function RRpemasok()
    {
        return $this->belongsTo(Pemasok::class, 'id_pemasok');
    }

    public function Rbarang()
    {
        return $this->hasMany(Barang::class);
    }

}
