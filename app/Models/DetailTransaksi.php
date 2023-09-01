<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    use HasFactory;
    protected $fillable = [
        'transaksi_id',
        'barangtoko_id',
        'jumlah',
        'total',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function barangtoko()
    {
        return $this->belongsTo(TotalStokToko::class); // Ganti sesuai dengan model yang sesuai
    }
}
