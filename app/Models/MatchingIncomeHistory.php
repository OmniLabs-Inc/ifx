<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchingIncomeHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'match_income',
        'left_business',
        'right_business',
        'left_carry_forward',  // left refer link
        'right_carry_forward',  // right refer link
        'date'
    ];

}
