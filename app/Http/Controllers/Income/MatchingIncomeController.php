<?php

namespace App\Http\Controllers\Income;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User, StakedPlan, UserMeta, BinaryTree, GenerationTree,RewardIncome, WashOut, MatchingIncome, MatchingIncomeHistory, Setting};
use Illuminate\Support\Facades\DB;
use App\Traits\{Reply, Common, Calculation, Variables};
use Illuminate\Support\Carbon;
 
class MatchingIncomeController extends Controller
{
    use Reply, Common, Calculation, Variables;


    # This function is used for getting decendent of binary tree id
    public function getBinaryDecendant($tree_id = 0)
    {
        if ($tree_id == 0) {
            return [];
        }

        return BinaryTree::whereDescendantOrSelf($tree_id)->pluck('user_id') ?? [];
    }

    # This function is used for getting total staked previous week usdt amount
    public function getTotalBusinessPreviousDay($user_ids , $previous_date)
    {
        $total_business = StakedPlan::whereIn('user_id', $user_ids)->whereRaw('DATE(created_at) = ?', [$previous_date])->where('status','opened')->sum('usdt_amount') ?? 0;

        return $total_business;
    }

    public function setRewardIncome($last_achieved, $left_team, $right_team){
        
        if($last_achieved >= 100000000){ // 100 million  // 11 stage  // 10 crore
            return [
                "need_update" => 0
            ];
        }

        // business =>  income , times , days
        $reward_incomes = [
            "100000000" => [100000, 12, 360], // 100 million  // 11 stage // 10 crore
            "10000000" => [21000, 12, 360], // 10 million  // 10 stage  // 1 crore
            "1000000" => [5000, 12, 360], // 1 million   // 9 stage // 10 lakh
            "500000" => [2100, 9, 270],// 8 stage
            "250000" => [1000, 9, 270], // 7 stage
            "100000" => [500, 9, 270], // 6 stage
            "50000" => [300, 9 , 270], // 5 stage
            "25000" => [200, 6, 180], // 4 stage
            "15000" => [100, 6, 180],   // 3 stage
            "10000" => [50, 6, 180],   // 2 stage
        ];

        # HERE WE GETTING LEFT DOWNLINE TOTAL STAKED PLAN AMOUNT LIKE 550$
        $left_total_buisness = $this->getTotalBusiness($left_team);

        # HERE WE GETTING RIGHT DOWNLINE TOTAL STAKED PLAN AMOUNT LIKE 100$
        $right_total_buisness = $this->getTotalBusiness($right_team);

        # IF business is less than 10 thousand dollar return from here!!
        if($left_total_buisness < 10000 ||  $right_total_buisness  < 10000){
            return [
                "need_update" => 0
            ];
        }


        foreach($reward_incomes as $key_amount => $key_reward){


            if( ($left_total_buisness >= $key_amount) && ($right_total_buisness >= $key_amount) ){    // 1 stage
                

                return [
                    'reward_last_achieved'   => $key_amount,
                    "reward_income_gain" => $key_reward['0'],
                    'reward_times_left'   => $key_reward['1'], 
                    'reward_next_income'   => date('Y-m-d', strtotime("+30 days")),
                    'reward_expiry_income'   => date('Y-m-d', strtotime("+".$key_reward['2']." days")),
                    "need_update" => 1
                ]; 

                break;
            }

        }


        return [
            "need_update" => 0
        ];

    }

    public function getTotalBusiness($user_ids)
    {
        $total_business = StakedPlan::whereIn('user_id', $user_ids)->sum('usdt_amount') ?? 0;

        return $total_business;
    }

