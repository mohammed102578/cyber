<?php

namespace App\Repository\admin;

use App\Events\ReportMessageSent;
use App\Http\Requests\Admin\ChatRequest;
use App\Http\Services\Notification;
use App\Interfaces\admin\ReportChatInterface;
use App\Models\ChatReport;
use App\Models\Notification as ModelsNotification;
use App\Models\Reporter\Reporter;
use Illuminate\Support\Facades\Auth;

class ReportChatRepository implements ReportChatInterface
{

    public $reporter_notification;
    public function __construct()
    {
        $this->reporter_notification = new Notification;

    }


public function store($request)


{

    try{
          $data=[
          'report_id'=>$request->report_id,
          'body'=>$request->body,
          'reporter_id'=>$request->reporter_id,
          'admin_id'=>Auth::guard('admin')->user()->id,
          'sender_type'=>'admin',
          'receiver_type'=>'reporter',

          ];
          $check = ChatReport::orderBy('id','DESC')->first();
          if($check != null && $check->body==$request->body&&$check->sender_type=='admin'&&$check->admin_id==Auth::guard('reporter')->user()->id&&$check->reporter_id==$request->reporter_id)
          {
          return response()->json(['status' => 'failed']);
          }else{
          $chat_report = ChatReport::create($data);
          $image=Auth::guard('admin')->user()->image;
          $message=$chat_report;
          //realtime chat
          event(new ReportMessageSent($message,$request->reporter_id,$message->receiver_type,$image));

           // send notification

          	// send notification
              $this->reporter_notification->
              sendReporterNotification('Report Chat',Auth::guard('admin')->user()->name.'  Added a message to the report chat ',
              'show_reporter_report',$request->report_id,$request->reporter_id,'admin');
              ModelsNotification::where('title','Report Chat')->where('notificationable_type','App\Models\Admin\Admin')->update(['read'=>1]);

          return response()->json(['status' => 'success'], 200);
          }

    } catch (\Exception $ex) {
        return back()->with('error',  'something went wrong');
    }

}


}
