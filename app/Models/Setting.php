<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
    */
    protected $fillable = [
        'roi_last_updated_date',
        'matching_last_updated_date',
        'reward_last_updated_date',
        'alpha_price',
        'maintenance',
        'withdraw_fee'
    ];
}
