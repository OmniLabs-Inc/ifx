<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Traits\Reply;


class SettingController extends Controller
{
    use Reply;

    public function setAfcPrice(Request $request){

        try {
            $validator  = Validator::make(
                $request->all(),
                [
                    'price'    => 'required|regex:/^\d+(\.\d{1,8})?$/|gt:0'
                ]
            );

            if ($validator->errors()->all()) {
                throw new Exception($validator->errors()->first());
            }

            Setting::where("id",1)->update(["alpha_price" =>  $request->price]);

           return $this->success("Price Updated Successfully!");

        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }

    public function setWithdrawalFees(Request $request){

        try {
            $validator  = Validator::make(
                $request->all(),
                [
                    'fee'    => 'required|regex:/^\d+(\.\d{1,8})?$/|gt:0'
                ]
            );

            if ($validator->errors()->all()) {
                throw new Exception($validator->errors()->first());
            }

            Setting::where("id",1)->update(["withdraw_fee" =>  $request->fee]);  // in $

           return $this->success("Withdraw commission Updated Successfully!");

        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }

    }
}
