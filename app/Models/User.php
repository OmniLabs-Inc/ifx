<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\{StakedPlan, UserMeta, UserWallet};

class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'lrl',  // left refer link
        'rrl',  // right refer link
        'direct_sponser_id',  // right refer link
        'side',
        'user_unique_address',
        'is_email_verified',
        'status',
        'plan_active',
        'plan_activate_at',
        'user_unique_id',
        'user_sponser_id',
        'balance',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        // 'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function stakePlan()
    {
        return $this->hasMany(StakedPlan::class);
    }

    public function usermeta()
    {
        return $this->hasOne(UserMeta::class);
    }

    public function user_wallet()
    {
        return $this->hasMany(UserWallet::class);
    }
}
