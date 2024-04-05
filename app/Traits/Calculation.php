<?php

namespace App\Traits;

trait Calculation
{
    protected $decimals = 8 ;

    public function lbm_sub($num1 , $num2 )
    {
        $result = bcsub($num1, $num2 , $this->decimals);
        return number_format($result, 8, '.', "");
    }

    public function lbm_add($num1 , $num2)
    {
        $result = bcadd($num1, $num2 , $this->decimals);
        return number_format($result, 8, '.', "");
    }

    public function lbm_mul($num1 , $num2)
    {
        $result = bcmul($num1, $num2 , $this->decimals);
        return number_format($result, 8, '.', "");
    }

    public function lbm_div($num , $num2)
    {
        $result = bcdiv($num, $num2 , $this->decimals);
        return number_format($result, 8, '.', "");
    }

    public function calculate_percentage($percent, $amount){
        $pp = $this->lbm_mul($percent, $amount);
        return $this->lbm_div($pp, 100);
    }

}


