<?php

namespace App\Repository\admin;

use App\Events\CorporateEventsNotification;
use App\Events\ReporterEventsNotification;
use App\Interfaces\admin\NotificationInterface;
use App\Models\Activity;
use App\Models\Admin\Admin;
use App\Models\Corporate\Corporate;
use App\Models\Notification;
use App\Models\Reporter\Reporter;
use App\Notifications\CorporateNotification;
use App\Notifications\ReporterNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class  NotificationRepository implements NotificationInterface
{



public function notification()

{
    try{

        $admin=Admin::find(Auth::guard('admin')->user()->id);
        $read_notifications=Notification::where('read',1)->orderBy('id','DESC')->get();
        $unread_notifications=Notification::where('read',0)->orderBy('id','desc')->get();
        $all_notifications=Notification::orderBy('id','desc')->get();
        $reporters=Reporter::all();
        $corporates=Corporate::all();
        return view('content.admin.pages.general.notifications',compact(['reporters','corporates','unread_notifications','read_notifications','all_notifications']));
       }catch(\Exception $ex){
    return redirect()->back()->with('error','something went wrong');
    }
}


public function get_read_notifications($request)
{
    try{
        if (
        $request->ajax()) {

            $data =DB::table('notifications')->where('read',1)->orderBy('id','DESC')->get();

        return Datatables()->of($data)
        ->addIndexColumn()
        //notification_created
        ->addColumn('created', function($data){
            $date = strtotime ( $data->created_at );

            return $created_at=date ( 'd M Y h:i' , $date );
            })

        ->rawColumns(['created'])

         //notification_type
         ->addColumn('notification_type', function($data){

            if($data->notificationable_type== "App\Models\Reporter\Reporter"){
                $notification_type ="Reporter";
            }elseif($data->notificationable_type== "App\Models\Corporate\Corporate")
            {

                $notification_type ="Corporate";

            }else{

                $notification_type ="Admin";

            }

            return $notification_type;
                })
           ->rawColumns(['notification_type'])

        ->addColumn('action', function($data){
            $actionBtn = '
            <a class="notification_delete_read" id="'. $data->id.'"><i class="bx bx-trash text-danger"></i></a>

           <a class="notification_read_read" id="'. $data->id.'" ><i class="bx bxs-badge-check text-success "></i></a>

            ';
            return $actionBtn;
        })
        ->rawColumns(['action'])
        ->make(true);
        }

    } catch (\Exception $ex) {

       return back()->with('error', 'something went wrong');

    }
}


public function get_unread_notifications($request)
{

    try{
        if (
        $request->ajax()) {
            $data =DB::table('notifications')->where('read',0)->orderBy('id','DESC')->get();


        return Datatables()->of($data)
        ->addIndexColumn()
        //notification_created
        ->addColumn('created', function($data){

            $date = strtotime ( $data->created_at );

            return $created_at=date ( 'd M Y h:i' , $date );               })

        ->rawColumns(['created'])

         //notification_type
         ->addColumn('notification_type', function($data){

            if($data->notificationable_type== "App\Models\Reporter\Reporter"){
                $notification_type ="Reporter";
            }elseif($data->notificationable_type== "App\Models\Corporate\Corporate")
            {

                $notification_type ="Corporate";

            }else{

                $notification_type ="Admin";

            }

            return $notification_type;
                })
           ->rawColumns(['notification_type'])

        ->addColumn('action', function($data){
            $actionBtn = '
            <a class="notification_delete_unread" id="'. $data->id.'"><i class="bx bx-trash text-danger"></i></a>

           <a class="notification_read_unread" id="'. $data->id.'" ><i class="bx bxs-badge-check "></i></a>

            ';
            return $actionBtn;
        })
        ->rawColumns(['action'])
        ->make(true);
        }

    } catch (\Exception $ex) {

       return back()->with('error', 'something went wrong');

    }
}



public function get_all_notifications($request)
{
    try{
        if (
        $request->ajax()) {

            $data =DB::table('notifications')->orderBy('id','DESC')->get();

        return Datatables()->of($data)
        ->addIndexColumn()
        //notification_created
        ->addColumn('created', function($data){

            $date = strtotime ( $data->created_at );

            return $created_at=date ( 'd M Y h:i' , $date );               })

        ->rawColumns(['created'])

         //notification_type
         ->addColumn('notification_type', function($data){

            if($data->notificationable_type== "App\Models\Reporter\Reporter"){
                $notification_type ="Reporter";
            }elseif($data->notificationable_type== "App\Models\Corporate\Corporate")
            {

                $notification_type ="Corporate";

            }else{

                $notification_type ="Admin";

            }

            return $notification_type;
                })
           ->rawColumns(['notification_type'])

        ->addColumn('action', function($data){
            if($data->read==1){
                $actionBtn = '
                <a class="notification_delete_all" id="'. $data->id.'"><i class="bx bx-trash text-danger"></i></a>
               <a class="notification_read_all" id="'. $data->id.'" ><i class="bx bxs-badge-check text-success"></i></a>
                ';
            }else{
                $actionBtn = '
                <a class="notification_delete_all" id="'. $data->id.'"><i class="bx bx-trash text-danger"></i></a>
               <a class="notification_read_all" id="'. $data->id.'" ><i class="bx bxs-badge-check "></i></a>
                ';
            }

            return $actionBtn;
        })
        ->rawColumns(['action'])
        ->make(true);
        }

    } catch (\Exception $ex) {

       return back()->with('error', 'something went wrong');

    }
}


public function read_notification($request)

{
    try{
        $id = $request->id;
        $check = Notification::find($id);
        if($check){
        $data=$check;
        if($data->read == 1){
        $status=0;
        }else{
        $status=1;
        }


        Notification::where('id',$data->id)->update(['read' => $status]);

        Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Corporate\Corporate',
        'activity'=>'Updated Notification Status','description_activity'=>"updated Notification Status To read"]);
        $admin=Admin::find(Auth::guard('admin')->user()->id);
        $count=$admin->notificationable()->where('read',0)->orderBy('id','desc')->count();
        $read_notifications=Notification::where('read',1)->orderBy('id','DESC')->count();
        $unread_notifications=Notification::where('read',0)->orderBy('id','desc')->count();
        $all_notifications=Notification::orderBy('id','desc')->count();

        return response()->json(['status' => 'success','count'=>$count,'read_count'=>$read_notifications,'unread_count'=>$unread_notifications,'all_count'=>$all_notifications], 200);


    }else{
        return response()->json(['status' => 'failed']);
        }

    } catch (\Exception $ex) {
       return back()->with('error',  'something went wrong');
    }
}



