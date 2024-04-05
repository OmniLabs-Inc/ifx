<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class StakedPlan extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'stake_currency_amount',
        'stake_currency',
        'stake_currency_price',
        'usdt_amount',
        'daily_roi_income',
        'roi_percent',
        'monthly_roi_percent',
        'plan_name',
        'plan_start_at',
        'is_admin_created',
        'status'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function usermeta()
    {
        return $this->belongsTo(UserMeta::class, "user_id" , "user_id");
    }

}

