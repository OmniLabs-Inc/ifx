<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Withdrawal extends Model
{
    use HasFactory;

    protected $table = "withdrawal";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'amount',
        'alpha_price',
        'withdraw_fees_percentage',
        'total_fees',
        'usdt_after_fees',
        'alpha_qty',
        'user_id',
        'to_address',
        'reason',
        'transaction_detail',
        'status'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