public function store($request)
{

    try{
    if($request->reporter=='all'){
   $reporters=Reporter::all();
   foreach($reporters as $reporter){
    $notification=['title'=>$request->title,'body'=>$request->body,'notificationable_id'=>
    $reporter->id,'notificationable_type'=>'App\Models\Reporter\Reporter','link'=>'reporter_notification',
    'sender_image'=>Auth::guard('admin')->user()->image];
    $create=Notification::create($notification);
    }
    $notification=Notification::orderBy('id','DESC')->first();
    $data =[
        'notification_id' =>$notification->id,
        'title'  =>$notification->title,
        'body'  =>$notification->body,
        'created_at' => $notification->created_at->diffForHumans(),
        'sender_image' =>$notification->sender_image,
        ];

    event(new ReporterEventsNotification($data));

    }elseif($request->reporter !='No' && $request->reporter !='all'){
   $reporter_id=$request->reporter;
   $notification=['title'=>$request->title,'body'=>$request->body,'notificationable_id'=>
   $reporter_id,'notificationable_type'=>'App\Models\Reporter\Reporter','link'=>'reporter_notification',
   'sender_image'=>Auth::guard('admin')->user()->image];
   $create=Notification::create($notification);

    $notification=Notification::find($create->id);

    $reporter=Reporter::find($reporter_id);


        $notification->time=$notification->created_at->diffForHumans();
        $notification->admin_image=Auth::guard('admin')->user()->image;
        $reporter->notify((new ReporterNotification($notification)));
    }




    if($request->corporate=='all'){
   $corporates=Corporate::all();
   foreach($corporates as $corporate){
    $notification=['title'=>$request->title,'body'=>$request->body,'notificationable_id'=>
    $corporate->id,'notificationable_type'=>'App\Models\Corporate\Corporate','link'=>'corporate_notification',
    'sender_image'=>Auth::guard('admin')->user()->image];
    $create=Notification::create($notification);
    }
    $notification=Notification::orderBy('id','DESC')->first();
    $data =[
        'notification_id' =>$notification->id,
        'title'  =>$notification->title,
        'body'  =>$notification->body,
        'created_at' => $notification->created_at->diffForHumans(),
        'sender_image' =>$notification->sender_image,
        ];

    event(new CorporateEventsNotification($data));

    }elseif($request->corporate !='No' && $request->corporate !='all'){
   $corporate_id=$request->corporate;
   $notification=['title'=>$request->title,'body'=>$request->body,'notificationable_id'=>
   $corporate_id,'notificationable_type'=>'App\Models\Corporate\Corporate','link'=>'corporate_notification',
   'sender_image'=>Auth::guard('admin')->user()->image];
   $create=Notification::create($notification);


   $notification=Notification::find($create->id);

   $corporate=Corporate::find($corporate_id);


       $notification->time=$notification->created_at->diffForHumans();
       $notification->admin_image=Auth::guard('admin')->user()->image;
       $corporate->notify((new CorporateNotification($notification)));

    }

    if($request->corporate=='No' && $request->reporter=='No'){
        return response()->json(['status' => 'failed']);

    }


if(isset($create)){
    return response()->json(['status' => 'success'], 200);


}else{
  return response()->json(['status' => 'failed']);

}




} catch (\Exception $ex) {

return back()->with('error',  'something went wrong');

}



}



public function delete_notification($request)

{
    try{
        $id = $request->id;
        $check = Notification::find($id);
        if($check){
        $data=$check;

        Notification::where('id',$data->id)->delete();
        Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
        'activity'=>'deleted Notification ','description_activity'=>"deleted Notification"]);
        $admin=Admin::find(Auth::guard('admin')->user()->id);
        $count=$admin->notificationable()->where('read',0)->orderBy('id','desc')->count();
        $read_notifications=Notification::where('read',1)->orderBy('id','DESC')->count();
        $unread_notifications=Notification::where('read',0)->orderBy('id','desc')->count();
        $all_notifications=Notification::orderBy('id','desc')->count();

        return response()->json(['status' => 'success','count'=>$count,'read_count'=>$read_notifications,'unread_count'=>$unread_notifications,'all_count'=>$all_notifications], 200);


        }else{
        return response()->json(['status' => 'failed']);
        }

    } catch (\Exception $ex) {
       return back()->with('error',  'something went wrong');
    }
}



}
