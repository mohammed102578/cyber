<?php

namespace App\Repository\reporter;


use App\Interfaces\reporter\NotificationInterface;
use App\Models\Activity;
use App\Models\Notification;
use App\Models\Reporter\Reporter;
use Illuminate\Support\Facades\Auth;

class  NotificationRepository implements NotificationInterface
{



public function notification()

{
    try{
        $reporter=Reporter::find(Auth::guard('reporter')->user()->id);
        $read_notifications=$reporter->notificationable()->where('read',1)->orderBy('id','desc')->get();
        $unread_notifications=$reporter->notificationable()->where('read',0)->orderBy('id','desc')->get();
        $all_notifications=$reporter->notificationable()->orderBy('id','desc')->get();
        return view('content.reporter.pages.notification',compact(['unread_notifications','read_notifications','all_notifications']));
         }catch(\Exception $ex){
    return redirect()->back()->with('error','something went wrong');
    }
}


public function get_read_notifications($request)
{
    try{
        if (
        $request->ajax()) {
            $reporter=Reporter::find(Auth::guard('reporter')->user()->id);

            $data=$reporter->notificationable()->where('read',1)->orderBy('id','desc')->get();

        return Datatables()->of($data)
        ->addIndexColumn()
        //notification_created
        ->addColumn('created', function($data){

            return $created_at=$data->created_at->format('M d Y  H:i') ;
            })

        ->rawColumns(['created'])

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
            $reporter=Reporter::find(Auth::guard('reporter')->user()->id);

            $data=$reporter->notificationable()->where('read',0)->orderBy('id','desc')->get();

        return Datatables()->of($data)
        ->addIndexColumn()
        //notification_created
        ->addColumn('created', function($data){

            return $created_at=$data->created_at->format('M d Y  H:i') ;
            })

        ->rawColumns(['created'])

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
            $reporter=Reporter::find(Auth::guard('reporter')->user()->id);

            $data=$reporter->notificationable()->orderBy('id','desc')->get();

        return Datatables()->of($data)
        ->addIndexColumn()
        //notification_created
        ->addColumn('created', function($data){

            return $created_at=$data->created_at->format('M d Y  H:i') ;
            })

        ->rawColumns(['created'])

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

        Activity::create(['activeable_id'=>Auth::guard('reporter')->user()->id,'activeable_type'=>'App\Models\Corporate\Corporate',
        'activity'=>'Updated Notification Status','description_activity'=>"updated Notification Status To read"]);



        $reporter=Reporter::find(Auth::guard('reporter')->user()->id);
        $read=$reporter->notificationable()->where('read',1)->orderBy('id','desc')->count();
        $unread=$reporter->notificationable()->where('read',0)->orderBy('id','desc')->count();
        $all=$reporter->notificationable()->orderBy('id','desc')->count();

        return response()->json(['status' => 'success','count'=>$unread,'read_count'=>$read,'unread_count'=>$unread,'all_count'=>$all], 200);



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
        Activity::create(['activeable_id'=>Auth::guard('reporter')->user()->id,'activeable_type'=>'App\Models\Corporate\Corporate',
        'activity'=>'deleted Notification ','description_activity'=>"deleted Notification"]);
        $reporter=Reporter::find(Auth::guard('reporter')->user()->id);
        $read=$reporter->notificationable()->where('read',1)->orderBy('id','desc')->count();
        $unread=$reporter->notificationable()->where('read',0)->orderBy('id','desc')->count();
        $all=$reporter->notificationable()->orderBy('id','desc')->count();

        return response()->json(['status' => 'success','count'=>$unread,'read_count'=>$read,'unread_count'=>$unread,'all_count'=>$all], 200);

        }else{
        return response()->json(['status' => 'failed']);
        }

    } catch (\Exception $ex) {
       return back()->with('error',  'something went wrong');
    }
}



}
