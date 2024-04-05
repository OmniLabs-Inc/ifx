<?php

namespace App\Http\Controllers;

use App\Mail\SendOtp;
use Exception;
use App\Models\User;
use App\Traits\Reply;
use App\Traits\{Common, CurlRequest};
use App\Traits\Variables;
use App\Traits\Security;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\{UserWalletLog, GenerationTree, BinaryTree, Otp};
use App\Traits\Calculation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

# Setup these tables when u start this project
// 1. user
// 2. usermeta
// 3. binary_tree
// 4. generation
// 5. setting

class UserController extends Controller
{

    use Reply, Variables, Common, Security, Calculation, CurlRequest;

    const LOGIN_DATA_FIELDS = ['id', 'name', 'user_unique_id', 'email', 'balance', 'lrl', 'rrl', 'direct_sponser_id', 'user_unique_address', 'status', 'is_email_verified','role','plan_active'];

    const DEFAULT_ROI_CURRENCY = "USDT";


    /*
        This function generate referal code and check
        in user table that not already exists!
    */
    public function referCode($side)
    {
        $code = 'IFX' . $this->getRandomNumber();
        return  User::where($side, $code)->exists() ? $this->ReferCode($side) : $code;
    }

    # This function generate user unique address
    public function userUniqueAddress()
    {
        $address = $this->coin_name . $this->generateRandomString(61);
        return  User::where('user_unique_address', $address)->exists() ? $this->userUniqueAddress() : $address;
    }

    /*
        This function need sponser user id and children user id
        for adding in the generation tree !
    */
    public function generateGenerationTree($parent_user_id, $children_user_id)
    {

        $parent_ = GenerationTree::where('user_id', $parent_user_id)->first();

        GenerationTree::create([
            "user_id" => $children_user_id
        ], $parent_);
    }


    # This function is for testing purpose for getting binary tree!!
    public function test(Request $request)
    {
        $tt = [];
        $uu =  User::get(['lrl', 'rrl']);

        foreach ($uu as $val) {
            $tt[] = $val->lrl;
            $tt[] = $val->rrl;
        }

        return $tt;
        // GenerationTree::fixTree();
        // BinaryTree::fixTree();

        return ["STATUS" => "SUCCESS", "MESSAGE" => "ROI INCOME GENERATED!!"];
    }

    # This function used for getting empty space in binary table || tree !
    public function get_binary_parent_node($user_id, $team_side, $side)
    {

        $parent = BinaryTree::where(['user_id' => $user_id])->first(); // sponser

        $result = BinaryTree::where('parent_id', $parent->id)->get() ?? []; // children

        if (count($result) == 0) {
            return   [
                'user_id' => $user_id,
                'side' => $side
            ];
        }

        if (count($result) == 1) {
            // / left != left
            if ($result[0]['side'] != $side) {
                return [
                    'user_id' => $user_id,
                    'side' => $result[0]['side'] == 'right' ? 'left' : 'right'
                ];
            }

            $result2 = BinaryTree::whereDescendantOf($parent)->where('team', $team_side)->where('side', $side)->get() ?? [];

            $last = $result2[count($result2) - 1];

            return   [
                'user_id' => $last['user_id'],
                'side' => $side
            ];
        }

        if ($result[0]['side'] == $side) {
            $not_id = $result[1];
        } else {
            $not_id = $result[0];
        }

        $result = BinaryTree::whereDescendantOf($parent)->where('team', $team_side)->where('side', $side)->whereNotDescendantOf($not_id)->get() ?? [];

        $last = $result[count($result) - 1];

        return   [
            'user_id' => $last['user_id'],
            'side' => $side
        ];
    }

