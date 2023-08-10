<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

}