    public function oneTimeRewardIncome($user_id, $activate_at, $left_team, $right_team){

        //  one time reward income 
        // $1500  $50 onetime 15 days
        // $3500  $100 onetime 30 days
        // $7000  $200  onetime 45 days

        // `````````````````````````    
        $now = time(); // or your date as well
        $your_date = strtotime($activate_at);
        $datediff = $now - $your_date;

        $c_date = round($datediff / (60 * 60 * 24));

        if($c_date > 45){
            return;
        }

        $meta = UserMeta::where('remain_capping', '>', 0)->where("user_id",$user_id)->first();

        $remaining_capping = $meta->remain_capping ?? 0;

        # HERE WE GETTING LEFT DOWNLINE TOTAL STAKED PLAN AMOUNT LIKE 550$
        $left_total_buisness = $this->getTotalBusiness($left_team);

        # HERE WE GETTING RIGHT DOWNLINE TOTAL STAKED PLAN AMOUNT LIKE 100$
        $right_total_buisness = $this->getTotalBusiness($right_team);


        if($left_total_buisness < 1500 ||  $right_total_buisness  < 1500){
            return;
        }

        $current_date = date('Y-m-d');

        $find_one_time = [
            ['days' => 15, 'achieve' => 1500, 'income' => 50],
            ['days' => 30, 'achieve' => 3500, 'income' => 100],
            ['days' => 45, 'achieve' => 7000, 'income' => 200]
        ];


        foreach ($find_one_time as $key => $one_time) {
        
            if($c_date <= $one_time['days'] && $left_total_buisness >= $one_time['achieve'] && $right_total_buisness >= $one_time['achieve']){
            
                $record = RewardIncome::where(['user_id' => $user_id, 'achieved_reward' => $one_time['achieve']])->first();
     
                 if(!$record){
                     // means record is not exists and user completed 15 day chellenge
                     
                     
                     $gain_matching_income = 0;
     
                     # HERE Washout Income Log
                     if ($one_time['income'] > $remaining_capping) {
         
                         $washout_income = $this->lbm_sub($one_time['income'], $remaining_capping);
         
         
                         WashOut::create([
                             "user_id" => $user_id,
                             "matching_income" => $one_time['income'],  // 48$
                             "washout_income" => $washout_income, // $3
                             "current_remaing_capping" => $remaining_capping,  // $45
                             "date" => $current_date,
                             "status" => "wash_out_reward"
                         ]);
         
                         # HERE WE UPDATE USER META TABLE
                         $meta->remain_capping = 0;
                         $meta->total_reward_income = $this->lbm_add($meta->total_reward_income, $remaining_capping);
                         $meta->save();
         
                         $gain_matching_income = $remaining_capping; // 45
         
                     } else {
         
                         # HERE WE UPDATE USER META TABLE
                         $meta->remain_capping = $this->lbm_sub($remaining_capping, $one_time['income']); // 28
                         $meta->total_reward_income = $this->lbm_add($meta->total_reward_income, $one_time['income']); // 117
                         $meta->save();
         
                         $gain_matching_income = $one_time['income'];
                     }
                     
                     
                     RewardIncome::create([
                         'user_id' => $user_id,
                         'reward_income' => $gain_matching_income,
                         'date' => date('Y-m-d'),
                         'achieved_reward' => $one_time['achieve']
                     ]);
     
                      # user wallet add amount with user wallet log
                     $comment = "Deposit Reward ($1500) One time Income $ $gain_matching_income $this->default_income_currency from date $current_date";
     
                     $this->addUserCrypto($user_id, $gain_matching_income, $this->default_income_currency, $comment);
                 }
            }
        }

    }

