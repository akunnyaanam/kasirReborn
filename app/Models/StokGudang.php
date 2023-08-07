<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokGudang extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_barang',
        'id_gudang',
        'stok'
    ];

    public function RRbarang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    public function RRgudang()
    {
        return $this->belongsTo(Gudang::class, 'id_gudang');
    }


}
