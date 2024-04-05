<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{User};


class UserMeta extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'initial_staked_plan_id',
        'total_capping',
        'remain_capping',
        'total_plans',
        'total_plans_active',
        'total_plans_expired',
        'total_roi_income',
        'total_direct_income',
        'total_matching_income',
        'total_reward_income',
        'left_carry_forward',
        'right_carry_forward',
        'minimum_package',
        'direct_expiry_at',
        'is_5_direct',
        'is_10_direct',
        'eligible_4x',
        'is_plan_active',
        'reward_last_achieved',
        'reward_income_gain',
        'reward_times_left',
        'reward_last_income',
        'reward_next_income',
        'reward_expiry_income'
    ];




    public function user(){
        return $this->belongsTo(User::class);
    }


}
