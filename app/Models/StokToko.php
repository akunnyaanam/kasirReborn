<?php

namespace App\Models;

use App\Models\Toko;
use App\Models\TotalStokToko;
use App\Models\DetailStokToko;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StokToko extends Model
{
    use HasFactory;
    protected $fillable = ['kode_suratjalan', 'toko_id'];

    public function toko(): BelongsTo
    {
        return $this->belongsTo(Toko::class, 'toko_id');
    }

    public function detailStokTokos(): HasMany
    {
        return $this->hasMany(DetailStokToko::class, 'stoktoko_id');
    }

}
