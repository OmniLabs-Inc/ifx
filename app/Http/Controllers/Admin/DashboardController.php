<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{UserMeta,Plan, StakedPlan};
use Illuminate\Support\Facades\Auth;
use App\Traits\{Common,Reply};
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use Reply, Common;

    public function get()
    {

        $total =  UserMeta::select(
            DB::raw('sum(total_plans_active) as total_plans_active'),
            DB::raw('sum(total_plans_expired) as total_plans_expired'),
            DB::raw('sum(total_capping) as total_capping'),
            DB::raw('sum(remain_capping) as remain_capping')
            )->first();

        $staked =  StakedPlan::select(
            DB::raw('sum(usdt_amount) as total_usdt_amount'),
            DB::raw('sum(stake_currency_amount) as total_stake_afc'),
            )->first();

        $return = [
            "total" => $total,
            "staked" => $staked,
            "prices" => $this->live_price()
        ];

        return $this->success("Dashboard data fetched Successfully!", $return);
    }
}
