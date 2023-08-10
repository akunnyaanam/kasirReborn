<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokToko extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_barang',
        'id_toko',
        'stok_toko'
    ];

    public function RRstokbarang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    public function RRstoktoko()
    {
        return $this->hasMany(Toko::class, 'id_toko');
    }

}
