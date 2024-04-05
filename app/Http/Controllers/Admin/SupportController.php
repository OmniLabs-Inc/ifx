<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\Support;
use App\Models\SupportComment;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    public function __construct()
    {
        $this->theme = template();
    }
    
    public function supportIndex() {
        $data['page_title'] = 'All Support List';
        $data['all_support'] = Support::orderBy('id', 'desc')->paginate(15);
        return view('admin.support.support', $data);
    }

    public function adminSupport($support) {
        $data['page_title'] = "View Ticket";
        $data['ticket_object'] = Support::with('admin_member')->with('user_member')->where('ticket', $support)->first();
        $data['ticket_data'] = SupportComment::where('ticket_id', $support)->get();
        return view('admin.support.view_reply', $data);
    }

    public function adminReply(Request $request, $support) {
        $this->validate($request, [
            'comment' => 'required',
        ]);
        SupportComment::create([
            'ticket_id' => $support,
            'type'      => 0,
            'comment'   => $request->comment,
        ]);
        Support::where('ticket', $support)
            ->update([
                'status' => 2,
            ]);
        
        $ticket = Support::with('user_member')->where('ticket', $support)->firstOrFail();
        $user = $ticket->user_member;
        $shortCodes = [
            'ticket_id' => $ticket->ticket,
            'ticket_subject' => $ticket->subject,
            'reply' => $request->comment,
        ];
        @send_email($user, 'ADMIN_SUPPORT_REPLY', $shortCodes);
        return redirect()->back()->with('message', 'Message Send Successful');
    }

    public function customerSupportIndex() {
        $data['page_title'] = 'My Tickets';
        $data['all_support'] = Support::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(15);
        return view($this->theme .'user.support.support', $data);
    }

    public function ticketCreate() {
        $data['page_title'] = 'Add New Tickets';
        return view($this->theme .'user.support.add_ticket', $data);
    }

    public function ticketStore(Request $request) {
        
        $this->validate($request, [
            'subject' => 'required',
            'detail'  => 'required',
        ]);
        $a = strtoupper(md5(uniqid(rand(), true)));
        $support = Support::create([
            'subject'     => $request->subject,
            'ticket'      => substr($a, 0, 8),
            'user_id' => Auth::user()->id,
        ]);
        SupportComment::create([
            'ticket_id' => $support->ticket,
            'type'      => 1,
            'comment'   => $request->detail,
        ]);

        $adminNotification = new Notification();
        $adminNotification->user_id = Auth::user()->id;
        $adminNotification->title = 'New support ticket has opened';
        $adminNotification->click_url = urlPath('support.admin.reply', $support->ticket);
        $adminNotification->save();

        return redirect()->route('ticket.customer.reply', $support->ticket)->with('message', 'Message Send Successfully');
    }

    public function ticketClose($support) {
        Support::where('ticket', $support)
            ->update([
                'status' => 9,
            ]);
        return redirect()->back()->with('message', 'Conversation closed, But you can start again');
    }

    public function ticketReply($support) {
        $page_title = "Support Reply";
        $ticket_object = Support::where('user_id', Auth::user()->id)
            ->where('ticket', $support)->first();
        $ticket_data = SupportComment::where('ticket_id', $support)->get();
        if ($ticket_object == '') {
            return redirect()->route('pagenot.found');
        } else {
            return view($this->theme .'user.support.view_reply', compact('ticket_data', 'ticket_object', 'page_title'));
        }
    }

    public function ticketReplyStore(Request $request, $support) {
        $this->validate($request, [
            'comment' => 'required',
        ]);
        SupportComment::create([
            'ticket_id' => $support,
            'type'      => 1,
            'comment'   => $request->comment,
        ]);
        Support::where('ticket', $support)
            ->update([
                'status' => 3,
            ]);
        return redirect()->back()->with('message', 'Message Send Successful');
    }
}

?>