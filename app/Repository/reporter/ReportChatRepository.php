<?php

namespace App\Repository\reporter;

use App\Events\ReportMessageSent;
use App\Interfaces\reporter\ReportChatInterface;
use App\Models\Admin\Admin;
use App\Models\ChatReport;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\Notification;
use App\Models\Notification as ModelsNotification;

class ReportChatRepository implements ReportChatInterface
{


      public object $admin_notification;
      public function __construct()
      {
          $this->admin_notification = new Notification;

      }

//store  report chat between reporter and admin

public function store($request)
{

    try{
        $admin_id=Admin::first();
        $data=[
        'report_id'=>$request->report_id,
        'body'=>$request->body,
        'reporter_id'=>Auth::guard('reporter')->user()->id,
        'admin_id'=>$admin_id->id,
        'sender_type'=>'reporter',
        'receiver_type'=>'admin',
        ];
        $check = ChatReport::orderBy('id','DESC')->first();
        if($check != null && $check->body==$request->body&&$check->sender_type=='reporter'&&$check->admin_id==1&&$check->reporter_id==Auth::guard('reporter')->user()->id)
        {
        return response()->json(['status' => 'failed']);
        }else{
        $chat_report = ChatReport::create($data);
        $image=Auth::guard('reporter')->user()->image;
        $message=$chat_report;
        //realtime chat
        event(new ReportMessageSent($message,$message->admin_id,$message->receiver_type,$image));

         //send_notification
       // return  $request->report_id;
         $this->admin_notification->
         sendAdminNotification('Report Chat',Auth::guard('reporter')->user()->first_name.'  Added a message to the report chat',
         'show_admin_report',$request->report_id,'reporter');
         ModelsNotification::where('title','Report Chat')->where('notificationable_type','App\Models\Reporter\Reporter')->update(['read'=>1]);

        return response()->json(['status' => 'success'], 200);
        }

  } catch (\Exception $ex) {

        return back()->with('error',  'something went wrong');

  }

}


}
