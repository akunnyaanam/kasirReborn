<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisBarang extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode_jenis_barang',
        'kategori_barang'
    ];

    public function Rbarang()
    {
        return $this->hasMany(Barang::class);
    }
}
