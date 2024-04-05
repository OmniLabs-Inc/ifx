<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{DepositCryptoLog, ExchangeCode};
use App\Models\StakedPlan;
use Illuminate\Http\Request;
use App\Traits\Reply;
use Exception;

class ReportController extends Controller
{
    use Reply;

    public function deposit_report(Request $request)
    {
        try {
            $deposit = new DepositCryptoLog();

            if ($request->usdt_amount) {
                $deposit = $deposit->where("usdt_amount", $request->usdt_amount);
            }
            if ($request->transaction_id) {
                $deposit = $deposit->where("transaction_id", $request->transaction_id);
            }
            if ($request->hash) {
                $deposit = $deposit->where("hash", $request->hash);
            }
            if ($request->from_wallet_address) {
                $deposit = $deposit->where("from_wallet_address", $request->from_wallet_address);
            }
            if ($request->status) {
                $deposit = $deposit->where("status", $request->status);
            }

            # User Table condition
            $user_where = [];

            if ($request->user_sponser_id) {
                $user_where["user_sponser_id"] = $request->user_sponser_id;
            }
            if ($request->unique_id) {
                $user_where["user_unique_id"] = $request->unique_id;
            }

            $deposit = $deposit->with(['user:id,user_unique_id,user_sponser_id']);

            $deposit = $deposit->whereHas('user', function ($q) use ($user_where) {
                $q->where($user_where);
            });

            $deposit = $deposit->select(["transaction_id","user_id","status", "usdt_amount", "hash", "from_wallet_address", "created_at"])->paginate($request->per_page ?? 10);
            return $this->success("Deposit Reports Fetched Successfully.", $deposit);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }

    public function stake_report(Request $request)
    {
        try {
            $stake = new StakedPlan();

            if ($request->usdt_amount) {
                $stake =  $stake->where("usdt_amount", $request->usdt_amount);
            }
            if ($request->daily_roi_income) {
                $stake =  $stake->where("daily_roi_income", $request->daily_roi_income);
            }
            if ($request->plan_start_at) {
                $stake =  $stake->where("plan_start_at", $request->plan_start_at);
            }

            if ($request->status) {
                $stake =  $stake->where("status", $request->status);
            }

            # User Table condition
            $user_where = [];

            if ($request->user_sponser_id) {
                $user_where["user_sponser_id"] = $request->user_sponser_id;
            }
            if ($request->unique_id) {
                $user_where["user_unique_id"] = $request->unique_id;
            }

            $stake = $stake->with(['user:id,user_unique_id,user_sponser_id']);

            $stake = $stake->whereHas('user', function ($q) use ($user_where) {
                $q->where($user_where);
            });

            $stake = $stake->select([ "usdt_amount", "user_id", "daily_roi_income", "plan_start_at",  "status", "created_at"])->paginate($request->per_page ?? 10);
            return $this->success("Stake Reports Fetched Successfully.", $stake);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }

    public function p2p_report(Request $request)
    {
        try {
            //             amount
            // currency
            // code
            // expired_at
            // status
            $codes = new ExchangeCode();
            if ($request->amount) {
                $codes = $codes->where('amount', $request->amount);
            }
            if ($request->currency) {
                $codes = $codes->where('currency', $request->currency);
            }
            if ($request->code) {
                $codes = $codes->where('code', $request->code);
            }
            if ($request->expired_at) {
                $codes = $codes->where('expired_at', $request->expired_at);
            }
            if ($request->status) {
                $codes = $codes->where('is_redeemed', $request->status);
            }

            $codes = $codes->with(['user_created:id,user_unique_id AS created_by']);
            $codes = $codes->with(['user_redeem:id,user_unique_id AS redeemed_by']);

            $codes =  $codes->orderBy('id', 'DESC')->select(['user_id','code', 'redeemed_by', 'expired_at', 'amount', 'currency', 'is_redeemed'])->paginate($request->per_page  ?? 10);
            return $this->success("Successfully fetched.", $codes);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }
}
