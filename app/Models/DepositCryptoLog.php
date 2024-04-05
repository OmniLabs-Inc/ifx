<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{User};

class DepositCryptoLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'transaction_id',
        'deposit_currency',
        'deposit_currency_price',
        'deposit_currency_amount',
        'usdt_amount',
        'alpha_amount',
        'alpha_price',
        'hash',
        'chain_type',
        'token_address',
        'token_type',
        'reason',
        'from_wallet_address',
        'to_wallet_address',
        'status'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

}