    # daily closing 12:05 AM
    public function generateIncome(Request $request){
        // return $request->all();

        if($request->api_key != env("MATCHING_INCOME_KEY")){
            return ["STATUS" => "INVALID REQUEST"];
        }

        $previous_date = date('Y-m-d', strtotime("-1 days"));

        $setting = Setting::where("id",1)->first();

        # used for passing custom date for matching income generate
        if($request->is_date == 1){
            $previous_date = $request->date;
        }

        if(strtotime($setting->matching_last_updated_date) >= strtotime($previous_date)){
            return ["STATUS" => "INVALID REQUEST!!"];
        }

        $all_users = User::where("status", 1)->get();


        foreach ($all_users as $user) {


            $loop_user_id = $user->id;

            $loop_user_active = $user->plan_active;

            $loop_user_active_at = $user->plan_activate_at;

            $matched =  MatchingIncome::where([
                "user_id" =>  $loop_user_id,
                "date" => $previous_date
            ])->first();


            if ($matched) {
               continue; # This is used for skipping current index in for each loop
            }

            # HERE WE ARE GETTING CURRENT USER PARENT ID
            $parent_id = BinaryTree::where("user_id",  $loop_user_id)->value("id");

            # HERE WE ARE GETTING FIRST LEFT AND RIGHT CHILD ID
            $downline = BinaryTree::where("parent_id", $parent_id)->get(['id', 'team']);

            $left_child_id = 0;
            $right_child_id = 0;

            # IF THERE IS ONLY ONE CHILD
            if (count($downline) == 1) {
                $left_child_id = ($downline[0]['team'] == "left_team") ? $downline[0]['id'] : 0;
                $right_child_id = ($downline[0]['team'] == "right_team") ? $downline[0]['id'] : 0;
            }

            # IF THERE IS TWO CHILD
            if (count($downline) == 2) {
                $left_child_id = ($downline[0]['team'] == "left_team") ? $downline[0]['id'] : $downline[1]['id'];
                $right_child_id = ($downline[0]['team'] == "right_team") ? $downline[0]['id'] : $downline[1]['id'];
            }

            # HERE WE GETTING LEFT DOWNLINE USER IDS EXAMPLE - [2,5,6,7]
            $left_team = $this->getBinaryDecendant($left_child_id);

            # HERE WE GETTING RIGHT DOWNLINE USER IDS EXAMPLE - [3,6]
            $right_team = $this->getBinaryDecendant($right_child_id);

            # HERE WE GETTING LEFT DOWNLINE TOTAL STAKED PLAN AMOUNT LIKE 550$
            $left_total_buisness = $this->getTotalBusinessPreviousDay($left_team , $previous_date);

            # HERE WE GETTING RIGHT DOWNLINE TOTAL STAKED PLAN AMOUNT LIKE 100$
            $right_total_buisness = $this->getTotalBusinessPreviousDay($right_team , $previous_date);

            # HERE WE ARE FETCHING USER META DATA FOR GETTING MATCHING INCOME
            $user_meta = UserMeta::where("user_id", $loop_user_id)->first();

            # SUM PREVIOUS LEFT CARRY FORWARD WITH CURRENT WEEK LEFT INCOME
            $left_total_buisness_forward = $this->lbm_add($left_total_buisness, $user_meta->left_carry_forward); // 615

            # SUM PREVIOUS RIGHT CARRY FORWARD WITH CURRENT WEEK RIGHT INCOME
            $right_total_buisness_forward = $this->lbm_add($right_total_buisness, $user_meta->right_carry_forward); // 300


            // if id is not activated 

            if($user->plan_active == 0){
                # HERE WE ARE UPDATING CARRY FORWARD FOR NEXT WEEK
                $user_meta->left_carry_forward = $left_total_buisness_forward;
                $user_meta->right_carry_forward = $right_total_buisness_forward;
                $user_meta->save();

                continue; # This is used for skipping current index in for each loop
            }

            $total_matched =  MatchingIncome::where([
                "user_id" =>  $loop_user_id
            ])->count();  

            if($total_matched > 0){

                # NOW WE ARE GETTING MINIMUM INCOME FROM RIGHT AND LEFT
                $matching_income = min($left_total_buisness_forward, $right_total_buisness_forward);

                # NOW WE ARE SUBTRACTING LEFT CARRY FORWARD INCOME WITH MATCHING INCOME
                $left_carry_forward_now = $this->lbm_sub($left_total_buisness_forward, $matching_income);

                # NOW WE ARE SUBTRACTING RIGHT CARRY FORWARD INCOME WITH MATCHING INCOME
                $right_carry_forward_now = $this->lbm_sub($right_total_buisness_forward, $matching_income);


            }else{
                
                # NOW WE ARE GETTING MINIMUM INCOME FROM RIGHT AND LEFT NEW CODE GUJJAR JI

                $max_side = $left_total_buisness_forward >= $right_total_buisness_forward ? 'left' : 'right';
        
                $matching_income =   $max_side == "left" ? $right_total_buisness_forward : $left_total_buisness_forward; 
        
        
                if($max_side == "left"){
                    $left_carry_forward_now =  $this->lbm_sub($left_total_buisness_forward, $right_total_buisness_forward);
                    $left_carry_forward_now = ($left_carry_forward_now > 20) ? $this->lbm_sub($left_carry_forward_now , 20) : $left_carry_forward_now;
                    $right_carry_forward_now =  0;
                }
        
                if($max_side == "right"){
                    $left_carry_forward_now =  0;
                    $right_carry_forward_now =  $this->lbm_sub($right_total_buisness_forward, $left_total_buisness_forward);
                    $right_carry_forward_now = ($right_total_buisness_forward > 20) ? $this->lbm_sub($right_total_buisness_forward , 20) : $right_carry_forward_now;
                }
               

                # NOW WE ARE GETTING MINIMUM INCOME FROM RIGHT AND LEFT OLD CODE RAMAN SIR
                // $max_matching_income = max($left_total_buisness_forward, $right_total_buisness_forward);

                // $max_matching_income = $this->lbm_div($max_matching_income, 2);

                // $max_side = $left_total_buisness_forward >= $max_matching_income ? 'left' : 'right';

                // # NOW WE ARE GETTING MINIMUM INCOME FROM RIGHT AND LEFT
                // $min_matching_income = min($left_total_buisness_forward, $right_total_buisness_forward);

                // $matching_income = min($max_matching_income, $min_matching_income);

                // $left_carry_forward_now = $max_side == "left" ? $left_total_buisness_forward - ($matching_income * 2) : $left_total_buisness_forward - $matching_income;

                // $right_carry_forward_now = $max_side == "right" ? $right_total_buisness_forward - ($matching_income * 2) : $right_total_buisness_forward - $matching_income;

                

            }


            // return [
            //     'left_total_buisness_forward' =>$left_total_buisness_forward,
            //     'right_total_buisness_forward' =>$right_total_buisness_forward,
            //     'max_side' =>$max_side,
            //     'matching_income' =>$matching_income,
            //     'left_carry_forward_now' =>$left_carry_forward_now,
            //     'right_carry_forward_now' =>$right_carry_forward_now,
            // ];


            # HERE WE ARE GETTING REMAINING CAPPING
            $remaining_capping = $user_meta->remain_capping;

      
           

            # HERE WE ARE UPDATING CARRY FORWARD FOR NEXT WEEK
            $user_meta->left_carry_forward = $left_carry_forward_now;
            $user_meta->right_carry_forward = $right_carry_forward_now;
            $user_meta->save();


            $previous_day_matching = $this->calculate_percentage(10, $matching_income);


            # IF NO INCOME FOUND PREVIOUS WEEK
            if ($previous_day_matching == 0) {

                # HERE WE CREATE A LOG FOR MATCHING INCOME
                MatchingIncome::create([
                    "user_id" => $loop_user_id,
                    "matching_income" => 0,
                    "date" => $previous_date,
                    "status" => "completed"
                ]);


                MatchingIncomeHistory::create([
                    'user_id' => $loop_user_id,
                    'match_income' => 0,
                    'left_business' => $left_total_buisness,
                    'right_business' =>  $right_total_buisness,
                    'left_carry_forward' => $left_carry_forward_now,
                    'right_carry_forward' => $right_carry_forward_now,
                    'date' => $previous_date
                ]);


                continue; # This is used for skipping current index in for each loop
            }


            # HERE WE ARE CHECKING REMAINING CAPPING IF USER DON'T HAVE CAPPING THEN
            # RECORD CREATED IN WASHOUT TABLE!!

            $gain_matching_income = 0;

            # HERE Washout Income Log
            if ($previous_day_matching > $remaining_capping) {

                $washout_income = $this->lbm_sub($previous_day_matching, $remaining_capping);


                WashOut::create([
                    "user_id" => $loop_user_id,
                    "matching_income" => $previous_day_matching,  // 48$
                    "washout_income" => $washout_income, // $3
                    "current_remaing_capping" => $remaining_capping,  // $45
                    "date" => $previous_date,
                    "status" => "wash_out"
                ]);

                # HERE WE UPDATE USER META TABLE
                $user_meta->remain_capping = 0;
                $user_meta->total_matching_income = $this->lbm_add($user_meta->total_matching_income, $remaining_capping);
                $user_meta->save();

                $gain_matching_income = $remaining_capping; // 45

            } else {

                # HERE WE UPDATE USER META TABLE
                $user_meta->remain_capping = $this->lbm_sub($remaining_capping, $previous_day_matching); // 28
                $user_meta->total_matching_income = $this->lbm_add($user_meta->total_matching_income, $previous_day_matching); // 117
                $user_meta->save();

                $gain_matching_income = $previous_day_matching;
            }

            # HERE WE CREATE A LOG FOR MATCHING INCOME
            MatchingIncome::create([
                "user_id" => $loop_user_id,
                "matching_income" => $gain_matching_income, // 45
                "date" => $previous_date,
                "status" => "completed"
            ]);

            MatchingIncomeHistory::create([
                'user_id' => $loop_user_id,
                'match_income' => $matching_income,
                'left_business' => $left_total_buisness,
                'right_business' =>  $right_total_buisness,
                'left_carry_forward' => $left_carry_forward_now,
                'right_carry_forward' => $right_carry_forward_now,
                'date' => $previous_date
            ]);

            


            # user wallet add amount with user wallet log
            $comment = "Deposit Matching Income $gain_matching_income $this->default_income_currency to User Id $loop_user_id from $previous_date";

            $this->addUserCrypto($loop_user_id, $gain_matching_income, $this->default_income_currency, $comment);


            # here wE set one time reward system
            if($loop_user_active == 1){
                $this->oneTimeRewardIncome($loop_user_id, $loop_user_active_at, $left_team, $right_team);
            }

            # HERE WE SET REWARD INCOME -
            $reward_eligible = $this->setRewardIncome($user_meta->reward_last_achieved , $left_team, $right_team);


            if($reward_eligible['need_update'] == 1){

                $user_meta->reward_last_achieved = $reward_eligible['reward_last_achieved'];
                $user_meta->reward_income_gain = $reward_eligible['reward_income_gain'];
                $user_meta->reward_times_left = $reward_eligible['reward_times_left'];
                $user_meta->reward_last_income = null;
                $user_meta->reward_next_income = $reward_eligible['reward_next_income'];
                $user_meta->reward_expiry_income = $reward_eligible['reward_expiry_income'];
                $user_meta->save();
            }


        }

        Setting::where('id', 1)->update([
            'matching_last_updated_date' => $previous_date
        ]);

        return ["STATUS" => "SUCCESS", "MESSAGE" => "HERE WE HAVE MATCHING INCOME GENERATED DATE - $previous_date"];
    }

