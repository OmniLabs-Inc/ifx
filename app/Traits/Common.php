<?php

namespace App\Traits;
use App\Traits\{CurlRequest,Calculation};
use App\Models\{Setting, UserWallet, UserWalletLog, UserMeta};


trait Common
{
    use CurlRequest, Calculation;

    public function getRandomNumber()
    {
        return rand(999999999, 100000000);
    }

    public function generateRandomString($length)
    {
        # String of all alphanumeric character
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

        # Shuffle the $str_result and returns substring of specified length
        return substr(str_shuffle($str_result), 0, $length);
    }

    public function live_price()
    {
        $alpha_price = Setting::where('id',1)->value('alpha_price');

        $data = [
            "USDT" => 1,
            "AFC"  =>  $alpha_price,
            "ETHS" => 1
        ];

        return $data;
    }

    public function addUserCrypto($user_id, $amount, $currency, $comment){

       $wallet = UserWallet::where(['user_id' => $user_id, "currency" => $currency])->first();

       if($wallet){

            $wallet->balance = $this->lbm_add($wallet->balance, $amount);
            $wallet->save();
       }
       else{
            $wallet = UserWallet::create(['user_id' => $user_id, "currency" => $currency, "balance" => $amount])->first();
       }

       # Create wallet log
       UserWalletLog::create([
        'user_id'   => $user_id,
        'wallet_id' => $wallet->id,
        'comment' => $comment
       ]);

    }
}
