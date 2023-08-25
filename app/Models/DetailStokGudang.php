<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailStokGudang extends Model
{
    use HasFactory;
    protected $fillable = [
        'stokgudang_id',
        'barang_id',
        'stok'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function stokGudang()
    {
        return $this->belongsTo(StokGudang::class, 'stokgudang_id');
    }
    
    public function stokToko()
    {
        return $this->hasMany(DetailStokToko::class);
    }
    
    public function totalStokToko()
    {
        return $this->hasMany(TotalStokToko::class, 'barang_id');
    }
}
