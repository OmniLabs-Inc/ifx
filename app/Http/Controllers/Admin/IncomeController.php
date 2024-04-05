<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{RoiIncome, WashOut, MatchingIncome, DirectIncome, RewardIncome};
use App\Traits\{Reply};
use Exception;

class IncomeController extends Controller
{
    use Reply;

    # ROI INCOME FETCHING
    public function roiIncome(Request $request)
    {
        try {
            $roi = new RoiIncome();
            if ($request->roi_income) {
                $roi = $roi->where("roi_income", $request->roi_income);
            }
            if ($request->date) {
                $roi = $roi->where("date", $request->date);
            }

            #Staked plan condition
            $staked_where = [];
            if ($request->usdt_amount) {
                $staked_where["usdt_amount"] = $request->usdt_amount;
            }
            if ($request->plan_start_at) {
                $staked_where["plan_start_at"] = $request->plan_start_at;
            }

            if ($request->status) {
                $staked_where["status"] = $request->status;
            }

            $roi = $roi->with(['staked_plan:id,status,usdt_amount,plan_start_at']);

            $roi = $roi->whereHas('staked_plan', function ($q) use ($staked_where) {
                $q->where($staked_where);
            });

            # User Table condition
            $user_where = [];

            if ($request->user_sponser_id) {
                $user_where["user_sponser_id"] = $request->user_sponser_id;
            }
            if ($request->unique_id) {
                $user_where["user_unique_id"] = $request->unique_id;
            }

            $roi = $roi->with(['user:id,user_unique_id,user_sponser_id']);

            $roi = $roi->whereHas('user', function ($q) use ($user_where) {
                $q->where($user_where);
            });

            $roi = $roi->paginate($request->per_page  ?? 10);
            return $this->success("Roi Incomes fetch successfully", $roi);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }

    # WASHOUT INCOME FETCHING
    public function washoutIncome(Request $request)
    {
        try {

            $washoutincome = new WashOut();
            if ($request->current_remaing_capping) {
                $washoutincome = $washoutincome->where("current_remaing_capping", $request->current_remaing_capping);
            }
            if ($request->matching_income) {
                $washoutincome = $washoutincome->where("matching_income", $request->matching_income);
            }
            if ($request->washout_income) {
                $washoutincome = $washoutincome->where("washout_income", $request->washout_income);
            }
            if ($request->user_id) {
                $washoutincome = $washoutincome->where("user_id", $request->user_id);
            }
            if ($request->status) {
                $washoutincome = $washoutincome->where("status", $request->status);
            }

            # User Table condition
            $user_where = [];

            if ($request->user_sponser_id) {
                $user_where["user_sponser_id"] = $request->user_sponser_id;
            }
            if ($request->unique_id) {
                $user_where["user_unique_id"] = $request->unique_id;
            }

            $washoutincome = $washoutincome->with(['user:id,user_unique_id,user_sponser_id']);

            $washoutincome = $washoutincome->whereHas('user', function ($q) use ($user_where) {
                $q->where($user_where);
            });


            $washout = $washoutincome->paginate($request->per_page  ?? 10);
            return $this->success("WashOut Incomes fetch successfully", $washout);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }

    # MATCHING INCOME FETCHING
    public function matchingIncome(Request $request)
    {
        
        try {
            $matching = new MatchingIncome();
            if ($request->receive_date) {
                $matching = $matching->where('date', $request->receive_date);
            }
            if ($request->matching_income) {
                $matching = $matching->where('matching_income', $request->matching_income);
            }
            if ($request->status) {
                $matching = $matching->where('status', $request->status);
            }
            if ($request->user_id) {
                $matching = $matching->where('user_id', $request->user_id);
            }

            # User Table condition
            $user_where = [];

            if ($request->user_sponser_id) {
                $user_where["user_sponser_id"] = $request->user_sponser_id;
            }
            if ($request->unique_id) {
                $user_where["user_unique_id"] = $request->unique_id;
            }

            $matching = $matching->with(['user:id,user_unique_id,user_sponser_id']);

            $matching = $matching->whereHas('user', function ($q) use ($user_where) {
                $q->where($user_where);
            });

            $matching = $matching->paginate($request->per_page  ?? 10);
            return $this->success("Matching Incomes fetch successfully", $matching);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }

    public function directIncome(Request $request)
    {
        try {
            $direct = new DirectIncome();

            if ($request->direct_income) {
                $direct = $direct->where("direct_income", $request->direct_income);
            }
            if ($request->created_at) {
                $direct = $direct->whereRaw('DATE(created_at) = ?', [$request->created_at]);
            }

            # User Table condition
            $user_where = [];

            if ($request->user_sponser_id) {
                $user_where["user_sponser_id"] = $request->user_sponser_id;
            }
            if ($request->unique_id) {
                $user_where["user_unique_id"] = $request->unique_id;
            }

            $direct = $direct->with(['user:id,user_unique_id,user_sponser_id']);

            $direct = $direct->whereHas('user', function ($q) use ($user_where) {
                $q->where($user_where);
            });

            $direct_income = $direct->select(["id", "user_id", "direct_income", "date", "created_at"])->paginate($request->per_page  ?? 10);
            return $this->success("Direct Incomes fetch successfully", $direct_income);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }


    public function rewardIncome(Request $request){
        try {
            $reward = new RewardIncome();
            if ($request->receive_date) {
                $reward = $reward->where('date', $request->receive_date);
            }
            if ($request->reward_income) {
                $reward = $reward->where('reward_income', $request->reward_income);
            }
            if ($request->achieved_reward) {
                $reward = $reward->where('achieved_reward', $request->achieved_reward);
            }
            if ($request->user_id) {
                $reward = $reward->where('user_id', $request->user_id);
            }

            # User Table condition
            $user_where = [];

            if ($request->user_sponser_id) {
                $user_where["user_sponser_id"] = $request->user_sponser_id;
            }
            if ($request->unique_id) {
                $user_where["user_unique_id"] = $request->unique_id;
            }

            $reward = $reward->with(['user:id,user_unique_id,user_sponser_id']);

            $reward = $reward->whereHas('user', function ($q) use ($user_where) {
                $q->where($user_where);
            });

            $reward = $reward->paginate($request->per_page  ?? 10);
            return $this->success("Reward Incomes fetch successfully", $reward);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }
}
