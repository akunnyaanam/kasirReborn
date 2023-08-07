<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemasok extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode_pemasok',
        'nama',
        'alamat',
        'no_telp'
    ];

    public function Rpemasok()
    {
        return $this->hasMany(Barang::class);
    }
}
