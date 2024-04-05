<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\ManageUser;


class ManageUserController extends Controller
{
    public function index() {
        $page_title = 'User Group';
        $groups = ManageUser::get();
        return view('admin.manage_user.group.index', compact('groups', 'page_title'));
    }

    public function getPermissionCollection() {
        $group[1] = 'Post Management';
        $collect = collect([]);
        $collect->push([
            'name'  => 'referral',
            'label' => 'Manage Referral',
            'group' => $group[1],
            'value' => false,
        ]);
        $collect->push([
            'name'  => 'user_management',
            'label' => 'User Management',
            'group' => $group[1],
            'value' => false,
        ]);
        $collect->push([
            'name'  => 'active_user',
            'label' => 'Active User',
            'group' => $group[1],
            'value' => false,
        ]);
        $collect->push([
            'name'  => 'ban_user',
            'label' => 'Deactive User',
            'group' => $group[1],
            'value' => false,
        ]);
        $collect->push([
            'name'  => 'pending_kyc',
            'label' => 'Pending KYC',
            'group' => $group[1],
            'value' => false,
        ]);
        $collect->push([
            'name'  => 'log_kyc',
            'label' => 'KYC Log',
            'group' => $group[1],
            'value' => false,
        ]);
        $collect->push([
            'name'  => 'gateway_management',
            'label' => 'Gateway',
            'group' => $group[1],
            'value' => false,
        ]);
        $collect->push([
            'name'  => 'deposit_pending',
            'label' => 'Pending Deposit',
            'group' => $group[1],
            'value' => false,
        ]);
        $collect->push([
            'name'  => 'deposit_accept',
            'label' => 'Accept Deposit',
            'group' => $group[1],
            'value' => false,
        ]);
        $collect->push([
            'name'  => 'deposit_reject',
            'label' => 'Reject Deposit',
            'group' => $group[1],
            'value' => false,
        ]);
        $collect->push([
            'name'  => 'deposit_log',
            'label' => 'Deposit Log',
            'group' => $group[1],
            'value' => false,
        ]);
        $collect->push([
            'name'  => 'withdraw_method',
            'label' => 'Withdraw Management',
            'group' => $group[1],
            'value' => false,
        ]);
        $collect->push([
            'name'  => 'withdraw_pending',
            'label' => 'Pending Withdraw',
            'group' => $group[1],
            'value' => false,
        ]);
        $collect->push([
            'name'  => 'withdraw_log',
            'label' => 'Withdraw Log',
            'group' => $group[1],
            'value' => false,
        ]);
        $collect->push([
            'name'  => 'schedule',
            'label' => 'Schedule',
            'group' => $group[1],
            'value' => false,
        ]);
        $collect->push([
            'name'  => 'email_settings',
            'label' => 'Email Settings',
            'group' => $group[1],
            'value' => false,
        ]);
        $collect->push([
            'name'  => 'identity_form',
            'label' => 'Identity Form',
            'group' => $group[1],
            'value' => false,
        ]);
        $collect->push([
            'name'  => 'transaction',
            'label' => 'Transaction',
            'group' => $group[1],
            'value' => false,
        ]);
        $collect->push([
            'name'  => 'banner',
            'label' => 'Banner',
            'group' => $group[1],
            'value' => false,
        ]);
        $collect->push([
            'name'  => 'logo',
            'label' => 'Logo & Favicon',
            'group' => $group[1],
            'value' => false,
        ]);
        $collect->push([
            'name'  => 'news',
            'label' => 'News',
            'group' => $group[1],
            'value' => false,
        ]);
        $collect->push([
            'name'  => 'plan',
            'label' => 'Plan',
            'group' => $group[1],
            'value' => false,
        ]);
        $collect->push([
            'name'  => 'menu',
            'label' => 'Menu',
            'group' => $group[1],
            'value' => false,
        ]);
        $collect->push([
            'name'  => 'view_pass',
            'label' => 'View Password',
            'group' => $group[1],
            'value' => false,
        ]);
        $collect->push([
            'name'  => 'web_interface',
            'label' => 'Web Interface',
            'group' => $group[1],
            'value' => false,
        ]);
        $collect->push([
            'name'  => 'manage_user',
            'label' => 'Manage User',
            'group' => $group[1],
            'value' => false,
        ]);
        $collect->push([
            'name'  => 'ico',
            'label' => 'Ico Settings',
            'group' => $group[1],
            'value' => false,
        ]);
        $collect->push([
            'name'  => 'google_tools',
            'label' => 'Google Tools',
            'group' => $group[1],
            'value' => false,
        ]);
        $collect->push([
            'name'  => 'support',
            'label' => 'Support',
            'group' => $group[1],
            'value' => false,
        ]);
        $collect->push([
            'name'  => 'settings',
            'label' => 'Settings',
            'group' => $group[1],
            'value' => false,
        ]);
        $collect->push([
            'name'  => 'theme',
            'label' => 'Manage Theme',
            'group' => $group[1],
            'value' => false,
        ]);
        $collect->push([
            'name'  => 'language',
            'label' => 'Language Manage',
            'group' => $group[1],
            'value' => false,
        ]);
        return $collect;
    }

