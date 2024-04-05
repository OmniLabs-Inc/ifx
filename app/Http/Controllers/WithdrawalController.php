<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\UserWallet;
use App\Models\{Withdrawal, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Traits\{Common, Variables, Reply};
use Illuminate\Support\Facades\Http;


class WithdrawalController extends Controller
{
    use Common, Variables, Reply;

    /*
    public function initiate(Request $request)
    {

        try {
            $validator  = Validator::make(
                $request->all(),
                [
                    'amount'     => 'required|regex:/^\d+(\.\d{1,8})?$/|gt:0',
                    'to_address' => "required"
                ]
            );

            if ($validator->errors()->all()) {
                throw new Exception($validator->errors()->first());
            }

            $user_id = Auth::id();

            if(Auth::user()->plan_active != 1){
                throw new Exception('Invalid Data!!');
            }

            $wallet = UserWallet::where(["user_id" => $user_id, "currency" => $this->default_withdraw_currency])->first();
            if (!$wallet) {
                throw new Exception("You don't have USDT in your wallet.");
            }

            # Minimum withdraw amount is $10
            if ($request->amount < 10) {
                throw new Exception("The minimum withdraw amount is $10");
            }

            if ($wallet->balance < $request->amount) {
                throw new Exception("Insufficent Balance");
            }

            $setting = Setting::where("id", 1)->first(['alpha_price', 'withdraw_fee']);

            $fees = $this->calculate_percentage($setting['withdraw_fee'], $request->amount);

            $usdt_after_fees = $this->lbm_sub($request->amount, $fees);

            $alpha_qty = $this->lbm_div($usdt_after_fees, $setting['alpha_price']);


            $withdraw = Withdrawal::create([
                "amount" => $request->amount,
                "alpha_price" => $setting['alpha_price'],
                "withdraw_fees_percentage" => $setting['withdraw_fee'],
                "total_fees" => $fees,
                "usdt_after_fees" => $usdt_after_fees,
                "alpha_qty" => $alpha_qty,
                "user_id" => $user_id,
                "to_address" => $request->to_address
            ]);


            # deduct user wallet balance
            $wallet->balance = $this->lbm_sub($wallet->balance, $request->amount);
            $wallet->freeze_balance = $this->lbm_add($wallet->freeze_balance, $request->amount);
            $wallet->save();


            # here we are calling webhook
            $body['NODE_FUND_API_KEY'] = env('NODE_FUND_API_KEY');
            $body['withdraw_id'] = $withdraw->id;
            $body['user_id'] = $user_id;
            $body['currency'] = "AFC";
            $body['amount'] = $alpha_qty;
            $body['to_address'] = $request->to_address;
            $body['contract_address'] = $this->token_address['AFC']; // change with AFC
            $NODE_URL = env('NODE_BACKEND_URL');
            Http::timeout(1)->async()->post($NODE_URL . 'user/withdraw', $body)->wait();

            return $this->success("Withdrawl Initiated Successfully!!");
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    } */


    public function initiate(Request $request)
    {

        try {
            $validator  = Validator::make(
                $request->all(),
                [
                    'amount'     => 'required|regex:/^\d+(\.\d{1,8})?$/|gt:0',
                    'to_address' => "required"
                ]
            );

            if ($validator->errors()->all()) {
                throw new Exception($validator->errors()->first());
            }

            $user_id = Auth::id();

            if(Auth::user()->plan_active != 1){
                throw new Exception('Invalid Data!!');
            }

            $wallet = UserWallet::where(["user_id" => $user_id, "currency" => $this->default_withdraw_currency])->first();
            if (!$wallet) {
                throw new Exception("You don't have USDT in your wallet.");
            }

            # Minimum withdraw amount is $10
            if ($request->amount < 10) {
                throw new Exception("The minimum withdraw amount is $10");
            }

            if($request->wallet == 1){
                // The user is trying to withdraw from the Deposit/Main Wallet
                if ($wallet->balance < $request->amount) {
                    throw new Exception("Insufficent Balance in Main Wallet");
                }

                $wallet->balance = $wallet->balance - $request->amount;

            } elseif($request->wallet == 2) {

                if(date('d') != 1 && date("d") != 11 && date("d") != 21){
                    throw new Exception("Income Wallet can only be withdrawn on the 1st, 11th and 21st day of the month!");
                }

                if ($wallet->freeze_balance < $request->amount) {
                    throw new Exception("Insufficent Balance in Your Income Wallet");
                }

                $wallet->freeze_balance = $wallet->freeze_balance - $request->amount;

            } else {
                if ($wallet->reward_balance < $request->amount) {
                    throw new Exception("Insufficent Balance in Your Income Wallet");
                }

                $wallet->reward_balance = $wallet->reward_balance - $request->amount;
            }


            $withdraw = Withdrawal::create([
                "amount" => $request->amount,
                "alpha_price" => 1.000000,
                "withdraw_fees_percentage" => 0,
                "total_fees" => 0,
                "usdt_after_fees" => $request->amount,
                "alpha_qty" => 1.000000,
                "user_id" => $user_id,
                "to_address" => $request->to_address
            ]);

            $wallet->save();

            # here we are calling webhook
            //$body['NODE_FUND_API_KEY'] = env('NODE_FUND_API_KEY');
            //$body['withdraw_id'] = $withdraw->id;
            //$body['user_id'] = $user_id;
            //$body['currency'] = "AFC";
            //$body['amount'] = $alpha_qty;
            //$body['to_address'] = $request->to_address;
            //$body['contract_address'] = $this->token_address['AFC']; // change with AFC
            //$NODE_URL = env('NODE_BACKEND_URL');
            //Http::timeout(1)->async()->post($NODE_URL . 'user/withdraw', $body)->wait();

            return $this->success("Withdrawl Initiated Successfully!!");
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }



    public function get(Request $request)
    {
        try {

            $withdrawl = new Withdrawal();
            if ($request->amount) {
                $withdrawl = $withdrawl->where("amount", $request->amount);
            }
            if ($request->usdt_after_fees) {
                $withdrawl = $withdrawl->where("usdt_after_fees", $request->usdt_after_fees);
            }
            if ($request->withdraw_fees_percentage) {
                $withdrawl = $withdrawl->where("withdraw_fees_percentage", $request->withdraw_fees_percentage);
            }
            if ($request->alpha_qty) {
                $withdrawl = $withdrawl->where("alpha_qty", $request->alpha_qty);
            }
            if ($request->to_address) {
                $withdrawl = $withdrawl->where("to_address", $request->to_address);
            }

            if ($request->reason) {
                $withdrawl = $withdrawl->where("reason", $request->reason);
            }
            if ($request->status) {
                $withdrawl = $withdrawl->where("status", $request->status);
            }
            $withdrawl = $withdrawl->where(['user_id' => Auth::id()])->select(['id', 'user_id', 'amount', 'withdraw_fees_percentage', 'total_fees', 'usdt_after_fees', 'alpha_qty', 'to_address', 'reason', 'transaction_detail', 'status'])->orderBy('id', 'desc')->paginate($request->per_page  ?? 10);
            return $this->success("Withdrawl Fetched Successfully!!", $withdrawl);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }
}
