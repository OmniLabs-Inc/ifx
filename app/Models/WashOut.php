<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{ User};

class WashOut extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'matching_income',
        'washout_income',
        'current_remaing_capping',
        'date',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
