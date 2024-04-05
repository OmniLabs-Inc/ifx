<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\{UserMeta, User, Setting, BinaryTree, GenerationTree, MatchingIncome, LevelIncome, RoiIncome, StakedPlan, DirectIncome, RewardIncome};
use Illuminate\Support\Facades\Auth;
use App\Traits\{Common, Reply};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Income\LevelIncomeController;

class DashboardController extends Controller
{
    use Reply, Common;

    # This function is used for getting decendent of binary tree id
    public function getBinaryDecendant($tree_id = 0)
    {
        if ($tree_id == 0) {
            return [];
        }

        return BinaryTree::whereDescendantOrSelf($tree_id)->pluck('user_id') ?? [];
    }

    public function getBinaryMembers($user_id)
    {
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
        $left_team = $this->getBinaryDecendant($left_child_id) ?? [];

        # HERE WE GETTING RIGHT DOWNLINE USER IDS EXAMPLE - [3,6]
        $right_team = $this->getBinaryDecendant($right_child_id) ?? [];

        ###############################

        $left_business = $this->total__buisness($left_team);

        $right_business = $this->total__buisness($right_team);

        $total_business = $this->lbm_add($left_business, $right_business);


        $left_today_business = $this->getTodayBusiness($left_team);

        $right_today_business = $this->getTodayBusiness($right_team);




        $data = [];

        $bb = env("BACKEND_URL") . "front/dash/buisness-1.png";

        $data["business"] = [
            'Today Business' => [
                'value' => round(($left_today_business + $right_business), 0),
                'img' => $bb
            ],

            'Total Business' => [
                'value' => round($total_business, 0),
                'img' => $bb
            ]
        ];

        $data["today_business"] = [
            'Today Left Business' => [
                'value' => round($left_today_business, 0),
                'img' => $bb
            ],
            'Today Right Business' => [
                'value' => round($right_today_business, 0),
                'img' => $bb
            ]
        ];

        $data["members"] =  $this->fetchMembersBinary($left_team, $right_team);

        return $data;
    }

     # This function is used for getting total staked previous week usdt amount
     public function getTodayBusiness($user_ids)
     {
         $total_business = StakedPlan::whereIn('user_id', $user_ids)->whereRaw('DATE(created_at) = ?', [date('Y-m-d')])->where('status','opened')->sum('usdt_amount') ?? 0;

         return $total_business;
     }

    public function total__buisness($team)
    {
        return StakedPlan::whereIn('user_id', $team)->sum('usdt_amount') ?? 0;
    }

    public function fetchMembersBinary($left_team, $right_team)
    {

        $left_team_members = count($left_team);

        $right_team_members = count($right_team);

        $left_total_active = User::whereIn('id', $left_team)->where('plan_active', 1)->count();

        $left_total_inactive = $this->lbm_sub($left_team_members, $left_total_active);

        $right_total_active = User::whereIn('id', $right_team)->where('plan_active', 1)->count();

        $right_total_inactive = $this->lbm_sub($right_team_members, $right_total_active);

        $binary_total_members = $this->lbm_add($left_team_members, $right_team_members);

        $active = env("BACKEND_URL") . "front/dash/active.png";
        $inactive = env("BACKEND_URL") . "front/dash/inactive.png";

        return [
            'Total Members' => [
                'value' => round($binary_total_members, 0),
                'img' => $active
            ],
            'Total Active' => [
                'value' => round(($left_total_active + $right_total_active), 0),
                'img' => $active
            ],
            'Total Inactive' => [
                'value' => round(($left_total_inactive + $right_total_inactive), 0),
                'img' => $inactive
            ],

        ];
    }

    public function fetchMembersGeneration($active_m, $inactive_m, $total_m)
    {

        $active = env("BACKEND_URL") . "front/dash/active.png";
        $inactive = env("BACKEND_URL") . "front/dash/inactive.png";

        return [
            'Total Members' => [
                'value' => round($total_m, 0),
                'img' => $active
            ],
            'Total Active' => [
                'value' => round($active_m, 0),
                'img' => $active
            ],
            'Total Inactive' => [
                'value' => round($inactive_m, 0),
                'img' => $inactive
            ],

        ];
    }