    /*
        This function need sponser user id , side (left or right) and refered user id
        for adding refered user in the binary tree !
    */
    public function generateBinaryTree($user_id, $side, $refer_user_id)
    {

        $team = $side . '_team';

        $parent_node = $this->get_binary_parent_node($user_id,  $team, $side);

        $parent_ = BinaryTree::where('user_id', $parent_node['user_id'])->first();

        BinaryTree::create([
            "user_id" => $refer_user_id,
            "side"  => $parent_node['side'],
            'team'  => $team
        ], $parent_);
    }


    public function generateUserUniqueId()
    {
        // $code = strtolower(Str::random(15));
        $code = mt_rand(10000000, 99999999);
        return  User::where('user_unique_id', $code)->exists() ? $this->generateUserUniqueId() : $code;
    }



    public function register(Request $request)
    {

        try {

            $validator  = Validator::make(
                $request->all(),
                [
                    'name'     => 'required|min:4|max:255',
                    'email'    => 'required|min:4|max:255|email',
                    'password' => 'required|min:8|max:200',
                    'confirm_password' => 'required|same:password',
                    'sponser'  => 'required|min:12|max:12'
                ]
            );

            if ($validator->errors()->all()) {
                throw new Exception($validator->errors()->first());
            }

            $sponser = User::orWhere(["lrl" => $request->sponser, "rrl" => $request->sponser])->first();

            if (!$sponser) {
                return throw new Exception("Invalid Sponsor!");
            }

            $request['side'] = "left"; // ($sponser->lrl == $request->sponser) ? "left" : "right";

            # Hash Password for Security
            $request['password'] = Hash::make($request->password);
            $request['user_sponser_id'] = $sponser->user_unique_id;

            # referal link
            $request['lrl'] = $this->referCode('lrl');  // left
            $request['rrl'] = $this->referCode('rrl');  // right

            $request['user_unique_id']  = $this->generateUserUniqueId();

            # Direct Sponser Id
            $request['direct_sponser_id'] = $sponser->id;

            $request['user_unique_address'] = $this->userUniqueAddress();

            # Created New User
            $user = User::create($request->all());

            # If User not Created in Table.
            if (!$user) {
                return throw new Exception("Unable to register at this time, Try after some time!!");
            }

            $this->generateOtp($request['user_unique_id'], 'verify_email');

            return $this->success("Please verify your email!",$request['user_unique_id']);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }


    public function login(Request $request)
    {
        try {
            $validator  = Validator::make(
                $request->all(),
                [
                    'user_unique_id'    => 'required|exists:users,user_unique_id',
                    'password' => 'required'
                ]
            );

            if ($validator->errors()->all()) {
                throw new Exception($validator->errors()->first());
            }

            $user_data = User::where("user_unique_id", $request->user_unique_id)->first(self::LOGIN_DATA_FIELDS);

            if ($user_data->status == 0) {
                return  $this->failed('Please contact to Support Team.');
            }
            if ($user_data->is_email_verified == 0) {
                return  $this->failed('Please verify your email first.');
            }

            // $captcha_result = $this->verifyCaptcha($request->captcha_response);

            // if (!$captcha_result['success']) {
            //     return $this->failed('Invalid ReCaptcha');
            // }

            if (Auth::attempt(['user_unique_id' => $request->user_unique_id, 'password' => $request->password, 'status' => '1'])) {

                $data['token']  =   $user_data->createToken($user_data->email)->accessToken;
                $data['user']   =   $user_data;

                return $this->success('Login Success.', $data);
            }

            return  $this->failed('Invalid Credentials.');
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }


    public function logout()
    {

        $token_id = Auth::User()->token()->id;

        // Delete Refresh Token
        DB::table('oauth_refresh_tokens')->where('access_token_id', $token_id)->delete();

        // Delete Refresh Token
        DB::table('oauth_access_tokens')->where('id', $token_id)->delete();

        return $this->success('Logout Successfully');
    }

    public function hard_logout()
    {
        $token_data = DB::table('oauth_access_tokens')->where(['user_id' => Auth::id(), 'client_id' => '1']);

        $access_tokens = $token_data->pluck('id');

        // Delete Refresh Tokens
        DB::table('oauth_refresh_tokens')->whereIn('access_token_id', $access_tokens)->delete();

        // Delete Access Tokens
        $token_data->delete();

        return $this->success('Logout Successfully');
    }

    public function changepassword(Request $request)
    {
        try {
            $validator  = Validator::make(
                $request->all(),
                [
                    'old_password'     => 'required',
                    'new_password' => 'required|min:4|max:18|different:old_password',
                    'confirm_password' => 'required|same:new_password',
                ]
            );
            if ($validator->errors()->all()) {
                throw new Exception($validator->errors()->first());
            }
            $user_id = Auth::id();
            $user = User::where(["id" => $user_id])->first(['password']);
            $checkhash = Hash::check($request->old_password, $user->password);
            if (!$checkhash) {
                return $this->failed('Old password is incorrect');
            }
            $request["new_password"] = Hash::make($request["new_password"]);
            User::where('id', $user_id)->update(['password' => $request["new_password"]]);
            DB::table('oauth_access_tokens')->where('user_id', $user_id)->delete();

            return $this->success("Password changed successfully");
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }

    public function wallet_report(Request $request)
    {
        $user = User::where("id", Auth::id())->first(["id", "name"]);
        $wallet = UserWalletLog::where(["user_id" => Auth::id()])->select(["comment", "created_at"])->orderBy("created_at", "desc")->paginate($request->per_page ?? 10);
        return $this->success("Wallet Transfer Reports Fetched Successfully.", $wallet, ["user" => $user]);
    }


    public function direct_team_report(Request $request)
    {
        if($request->unique_id){
            $user = User::where (["direct_sponser_id" => Auth::id(), "user_unique_id" => $request->unique_id])->select(["id", "name", "email", "status", "plan_active", "user_unique_id","created_at"])->paginate($request->per_page  ?? 10);

            return $this->success("Downline Team Reports Fetched Successfully.", $user);
        }


        //$user = User::where("direct_sponser_id", Auth::id())->select(["id", "name", "email", "status", "plan_active", "user_unique_id"])->paginate($request->per_page ?? 10);

       /* $user = DB::table('users')->where("direct_sponser_id", Auth::id())
            ->leftJoin('staked_plans', 'users.id', '=', 'staked_plans.user_id')
            ->selectRaw('users.*, SUM(staked_plans.stake_currency_amount) as total_stake_amount')
            ->groupBy('users.id')
            ->paginate($request->per_page ?? 10); */

        $user = User::where("direct_sponser_id", Auth::id())
            ->select(["users.id", "users.name", "users.email", "users.status", "users.plan_active", "users.user_unique_id", "users.created_at", DB::raw('SUM(staked_plans.stake_currency_amount) as total_amount')])
            ->leftJoin('staked_plans', 'users.id', '=', 'staked_plans.user_id')
            ->groupBy('users.id', 'users.name', 'users.email', 'users.status', 'users.plan_active', 'users.user_unique_id', 'users.created_at')
            ->paginate($request->per_page ?? 10);

        return $this->success("Direct Team Reports Fetched Successfully.", $user);
    }

    public function generationblade($id)
    {
        $parent = GenerationTree::where('user_id', $id)->first();

        $root = GenerationTree::descendantsAndSelf($parent->id)->toTree()->first();

        return view('generation', compact('root'));
    }
    public function generateRandomOtp($length = 6)
    {
        $otp = '';

        // Generate random digits
        for ($i = 0; $i < $length; $i++) {
            $otp .= mt_rand(0, 9);
        }

        return $otp;
    }

    public function generateOtp($user_unique_id, $type)
    {

        try {
            $validator  = Validator::make(
                ['user_unique_id' => $user_unique_id],
                [
                    'user_unique_id' => 'required|exists:users,user_unique_id'
                ]
            );
            if ($validator->errors()->all()) {
                throw new Exception($validator->errors()->first());
            }



            $email = User::where('user_unique_id',$user_unique_id)->value('email');

            /*$existingOtp = Otp::where(['user_unique_id' => $user_unique_id])
                ->where('created_at', '>=', Carbon::now()->subMinutes(5))
                ->first();

            if ($existingOtp) {
                return  $this->failed("Please try after some time.");
            } */

            $otp = 123456; // $this->generateRandomOtp();
            $expiryTime = Carbon::now()->addMinutes(5);


            // Store the OTP and expiry time in the database
            /* Otp::updateOrCreate(
                [
                   'user_unique_id' => $user_unique_id
                ],
                [
                   'otp'     => $otp,
                   'expiry_time' => $expiryTime,
                   'email'   => $email,
                ]
            ); */


			//DB::table("otps")->insert(['otp' => $otp, 'expiry_time' => $expiryTime,'email'   => $email,]);

             Otp::create([
                 'email' =>  $email,
                 'otp' => $otp,
                 'expiry_time' => $expiryTime,
             ]);


            $link = env("FRONTEND_URL") . "otp?type=" . $type . "&user_unique_id=" . $user_unique_id;

            // http://192.168.11.162:5173/otp?type=verify_email&email=testnet@yopmail.com

            Mail::to($email)->send(new SendOtp('email', $otp, 'same4all', $link));
            // Send the OTP to the user via email or other means

            return $this->success("Otp sent successfully!!");
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }
    public function sendOtp(Request $request)
    {
        $validator  = Validator::make(
            $request->all(),
            [
                'user_unique_id'    => 'required|exists:users,user_unique_id',
                'type' => 'required'
            ]
        );
        if ($validator->errors()->all()) {
            return $this->failed($validator->errors()->first());
        }
        return $this->generateOtp($request->user_unique_id, $request->type);
    }


    public function startForgotPassword(Request $request){
        try {
            $validator  = Validator::make(
                $request->all(),
                [
                    'user_unique_id' => 'required|exists:users,user_unique_id'
                ]
            );

            if ($validator->errors()->all()) {
                throw new Exception($validator->errors()->first());
            }

            $email = User::where('user_unique_id',$request->user_unique_id)->value('email');


            $existingOtp = Otp::where(['email' => $email])
                ->where('created_at', '>=', Carbon::now()->subMinutes(15))
                ->first();

            if ($existingOtp) {
                return  $this->failed("Please try after some time.");
            }

            $otp = $this->generateRandomOtp();
            $expiryTime = Carbon::now()->addMinutes(15);


            // Store the OTP and expiry time in the database
            Otp::updateOrCreate(
                [
                   'user_unique_id' => $request->user_unique_id
                ],
                [
                   'otp'     => $otp,
                   'expiry_time' => $expiryTime,
                   'email'   => $email,
                ]
            );



            // Otp::create([
            //     'email' =>  $request->input('email'),
            //     'otp' => $otp,
            //     'expiry_time' => $expiryTime,
            // ]);
                //setpassword?user_unique_id=48279807&otp=470113
            $link = env("FRONTEND_URL") . "setpassword?otp=" . $otp . "&user_unique_id=" . $request->user_unique_id;

            // http://192.168.11.162:5173/otp?type=verify_email&email=testnet@yopmail.com

            Mail::to($email)->send(new SendOtp('email', $otp, 'same4all', $link));
            // Send the OTP to the user via email or other means

            return $this->success("Otp sent to your Email !!");
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }




    public function fogotPassword(Request $request)
    {
        try {
            $validator  = Validator::make(
                $request->all(),
                [
                    'user_unique_id'    => 'required|exists:users,user_unique_id',
                    'new_password' => 'required|min:4|max:18',
                    'confirm_new_password' => 'required|same:new_password',
                    'otp' => 'required|max:6|exists:otps,otp,user_unique_id,'.$request->user_unique_id
                ]
            );
            if ($validator->errors()->all()) {
                throw new Exception($validator->errors()->first());
            }

            $response = $this->VerifyOtp($request->user_unique_id, $request->otp);

            if ($response['status_code'] == 1) {

                $user = User::where(["user_unique_id" => $request->input('user_unique_id')])->first(['password', 'id']);


                if (!$user) {
                    return $this->failed('Invalid Data!!');
                }

                $request["new_password"] = Hash::make($request["new_password"]);

                User::where('id',  $user->id)->update(['password' => $request["new_password"]]);

                DB::table('oauth_access_tokens')->where('user_id',  $user->id)->delete();

                return $this->success("Password changed successfully");
            }

            return  $this->failed('Invalid Data!!');


        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }


    public function VerifyOtp($user_unique_id, $otp)
    {

        // return ['dd' =>$user_unique_id, 'oo' => $otp];
		$user_email = User::where('user_unique_id',$user_unique_id)->value('email');
		$user_otp = 123456;

        $otp = Otp::where(['email' => $user_email, 'otp' => $user_otp])->first();

        if (!$otp) {
            return $this->failed('Incorrect Otp ');
        }

        // $otp_detail = $otp->first();
        // $callback_fun = $otp_detail->callback;
        // $cb_params = $otp_detail->cb_params;

        # Validate Expiry
        if ($otp->expiry_time < now()) {
            return $this->failed('Otp Expired');
        }

        $email = $otp->email;
        // if ($callback_fun != null || $callback_fun != '') {
        //     $otp->delete();
        //     return call_user_func([get_class($this), $callback_fun], $this->getParams($cb_params));
        // }

        //$otp->delete();
        return $this->success('Verified Successfully' , $email);
    }



    public function validate_otp(Request $request)
    {
        return $this->VerifyOtp($request->user_unique_id, $request->otp);
    }

    public function verifyEmail(Request $request)
    {
        $validator  = Validator::make(
            $request->all(),
            [
                'user_unique_id' => 'required|exists:users,user_unique_id',
                'otp' => 'required|max:6'
            ]
        );

        if ($validator->errors()->all()) {
            return $this->failed($validator->errors()->first());
        }

        $response = $this->validate_otp($request);

        if ($response['status_code'] == 1) {

            User::where(['email' => $response['data'] , 'user_unique_id' => $request->user_unique_id])->update(['is_email_verified' => 1,'status' => 1]);

            $user = User::where(['email' => $response['data'] , 'user_unique_id' => $request->user_unique_id])->select(['id', 'email', 'direct_sponser_id', 'side'])->first();

            if (!$user) {
                return $this->failed("Error while getting user");
            }
            # Generate Generation Tree
            $this->generateGenerationTree($user->direct_sponser_id, $user->id);

            # Generate Binary Tree

            //$this->generateBinaryTree($user->direct_sponser_id, $user['side'], $user["id"]);


            $mail_content = $request->user_unique_id;

            Mail::to($response['data'])->send(new SendOtp('email', '', 'send_unique_id', $mail_content));

            return $this->success('Unique Id Sent to your email account.');
        }
        return $response;
    }
    public function resendOtp(Request $request)
    {
        $validator  = Validator::make(
            $request->all(),
            [
                'user_unique_id'    => 'required|exists:users,user_unique_id',
                'type' => 'required'
            ]
        );
        if ($validator->errors()->all()) {
            return $this->failed($validator->errors()->first());
        }
        return $this->generateOtp($request->user_unique_id, $request->type);
    }


    public function login_by_admin(Request $request)
    {
        try {
            $validator  = Validator::make(
                $request->all(),
                [
                    'email'    => 'required|min:4|max:255|email|exists:users,email',
                    'password' => 'required|min:4|max:18',
                ]
            );

            if ($validator->errors()->all()) {
                throw new Exception($validator->errors()->first());
            }

            $user_data = User::where(["email"=> $request->email,'role'=>'admin'])->first(self::LOGIN_DATA_FIELDS);


            if ($user_data->status == 0) {
                return  $this->failed('Please contact to Support Team.');
            }


            if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'status' => '1'])) {

                $data['token']  =   $user_data->createToken($user_data->email)->accessToken;
                $data['user']   =   $user_data;

                return $this->success('Login Success.', $data);
            }

            return  $this->failed('Invalid Credentials.');
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }



    public function changeProfile(Request $request){
        try {
            $validator  = Validator::make(
                $request->all(),
                [
                    'email'    => 'required|email|max:255',
                    'name' => 'required|min:4|max:200',
                    'mobile'   => 'required|max:18',
                ]
            );

            if ($validator->errors()->all()) {
                throw new Exception($validator->errors()->first());
            }

           $updated = User::where([
                'id' => Auth::id()
            ])->update([
                "email" =>  $request->email,
                "name" =>  $request->name,
                "mobile" => $request->mobile,
                "user_unique_address" => $request->wallet,
            ]);


            return ($updated) ? $this->success('Profile Updated Successfully.') : $this->failed('Invalid Credentials.');

        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }

    # This function is used for getting decendent of binary tree id
    public function getGenerationDecendant($tree_id = 0)
    {
        if ($tree_id == 0) {
            return [];
        }

        return GenerationTree::descendantsOf($tree_id)->pluck('user_id') ?? [];
    }

    public function getBinaryDecendant($tree_id = 0)
    {
        if ($tree_id == 0) {
            return [];
        }

        return BinaryTree::whereDescendantOrSelf($tree_id)->pluck('user_id') ?? [];
    }

    public function downline_team_report(Request $request){

        if($request->unique_id){
            $user = User::where ("user_unique_id", $request->unique_id)->select(["id", "name", "email", "status", "plan_active", "user_unique_id","user_sponser_id"])->paginate($request->per_page  ?? 10);

            $user->appends(['per_page' => $request->per_page  ?? 10, 'page' =>  $request->page  ?? 1]);

            return $this->success("Downline Team Reports Fetched Successfully.", $user);
        }


        # HERE WE ARE GETTING CURRENT USER PARENT ID
        $parent_id = BinaryTree::where("user_id",  Auth::id())->value("id");

        # HERE WE ARE GETTING FIRST LEFT AND RIGHT CHILD ID
        $downline = BinaryTree::where("parent_id", $parent_id)->get(['id', 'team']);

        $left_child_id = 0;
        $right_child_id = 0;

        # IF THERE IS ONLY ONE CHILD
        if (count($downline) == 1) {
            $left_child_id = ($downline[0]['team'] == "left_team") ? $downline[0]['id'] : 0;
            $right_child_id = ($downline[0]['team'] == "right_team") ? $downline[0]['id'] : 0;
        }

        # IF THERE IS TWO CHILD
        if (count($downline) == 2) {
            $left_child_id = ($downline[0]['team'] == "left_team") ? $downline[0]['id'] : $downline[1]['id'];
            $right_child_id = ($downline[0]['team'] == "right_team") ? $downline[0]['id'] : $downline[1]['id'];
        }

        # HERE WE GETTING LEFT DOWNLINE USER IDS EXAMPLE - [2,5,6,7]
        $left_team = $this->getBinaryDecendant($left_child_id);

        # HERE WE GETTING RIGHT DOWNLINE USER IDS EXAMPLE - [3,6]
        $right_team = $this->getBinaryDecendant($right_child_id);

        $user_ids =  array_merge($left_team->toArray(), $right_team->toArray());

        $user = User::whereIn("id", $user_ids)->select(["id", "name", "email", "status", "plan_active", "user_unique_id","user_sponser_id"])->paginate($request->per_page  ?? 10);

        $user->appends(['per_page' => $request->per_page  ?? 10, 'page' =>  $request->page  ?? 1]);

        return $this->success("Downline Team Reports Fetched Successfully.", $user);
    }

}
