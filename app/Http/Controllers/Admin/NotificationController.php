<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\General;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function notifications(){
        $page_title = 'Notifications';
        $general = General::first();
        $notifications = Notification::orderBy('id','desc')->with('user')->paginate($general->paginate);
        return view('admin.notifications',compact('page_title','notifications'));
    }

    public function notificationRead($id){
        $notification = Notification::findOrFail($id);
        $notification->read_status = 1;
        $notification->save();
        return redirect($notification->click_url);
    }

    public function readAll(){
        Notification::where('read_status',0)->update([
            'read_status' => 1
        ]);
        return back()->with('success', 'Notifications read successfully');
    }
}
