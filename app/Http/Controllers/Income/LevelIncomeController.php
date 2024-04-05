<?php

namespace App\Http\Controllers\Income;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User, StakedPlan, UserMeta, GenerationTree, LevelIncome, RewardIncome, WashOut, MatchingIncome, RoiIncome, Setting};
use Illuminate\Support\Facades\DB;
use App\Traits\{Reply, Common, Calculation, Variables};
use Illuminate\Support\Carbon;

class LevelIncomeController extends Controller
{
    use Common, Calculation, Variables;

    /**
     * @param mixed $user_ids
     *
     * @return [type]
     */
    public function getTotalBusiness($user_ids)
    {
        $total_business = StakedPlan::whereIn('user_id', $user_ids)->sum('usdt_amount') ?? 0;

        return $total_business;
    }

    public function getUserTotalBusiness($user_id){

        return StakedPlan::where('user_id', $user_id)->sum('usdt_amount') ?? 0;

    }

    /**
     * This method helps fetch all direct referrals of a the user
     * @param mixed $user_id
     *
     * @return [type]
     */
    public function getAllMyDirects($user_id){
        //first we get all my directs then we add up their investments
        $my_directs = DB::table('users')
                        ->where('direct_sponser_id', $user_id)
                        ->pluck('id')
                        ->toArray();

        return $my_directs;
    }


    /**
     * @param mixed $user_id
     * @param mixed $level
     *
     * @return [type]
     */
    public function getLevelDownline($user_id, $level){
        // we start with Level 2 downliners
        $my_directs = $this->getAllMyDirects($user_id);
        $level_downliners = [];

        if($level == 2){
            foreach($my_directs as $my_direct){
                $downliners = $this->getAllMyDirects($my_direct);
                $level_downliners = array_merge($level_downliners, $downliners);
            }
        }

        if($level == 3){
            // we will fetch both level 3 downliners
            $level2_downliners = $this->getLevelDownline($user_id,2);
            foreach($level2_downliners as $downliner){
                $downliners = $this->getAllMyDirects($downliner);
                $level_downliners = array_merge($level_downliners, $downliners);
            }

        }

        if($level == 4){
            // we will fetch both level 4 downliners
            $level3_downliners = $this->getLevelDownline($user_id,3);
            foreach($level3_downliners as $downliner){
                $downliners = $this->getAllMyDirects($downliner);
                $level_downliners = array_merge($level_downliners, $downliners);
            }

        }

        if($level == 5){
            // we will fetch both level 4 downliners
            $level4_downliners = $this->getLevelDownline($user_id,4);
            foreach($level4_downliners as $downliner){
                $downliners = $this->getAllMyDirects($downliner);
                $level_downliners = array_merge($level_downliners, $downliners);
            }

        }

        if($level == 6){
            // we will fetch both level 4 downliners
            $level5_downliners = $this->getLevelDownline($user_id,5);
            foreach($level5_downliners as $downliner){
                $downliners = $this->getAllMyDirects($downliner);
                $level_downliners = array_merge($level_downliners, $downliners);
            }

        }

        if($level == 7){
            // we will fetch both level 4 downliners
            $level6_downliners = $this->getLevelDownline($user_id,6);
            foreach($level6_downliners as $downliner){
                $downliners = $this->getAllMyDirects($downliner);
                $level_downliners = array_merge($level_downliners, $downliners);
            }

        }

        return $level_downliners;

    }

    public function checkLevelsInfo($user_id, $level){
        $user = User::find($user_id);
        $user_total_invest = $this->getUserTotalBusiness($user->id);
        $total_business = 0;

        if($level == 1){
            $my_directs = $this->getAllMyDirects($user->id);
            $total_business = $this->getTotalBusiness($my_directs);
        } else

        {
            $downline = $this->getLevelDownline($user_id, $level);
            $total_business = $this->getTotalBusiness($downline);
        }


        // Return false if the user does not qualify for the specified level
        return $total_business;
    }

    public function countLevelsTeam($user_id, $level){

        $team = 0;
        $count_active = 0;
        $count_inactive = 0;

        if($level == 1){
            $team = $this->getAllMyDirects($user_id);
        } elseif($level > 1) {
            $team = $this->getLevelDownline($user_id, $level);
        }

        foreach($team as $member){
            $get = User::find($member);
                if($get->plan_active == 1){
                    $count_active++;
                } else {
                    $count_inactive++;
                }

        }

        $result = ["active" => $count_active,
                    "inactive" => $count_inactive,
                    "total" => count($team)
                ];

        return $result;
    }


