<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\{Reply, Common, Variables};
use App\Models\{TransferFund};
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Carbon;

class FundTransferController extends Controller
{

    use Reply, Variables, Common;

    public function transfer(Request $request){

        try {
            $validator  = Validator::make(
                $request->all(),
                [
                    'amount'  => 'required|regex:/^\d+(\.\d{1,8})?$/|gt:0',
                    'user_id'  => 'required|exists:users,id'
                ]
            );

            if ($validator->errors()->all()) {
                throw new Exception($validator->errors()->first());
            }

            # user wallet add amount with user wallet log
            $comment = "Virtual Fund $request->amount $this->coin_name transfer to user id $request->user_id";
            # send amont to user
            $this->addUserCrypto($request->user_id, $request->amount, $this->coin_name, $comment);


            // create a log for fund transfer
            TransferFund::create([
                "user_id" => $request->user_id,
                "amount" => $request->amount,
                "currency" => $this->coin_name,
                "transfer_date" => Carbon::now()->toDateString()
            ]);
           
            return $this->success("Fund Transfered Successfully!");

        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }



    public function get(Request $request){
        // Here we are getting transfer report 

        try {

            $codes = new TransferFund();

            if ($request->amount) {
                $codes = $codes->where("amount", $request->amount);
            }

            if ($request->date) {
                $codes = $codes->where("transfer_date", $request->date);
            }
            
            $codes = $codes->with(['user:id,user_unique_id,user_sponser_id']);

            $redeem_where = [];

            if ($request->unique_id) {
                $redeem_where['user_unique_id'] = $request->unique_id;
            }

            if(count($redeem_where) > 0){
                $codes = $codes->whereHas('user', function ($q) use ($redeem_where) {
                    $q->where($redeem_where);
                });
            }

            $codes =  $codes->select(['id', 'user_id', 'amount', 'currency', 'transfer_date'])->orderBy('id', 'DESC')->paginate($request->per_page  ?? 10);

            return $this->success("Virtual Fund Transfer Fetched Successfully.", $codes);

        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }

    }
}
