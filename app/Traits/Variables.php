<?php

namespace App\Traits;

trait Variables
{

    protected $coin_name = "SIP";

    // protected $deposit_wallet_address = "0xBa72e57e35661D51Da41feC2920e4bd5a9cd0d2E";

    protected $token_address = [
        "USDT" => "0x2B90E061a517dB2BbD7E39Ef7F733Fd234B494CA",
        "AFC"  => "0x6ed903eAbB327577166B55825309432747D67E47",  // 18 decimal
        // "ETHS"  => "0x7830aa823C7F22E6Ec32F590875d5C5AD6Bc6D05" // 8 decimal
    ];

    protected $default_income_currency = "USDT";

    protected $default_withdraw_currency = "USDT";
}