    /**
     * Check if the user qualifies for a specific income level based on given criteria.
     * @param int $level The income level to check for.
     *
     * @return bool Returns true if the user qualifies and the status is updated, otherwise false.
     */
    public function checkUserQualify($user_id, $level){
        $user = User::find($user_id);

        if($user->level_income_status < $level){
            $user_total_invest = $this->getUserTotalBusiness($user->id);
            $my_directs = $this->getAllMyDirects($user->id);
            $directs_total_business = $this->getTotalBusiness($my_directs);

            $level2_downline = $this->getLevelDownline($user_id, 2);
            $level2_total_business = $this->getTotalBusiness($level2_downline);

            $level3_downline = $this->getLevelDownline($user_id, 3);
            $level3_total_business = $this->getTotalBusiness($level3_downline);

            $level4_downline = $this->getLevelDownline($user_id, 4);
            $level4_total_business = $this->getTotalBusiness($level4_downline);

            $level5_downline = $this->getLevelDownline($user_id, 5);
            $level5_total_business = $this->getTotalBusiness($level5_downline);

            $level6_downline = $this->getLevelDownline($user_id, 6);
            $level6_total_business = $this->getTotalBusiness($level6_downline);

            $level7_downline = $this->getLevelDownline($user_id, 7);
            $level7_total_business = $this->getTotalBusiness($level7_downline);


            if($level == 1 && $user_total_invest >= 200 && $directs_total_business >= 5000){
                // the user qualifies for 1st LEVEL INCOME
                $user->level_income_status = 1;
            }
            if($level == 2 && $user_total_invest >= 500 && ($level2_total_business + $level3_total_business) >= 25000){
                // the user qualifies for 2nd and 3rd LEVEL INCOME
                $user->level_income_status = 2;
            }
            if($level == 3 && $user_total_invest >= 1000 && ($level4_total_business + $level5_total_business) >= 75000){
                // the user qualifies for 4th * 5th LEVEL INCOME
                $user->level_income_status = 3;
            }
            if($level == 4 && ($level6_total_business + $level7_total_business) >= 150000){
                // get direct business
                $user->level_income_status = 4;
            }

            $user->update();

            return true;
        }
        // Return false if the user does not qualify for the specified level
        return false;
    }


    public function addNewLevelIncome($user_id, $level = null) {
        $user = User::find($user_id);

        if ($level === null) {
            $level_income_status = $user->level_income_status;
        } else {
            $level_income_status = $level;
        }

        $user_level_income = 0;
        $generationLevel = 0;

        if ($level_income_status == 0) {
            // 1st level Income
            $percent = 15;
            $generationLevel = 1;
            $getLevelDownline = $this->getAllMyDirects($user_id);
        } elseif ($level_income_status == 4) {
            // Club Income
            return false;
        } else {
            // Handle other level incomes
            $percent = ($level_income_status == 1) ? 12 : (($level_income_status == 2) ? 8 : 5);
            $generationLevel = ($level_income_status == 1) ? 2 : (($level_income_status == 2) ? 4 : 6);

            $previous_level = $level_income_status - 1;
            $this->addLevelIncome($user_id, $previous_level);

            $getLevelDownline = $this->getLevelDownline($user_id, $generationLevel - 1);
        }

        foreach ($getLevelDownline as $downliner) {
            $sender = User::find($downliner);
            $getLevelRoiIncome = RoiIncome::where('user_id', $downliner)
                                            ->where('date', date('Y-m-d'))
                                            ->latest()->get();
            if ($getLevelRoiIncome) {
                foreach ($getLevelRoiIncome as $roi) {
                    $user_level_income = round(($roi->roi_income * $percent / 100), 6);

                    LevelIncome::create([
                        "user_id"      => $user_id,
                        "level_income" => $user_level_income,
                        "status"       => "PAID from Level " . $generationLevel,
                        "sender"       => $sender->name,
                        "date"         => date('Y-m-d'),
                    ]);
                }
            }

            $this->addUserCrypto($user_id, $user_level_income, "USDT", "");
        }

        return true;
    }

