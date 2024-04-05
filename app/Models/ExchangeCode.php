<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'amount',
        'currency',
        'expired_at',
        'is_redeemed',
        'redeemed_by'
    ];
    public function user_redeem()
    {
        return $this->hasOne(User::class, 'id', 'redeemed_by');
    }
    public function user_created()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    public function getStatusAttribute()
    {
        $status = '';
        switch ($this->is_redeemed) {
            case '0':
                $status = 'Pending';
                break;
            case '1':
                $status = 'Completed';
                break;
            case '2':
                $status = 'Expired';
                break;
            default:
                $status = 'Expired';
                break;
        }

        return $status;
    }


    public function toArray()
    {
        $array = parent::toArray();
        foreach ($this->getMutatedAttributes() as $key) {
            if (!array_key_exists($key, $array)) {
                $array[$key] = $this->{$key};
            }
        }
        return $array;
    }
}
