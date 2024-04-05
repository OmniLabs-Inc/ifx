<?php

namespace App\Http\Controllers\Income;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\{RewardIncome, UserMeta, Setting , User, BinaryTree,WashOut, StakedPlan};
use Illuminate\Support\Facades\DB;
use App\Traits\{Calculation, Common, Variables};



class RewardIncomeController extends Controller
{
    use Common, Calculation, Variables;


    public function generateIncome(Request $request)
    {
        if ($request->api_key != env("REWARD_INCOME_KEY")) {
            return ["STATUS" => "INVALID REQUEST"];
        }

        $date = date('Y-m-d');

        $setting = Setting::where("id",1)->first();

        if(strtotime($setting->reward_last_updated_date) >= strtotime($date)){
            return ["STATUS" => "INVALID REQUEST!!"];
        }


        $reward_users = UserMeta::where('reward_last_achieved', '>', 0)->where('reward_times_left', '>', 0)->where('remain_capping', '>', 0)->where("reward_next_income",$date)->get();

        foreach($reward_users as $ruser){

            # Reward time is equal to o
            if($ruser->reward_times_left == 0){
                continue;
            }

            $remaining_capping = $ruser->remain_capping;

            $user_id =  $ruser->user_id;

            $REWARD_INCOME = $ruser->reward_income_gain;

            $total_reward_income = $ruser->total_reward_income;

            $reward_times_left = $ruser->reward_times_left;

            $gain_matching_income = 0;

            $_next_income = date('Y-m-d', strtotime($date. ' + 30 days'));

            $reward_next_income = null;
            
            $current_times = ($ruser->reward_times_left - 1);

            if((strtotime($ruser->reward_expiry_income) >= strtotime($_next_income)) && $current_times > 0){
                $reward_next_income = $_next_income;
            }

            # HERE Washout Income Log
            if ($REWARD_INCOME > $remaining_capping) {
            
                $washout_income = $this->lbm_sub($REWARD_INCOME, $remaining_capping);


                WashOut::create([
                    "user_id" => $user_id,
                    "matching_income" => $REWARD_INCOME,  // 48$
                    "washout_income" => $washout_income, // $3
                    "current_remaing_capping" => $remaining_capping,  // $45
                    "date" => $date,
                    "status" => "wash_out_reward"
                ]);

                # HERE WE UPDATE USER META TABLE
                UserMeta::where('user_id', $user_id)->update([
                    'remain_capping' => 0,
                    'total_reward_income' => $this->lbm_add($total_reward_income, $remaining_capping),
                    'reward_times_left' => ($current_times == 0) ? 0 : $this->lbm_sub($reward_times_left,1),
                    'reward_last_income' => $date,
                    'reward_next_income' => ($reward_next_income != null) ? $reward_next_income : null
                ]);
                
                $gain_matching_income = $remaining_capping; // 45

            } else {

                 # HERE WE UPDATE USER META TABLE
                 UserMeta::where('user_id', $user_id)->update([ 
                    'remain_capping' => $this->lbm_sub($remaining_capping, $REWARD_INCOME),
                    'total_reward_income' => $this->lbm_add($total_reward_income, $REWARD_INCOME),
                    'reward_times_left' => ($current_times == 0) ? 0 :  $this->lbm_sub($reward_times_left,1),
                    'reward_last_income' => $date,
                    'reward_next_income' => ($reward_next_income != null) ? $reward_next_income : null
                ]);

                $gain_matching_income = $REWARD_INCOME;
            }


            RewardIncome::create([
                "user_id" => $user_id,
                "reward_income" => $gain_matching_income,
                "achieved_reward" => $ruser->reward_last_achieved,
                "date" => $date
            ]);

            # user wallet add amount with user wallet log
            $comment = "Deposit Reward Income $gain_matching_income $this->default_income_currency from date $date";

            $this->addUserCrypto($ruser->user_id, $gain_matching_income, $this->default_income_currency, $comment);

        }


        $setting->reward_last_updated_date = $date;
        $setting->save();


        return ["STATUS" => "SUCCESS", "MESSAGE" => "HERE WE HAVE REWARD INCOME GENERATED DATE - $date"];
    }

    public function testing(){

          $dd = User::where('plan_active', 1)->get();

          $final = [];

          foreach ($dd as $key => $value) {
    

            array_push($final, [
                'id'=> $value['id'],
                'plan_activate_at' => substr($value['created_at'],0, 10)
            ]);


            User::where('id', $value['id'])->update([
                'plan_activate_at' => substr($value['created_at'],0, 10)
            ]);
          }


          return  $final;

    }

}
