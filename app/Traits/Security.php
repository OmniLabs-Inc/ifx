<?php

namespace App\Traits;

trait Security
{
    private $secret_Key_1 = "fUjXn2r5u8x/A%D*G-KaPdSgVkYp3s6v";
    private $secret_iv_1 = "8x/A?D(G+KbPeShV";

    public function encrypt_decrypt($action, $string){

        $encrypt_method = 'AES-256-CBC';
        $output = false;

        $key = hash('sha256', $this->secret_Key_1); #hash secret key

        $iv = substr(hash('sha256', $this->secret_iv_1), 0, 16);

        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if( $action == 'decrypt' ) {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }

    public function encrypt($string){
        $output = $this->encrypt_decrypt('encrypt',$string);
        return $output;
    }

    public function decrypt($string){
        $output = $this->encrypt_decrypt('decrypt',$string);
        return $output;
    }
}
