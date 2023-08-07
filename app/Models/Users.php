<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Users extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];

    protected $hidden = [
        'password',
    ];

    public function userRule(): BelongsTo
    {
        return $this->belongsTo(UserRules::class, 'id_rule');
    }

}
