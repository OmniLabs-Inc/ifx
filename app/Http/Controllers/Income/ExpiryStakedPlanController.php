<?php

namespace App\Http\Controllers\Income;

use App\Http\Controllers\Controller;
use App\Models\{StakedPlan, UserMeta};
use App\Traits\{Common, Variables, Calculation};


class ExpiryStakedPlanController extends Controller
{
    use Common, Variables, Calculation;

    public function expiry()
    {
        $expiry_users = UserMeta::where('remain_capping', 0)->get();

        if (count($expiry_users) == 0) {
            return ['STATUS' => 'FAILED', "MESSAGE" => 'NO PLAN TO EXPIRE!!'];
        }

        foreach ($expiry_users as $plan) {


            $stake_ids = StakedPlan::where('user_id',$plan->user_id)->where(['status' => 'opened'])->pluck('id');

            if(count($stake_ids) > 0){

                StakedPlan::whereIn('id',$stake_ids)->update(['status' => 'expired']);
    
                #update user meta table plan expired
                $plan->total_plans_active = $this->lbm_sub($plan->total_plans_active, count($stake_ids));
                $plan->total_plans_expired = $this->lbm_add($plan->total_plans_expired, count($stake_ids));
                $plan->save();
            }

        }

        return ['STATUS' => 'SUCCESS', "MESSAGE" => 'PLAN EXPIRED!!'];
    }
}