    public function getGenerationMembers($user_id)
    {

        $total_members    = 0;
        $active_members   = 0;
        $inactive_members = 0;

        # HERE WE ARE GETTING FIRST LEFT AND RIGHT CHILD ID
        $tree_id = GenerationTree::where("user_id", $user_id)->value('id');
		$downline = GenerationTree::where("parent_id", $tree_id)->get();

        # IF THERE IS ONLY ONE CHILD
        if (count($downline) > 0) {
            $total_members = count($downline);

            foreach($downline as $member){

                $checkUser = User::find($member->user_id);
                if($checkUser->plan_active == 1){
                    $active_members += 1;
                } else {
                    $inactive_members += 1;
                }

                $gen = $this->getGenerationMembers($member->user_id);
                if($gen){
                    $total_members = $total_members + $gen['generation']['total'];
                    $active_members = $active_members + $gen['generation']['active'];
                    $inactive_members = $inactive_members + $gen['generation']['inactive'];
                }
            }
        }


        $data = [];

        $bb = env("BACKEND_URL") . "front/dash/buisness-1.png";

        $data['generation'] = [
            'active'   => $active_members,
            'inactive' => $inactive_members,
            'total'    => $total_members,
        ];

        $data["business"] = [
            'Today Business' => [
                'value' => 0,
                'img' => $bb
            ],

            'Total Business' => [
                'value' => 0,
                'img' => $bb
            ]
        ];

        $data["today_business"] = [
            'Today Left Business' => [
                'value' => 0,
                'img' => $bb
            ],
            'Today Right Business' => [
                'value' => 1,
                'img' => $bb
            ]
        ];

        $data["members"] =  $this->fetchMembersGeneration($active_members, $inactive_members, $total_members);

        return $data;
    }