    public function addSpecificLevelIncome($user_id, $level){
        $user = User::find($user_id);

        $user_level_income = 0;
        $generationLevel = 0;

        if($level == 0){
            $percent = 15;
            $generationLevel = 1;
            $getLevelDownline = $this->getAllMyDirects($user_id);
        }
        if($level == 1){
            // 2nd and 3rd level Income
            $percent = 12;
            $generationLevel = 2;
            $getLevelDownline = $this->getLevelDownline($user_id, 2);
        }
        if($level == 2){
            // 4th and 5th level Income
            $percent = 8;
            $generationLevel = 4;
            $getLevelDownline = $this->getLevelDownline($user_id, 4);

        }
        if($level == 3){
            // 6th and 7th level Income
            $percent = 5;
            $generationLevel = 6;
            $getLevelDownline = $this->getLevelDownline($user_id, 6);
        }

        if($level == 4){
            // THIS IS CLUB INCOME LEVEL TO BE PROCESSED BY ADMIN
           return false;
        }

		 foreach($getLevelDownline as $downliner){
            $sender = User::find($downliner);
            $getLevelRoiIncome = RoiIncome::where('user_id',$downliner)
                                    ->where('date', date('Y-m-d'))
                                    ->latest()->get();
			//print_r($downliner . ' -> ' . $getLevelRoiIncome . "<br />");
            if($getLevelRoiIncome){
                foreach($getLevelRoiIncome as $roi){
					//print_r('ROI: '. $roi . '<br />');
					$user_level_income = round(($roi->roi_income * $percent / 100), 6);

                LevelIncome::create([
                    "user_id"      => $user_id,
                    "level_income" => $user_level_income,
                    "status"       => "PAID from Level ". $generationLevel,
                    "sender"       => $sender->name,
                    "date"         => date('Y-m-d'),
                ]);

                }
            }

			$this->addUserCrypto($user_id, $user_level_income, "USDT", "");
        }


// Business model to be replaced with new one


        if($level == 1){
            // 3rd level Income
            $percent = 8;
            $generationLevel = 3;
            $getLevelDownline = $this->getLevelDownline($user_id, 3);
        }

        if($level == 2){
            // 5th level Income
            $percent = 5;
            $generationLevel = 5;
            $getLevelDownline = $this->getLevelDownline($user_id, 5);
        }

        if($level == 3){
            // 7th level Income
            $percent = 5;
            $generationLevel = 7;
            $getLevelDownline = $this->getLevelDownline($user_id, 7);
        }

       // HERE WE PROCESS THE SECOND GENERATION LEVEL INCOME (LEVEL 2-3/4-5/6-7)
		 foreach($getLevelDownline as $downliner){
            $sender = User::find($downliner);
            $getLevelRoiIncome = RoiIncome::where('user_id',$downliner)
                                    ->where('date', date('Y-m-d'))
                                    ->latest()->get();

            if($getLevelRoiIncome){
				foreach($getLevelRoiIncome as $roi){
                    $user_level_income = round(($roi->roi_income * $percent / 100), 6);
                //$user_level_income = round(($getLevelRoiIncome->roi_income * $percent / 100), 6);

                LevelIncome::create([
                    "user_id"      => $user_id,
                    "level_income" => $user_level_income,
                    "status"       => "PAID from Level ". $generationLevel,
                    "sender"       => $sender->name,
                    "date"         => date('Y-m-d'),
                ]);

            	}

			}

			$this->addUserCrypto($user_id, $user_level_income, "USDT", "");
        }

        return true;
    }

