<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gudang extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode_gudang',
        'nama',
        'alamat'
    ];

    public function detailMutasi()
    {
        return $this->hasMany(DetailMutasi::class, 'gudang_id');
    }

    public function stokGudangs()
    {
        return $this->hasMany(StokGudang::class, 'gudang_id');
    }

    public function Rgudang()
    {
        return $this->hasOne(Gudang::class);
    }

    public function Rbarang()
    {
        return $this->hasOne(Gudang::class);
    }

    public function totalStokGudang()
    {
        return $this->hasMany(TotalStokGudang::class, 'gudang_id');
    }
}
