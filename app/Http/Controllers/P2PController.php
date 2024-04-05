<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\{ExchangeCode, UserWallet};
use App\Traits\{Calculation, Common, Reply};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;


class P2PController extends Controller
{
    use Calculation, Common, Reply;

    public function exCode()
    {
        $code = Str::random(64);
        return ExchangeCode::where('code', $code)->exists() ? $this->exCode() : $code;
    }

    public function create(Request $request)
    {

        try {

            $validator  = Validator::make(
                $request->all(),
                [
                    'amount' => 'required|regex:/^\d+(\.\d{1,8})?$/|gt:0',
                    'currency' => 'required|in:AFC'
                ]
            );

            if ($validator->errors()->all()) {
                throw new Exception($validator->errors()->first());
            }

            $user_id = Auth::id();

            $check_user_wallet = UserWallet::where(["user_id" => $user_id, "currency" => $request->currency])->first();

            if (!$check_user_wallet) {
                return $this->failed("You dont have wallet with currency $request->currency");
            }

            if ($request->amount > $check_user_wallet->balance) {
                return $this->failed("Insufficient balance");
            }

            $code = $this->exCode();

            $expiresAt = Carbon::now()->addMinutes(15);

            $balance = $this->lbm_sub($check_user_wallet->balance, $request->amount);

            $freeze_balance =  $this->lbm_add($check_user_wallet->freeze_balance, $request->amount);

            UserWallet::where(['user_id' => $user_id, 'currency' => $request->currency])->update([
                'balance' => $balance,
                'freeze_balance' =>   $freeze_balance
            ]);

            ExchangeCode::create([
                "amount" => $request->amount,
                "currency" => $request->currency,
                "user_id" => $user_id,
                "code" => $code,
                "expired_at" => $expiresAt
            ]);

            return $this->success("Code generated successfully", ["code" => $code]);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }


    public function redeem(Request $request)
    {
        try {
            $validator  = Validator::make(
                $request->all(),
                [
                    'code' => 'required|exists:exchange_codes,code,is_redeemed,0' # not used code
                ]
            );

            if ($validator->errors()->all()) {
                throw new Exception($validator->errors()->first());
            }

            $user_id = Auth::id();

            $check_code = ExchangeCode::where(["code" => $request->code, 'is_redeemed' => 0])->first();

            if (!$check_code) {
                return $this->failed("Invalid Code");
            }

            if ($check_code->is_redeemed == 1) {
                return $this->failed("This code is already redeemed.");
            }

            # from sender wallet
            $transfer_wallet =  UserWallet::where(["user_id" => $check_code->user_id, 'currency' => $check_code->currency])->first();

            if (!$transfer_wallet) {
                return $this->failed("Invalid Code");
            }

            $current_time = Carbon::now();
            $expiry_at = Carbon::parse($check_code->expired_at);

            if ($current_time->greaterThan($expiry_at)) {

                $transfer_wallet->freeze_balance =  $this->lbm_sub($transfer_wallet->freeze_balance, $check_code->amount);
                $transfer_wallet->balance =  $this->lbm_add($transfer_wallet->balance, $check_code->amount);
                $transfer_wallet->save();

                $check_code->is_redeemed = 2;  # Expired code
                $check_code->save();


                return $this->failed("This code is expired.");
            }

            if ($check_code->user_id == $user_id) {
                return $this->failed("Invalid Data!!.");
            }

            $unfreezed_balance =  $this->lbm_sub($transfer_wallet->freeze_balance, $check_code->amount);
            $transfer_wallet->freeze_balance = $unfreezed_balance;
            $transfer_wallet->save();

            $comment = "RECIEVED $check_code->amount $check_code->currency from generated code $request->code";
            $this->addUserCrypto($user_id, $check_code->amount, $check_code->currency, $comment);

            $check_code->is_redeemed = 1; # redeem Code
            $check_code->redeemed_by = $user_id;
            $check_code->save();

            return $this->success("Fund Setteled!!");
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }
    
    public function get(Request $request)
    {
        try {
            $codes = new ExchangeCode();
            if ($request->amount) {
                $codes = $codes->where("amount", $request->amount);
            }
            if ($request->code) {
                $codes = $codes->where("code", $request->code);
            }
            if ($request->currency) {
                $codes = $codes->where("currency", $request->currency);
            }
            if ($request->expired_at) {
                $codes = $codes->where("expired_at", $request->expired_at);
            }
            if ($request->is_redeemed) {
                $codes = $codes->where("is_redeemed", $request->is_redeemed);
            }
            $codes = $codes->with(['user_created:id,user_unique_id']);
            $codes = $codes->with(['user_redeem:id,user_unique_id']);
            $redeem_where = [];
            if ($request->redeemed_by) {
                $redeem_where['user_unique_id'] = $request->redeemed_by;
            }
            // $codes = $codes->whereHas('user_redeem', function ($q) use ($redeem_where) {
            //     $q->where($redeem_where);
            // });
            $user_id = Auth::id();
            $codes =  $codes->where(["user_id" =>  $user_id])->select(['id', 'user_id', 'code', 'expired_at', 'amount', 'currency', 'is_redeemed', 'redeemed_by'])->orderBy('id', 'DESC')->paginate($request->per_page  ?? 10);

            return $this->success("Successfully fetched.", $codes);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }

    public function getReedeem(Request $request)
    {
        try {
            $codes = new ExchangeCode();
            if ($request->amount) {
                $codes = $codes->where("amount", $request->amount);
            }
            if ($request->code) {
                $codes = $codes->where("code", $request->code);
            }
            if ($request->currency) {
                $codes = $codes->where("currency", $request->currency);
            }
            if ($request->expired_at) {
                $codes = $codes->where("expired_at", $request->expired_at);
            }
            if ($request->is_redeemed) {
                $codes = $codes->where("is_redeemed", $request->is_redeemed);
            }
            $codes = $codes->with(['user_created:id,user_unique_id']);
            $codes = $codes->with(['user_redeem:id,user_unique_id']);
            $redeem_where = [];
            if ($request->redeemed_by) {
                $redeem_where['user_unique_id'] = $request->redeemed_by;
            }
            // $codes = $codes->whereHas('user_redeem', function ($q) use ($redeem_where) {
            //     $q->where($redeem_where);
            // });
            $user_id = Auth::id();
            $codes =  $codes->where(["redeemed_by" =>  $user_id])->select(['id', 'user_id', 'code', 'expired_at', 'amount', 'currency', 'is_redeemed', 'redeemed_by'])->orderBy('id', 'DESC')->paginate($request->per_page  ?? 10);

            return $this->success("Successfully fetched.", $codes);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }
}
