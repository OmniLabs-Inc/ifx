<?php

namespace App\Http\Controllers\Income;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{StakedPlan, RoiIncome, UserMeta, GenerationTree, LevelIncome, Setting, User, UserWallet};
use App\Traits\{Calculation, Common, Variables};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class RoiIncomeController extends Controller
{
    use Common, Calculation, Variables;


    public function generateIncome(Request $request)
    {

        // return $request->all();

        if ($request->api_key != env("ROI_LEVEL_INCOME_KEY")) {
            return ["STATUS" => "INVALID REQUEST"];
        }

        $today = Carbon::now()->toDateString();

        # used for passing custom date for roi and level income generate
        if ($request->is_date == 1) {
            $today = $request->date; // "2023-05-14"
        }

        $setting = Setting::where("id", 1)->first();

        if (strtotime($setting->roi_last_updated_date) >= strtotime($today)) {
            return ["STATUS" => "INVALID REQUEST!!"];
        }

        $staked_plans = StakedPlan::with(["user:id,name,status,plan_active","usermeta:id,user_id,remain_capping"])->whereHas('usermeta', function ($q) {
            $q->where('remain_capping', ">", 0);
        })->where(["status" => "opened","is_admin_created" => "0"])->whereDate("plan_start_at", '<=', $today)->get(["id", "user_id", "daily_roi_income", "plan_start_at", "status"]);

        if (count($staked_plans) == 0) {
            return ["STATUS" => "Don't have any staked plan!!"];
        }

        foreach ($staked_plans as $stake) {

            # Validate user active or not
            if ($stake->user->status == 0) {
                continue; # this is used for skipping current index
            }

            # Validate user active or not
            if ($stake->user->plan_active != 1) {
                continue; # this is used for skipping current index
            }

            # User Don't have capping to achieve roi
            if($stake->usermeta->remain_capping < $stake->daily_roi_income){
                continue; # this is used for skipping current index
            }

            # create record in roi_incomes table
            RoiIncome::create([
                "staked_plan_id" => $stake->id,
                "user_id" => $stake->user_id,
                "roi_income" => $stake->daily_roi_income,
                "date" => $today
            ]);

            # update user meta table user total_roi_income
            UserMeta::where('user_id', $stake->user_id)->update([
                'total_roi_income' => DB::raw('total_roi_income + ' . $stake->daily_roi_income),
                'remain_capping' => DB::raw('remain_capping - ' . $stake->daily_roi_income),
            ]);

            # user wallet add amount with user wallet log
            $comment = "Deposit ROI Income $stake->daily_roi_income $this->default_income_currency from stake id $stake->id";

            $this->addUserCrypto($stake->user_id, $stake->daily_roi_income, $this->default_income_currency, $comment);
        }

        Setting::where('id', 1)->update([
            'roi_last_updated_date' => $today
        ]);

        return ["status" => "DONE", "message" => "HERE WE HAVE GENERATE ROI && LEVEL INCOME - DATE $today"];
    }

    public function collectEarningsForWithdrawal(){
        $getUsers = User::where('plan_active',1)->get();

        foreach($getUsers as $user){
            $user_id = $user->id;

            $roi_income = RoiIncome::where('user_id', $user_id)->sum('roi_income');
            $level_income = LevelIncome::where('user_id', $user_id)->sum('level_income');

            $wallet = UserWallet::where('user_id',$user_id)->first();
            if($wallet){
            $wallet->freeze_balance += ($roi_income + $level_income);
            $wallet->balance = "0.00000000";
            $wallet->update();
            }

            echo "User updated: ". $user_id . " ROI Added: " . $roi_income . " Level Added: " . $level_income . "<br />";
        }
    }
}
