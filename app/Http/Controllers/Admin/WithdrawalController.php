<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Exception;
use App\Traits\{Common, Variables, Reply};


class WithdrawalController extends Controller
{
    use Common, Variables, Reply;


    public function get(Request $request)
    {
        try {
            $withdrawl = new Withdrawal();
            if ($request->user_id) {
                $withdrawl = $withdrawl->where("user_id", $request->user_id);
            }
            if ($request->amount) {
                $withdrawl = $withdrawl->where("amount", $request->amount);
            }
            if ($request->usdt_after_fees) {
                $withdrawl = $withdrawl->where("usdt_after_fees", $request->usdt_after_fees);
            }
            if ($request->total_fees) {
                $withdrawl = $withdrawl->where("total_fees", $request->total_fees);
            }
            if ($request->withdraw_fees_percentage) {
                $withdrawl = $withdrawl->where("withdraw_fees_percentage", $request->withdraw_fees_percentage);
            }
            if ($request->alfa_qty) {
                $withdrawl = $withdrawl->where("alpha_qty", $request->alfa_qty);
            }
            if ($request->to_address) {
                $withdrawl = $withdrawl->where("to_address", $request->to_address);
            }
            if ($request->status) {
                $withdrawl = $withdrawl->where("status", $request->status);
            }

            # User Table condition
            $user_where = [];

            if ($request->user_sponser_id) {
                $user_where["user_sponser_id"] = $request->user_sponser_id;
            }
            if ($request->unique_id) {
                $user_where["user_unique_id"] = $request->unique_id;
            }

            $withdrawl = $withdrawl->with(['user:id,user_unique_id,user_sponser_id']);

            $withdrawl = $withdrawl->whereHas('user', function ($q) use ($user_where) {
                $q->where($user_where);
            });

            if ($request->created_at) {
                $withdrawl = $withdrawl->whereRaw('DATE(created_at) = ?', [$request->created_at]);
            }

            $withdrawl = $withdrawl->select(['id', 'user_id', 'amount', 'withdraw_fees_percentage', 'total_fees', 'usdt_after_fees', 'alpha_qty', 'to_address', 'reason', 'transaction_detail', 'status', 'created_at'])->orderBy('id', 'desc')->paginate($request->per_page  ?? 10);
            return $this->success("All Withdrawl Fetched Successfully!!", $withdrawl);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }
}
