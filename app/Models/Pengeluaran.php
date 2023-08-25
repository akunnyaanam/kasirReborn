<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode_pengeluaran',
        'deskripsi',
        'jumlah'
    ];

    public function detailPengeluaran()
    {
        return $this->hasOne(DetailPengeluaran::class, 'pengeluaran_id');
    }
}
