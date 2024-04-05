<?php
namespace App\Custom;

use App\Traits\CurlRequest;

class TronGrid {
    use CurlRequest;

    const Api_url="https://shastapi.tronscan.org/";  // TESTNET
    // const $Api_url="https://apilist.tronscan.org/"; // MAINNET

    public function validateAddress($address){

        $url = $this::Api_url . "api/account?address=". $address;

        $response = $this->getCurl($url);

        if(array_key_exists("balances",$response)){
            return 1;
        }

        return 0;
    }

    public function validateTransaction($hash){

        $url = $this::Api_url . "api/transaction-info?hash=". $hash;

        $response = $this->getCurl($url);

        if(array_key_exists("hash",$response)){
            return $response;
        }

        return 0;
    }
}
?>