	 public function addLevelIncome($user_id){
        $user = User::find($user_id);

        $level_income_status = $user->level_income_status;
        $user_level_income = 0;
        $generationLevel = 0;

        if($level_income_status == 0){
            $percent = 15;
            $generationLevel = 1;
            $getLevelDownline = $this->getAllMyDirects($user_id);
        }
        if($level_income_status == 1){
            // 2nd level Income
            $percent = 12;
            $generationLevel = 2;
            $this->addSpecificLevelIncome($user_id, 0);
            $getLevelDownline = $this->getLevelDownline($user_id, 2);

            // 3rd level Income
            $percent_ = 8;
            $generationLevel_ = 3;
            $getLevelDownline_ = $this->getLevelDownline($user_id, 3);
        }
        if($level_income_status == 2){
            // 4th level Income
            $percent = 8;
            $generationLevel = 4;
            $this->addSpecificLevelIncome($user_id, 0);
            $this->addSpecificLevelIncome($user_id, 1);
            $getLevelDownline = $this->getLevelDownline($user_id, 4);

            // 5th level Income
            $percent_ = 5;
            $generationLevel_ = 5;
            $getLevelDownline_ = $this->getLevelDownline($user_id, 5);

        }
        if($level_income_status == 3){
            // 6th and 7th level Income
            $percent = 5;
            $generationLevel = 6;
            $this->addSpecificLevelIncome($user_id, 0);
            $this->addSpecificLevelIncome($user_id, 1);
            $this->addSpecificLevelIncome($user_id, 2);
            $getLevelDownline = $this->getLevelDownline($user_id, 6);

            // 7th level Income
            $percent_ = 5;
            $generationLevel_ = 7;
            $getLevelDownline_ = $this->getLevelDownline($user_id, 7);
        }

        if($level_income_status == 4){
            // THIS IS CLUB INCOME LEVEL TO BE PROCESSED BY ADMIN
           return false;
        }

		 foreach($getLevelDownline as $downliner){
            $sender = User::find($downliner);
            $getLevelRoiIncome = RoiIncome::where('user_id',$downliner)
                                    ->where('date', date('Y-m-d'))
                                    ->latest()->get();
			//print_r($downliner . ' -> ' . $getLevelRoiIncome . "<br />");
            if($getLevelRoiIncome){
                foreach($getLevelRoiIncome as $roi){
					//print_r('ROI: '. $roi . '<br />');
					$user_level_income = round(($roi->roi_income * $percent / 100), 6);

                LevelIncome::create([
                    "user_id"      => $user_id,
                    "from_user_id" => $sender->user_unique_id,
                    "level_income" => $user_level_income,
                    "status"       => "PAID from Level ". $generationLevel,
                    "sender"       => $sender->name,
                    "date"         => date('Y-m-d'),
                ]);

                }
            }

			$this->addUserCrypto($user_id, $user_level_income, "USDT", "");
        }


       // HERE WE PROCESS THE SECOND GENERATION LEVEL INCOME (LEVEL 2-3/4-5/6-7)
		 foreach($getLevelDownline_ as $downliner){
            $sender = User::find($downliner);
            $getLevelRoiIncome = RoiIncome::where('user_id',$downliner)
                                    ->where('date', date('Y-m-d'))
                                    ->latest()->get();

            if($getLevelRoiIncome){
				foreach($getLevelRoiIncome as $roi){
                    $user_level_income = round(($roi->roi_income * $percent_ / 100), 6);

                    LevelIncome::create([
                        "user_id"      => $user_id,
                        "level_income" => $user_level_income,
                        "status"       => "PAID from Level ". $generationLevel_,
                        "sender"       => $sender->name,
                        "date"         => date('Y-m-d'),
                    ]);

            	}

			}

			$this->addUserCrypto($user_id, $user_level_income, "USDT", "");
        }

        return true;

    }

    public function removeDuplicateIncome(){

        $today = '2024-3-12';

        $getEntries = DB::table('level_incomes')->where('date', $today)->get();
        foreach($getEntries as $data){
            //echo $data->sender . '<br />';

            $getDuplicate = DB::table('level_incomes')
                            ->where('user_id',$data->user_id)
                            ->where('sender', $data->sender)
                            ->where('level_income', $data->level_income)
                            ->where('date', $today)
                            ->get();

            if(count($getDuplicate) > 1){
                if($getDuplicate[0]->from_user_id == ''){
                    DB::table('level_incomes')->where('id', $getDuplicate[0]->id)->delete();
                    echo 'sender '. $getDuplicate[0]->sender . '<br />';
                } else {
                    DB::table('level_incomes')->where('id', $getDuplicate[1]->id)->delete();
                    echo "sender2 {$getDuplicate[1]->sender} \n";
                }
            }
        }
    }

    /**
     * we process all level income through CRON
     * @return [type]
     */
    public function generateIncome(){
        $users = User::all();

        foreach($users as $user){
            $this->addLevelIncome($user->id);
        }

        $status = "OK";

        return $status;
    }


    /**
     * Method used by cron hourly to check if a user qualifies for
     * higher level income status
     *
     * @return bool Return true if the suer needs to be upgraded
     */
    public function updateUsersIncomeStatus(){
        $users = User::all();

        foreach($users as $user){
            $level = $user->level_income_status + 1;

            $this->checkUserQualify($user->id, $level);
        }

        $status = "OK";

        return $status;
    }



}
