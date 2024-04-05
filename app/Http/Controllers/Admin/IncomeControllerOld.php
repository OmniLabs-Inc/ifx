<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{RoiIncome, WashOut, MatchingIncome, LevelIncome, PrimmestRoyalityIncome, MasterRoyalityIncome, CommunityRoyalityIncome, TritonBlasterIncome, SpartanBlasterIncome, FederalBlasterIncome};
use App\Traits\{Reply};
use Exception;

class IncomeController extends Controller
{
    use Reply;

    # ROI INCOME FETCHING
    public function roiIncome(Request $request)
    {
        try {
            $roi = RoiIncome::with(['staked_plan:id,plan_id,status,usdt_amount,plan_start_at,plan_expiry_at', "staked_plan.plan:id,name,days"])->paginate($request->per_page  ?? 10);
            return $this->success("Roi Incomes fetch successfully", $roi);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }

    # WASHOUT INCOME FETCHING
    public function washoutIncome(Request $request)
    {
        try {
            $washout = WashOut::paginate($request->per_page  ?? 10);
            return $this->success("WashOut Incomes fetch successfully", $washout);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }

    # MATCHING INCOME FETCHING
    public function matchingIncome(Request $request)
    {
        try {
            $matching = MatchingIncome::paginate($request->per_page  ?? 10);
            return $this->success("Matching Incomes fetch successfully", $matching);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }

    # LEVEL INCOME FETCHING
    public function levelIncome(Request $request)
    {
        try {
            $level_income = LevelIncome::with(['staked_plan:id,plan_id,status,usdt_amount,plan_start_at,plan_expiry_at', "staked_plan.plan:id,name,days"])->select(["id", "user_id", "staked_plan_id", "plan_id", "level", "level_income", "created_at"])->paginate($request->per_page  ?? 10);
            return $this->success("Level Incomes fetch successfully", $level_income);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }

    # Primmest Royality Income FETCHING
    public function PrimmestRoyalityIncome(Request $request)
    {
        try {
            $PrimmestRoyalityIncome = PrimmestRoyalityIncome::select(['user_id','week_start_date','week_end_date','royality_income','royality_income_percentage','loyalty_income','loyalty_income_percentage','total_business','total_business_roi'])->paginate($request->per_page  ?? 10);
            return $this->success("Primmest Royality Income fetch successfully", $PrimmestRoyalityIncome);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }

    # Master Royality Income FETCHING
    public function MasterRoyalityIncome(Request $request)
    {
        try {
            $MasterRoyalityIncome = MasterRoyalityIncome::select(["user_id","week_start_date","week_end_date","primmest_royality_income","primmest_royality_income_percentage","primmest_loyalty_income","primmest_loyalty_income_percentage","primmest_total_business","primmest_total_business_roi","club_royality_income","club_royality_income_percentage","club_loyalty_income","club_loyalty_income_percentage","club_total_business","club_total_business_roi"])->paginate($request->per_page  ?? 10);
            return $this->success("Master Royality Income fetch successfully", $MasterRoyalityIncome);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }

    # Community Royality Income FETCHING
    public function CommunityRoyalityIncome(Request $request)
    {
        try {
            $CommunityRoyalityIncome = CommunityRoyalityIncome::select(["user_id","week_start_date","week_end_date","royality_income","royality_income_percentage","loyalty_income","loyalty_income_percentage","total_business","total_business_roi","community_income"])->paginate($request->per_page  ?? 10);
            return $this->success("Community Royality Income fetch successfully", $CommunityRoyalityIncome);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }

    # Tritons Income FETCHING
    public function TritonsIncome(Request $request)
    {
        try {
            $TritonsIncome = TritonBlasterIncome::select(['user_id','week_start_date','week_end_date','triton_income','triton_income_percentage','total_matching_business'])->paginate($request->per_page  ?? 10);
            return $this->success("Tritons Income fetch successfully", $TritonsIncome);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }

    # Spartan Income FETCHING
    public function SpartansIncome(Request $request)
    {
        try {
            $SpartansIncome = SpartanBlasterIncome::select(['user_id','week_start_date','week_end_date','spartan_income','spartan_income_percentage','total_matching_business'])->paginate($request->per_page  ?? 10);
            return $this->success("Spartan Income fetch successfully", $SpartansIncome);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }

    # Federal Income FETCHING
    public function FederalIncome(Request $request)
    {
        try {
            $FederalIncome = FederalBlasterIncome::select(['user_id','week_start_date','week_end_date','federal_income','federal_income_percentage','total_matching_business'])->paginate($request->per_page  ?? 10);
            return $this->success("Federal Income fetch successfully", $FederalIncome);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }
}
