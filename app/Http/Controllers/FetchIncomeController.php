<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{RoiIncome, WashOut, MatchingIncome, LevelIncome, DirectIncome, RewardIncome, MatchingIncomeHistory};
use App\Traits\{Reply, Calculation};
use Exception;
use Illuminate\Support\Facades\Auth;


class FetchIncomeController extends Controller
{
    use Reply, Calculation;

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

            $roi = $roi->where("user_id", Auth::id())->orderBy('id', 'desc')->paginate($request->per_page  ?? 10);

            return $this->success("Roi Incomes fetch successfully", $roi);

        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }

    # WASHOUT INCOME FETCHING
    public function washoutIncome(Request $request)
    {
        try {

            $washout = new WashOut();

            if ($request->date) {
                $washout = $washout->where('date', $request->date);
            }

            if ($request->matching_income) {
                $washout = $washout->where('matching_income', $request->matching_income);
            }

            if ($request->washout_income) {
                $washout = $washout->where('washout_income', $request->washout_income);
            }
            if ($request->status) {
                $washout = $washout->where('status', $request->status);
            }
            $washout = $washout->where("user_id", Auth::id())->orderBy('id', 'desc')->paginate($request->per_page  ?? 10);
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
            $matching = $matching->where(["user_id" => Auth::id()])->orderBy('id', 'desc')->where('matching_income','>', 0)->paginate($request->per_page  ?? 10);
            return $this->success("Matching Incomes fetch successfully", $matching);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }

    # MATCHING INCOME FETCHING
    public function levelIncome(Request $request)
    {
        try {
            $matching = new LevelIncome();
            //if ($request->receive_date) {
             //   $matching = $matching->where('date', $request->receive_date);
            //}
            if ($request->level_income) {
                $matching = $matching->where('level_income', $request->matching_income);
            }
            if ($request->status) {
                $matching = $matching->where('status', $request->status);
            }

            $matching = $matching->where(["user_id" => Auth::id()])->orderBy('id', 'desc')->where('level_income','>', 0)->paginate($request->per_page  ?? 10);

            return $this->success("Level Incomes fetch successfully", $matching);

        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }

    # Direct INCOME FETCHING
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

            //$direct_income = $direct->where("user_id", Auth::id())->select(["id", "user_id", "direct_income", "from_user_id", "date", "created_at"])->orderBy('id', 'desc')->paginate($request->per_page  ?? 10);

            $direct_income = $direct->where("user_id", Auth::id())
                ->select(["direct_incomes.id", "direct_incomes.user_id", "direct_income", "from_user_id", "date", "direct_incomes.created_at", "users.name","users.user_unique_id"])
                ->leftJoin('users', 'direct_incomes.from_user_id', '=', 'users.id')
                ->orderBy('direct_incomes.id', 'desc')
                ->paginate($request->per_page ?? 10);

            $direct_income->appends(['per_page' => $request->per_page  ?? 10, 'page' =>  $request->page  ?? 1]);

            return $this->success("Direct Incomes fetch successfully", $direct_income);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }


     # Direct INCOME FETCHING
     public function matching_history(Request $request)
     {

         try {
             $direct = new MatchingIncomeHistory();


             if ($request->created_at) {
                 $direct = $direct->whereRaw('DATE(created_at) = ?', [$request->created_at]);
             }

             $direct_income = $direct->where("user_id", Auth::id())->select(["id", "user_id", "match_income", "left_business", "right_business", "left_carry_forward", "right_carry_forward", "date", "created_at"])->orderBy('id', 'desc')->paginate($request->per_page  ?? 10);

             $direct_income->appends(['per_page' => $request->per_page  ?? 10, 'page' =>  $request->page  ?? 1]);

             return $this->success("Matching History fetch successfully", $direct_income);
         } catch (Exception $e) {
             return  $this->failed($e->getMessage());
         }
     }

    # Reward INCOME FETCHING
    public function rewardIncome(Request $request)
    {
        try {
            $reward = new RewardIncome();

            if ($request->reward_income) {
                $reward = $reward->where("reward_income", $request->reward_income);
            }

            if ($request->created_at) {
                $reward = $reward->whereRaw('DATE(created_at) = ?', [$request->created_at]);
            }

            if ($request->achieved_reward) {
                $reward = $reward->where('achieved_reward', $request->achieved_reward);
            }

            $reward_income = $reward->where("user_id", Auth::id())->select(["id", "reward_income", "achieved_reward", "date", "created_at"])->orderBy('id', 'desc')->paginate($request->per_page  ?? 10);
            return $this->success("Reward Incomes fetch successfully", $reward_income);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }

}
