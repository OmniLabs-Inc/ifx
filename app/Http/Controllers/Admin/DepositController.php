<?php

namespace App\Http\Controllers\Admin;

use App\Models\General;
use App\Http\Controllers\Controller;
use App\Models\DepositRequest as DR;
use App\Models\Deposit;
use App\Models\PaymentGatway;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DepositController extends Controller
{
    public function pending() {
        $data['deposits'] = DR::where('accepted', 0)->latest()->paginate(9);
        $data['page_title'] = 'Pending Request';
        return view('admin.deposit.requests', $data);
    }

    public function acceptedRequests() {
        $data['deposits'] = DR::where('accepted', 1)->latest()->paginate(9);
        $data['page_title'] = 'Accepted Request';
        return view('admin.deposit.requests', $data);
    }

    public function rejectedRequests() {
        $data['deposits'] = DR::where('accepted', -1)->latest()->paginate(9);
        $data['page_title'] = 'Rejected Request';
        return view('admin.deposit.requests', $data);
    }

    public function showReceipt() {
        $dID = $_GET['dID'];
        $deposit = DR::find($dID);
        return $deposit;
    }

    public function accept(Request $request) {
        try{
            $gs = General::first();
            $gt= PaymentGatway::find($request->gid);
            $dr = DR::find($request->dID);

            $dr->accepted = 1;
            $dr->save();
            $user = User::find($dr->user_id);
            $newBalance = $user->balance + $dr->amount;
            createTransaction('Deposit via '.$gt->name, $dr->amount,$user->balance,$newBalance,1,$user->id);
            $user->balance = $newBalance;
            $user->save();

            $shortCodes = [
                'trx' => $dr->trx,
                'amount' => $dr->amount,
                'charge' => $dr->charge,
                'rate' => $dr->gateway->rate,
                'currency' => $gs->currency,
                'method_name' => $dr->gateway->name,
                'method_currency' => $gs->currency,
            ];
            @send_email($user, 'DEPOSIT_APPROVE' , $shortCodes);
            Session::flash('success', 'Request has been accepted successfully');
            return redirect()->back();
        }catch (\Exception $e){
            return back()->with('alert',$e->getMessage());
        }
    }

    public function depositLog() {
        $data['deposits'] = Deposit::latest()->paginate(9);
        $data['page_title'] = 'Deposit Log';
        return view('admin.deposit.deposits', $data);
    }

    public function rejectReq(Request $request) {
        try{
            $gs = General::first();
            $dr = DR::find($request->dID);
            $dr->accepted = -1;
            $dr->save();
            $user = User::find($dr->user_id);
            $shortCodes = [
                'trx' => $dr->trx,
                'amount' => $dr->amount,
                'charge' => $dr->charge,
                'rate' => $dr->gateway->rate,
                'currency' => $gs->currency,
                'method_name' => $dr->gateway->name,
                'method_currency' => $gs->currency,
            ];
            @send_email($user, 'DEPOSIT_REJECT' , $shortCodes);
            Session::flash('success', 'Request has been rejected');
            return redirect()->back();
        }catch (\Exception $e){
            return back()->with('alert',$e->getMessage());
        }

    }

}

?>