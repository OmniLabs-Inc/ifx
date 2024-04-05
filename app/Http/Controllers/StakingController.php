<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Traits\{Reply, Common, CurlRequest, Variables, Calculation};
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use App\Models\{UserMeta, UserWallet, UserWalletLog, Plan, StakedPlan, BinaryTree, User, DirectIncome, Setting};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Constants\Status;

class StakingController extends Controller
{
    use Reply, Variables, Common, CurlRequest, Calculation;

    # 2 * package
    # if user add 1 direct then it will be 4X
    const CAPPING = 2;


    public function check_direct($side, $sponser_id, $minimum_package){
         $users = User::where(["direct_sponser_id" =>  $sponser_id, "plan_active" => 1, "side" => $side])->pluck("id") ?? [];

        if(count($users) == 0){
            return 0;
        }

        $staked_plans_users = StakedPlan::whereIn("user_id", $users)->where("usdt_amount", ">=", "20")->pluck('usdt_amount')->toArray() ?? [];

        if(count($staked_plans_users) == 0){
            return 0;
        }

        foreach ($staked_plans_users as $key => $value) {
            if ($value >= $minimum_package) {
                return 2;
                break;
            }
        }

        if(count($staked_plans_users) > 0){
            return 1;
        }

    }

    public function calculate_price(Request $request)
    {
        try {
            $result = $this->live_price();
            return $this->success("Current Price", $result);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }

    public function buy_education_package(Request $request)
    {

        try {
            $validator  = Validator::make(
                $request->all(),
                [
                    'amount'  => 'required|regex:/^\d+(\.\d{1,8})?$/|gt:0'
                ]
            );

            if ($validator->errors()->all()) {
                throw new Exception($validator->errors()->first());
            }

            $today = Carbon::now()->toDateString();

            $user_id = Auth::id();

            # User Meta Table Update
            $meta_data = UserMeta::where("user_id", $user_id)->first();

            if(!$meta_data){
                throw new Exception('Unable to stake at this time.');
            }

            $live_price = $this->live_price();

            $usdt_amount = $request->amount;
            $alfa_amount = $this->lbm_div($request->amount, $live_price['AFC']);

            $rr = [];



            $user_wallet = UserWallet::where(["user_id" => $user_id, "currency" => $this->coin_name])->first();

            if (!$user_wallet) {
                throw new Exception('Insufficent Balance!!');
            }

            $wallet_usdt_amount = $this->lbm_mul($user_wallet->balance, $live_price['AFC']);

            if ($wallet_usdt_amount < $usdt_amount) {
                throw new Exception('Insufficent Balance');
            }

            // WE DETERMINE WHAT PACKAGE WILL BE STAKED BASED ON THE VALUE OF THE INVESTMENT
            if($usdt_amount == 99){
                $plan_name = "BASIC";
            }
            if($usdt_amount == 149){
                $plan_name = "SIGNAL";
            }
            if($usdt_amount == 249){
                $plan_name = "ADVANCED";
            }
            if($usdt_amount == 499){
                $plan_name = "EXPERT";
            }

            $daily_roi_percent = 0;

            $rr['user_id'] = $user_id;
            $rr['stake_currency_amount'] = $alfa_amount;
            $rr['stake_currency'] = $this->coin_name;
            $rr['stake_currency_price'] = $live_price['AFC'];
            $rr['usdt_amount'] = $usdt_amount;
            $rr['roi_percent'] = $daily_roi_percent; // the computed daily return based on monthly ROI/30
            $rr['plan_name']   = $plan_name;
            $rr['plan_start_at'] = Carbon::now()->addDays(1)->toDateString();
            $rr['status'] = "complete";

            $rr['daily_roi_income'] = 0;

            $staked = StakedPlan::create($rr);

            if(!$staked){
                throw new Exception('Unable to stake plan at this time!!');
            }

            // return $rr;

            # Deduct AFC in User Wallet Table
            $user_wallet->balance  = $this->lbm_sub($user_wallet->balance, $alfa_amount);
            $user_wallet->save();

            # create log in user wallet table
            $comment = "User Id " . $user_id . " Order Education Package for $" . $usdt_amount;

            UserWalletLog::create([
                "user_id" => $user_id,
                "wallet_id" => $user_wallet->id,
                "comment"  => $comment
            ]);


            return ($staked) ? $this->success("Plan Activated Successfully!") : $this->failed("Unable to active plan at this time!");

        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }


    public function stake_now(Request $request)
    {

        try {
            $validator  = Validator::make(
                $request->all(),
                [
                    'amount'  => 'required|regex:/^\d+(\.\d{1,8})?$/|gt:0'
                ]
            );

            if ($validator->errors()->all()) {
                throw new Exception($validator->errors()->first());
            }

            $today = Carbon::now()->toDateString();

            $user_id = Auth::id();

            # User Meta Table Update
            $meta_data = UserMeta::where("user_id", $user_id)->first();

            if(!$meta_data){
                throw new Exception('Unable to stake at this time.');
            }

            $live_price = $this->live_price();

            $usdt_amount = $request->amount;
            $alfa_amount = $this->lbm_div($request->amount, $live_price['AFC']);

            $rr = [];

            # minimum amount validate
            if (100 > $usdt_amount) {
                throw new Exception('The minimum amount for this plan is greater than $100.');
            }

            if($user_id != 1){

                $sponser_id = Auth::user()->direct_sponser_id;

                $sponser_meta = UserMeta::where("user_id", $sponser_id)->first();

                # minimum amount of sponser validate
                // if($sponser_meta->minimum_package > $usdt_amount){
                //     throw new Exception('The minimum amount for this plan is greater than or equal to $'. $sponser_meta->minimum_package);
                // }
            }

            $user_wallet = UserWallet::where(["user_id" => $user_id, "currency" => $this->coin_name])->first();

            if (!$user_wallet) {
                throw new Exception('Insufficent Balance!!');
            }

            $wallet_usdt_amount = $this->lbm_mul($user_wallet->balance, $live_price['AFC']);

            if ($wallet_usdt_amount < $usdt_amount) {
                throw new Exception('Insufficent Balance');
            }

            // WE DETERMINE WHAT PACKAGE WILL BE STAKED BASED ON THE VALUE OF THE INVESTMENT
            if($usdt_amount >= Status::STARTER_PLAN_MIN && $usdt_amount <= Status::STARTER_PLAN_MAX){
                $rr['monthly_roi_percent'] = 5;
                $plan_name = "STARTER";
            }
            if($usdt_amount >= Status::TRADER_PLAN_MIN && $usdt_amount <= Status::TRADER_PLAN_MAX){
                $rr['monthly_roi_percent'] = 6;
                $plan_name = "TRADER";
            }
            if($usdt_amount >= Status::PREMIUM_PLAN_MIN){
                $rr['monthly_roi_percent'] = 7;
                $plan_name = "PREMIUM";
            }

            $daily_roi_percent = round($rr['monthly_roi_percent'] / 30, 2);

            $rr['user_id'] = $user_id;
            $rr['stake_currency_amount'] = $alfa_amount;
            $rr['stake_currency'] = $this->coin_name;
            $rr['stake_currency_price'] = $live_price['AFC'];
            $rr['usdt_amount'] = $usdt_amount;
            $rr['roi_percent'] = $daily_roi_percent; // the computed daily return based on monthly ROI/30
            $rr['plan_name']   = $plan_name;
            $rr['plan_start_at'] = Carbon::now()->addDays(1)->toDateString();
            $rr['status'] = "opened"; // expired, opened



            if($meta_data->eligible_4x == 1){
                $total_capping = $this->lbm_mul($usdt_amount, 4);
            }else{
                $total_capping = $this->lbm_mul($usdt_amount, 2);
            }

            //$dailyRoiIncomes = $this->calculate_percentage($daily_roi_percent, $usdt_amount);

            $rr['daily_roi_income'] = round((($usdt_amount * $rr['monthly_roi_percent']) / 100) / 30, 2); // $dailyRoiIncomes;

            $staked = StakedPlan::create($rr);

            if(!$staked){
                throw new Exception('Unable to stake plan at this time!!');
            }

            // return $rr;

            # Deduct AFC in User Wallet Table
            $user_wallet->balance  = $this->lbm_sub($user_wallet->balance, $alfa_amount);
            $user_wallet->save();

            # create log in user wallet table
            $comment = "User Id " . $user_id . " Activate plan with amount " . $usdt_amount . " deducted AFC " . $alfa_amount;

            UserWalletLog::create([
                "user_id" => $user_id,
                "wallet_id" => $user_wallet->id,
                "comment"  => $comment
            ]);

            User::where(['id' => $user_id , 'plan_active' => 0])->update(['plan_active' => 1, 'plan_activate_at' => $today]);

            $meta_data->total_capping = $this->lbm_add($meta_data->total_capping, $total_capping);
            $meta_data->remain_capping = $this->lbm_add($meta_data->remain_capping, $total_capping);
            $meta_data->total_plans = $this->lbm_add($meta_data->total_plans, 1);
            $meta_data->total_plans_active = $this->lbm_add($meta_data->total_plans_active, 1);


            $total_staked_plan = StakedPlan::where("user_id",$user_id)->count();

            # if total staked plan is equal to 1
            if($total_staked_plan == 1){
                $meta_data->minimum_package = $usdt_amount;
                $meta_data->direct_expiry_at =  Carbon::now()->addDays(7)->toDateString();
                $meta_data->initial_staked_plan_id	= $staked->id;
            }

            if($user_id != 1) {
                # Direct Income Added to Sponser Account
                //TO DO: DIRECT INCOME TO BE REVISED TO IMPLEMENT VARIOUS LEVEL EARNING AND CONDITIONS
                $direct_income = $this->calculate_percentage(5, $usdt_amount);

                $sponsor_info = User::find($sponser_id);
                if($sponsor_info->plan_active != 0){

                    DirectIncome::create([
                        'user_id' => $sponser_id,
                        'from_user_id' => $user_id,
                        'direct_income' => $direct_income,
                        'date' => $today
                    ]);


                    $sponser_meta->update([
                        'total_direct_income' => DB::raw('total_direct_income + ' . $direct_income),
                        'remain_capping' => DB::raw('remain_capping - ' . $direct_income)
                    ]);

                    # user wallet add amount with user wallet log
                    $comment = "Deposit DIRECT INCOME $direct_income $this->default_income_currency from user id $user_id";

                    $this->addUserCrypto($sponser_id, $direct_income, $this->default_income_currency, $comment);
                }

                # here we are checking sponser direct
                $sposer_total_direct = User::where(["direct_sponser_id" =>  $sponser_id, "plan_active" => 1])->count();

                if(($sposer_total_direct >= 5) && ($sponser_meta->direct_expiry_at >= $today) && ($sponser_meta->is_plan_active == 1) ){
                    $sponser_meta->is_5_direct = 1;
                    $sponser_meta->save();
                    $first_stake = StakedPlan::where('id', $sponser_meta->initial_staked_plan_id)->first();
                    $dailyRoiIncomes1 = $this->calculate_percentage(0.75, $first_stake->usdt_amount);
                    $first_stake->daily_roi_income = $dailyRoiIncomes1;
                    $first_stake->save();
                }

                if(($sposer_total_direct >= 10) && ($sponser_meta->direct_expiry_at >= $today) && ($sponser_meta->is_plan_active == 1) ){
                    $sponser_meta->is_10_direct = 1;
                    $sponser_meta->save();
                    $first_stake = StakedPlan::where('id', $sponser_meta->initial_staked_plan_id)->first();
                    $dailyRoiIncomes2 = $this->calculate_percentage(1, $first_stake->usdt_amount);
                    $first_stake->daily_roi_income = $dailyRoiIncomes2;
                    $first_stake->save();
                }



                if($sponser_meta->eligible_4x != 1 && $sposer_total_direct > 0){


                    $eligible_left =  $this->check_direct("left", $sponser_id, $sponser_meta->minimum_package);

                    $eligible_right =  $this->check_direct("right", $sponser_id, $sponser_meta->minimum_package);


                    $matched = false;

                    if($eligible_left >= 1 && $eligible_right >= 2){
                        $matched = true;
                    }

                    if($eligible_left >= 2 && $eligible_right >= 1){
                        $matched = true;
                    }

                    if($eligible_left >= 2 && $eligible_right >= 2){
                        $matched = true;
                    }

                    # acheived matched 4x income
                    if($matched){


                        $pending_capping =  StakedPlan::where(['user_id' => $sponser_meta->user_id, 'status' => 'opened'])->sum('usdt_amount');

                        $increase_capping = $this->lbm_mul($pending_capping, 2);


                        UserMeta::where("user_id",$sponser_id)->update([
                            'total_capping' => DB::raw('total_capping + ' . $increase_capping),
                            'remain_capping' => DB::raw('remain_capping + ' . $increase_capping),
                            'eligible_4x' => 1
                        ]);

                    }

                }

            }


            if($meta_data->is_plan_active == 0){
                $meta_data->is_plan_active = 1;
            }

            $meta_data->save();



            return ($staked) ? $this->success("Plan Activated Successfully!") : $this->failed("Unable to active plan at this time!");
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }


    public function stake_report(Request $request)
    {
        $deposit = StakedPlan::where(["user_id" => Auth::id()])->select(["usdt_amount", "daily_roi_income", "plan_start_at", "status", "created_at"])->paginate($request->per_page ?? 10);
        return $this->success("Stake Reports Fetched Successfully.", $deposit);
    }


    public function stakeByAdmin(Request $request)
    {
        // return $request->all();
        try {
            $validator  = Validator::make(
                $request->all(),
                [
                    'amount'  => 'required|regex:/^\d+(\.\d{1,8})?$/|gt:0',
                    'user_id'  => 'required|exists:users,id,status,1'
                ]
            );

            if ($validator->errors()->all()) {
                throw new Exception($validator->errors()->first());
            }

            $today = Carbon::now()->toDateString();

            $user_id = $request->user_id;

            # User Meta Table Update
            $meta_data = UserMeta::where("user_id", $user_id)->first();

            if(!$meta_data){
                throw new Exception('Unable to stake at this time.');
            }

            $live_price = $this->live_price();

            $usdt_amount = $request->amount;
            $alfa_amount = $this->lbm_div($request->amount, $live_price['AFC']);

            $rr = [];

            # minimum amount validate
            if (20 > $usdt_amount) {
                throw new Exception('The minimum amount for this plan is greater than $20.');
            }

            if($user_id != 1){


                $by_sponser = User::where('id',$user_id)->first();


                $sponser_id = $by_sponser->direct_sponser_id;

                $sponser_meta = UserMeta::where("user_id", $sponser_id)->first();

                # minimum amount of sponser validate
                // if($sponser_meta->minimum_package > $usdt_amount){
                //     throw new Exception('The minimum amount for this plan is greater than or equal to $'. $sponser_meta->minimum_package);
                // }
            }


            $rr['user_id'] = $user_id;
            $rr['stake_currency_amount'] = $alfa_amount;
            $rr['stake_currency'] = $this->coin_name;
            $rr['stake_currency_price'] = $live_price['AFC'];
            $rr['usdt_amount'] = $usdt_amount;
            $rr['plan_start_at'] = Carbon::now()->addDays(1)->toDateString();
            $rr['status'] = "opened"; // expired, opened

            if($meta_data->eligible_4x == 1){
                $total_capping = $this->lbm_mul($usdt_amount, 4);
            }else{
                $total_capping = $this->lbm_mul($usdt_amount, 2);
            }


            $dailyRoiIncomes = $this->calculate_percentage(0.5, $usdt_amount);

            $rr['daily_roi_income'] = $dailyRoiIncomes;

            $staked = StakedPlan::create($rr);

            if(!$staked){
                throw new Exception('Unable to stake plan at this time!!');
            }

            User::where(['id' => $user_id , 'plan_active' => 0])->update(['plan_active' => 1, 'plan_activate_at' => $today]);


            # User Meta Table Update
            $meta_data = UserMeta::where("user_id", $user_id)->first();
            $meta_data->total_capping = $this->lbm_add($meta_data->total_capping, $total_capping);
            $meta_data->remain_capping = $this->lbm_add($meta_data->remain_capping, $total_capping);
            $meta_data->total_plans = $this->lbm_add($meta_data->total_plans, 1);
            $meta_data->total_plans_active = $this->lbm_add($meta_data->total_plans_active, 1);


            $total_staked_plan = StakedPlan::where("user_id",$user_id)->count();

            # if total staked plan is equal to 1
            if($total_staked_plan == 1){
                $meta_data->minimum_package = $usdt_amount;
                $meta_data->direct_expiry_at =  Carbon::now()->addDays(7)->toDateString();
                $meta_data->initial_staked_plan_id	= $staked->id;
            }

            if($user_id != 1) {
                # Direct Income Added to Sponser Account
                $direct_income = $this->calculate_percentage(10, $usdt_amount);

                if($sponser_meta->remain_capping >= $direct_income){

                    DirectIncome::create([
                        'user_id' => $sponser_id,
                        'from_user_id' => $user_id,
                        'direct_income' => $direct_income,
                        'date' => $today
                    ]);


                    $sponser_meta->update([
                        'total_direct_income' => DB::raw('total_direct_income + ' . $direct_income),
                        'remain_capping' => DB::raw('remain_capping - ' . $direct_income)
                    ]);

                    # user wallet add amount with user wallet log
                    $comment = "Deposit DIRECT INCOME $direct_income $this->default_income_currency from user id $user_id";

                    $this->addUserCrypto($sponser_id, $direct_income, $this->default_income_currency, $comment);
                }

                # here we are checking sponser direct
                $sposer_total_direct = User::where(["direct_sponser_id" =>  $sponser_id, "plan_active" => 1])->count();

                if(($sposer_total_direct >= 5) && ($sponser_meta->direct_expiry_at >= $today) && ($sponser_meta->is_plan_active == 1) ){
                    $sponser_meta->is_5_direct = 1;
                    $sponser_meta->save();
                    $first_stake = StakedPlan::where('id', $sponser_meta->initial_staked_plan_id)->first();
                    $dailyRoiIncomes1 = $this->calculate_percentage(0.75, $first_stake->usdt_amount);
                    $first_stake->daily_roi_income = $dailyRoiIncomes1;
                    $first_stake->save();
                }

                if(($sposer_total_direct >= 10) && ($sponser_meta->direct_expiry_at >= $today) && ($sponser_meta->is_plan_active == 1) ){
                    $sponser_meta->is_10_direct = 1;
                    $sponser_meta->save();
                    $first_stake = StakedPlan::where('id', $sponser_meta->initial_staked_plan_id)->first();
                    $dailyRoiIncomes2 = $this->calculate_percentage(1, $first_stake->usdt_amount);
                    $first_stake->daily_roi_income = $dailyRoiIncomes2;
                    $first_stake->save();
                }


                # here we check capping 4X and 2X

                if($sponser_meta->eligible_4x != 1 && $sposer_total_direct > 0){


                    $eligible_left =  $this->check_direct("left", $sponser_id, $sponser_meta->minimum_package);

                    $eligible_right =  $this->check_direct("right", $sponser_id, $sponser_meta->minimum_package);


                    $matched = false;

                    if($eligible_left >= 1 && $eligible_right >= 2){
                        $matched = true;
                    }

                    if($eligible_left >= 2 && $eligible_right >= 1){
                        $matched = true;
                    }

                    if($eligible_left >= 2 && $eligible_right >= 2){
                        $matched = true;
                    }

                    # acheived matched 4x income
                    if($matched){


                        $pending_capping =  StakedPlan::where(['user_id' => $sponser_meta->user_id, 'status' => 'opened'])->sum('usdt_amount');

                        $increase_capping = $this->lbm_mul($pending_capping, 2);


                        UserMeta::where("user_id",$sponser_id)->update([
                            'total_capping' => DB::raw('total_capping + ' . $increase_capping),
                            'remain_capping' => DB::raw('remain_capping + ' . $increase_capping),
                            'eligible_4x' => 1
                        ]);

                    }

                }
            }


            if($meta_data->is_plan_active == 0){
                $meta_data->is_plan_active = 1;
            }

            $meta_data->save();



            return ($staked) ? $this->success("Plan Activated Successfully!") : $this->failed("Unable to active plan at this time!");
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }


    public function convert_usdt(Request $request){
        try {
            $validator  = Validator::make(
                $request->all(),
                [
                    'amount'  => 'required|regex:/^\d+(\.\d{1,2})?$/|gt:0'
                ]
            );

            if ($validator->errors()->all()) {
                throw new Exception($validator->errors()->first());
            }

            $user_id = Auth::id();

            $wallet = UserWallet::where(["user_id" => $user_id , "currency" => "USDT"])->first();

            if(!$wallet){
                throw new Exception('Invalid Data.');
            }

            if($wallet->balance < $request->amount){
                throw new Exception("convert amount must be less than $wallet->balance.");
            }

            # DEDUCTED USDT BALANCE
            $wallet->balance = $this->lbm_sub($wallet->balance, $request->amount);
            $wallet->save();

            # GETTING AFC PRICE
            $alpha_price = Setting::where("id","1")->value('alpha_price');

            $converted_afc_coin = $this->lbm_div($request->amount,$alpha_price);

            $comment = "USER ID => $user_id CONVERTED $request->amount USDT to $converted_afc_coin AFC";

            $this->addUserCrypto($user_id, $converted_afc_coin, "AFC", $comment);

            return $this->success("USDT Convert to AFC!");
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }
}
