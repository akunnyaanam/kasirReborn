<?php

namespace App\Models;

use App\Models\StokToko;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Toko extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode_toko',
        'nama',
        'alamat'
    ];

    public function Rgudang()
    {
        return $this->hasOne(Toko::class);
    }
    public function Rbarang()
    {
        return $this->hasOne(Toko::class);
    }
    public function stokTokos()
    {
        return $this->hasMany(StokToko::class, 'toko_id');
    }
    public function totalStoks()
    {
        return $this->hasMany(TotalStok::class, 'toko_id');
    }

}
