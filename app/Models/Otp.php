<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;
    // use HasUuid;

    protected $table = 'otps';

    protected $fillable = [
        'entity_id',
        'entity_type',
        'code',
        'expires_at',
        'used_at',
        'type',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function entity(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function getIsExpiredAttribute()
    {
        return $this->expires_at->isPast();
    }
}
