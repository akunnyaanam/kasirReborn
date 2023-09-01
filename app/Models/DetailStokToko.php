<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailStokToko extends Model
{
    use HasFactory;
    protected $fillable = ['stoktoko_id', 'barang_id', 'stok'];

    public function stokToko(): BelongsTo
    {
        return $this->belongsTo(StokToko::class, 'stoktoko_id');
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(TotalStokGudang::class, 'barang_id');
    }
    
    public function totalStokToko(): HasMany
    {
        return $this->hasMany(TotalStokToko::class, 'barang_id');
    }

    public function toko()
    {
        return $this->belongsTo(Toko::class, 'toko_id');
    }

    public function barangg()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    
}
