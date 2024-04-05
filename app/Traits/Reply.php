<?php

namespace App\Traits;

trait Reply
{
    public function success($message, $data = null, $extra = null)
    {
        $info['status_code']    =  '1';
        $info['status_text']    =   'Success';
        $info['message']        =   $message;

        if ($data != null) {
            $info['data']       =   $data;
        }

        if ($extra != null) {
            foreach ($extra as $k => $o) {
                $info[$k] = $o;
            }
        }
        return $info;
    }

    public function failed($message)
    {
        $info['status_code']    =   '0';
        $info['status_text']    =   'Failed';
        $info['message']        =   $message;

        return $info;
    }
}
