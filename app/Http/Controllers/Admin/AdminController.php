<?php

namespace App\Http\Controllers\Admin;

use App\Models\Deposit;
use App\Http\Controllers\Controller;
use App\Models\DepositCryptoLog;
use App\Models\General;
use App\Models\StakedPlan;
use App\Models\Kyc;
use App\Models\Wallet;
use App\Models\Plan;
use App\Models\Referral;
use App\Models\RoiIncome;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\UserWalletLog;
use App\Models\Withdrawal;
use App\Models\WithdrawLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    protected function totalEarn($investLog){
        if (count($investLog) > 0){
            $totalEarn = 0;
            foreach ($investLog as $value){
                $totalEarn +=((floatval($value->stake_currency_amount) * floatval($value->roi_percent))/100);
            }
            $total_earn = round($totalEarn,2);
        }else{
            $total_earn = 0;
        }
        return $total_earn;
    }

    public function dayList()
    {
        $totalDays = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
        $daysByMonth = [];
        for ($i = 1; $i <= $totalDays; $i++) {
            array_push($daysByMonth, ['Day ' . sprintf("%02d", $i) => 0]);
        }

        return collect($daysByMonth)->collapse();
    }

    public function adminIndex(){

        $data['userRecord'] = collect(User::selectRaw('count(CASE WHEN status = 1  THEN id END) AS activeUser')
            ->selectRaw('COUNT((CASE WHEN created_at >= CURDATE()  THEN id END)) AS todayJoin')
            ->get()->makeHidden(['name', 'mobile'])->toArray())->collapse();


        $data['total_user'] = User::count();
        $data['total_user_mon'] = User::whereMonth('created_at',Carbon::now()->month)->count();

        $data['total_plan'] = 3;

        $investLogMonth = StakedPlan::whereMonth('created_at', Carbon::now()->month)->get();
        $data['month_earn'] = $this->totalEarn($investLogMonth);

        $investLog = StakedPlan::get();
        $data['total_earn'] = $this->totalEarn($investLog);

        $data['total_ac_user'] = User::where('plan_active',1)->count();
        $data['total_ac_user_month'] = User::where('plan_active',1)->whereMonth('created_at',Carbon::now()->month)->count();

        $data['total_bn_user'] = User::where('status',0)->count();
        $data['total_bn_user_month'] = User::where('status',0)->whereMonth('created_at',Carbon::now()->month)->count();

        $data['total_deposit'] = DB::table('deposit_crypto_logs')->where('status',2)->sum('deposit_currency_amount');
        $data['today_deposit'] = DB::table('deposit_crypto_logs')->where('status',2)->where('created_at',Carbon::now())->where('status',2)->sum('deposit_currency_amount');
        $data['total_deposit_month'] = DB::table('deposit_crypto_logs')->where('status',2)->whereMonth('updated_at',Carbon::now()->month)->sum('deposit_currency_amount');

        $data['total_withdraw'] = Withdrawal::where('status','completed')->sum('amount');
        $data['today_withdraw'] = Withdrawal::where('created_at',Carbon::now())->where('status','pending')->sum('amount');
        $data['panding_withdraw'] = Withdrawal::where('status','pending')->sum('amount');
        $data['reject_withdraw'] = Withdrawal::where('status','rejected')->sum('amount');
        $data['total_withdraw_month'] = Withdrawal::where('status','completed')->whereMonth('updated_at',Carbon::now()->month)->sum('amount');

        $data['total_invest'] = StakedPlan::sum('stake_currency_amount');
        $data['running_invest'] = StakedPlan::where('status', 'opened')->sum('stake_currency_amount');
        $data['complete_invest'] = StakedPlan::where('status', 'expired')->sum('stake_currency_amount');
        $data['today_invest'] = StakedPlan::where('created_at',Carbon::now())->sum('stake_currency_amount');
        $data['total_invest_month'] = StakedPlan::whereMonth('created_at',Carbon::now()->month)->sum('stake_currency_amount');

        $data['roi_plans'] = RoiIncome::latest('created_at')->get();
        $data['fixed_plans'] = RoiIncome::latest('created_at')->get();

        $data['investLog'] = StakedPlan::latest('updated_at')->paginate();

        $data['latestUser'] = User::latest()->limit(5)->get();

        $page_title = "Dashboard";

        $trxReport['date'] = collect([]);

        $trxReport['date'] = $trxReport['date']->unique()->toArray();

        $report['months']    = collect([]);
        $report['deposit_month_amount']    = collect([]);
        $report['withdraw_month_amount']    = collect([]);


        $depositsMonth = DB::table('deposit_crypto_logs')->where('created_at', '>=', Carbon::now()->subYear())
            ->where('status', 2)
            ->selectRaw("SUM(CASE WHEN status = 2 THEN deposit_currency_amount END) as depositAmount")
            ->selectRaw("DATE_FORMAT(created_at,'%M-%Y') as months")
            ->orderBy('created_at')
            ->groupBy('months')
            ->get();


        $depositsMonth->map(function ($depositData) use ($report) {
            $report['months']->push($depositData->months);
            $report['deposit_month_amount']->push(getAmount($depositData->depositAmount));
        });

        $withdrawalMonth = Withdrawal::where('created_at', '>=', Carbon::now()->subYear())->where('status', 'completed')
            ->selectRaw("SUM( CASE WHEN status = 'completed' THEN amount END) as withdrawAmount")
            ->selectRaw("DATE_FORMAT(created_at,'%M-%Y') as months")
            ->orderBy('created_at')
            ->groupBy('months')->get();
        $withdrawalMonth->map(function ($withdrawData) use ($report) {
            if (!in_array($withdrawData->months, $report['months']->toArray())) {
                $report['months']->push($withdrawData->months);
            }
            $report['withdraw_month_amount']->push(getAmount($withdrawData->withdrawAmount));
        });

        $months = $report['months'];

        for ($i = 0; $i < $months->count(); ++$i) {
            $monthVal = Carbon::parse($months[$i]);
            if (isset($months[$i + 1])) {
                $monthValNext = Carbon::parse($months[$i + 1]);
                if ($monthValNext < $monthVal) {
                    $temp           = $months[$i];
                    $months[$i]     = Carbon::parse($months[$i + 1])->format('F-Y');
                    $months[$i + 1] = Carbon::parse($temp)->format('F-Y');
                } else {
                    $months[$i] = Carbon::parse($months[$i])->format('F-Y');
                }
            }
        }

        $investMonth = StakedPlan::where('status', 'opened')->where('created_at', '>=', Carbon::now()->subYear())
            ->selectRaw("SUM(stake_currency_amount) as amount")
            ->selectRaw("DATE_FORMAT(created_at,'%M-%Y') as months")
            ->orderBy('created_at')
            ->groupBy('months')
            ->get();
        $investMonth->map(function ($investData) use ($report) {
            $report['months']->push($investData->months);
        });

        $interestMonth = RoiIncome::where('created_at', '>=', Carbon::now()->subYear())
            ->selectRaw("SUM(roi_income) as amount")
            ->selectRaw("DATE_FORMAT(created_at,'%M-%Y') as months")
            ->orderBy('created_at')
            ->groupBy('months')
            ->get();

        $interestMonth->map(function ($interestData) use ($report) {
            $report['months']->push($interestData->months);
        });

        return view('admin.home',$data , compact('page_title', 'report', 'months', 'depositsMonth', 'withdrawalMonth', 'trxReport', 'investMonth', 'interestMonth'));
    }


    public function gnlSetting(){
        $page_title = "Settings";
        return view('admin.general.settings', compact('page_title'));
    }

    public function selTemplate(){
        $page_title = "Template Settings";
        return view('admin.general.template', compact('page_title'));
    }

    public function adminLogout(){
        Auth::guard('admin')->logout();
        return redirect('/admin')->with('success','Logout successfully');
    }

    public function usersIndex()
    {
        $page_title = "Users Management";
        $getUsers = new User();
        $user = $getUsers->select(["users.*","user_wallets.balance"])
                    ->leftJoin('user_wallets', 'users.id', '=', 'user_wallets.user_id')
                    ->orderBy('users.id','desc')
                    ->paginate(30);
        return view('admin.user.index', compact('user','page_title'));
    }


    public function transactionIndex()
    {
        $page_title = "Transaction Log";
        $trans = UserWalletLog::latest('id')->paginate(30);
        return view('admin.trans-log', compact('trans','page_title'));
    }

    public function depositLog() {
        $data['deposits'] = DepositCryptoLog::latest()->paginate(9);
        $data['page_title'] = 'Deposit Log';
        return view('admin.deposit.deposits', $data);
    }

    public function usersActiveIndex()
    {
        $page_title = "Active Users";
        $getUsers = new User();
        $user = $getUsers->select(["users.*","user_wallets.balance"])
                    ->where('plan_active', 1)
                    ->leftJoin('user_wallets', 'users.id', '=', 'user_wallets.user_id')
                    ->orderBy('users.id','desc')
                    ->paginate(30);
        return view('admin.user.index', compact('user','page_title'));
    }

    public function usersBanndedIndex()
    {
        $page_title = "Inactive Users";

        $getUsers = new User();
        $user = $getUsers->select(["users.*","balance"])
                    ->where('plan_active', 0)
                    ->leftJoin('user_wallets', 'users.id', '=', 'user_wallets.user_id')
                    ->orderBy('users.id','desc')
                    ->paginate(30);

        return view('admin.user.index', compact('user','page_title'));
    }

    public function userSearch(Request $request)
    {
        $page_title = 'User Group';
        $user = User::where('name', 'LIKE',"%{$request->username}%")->paginate();
        return view('admin.user.index', compact('user', 'page_title'));
    }

    public function userSearchEmail(Request $request)
    {
        $page_title = 'User Group';
        $user = User::where('email','LIKE',"%{$request->email}%")->paginate();
        return view('admin.user.index', compact('user','page_title'));
    }

    public function indexUserDetail($id)
    {
        $page_title = "Users Details";
        $user = User::findOrFail($id);
        $wallets = UserWallet::where('user_id' ,$id)->get();
        $user_wallet = UserWallet::where('user_id' ,$id)->first();

        return view('admin.user.view',compact('user','wallets','user_wallet','page_title'));
    }

    public function activeUserUpdate(Request $request, $id) {

        $wallets = Wallet::findOrFail($id);
        Wallet::whereId($wallets->id)->update([
            'status' => 1,
        ]);
        return redirect()->back()->with('success', 'Received Coin Reject Success');

    }

    public function userPasswordReset(Request $request, $id){
        $user = User::findOrFail($id);
        $user->password = '$2y$10$.w5cdqISB.N2UvpjoHo.4.6q4qtvF1qdbrO1aa9GfD5B2rmFT/VfC';
        $user->save();

        return redirect()->back()->with('success', 'User Password has been Updated');
    }

    public function rejectUserUpdate(Request $request, $id) {
        $wallets = Wallet::findOrFail($id);
        Wallet::whereId($wallets->id)->update([
            'status' => 0,
        ]);
        return redirect()->back()->with('success', 'Received Coin Reject Success');

    }

    public function userUpdate(Request $request ,$id)
    {
        $this->validate($request,[
            'name' => 'required',
        ]);
        $user = User::findOrFail($id);
        $user->update($request->all());
        return redirect()->back()->with('success','Successfully Updated');
    }

    public function indexUserBalance($id)
    {
        $page_title = "User Wallet Management";
        $user = User::findOrFail($id);
        $user_wallet = UserWallet::where('user_id', $id)->first();
        $deposit_wallet = (!$user_wallet) ? '0.000000' : $user_wallet->balance;
        $income_wallet  = (!$user_wallet) ? '0.000000' : $user_wallet->freeze_balance;
        $reward_wallet  = (!$user_wallet) ? '0.000000' : $user_wallet->reward_balance;

        return view('admin.user.balance',compact('user','user_wallet', 'deposit_wallet','income_wallet', 'reward_wallet','page_title'));
    }

    public function indexBalanceUpdate(Request $request ,$id)
    {
        $this->validate($request,[
            'amount' => 'required|numeric|min:0',
            'operation' => 'required',
        ]);
        $user = User::find($id);
        $wallet = UserWallet::where('user_id',$id)->first();
        if(!$wallet){
            // we create a wallet for the user
            $wallet = new UserWallet();
            $wallet->user_id = $id;
            $wallet->balance = '0.00000000';
            $wallet->freeze_balance = '0.00000000';
            $wallet->currency = 'USDT';
            $wallet->save();
        }
        $general = General::first();
        if ($user instanceof User){
            if ($request->operation == 1){

                if($request->wallet == 1){
                    $wallet->balance += $request->amount;
                    //createTransaction("Balance to Cash Wallet", $request->amount,$user->cash_wallet,$new_balance,1, $user->id);

                } elseif($request->wallet == 2) {
                    $wallet->freeze_balance += $request->amount;
                    //createTransaction("Balance to Cash Wallet", $request->amount,$user->incentives,$new_balance,1, $user->id);

                } else {
                    $wallet->reward_balance += $request->amount;
                    DB::table('reward_incomes')->insert(["user_id" => $id,
                                                        "reward_income" => $request->amount,
                                                        "date" => date("Y-m-d"),
                                                        "achieved_reward" => $request->message,
                                                        "created_at" => date("Y-m-d H:i:s"),
                                                        "updated_at" => date("Y-m-d H:i:s")]
                                                     );
                }

                $wallet->update();

                if (!is_null($request->message)){
                    $shortCodes = [
                        'amount' => $request->amount,
                        'currency' => $general->currency,
                        'post_balance' => $wallet->freeze_balance,
                        'post_message' => $request->message,
                    ];
                   // @send_email($user, "BAL_ADD", $shortCodes);
                }

                return back()->with('success','Balance Add Successful');

            } else {

                if($request->wallet == 1){
                    //DEDUCTING FROM CASH WALLET
                    if ($wallet->balance >= $request->amount){

                        $wallet->balance -= $request->amount;
                        //createTransaction("Balance deduct via admin", $request->amount,$user->cash_wallet,$new_balance,1, $user->id);

                    } else {
                        return back()->with('alert','Insufficient Balance in Cash Wallet.');
                    }

                } elseif($request->wallet == 2) {

                    if ($wallet->freeze_balance >= $request->amount){
                        $wallet->freeze_balance -= $request->amount;
                        //createTransaction("Balance deduct via admin", $request->amount,$user->incentives,$new_balance,1, $user->id);

                    } else {
                        return back()->with('alert','Insufficient Balance in Incentives Wallet.');
                    }
                } else {
                    if ($wallet->reward_balance >= $request->amount){
                        $wallet->reward_balance -= $request->amount;
                        //createTransaction("Balance deduct via admin", $request->amount,$user->incentives,$new_balance,1, $user->id);

                    } else {
                        return back()->with('alert','Insufficient Balance in Incentives Wallet.');
                    }
                }
                    $wallet->update();
                    if (!is_null($request->message)){
                        $shortCodes = [
                            'amount' => $request->amount,
                            'currency' => $general->currency,
                            'post_balance' => $wallet->freeze_balance,
                            'post_message' => $request->message,
                        ];
                        //@send_email($user, "BAL_SUB", $shortCodes);
                    }

                    return back()->with('success','Balance Subtract Successful');
            }
        }
        return back()->with('alert','User not found.');
    }

    public function userSendMail($id)
    {
        $page_title = "Send Email Users";
        $user = User::findOrFail($id);
        return view('admin.user.user_mail',compact('user','page_title'));
    }

    public function userSendMailUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $subject =$request->subject;
        $message = $request->message;
        @send_email($user->email, $user->name ,$subject, $message);
        return back()->with('success','Mail Send');
    }

    public function changePass() {
        $page_title = "Profile";
        return view('admin.pass_change', compact('page_title'));
    }


    public function updatePassword(Request $request) {
        $request->validate( [
            'name' => 'required',
            'email' => 'required|email',
            'current_password' => 'required',
            'password' => 'required|confirmed'
        ]);
        if(Hash::check($request->current_password, Auth::guard('admin')->user()->password)) {
            $user = auth()->guard('admin')->user();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();
            return back()->with('success','Profile update successful');
        }
        return back()->with('alert','Old password not matched');
    }

    public function searchResult(Request $request)
    {
        $trans = UserWalletLog::query();
        if (!is_null($request->trans_id)){
            $trans->where("trans_id","LIKE","%{$request->trans_id}%");
        }
        if (!is_null($request->user)){
            $u = $request->user;
           // $trans->whereHas('user',function ($q) use ($u){
           //     $q->where('name',"LIKE","%{$u}%");
          //  });
          $trans->where("user_id", $request->user);
        }

        if (!is_null($request->type)){
            switch ($request->type){
                case "Invest":
                    $trans->where('status', 0);
                    break;
                case "Deposit":
                    $trans->where('status', 1);
                    break;
                case "Transfer":
                    $trans->where('status', 2);
                    break;
                case "Income":
                    $trans->where('status', 4);
                    break;
                case "Withdraw":
                    $trans->where('status', 3);
                    break;
                case "Referral":
                    $trans->where('status', 5);
                    break;
                default:
                    $trans->whereIn('status', [0,1,2,3,4,5]);
            }
        }

        $trans = $trans->latest('id')->paginate(50);
        $page_title = "Transaction Log";
        return view('admin.trans-log', compact('trans','page_title'));
    }

    public function referralIndex() {
        $page_title = "Manage Referral";
        $ref = Referral::all();
        $lastRef = Referral::orderBy('id','desc')->first();

         return view('admin.referral.index',compact('ref','lastRef','page_title'));
    }

    public function referralStore(Request $request){
        if(count($request->percentage) == 0){
            return back()->with('alert','Percentage field is required');
        }
        try{
            foreach($request->percentage as $data){
                if(!is_null($data)){
                    if(!is_numeric($data)) return back()->with('alert','Please insert numeric value.');
                }

            }
            Referral::truncate();
        foreach($request->percentage as $data){
            Referral::create([
                'percentage' => $data
            ]);
        }
        return back()->with('success','Referral Percentage Commission generated.');
        }catch(\Exception $e){
            return back()->with('success','Referral Percentage Commission generated.');
        }
    }
}

?>