    public function test(Request $request){

        $user_id = $request->user_id ?? null;

        if($user_id == null){
            return 'required user id param';
        }

        # HERE WE ARE GETTING CURRENT USER PARENT ID
        $parent_id = BinaryTree::where("user_id",  $user_id)->value("id");

        # HERE WE ARE GETTING FIRST LEFT AND RIGHT CHILD ID
        $downline = BinaryTree::where("parent_id", $parent_id)->get(['id', 'team']);

        $left_child_id = 0;
        $right_child_id = 0;

        # IF THERE IS ONLY ONE CHILD
        if (count($downline) == 1) {
            $left_child_id = ($downline[0]['team'] == "left_team") ? $downline[0]['id'] : 0;
            $right_child_id = ($downline[0]['team'] == "right_team") ? $downline[0]['id'] : 0;
        }

        # IF THERE IS TWO CHILD
        if (count($downline) == 2) {
            $left_child_id = ($downline[0]['team'] == "left_team") ? $downline[0]['id'] : $downline[1]['id'];
            $right_child_id = ($downline[0]['team'] == "right_team") ? $downline[0]['id'] : $downline[1]['id'];
        }

        # HERE WE GETTING LEFT DOWNLINE USER IDS EXAMPLE - [2,5,6,7]
        $left_team = $this->getBinaryDecendant($left_child_id);

        # HERE WE GETTING RIGHT DOWNLINE USER IDS EXAMPLE - [3,6]
        $right_team = $this->getBinaryDecendant($right_child_id);

        # HERE WE GETTING LEFT DOWNLINE TOTAL STAKED PLAN AMOUNT LIKE 550$
        $left_total_buisness = $this->getTotalBusiness($left_team);

        # HERE WE GETTING RIGHT DOWNLINE TOTAL STAKED PLAN AMOUNT LIKE 100$
        $right_total_buisness = $this->getTotalBusiness($right_team);


        return [
            'user_id' => $user_id,
            'left_total_buisness' => $left_total_buisness,
            'right_total_buisness' => $right_total_buisness,
        ];


    }
}
