<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{StakedPlan, User};

class DirectIncome extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
    */
    protected $fillable = [
        'user_id',
        'from_user_id',
        'direct_income',
        'date'
    ];

    public function staked_plan()
    {
        return $this->belongsTo(StakedPlan::class, "staked_plan_id", "id");
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
