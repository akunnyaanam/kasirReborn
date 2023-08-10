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
        'stok_gudang'
    ];

    public function RRstokbarang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    public function RRstokgudang()
    {
        return $this->hasMany(Gudang::class, 'id_gudang');
    }


}
