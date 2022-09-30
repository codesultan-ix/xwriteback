<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBan extends Model
{
    // use HasFactory;

    protected $fillable = [
        'user_id',
        'ban_type',
        'ban_reason',
        'ban_expire_on'
    ];

    protected $dates = ['ban_expire_on'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
