<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;
    protected $fillable = [
        'email',
        'user_unique_id',
        'otp',
        'expiry_time',
        'created_at'
    ];
}
