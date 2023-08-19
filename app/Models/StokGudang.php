<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokGudang extends Model
{
    use HasFactory;
    protected $fillable = [
        'gudang_id'
    ];
    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'gudang_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function detail()
    {
        return $this->hasMany(DetailStokGudang::class, 'stokgudang_id');
    }
    
    public function GudangAwal()
    {
        return $this->hasMany(DetailMutasi::class, 'gudang_awal_id');
    }

    // public function RRstokbarang()
    // {
    //     return $this->belongsTo(Barang::class, 'id_barang');
    // }

    // public function RRstokgudang()
    // {
    //     return $this->belongsTo(Gudang::class, 'id_gudang');
    // }


}