    public function create() {
        $page_title = 'Create User Group';
        $permission_collect = $this->getPermissionCollection();
        return view('admin.manage_user.group.create', compact('permission_collect', 'page_title'));
    }

    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required',
        ]);
        try{
            if (!$request->has('permission')) {
                $request['permission'] = [];
            }
            $permission = $this->getPermissionCollection()->map(function ($item, $key) use ($request) {
                $value = array_key_exists($item['name'], $request->permission) ? true : false;
                $item['value'] = $value;
                return $item;
            });
            $group = new ManageUser();
            $group->name = $request->name;
            $group->status = $request->has('status') ? 1 : 0;
            $group->permission = $permission->toJson();
            $group->save();
            return back()->with('success', 'Group Create Successful');
        }catch (\Exception $e){
            return back()->with('alert',$e->getMessage());
        }
    }

    public function edit($id) {
        $page_title = 'User Group edit';
        $group = ManageUser::findOrFail($id);
        $permission = $this->getPermissionCollection()->map(function ($item, $key) use ($group) {
            $value = false;
            if ($per = $group->permissions()->where('name', $item['name'])->first()) {
                $value = $per['value'];
            };
            $item['value'] = $value;
            return $item;
        });
        return view('admin.manage_user.group.edit', compact('group', 'page_title', 'permission'));
    }

    public function update(Request $request, $id) {
        $this->validate($request, [
            'name' => 'required',
        ]);
        try{
            if (!$request->has('permission')) {
                $request['permission'] = [];
            }
            $permission = $this->getPermissionCollection()->map(function ($item, $key) use ($request) {
                $value = array_key_exists($item['name'], $request->permission) ? true : false;
                $item['value'] = $value;
                return $item;
            });
            $group = ManageUser::findOrFail($id);
            $group->name = $request->name;
            $group->status = $request->has('status') ? 1 : 0;
            $group->permission = $permission->toJson();
            $group->save();
            return back()->with('success', 'Group Update Successful');
        }catch (\Exception $e){
            return back()->with('alert',$e->getMessage());
        }
    }

    public function getUser() {
        $page_title = 'User list';
        $users = Admin::get();
        $groups = ManageUser::whereStatus(1)->get();
        return view('admin.manage_user.user.index', compact('users', 'page_title', 'groups'));
    }

    public function userStore(Request $request) {
        $this->validate($request, [
            'name'     => 'required',
            'username' => 'required:unique:admins',
            'password' => 'required',
            'group_id' => 'required',
        ]);
        try{
            $user = new Admin();
            $user->name = $request->name;
            $user->user_group_id = $request->group_id;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->sct = $request->password;
            $user->save();
            return back()->with('success', 'Create Successful');
        }catch (\Exception $e){
            return back()->with('alert',$e->getMessage());
        }
    }

    public function userUpdate(Request $request) {
        $this->validate($request, [
            'name'     => 'required',
            'username' => 'required:unique:admins',
            'email'    => 'required:unique:admins',
            'group_id' => 'required',
        ]);
        try{
            $user = Admin::findOrFail($request->id);
            $user->name = $request->name;
            $user->user_group_id = $request->group_id;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->status = $request->status;
            $user->password = bcrypt($request->password);
            if (!is_null($request->password)) {
                $this->validate($request, [
                    'password' => 'required|min:6',
                ]);
                $user->sct = $request->password;
            }
            $user->update();
            return back()->with('success', 'Update Successful');
        }catch (\Exception $e){
            return back()->with('alert',$e->getMessage());
        }
    }
}

?>