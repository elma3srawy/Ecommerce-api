<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Verification extends Model
{
    protected $fillable = [
        'tokenable_id',
        'tokenable_type',
        'token',
        'expires_at'
    ];

    public function tokenable(): MorphTo
    {
        return $this->morphTo();
    }
}
