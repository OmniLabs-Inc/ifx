<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Exception;
use App\Traits\Reply;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    use Reply;

    public function get(Request $request)
    {

        try {
            $users = new User();
            if ($request->id) {
                $users = $users->where('id', $request->id);
            }
            if ($request->user_sponser_id) {
                $users = $users->where('user_sponser_id', $request->user_sponser_id);
            }
            if ($request->unique_id) {
                $users = $users->where('user_unique_id', $request->unique_id);
            }
            if ($request->name) {
                $users = $users->where('name', $request->name);
            }
            if ($request->email) {
                $users = $users->where('email', $request->email);
            }
            if ($request->user_unique_address) {
                $users = $users->where('user_unique_address', $request->user_unique_address);
            }
            if (($request->status == 0 || $request->status == 1) && $request->status != '') {
                $users = $users->where('status', $request->status);
            }

            
            $users = $users->select(['id', 'name','user_sponser_id', 'email', 'user_unique_id','user_unique_address', 'status', 'role'])->paginate($request->per_page  ?? 10);
            
            return $this->success("Users fetch successfully", $users);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }


    public function detail(Request $request)
    {
        try {
            $validator  = Validator::make(
                $request->all(),
                [
                    'id'     => 'required|integer|exists:users,id'
                ]
            );
            if ($validator->errors()->all()) {
                throw new Exception($validator->errors()->first());
            }

            # achievers + wallet user
            $data = User::with(['usermeta', 'user_wallet'])->where("id", $request->id)->get(['id', 'name', 'email', 'side', 'direct_sponser_id', 'user_unique_address', 'status']);

            # direct members
            $direct = User::where('direct_sponser_id', $request->id)->select(['id', 'name', 'email', 'side', 'user_unique_address', 'status'])->get();


            return $this->success("User Detail fetch successfully", $data, ['direct_members' => $direct]);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }


    public function block(Request $request)
    {
        try {
            $validator  = Validator::make(
                $request->all(),
                [
                    'id'     => 'required|integer|exists:users,id',
                    'status' => 'required|in:0,1'
                ]
            );


            if ($validator->errors()->all()) {
                throw new Exception($validator->errors()->first());
            }

            $user = User::where("id", $request->id)->select(['id', 'email', 'status'])->first();
            if (!$user) {
                throw new Exception('User not found.');
            }
            if ($user->status == $request->status) {
                throw new Exception("Invalid data!!");
            }

            # achievers + wallet user
            User::where("id", $request->id)->update(['status' => $request->status]);

            $token_data = DB::table('oauth_access_tokens')->where(['user_id' => $request->id, 'client_id' => '1']);

            $access_tokens = $token_data->pluck('id');

            // Delete Refresh Tokens
            DB::table('oauth_refresh_tokens')->whereIn('access_token_id', $access_tokens)->delete();

            // Delete Access Tokens
            $token_data->delete();

            return $this->success("User status updated successfully");
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }

    public function list(Request $request)
    {
        $data = User::get(['id', 'user_unique_id']);
        return $this->success("User Fetched Successfully.", $data);
    }


    public function change_password(Request $request){
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
}