    public function getSingle(Request $request)
    {
        try {
            $validator  = Validator::make(
                ["id" => $request->id],
                [
                    'id'  => 'required|exists:users,id'
                ]
            );

            if ($validator->errors()->all()) {
                throw new Exception($validator->errors()->first());
            }

            $images = [
                "capping" => env("BACKEND_URL") . "front/dash/capping.png",
                "remain_capping" => env("BACKEND_URL") . "front/dash/remain_capping.png",
                "plan" => env("BACKEND_URL") . "front/dash/plan.png",
                "plan_active" => env("BACKEND_URL") . "front/dash/plan_active.png",
                "plan_expired" => env("BACKEND_URL") . "front/dash/plan_expired.png",
                "roi_income" => env("BACKEND_URL") . "front/dash/income1.png",
                "direct_income" => env("BACKEND_URL") . "front/dash/income2.png",
                "matching_income" => env("BACKEND_URL") . "front/dash/income3.png",
                "reward_income" => env("BACKEND_URL") . "front/dash/income4.png",
                "USDT" => env("BACKEND_URL") . "front/dash/usd.png",
                "AFC" => env("BACKEND_URL") . "front/dash/afc.png",
                "SIDE" => env("BACKEND_URL") . "front/dash/active.png",
            ];


            $dashboard =  UserMeta::with(["user:id,name,lrl,rrl,side,created_at", "user.user_wallet:id,user_id,currency,balance"])->where("user_id", $request->id)->first();

            $general = [
                "Total Capping" => ["value" => $dashboard->total_capping, "img" => $images["capping"]],
                "Remain Capping" => ["value" => $dashboard->remain_capping, "img" => $images["remain_capping"]],
                "Total Plans" => ["value" => $dashboard->total_plans, "img" => $images["plan"]],
                "Total Active Plans" => ["value" => $dashboard->total_plans_active, "img" => $images["plan_active"]],
                "Total Expired Plans" => ["value" => $dashboard->total_plans_expired, "img" => $images["plan_expired"]],
                "Joined Side" =>  ["value" => ($dashboard->user->side != "") ? $dashboard->user->side : "root", "img" => $images["SIDE"]],
                "Joined Date" =>  ["value" => $dashboard->user->created_at->toDateString(), "img" => $images["SIDE"]],
                "5 Direct" =>  ["value" => ($dashboard->is_5_direct == 1) ? 'Achieved' : 'Not Achieved', "img" => $images["SIDE"]],
                "10 Direct" =>  ["value" => ($dashboard->is_10_direct == 1) ? 'Achieved' : 'Not Achieved', "img" => $images["SIDE"]],
                "Bonus Roi DeadLine" =>  ["value" => $dashboard->direct_expiry_at, "img" => $images["SIDE"]],
                "Eligible 4X" =>  ["value" => ($dashboard->eligible_4x == 1) ? 'Eligible' : 'Not Eligible', "img" => $images["SIDE"]],
            ];



            $income = [
                "Total Roi Income" => ["value" => $dashboard->total_roi_income, "img" => $images["roi_income"]],
                "Total Direct Income" => ["value" => $dashboard->total_direct_income, "img" => $images["direct_income"]],
                "Total Level Income" => ["value" => $dashboard->total_matching_income, "img" => $images["matching_income"]],
                "Total Reward Income" => ["value" => $dashboard->total_reward_income, "img" => $images["reward_income"]]
            ];

            $user_id = $request->id;

            $all_data = $this->getBinaryMembers($user_id);

            $return = [
                "general" => $general,
                "income" => $income,
                "user"  => [
                    "id" => $dashboard->user->id,
                    "name" => $dashboard->user->name,
                    "lrl" => env("FRONTEND_URL") . "register?sponser=" . $dashboard->user->lrl,
                    "rrl" => env("FRONTEND_URL") . "register?sponser=" . $dashboard->user->rrl,
                ],
                "user_wallet" => $dashboard->user->user_wallet,
                "walet_images" => [
                    "USDT" => $images["USDT"],
                    "AFC" => $images["AFC"]
                ],
                "prices" => $this->live_price(),
                "business" => $all_data['business'],
                "today_business" => $all_data['today_business'],
                "members" => $all_data['members']
            ];

            return $this->success("User data fetched Successfully!", $return);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }

    public function get()
    {

        $user_id = Auth::id();

        $images = [
            "capping" => env("BACKEND_URL") . "front/dash/capping.png",
            "remain_capping" => env("BACKEND_URL") . "front/dash/remain_capping.png",
            "plan" => env("BACKEND_URL") . "front/dash/plan.png",
            "plan_active" => env("BACKEND_URL") . "front/dash/plan_active.png",
            "plan_expired" => env("BACKEND_URL") . "front/dash/plan_expired.png",
            "roi_income" => env("BACKEND_URL") . "front/dash/income1.png",
            "direct_income" => env("BACKEND_URL") . "front/dash/income2.png",
            "matching_income" => env("BACKEND_URL") . "front/dash/income3.png",
            "reward_income" => env("BACKEND_URL") . "front/dash/income4.png",
            "USDT" => env("BACKEND_URL") . "front/dash/usd.png",
            "AFC" => env("BACKEND_URL") . "front/dash/afc.png",
            "SIDE" => env("BACKEND_URL") . "front/dash/active.png",
        ];


        $dashboard =  UserMeta::with(["user:id,name,lrl,rrl,side,created_at", "user.user_wallet:id,user_id,currency,balance,freeze_balance,reward_balance"])->where("user_id", Auth::id())->first();

		$total_staked_plan = StakedPlan::where("user_id",$user_id)->count();
        $total_staked_value= StakedPlan::where("user_id",$user_id)->sum("stake_currency_amount");
        $active_staked_plan = StakedPlan::where("user_id",$user_id)->first();

        $general = [
            //"Total Capping" => ["value" => $dashboard->total_capping, "img" => $images["capping"]],
            //"Remain Capping" => ["value" => $dashboard->remain_capping, "img" => $images["remain_capping"]],
            //"Total Plans" => ["value" => $dashboard->total_plans, "img" => $images["plan"]],
            "Total Active Plans" => ["value" => $total_staked_plan, "img" => $images["plan_active"]],
            "Total Expired Plans" => ["value" => $dashboard->total_plans_expired, "img" => $images["plan_expired"]],
           // "Joined Side" =>  ["value" => ($dashboard->user->side != "") ? $dashboard->user->side : "root", "img" => $images["SIDE"]],
            "Joined Date" =>  ["value" => $dashboard->user->created_at->toDateString(), "img" => $images["SIDE"]],
            //"5 Direct" =>  ["value" => ($dashboard->is_5_direct == 1) ? 'Achieved' : 'Not Achieved', "img" => $images["SIDE"]],
            "10 Direct" =>  ["value" => ($dashboard->is_10_direct == 1) ? 'Achieved' : 'Not Achieved', "img" => $images["SIDE"]],
            //"Bonus Roi DeadLine" =>  ["value" => $dashboard->direct_expiry_at, "img" => $images["SIDE"]],
            //"Eligible 4X" =>  ["value" => ($dashboard->eligible_4x == 1) ? 'Eligible' : 'Not Eligible', "img" => $images["SIDE"]],
        ];


        $c_date = date('Y-m-d');

        $where_condition_incomes = ['date' => $c_date, "user_id" => $user_id];

        $match_income = MatchingIncome::where($where_condition_incomes)->sum('matching_income') ?? 0;
		$level_income = LevelIncome::where($where_condition_incomes)->sum('level_income') ?? 0;
        $total_level_income = LevelIncome::where("user_id", $user_id)->sum('level_income') ?? 0;
        $roi_income = RoiIncome::where($where_condition_incomes)->sum('roi_income') ?? 0;
        $direct_income = DirectIncome::where($where_condition_incomes)->sum('direct_income') ?? 0;
        $reward_income = RewardIncome::where($where_condition_incomes)->sum('reward_income') ?? 0;


        $today_income = $this->lbm_add( $this->lbm_add($match_income, $roi_income) , $this->lbm_add($direct_income, $reward_income));

        $total_income = $this->lbm_add( $this->lbm_add($dashboard->total_roi_income, $dashboard->total_direct_income) , $this->lbm_add($dashboard->total_matching_income, $dashboard->total_reward_income));

        $income = [
            "Total Roi Income" => ["value" => $dashboard->total_roi_income, "img" => $images["roi_income"]],
            "Total Direct Income" => ["value" => $dashboard->total_direct_income, "img" => $images["direct_income"]],
            "Total Level Income" => ["value" => $total_level_income, "img" => $images["matching_income"]],
            "Total Reward Income" => ["value" => $dashboard->total_reward_income, "img" => $images["reward_income"]],
            "Today Income" => ["value" => $today_income, "img" => $images["reward_income"]],
            "Total Income" => ["value" => $total_income, "img" => $images["reward_income"]]
        ];



        $all_data = $this->getBinaryMembers($user_id);
        $call_data = $this->getGenerationMembers($user_id);

        $fetchIncomes = new LevelIncomeController;
        $fetch1 = $fetchIncomes->countLevelsTeam($user_id,1);
		$fetch2 = $fetchIncomes->countLevelsTeam($user_id,2);
		$fetch3 = $fetchIncomes->countLevelsTeam($user_id,3);
		$fetch4 = $fetchIncomes->countLevelsTeam($user_id,4);
		$fetch5 = $fetchIncomes->countLevelsTeam($user_id,5);
		$fetch6 = $fetchIncomes->countLevelsTeam($user_id,6);
		$fetch7 = $fetchIncomes->countLevelsTeam($user_id,7);

		$levelsInfo = [

            "L1" => ["team" => $fetch1['total'], "business" => $fetchIncomes->checkLevelsInfo($user_id,1), "active" => $fetch1['active'], "inactive" => $fetch1['inactive']],
            "L2" => ["team" => $fetch2['total'], "business" => $fetchIncomes->checkLevelsInfo($user_id,2), "active" => $fetch2['active'], "inactive" => $fetch2['inactive']],
            "L3" => ["team" => $fetch3['total'], "business" => $fetchIncomes->checkLevelsInfo($user_id,3), "active" => $fetch3['active'], "inactive" => $fetch3['inactive']],
            "L4" => ["team" => $fetch4['total'], "business" => $fetchIncomes->checkLevelsInfo($user_id,4), "active" => $fetch4['active'], "inactive" => $fetch4['inactive']],
            "L5" => ["team" => $fetch5['total'], "business" => $fetchIncomes->checkLevelsInfo($user_id,5), "active" => $fetch5['active'], "inactive" => $fetch5['inactive']],
            "L6" => ["team" => $fetch6['total'], "business" => $fetchIncomes->checkLevelsInfo($user_id,6), "active" => $fetch6['active'], "inactive" => $fetch6['inactive']],
            "L7" => ["team" => $fetch7['total'], "business" => $fetchIncomes->checkLevelsInfo($user_id,7), "active" => $fetch7['active'], "inactive" => $fetch7['inactive']],
        ];

        // RoiIncome::

        $return = [
            "general" => $general,
            "income" => $income,
            "user"  => [
                "id" => $dashboard->user->id,
                "name" => $dashboard->user->name,
				"balance" => $dashboard->user->balance,
                "lrl" => env("FRONTEND_URL") . "register?sponser=" . $dashboard->user->lrl,
                "rrl" => env("FRONTEND_URL") . "register?sponser=" . $dashboard->user->rrl,
            ],
            "user_wallet" => $dashboard->user->user_wallet,
            "walet_images" => [
                "USDT" => $images["USDT"],
                "AFC" => $images["AFC"]
            ],
            "prices" => $this->live_price(),
            "business" => $call_data['business'],
            "today_business" => $call_data['today_business'],
            "members" => $call_data['members'],
			"active_plan" => $total_staked_plan,
			"staked_plan" => $active_staked_plan,
			"levels_info" => $levelsInfo,
            "staked_value"=> $total_staked_value,
        ];

        return $this->success("Dashboard data fetched Successfully!", $return);
    }

    public function getSetting()
    {
        try {
            $setting = Setting::select(['id', 'alpha_price', 'withdraw_fee'])->first();
            return $this->success("Withdraw settings fetched successfully.", $setting);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }

    public function reward_available(){

        //
        $user_id = Auth::id();

        $user = User::where('plan_activate_at', '!=' , null)->where('id',$user_id)->first();

        if(!$user){
            return $this->failed("user not active");
        }

        $now = time(); // or your date as well
        $your_date = strtotime($user->plan_activate_at);
        $datediff = $now - $your_date;

        $c_date = round($datediff / (60 * 60 * 24));

        if($c_date > 45){
            return $this->failed("one time reward expired");
        }

        if($c_date <= 15){
            $check_reward = $this->findReward($user_id, 1500);

            $ddd = $this->lbm_sub(15,$c_date) + 1;

            if(!$check_reward){
                return $this->success("one time $50 reward expired soon!! Meet 1500$ business on both legs. Expiry this reward after $ddd days");
            }
        }

        if($c_date <= 30){
            $check_reward = $this->findReward($user_id, 3500);

            $ddd = $this->lbm_sub(30,$c_date) + 1;

            if(!$check_reward){
                return $this->success("one time $100 reward expired soon!! Meet 3500$ business on both legs. Expiry this reward after $ddd days");
            }
        }

        if($c_date <= 45){
            $check_reward = $this->findReward($user_id, 7000);

            $ddd = $this->lbm_sub(45,$c_date) + 1;

            if(!$check_reward){
                return $this->success("one time $200 reward expired soon!! Meet 7000$ business on both legs. Expiry this reward after $ddd days");
            }

        }


        return $this->failed("one time reward expired");
    }

    function findReward($user_id, $acheivement){
       return RewardIncome::where(['user_id' => $user_id, 'achieved_reward' => $acheivement])->exists() ? true : false;
    }



}
