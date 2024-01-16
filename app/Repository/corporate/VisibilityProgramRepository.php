<?php

namespace App\Repository\corporate;

use App\Http\Services\Notification;
use App\Interfaces\corporate\VisibilityProgramInterface;
use App\Models\Corporate\PrivateProgram;
use App\Models\Corporate\BlockingProgram;
use App\Models\Activity;
use App\Models\Corporate\Program;
use App\Models\Corporate\SemiPrivateProgram;
use App\Models\Reporter\Point;
use App\Models\Reporter\Report;
use App\Models\Reporter\ReportImage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



class VisibilityProgramRepository implements VisibilityProgramInterface
{
    public object $reporter_notification;
    public function __construct()
    {
        $this->reporter_notification = new Notification;

    }
//============get semi_private_program using Ajax

public function private_get($request,$id)
{
    try{
        if (
        $request->ajax()) {
        Session::put('id',$id);


        $data=Program::with('reporter_private')->where('id',$id)->first();
        $data=$data->reporter_private;
        $data=json_decode($data);

        return Datatables()->of($data)
        ->addIndexColumn()

        //created_at
        ->addColumn('created_at',function($data){

        $created_at=Carbon::parse($data->created_at)->format('Y-m-d');

        return $created_at;
        })
        ->rawColumns(['created_at'])
        //name
        ->addColumn('name',function($data){

        $name = $data->first_name." ".$data->last_name;

        return $name;
        })
        ->rawColumns(['name'])


        //action

        ->addColumn('action', function($data){
        $program_id=Session::get('id');

        $actionBtn = ' <div class="d-inline-block">
        <a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="bx bx-dots-vertical-rounded">

        </i></a>
        <div class="dropdown-menu dropdown-menu-end m-0">

        <a class=" hapus-modal_delete_private dropdown-item " reporter_id="'. $data->id.'" program_id="'.$program_id.'">
        <i class="bx bxs-trash" style="color:red"></i>

        Delete Reporter</a>
        </div>
        </div>
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







//============get semi_private_program using Ajax
public function semi_private_get($request,$id)
{
    try{
        if (
        $request->ajax()) {
        Session::put('id',$id);


        $data=Program::with('reporter_semi_private')->where('id',$id)->first()->reporter_semi_private;

        $data=json_decode($data);

        return Datatables()->of($data)
        ->addIndexColumn()
        //status
        ->addColumn('status', function($data){


        $status_code=SemiPrivateProgram::where('reporter_id',$data->id)->where('program_id',Session::get('id'))->first()->status;
        if($status_code == 1){
        $status = 'Accepted';


        }else{
        $status =  'pending';

        }
        return $status;
        })
        ->rawColumns(['status'])

        //name
        ->addColumn('name',function($data){

        $name = $data->first_name." ".$data->last_name;

        return $name;
        })
        ->rawColumns(['name'])

        //created_at
        ->addColumn('created_at',function($data){

        $created_at=Carbon::parse($data->created_at)->format('Y-m-d');

        return $created_at;
        })
        ->rawColumns(['created_at'])

        //action

        ->addColumn('action', function($data){
        $program_id=Session::get('id');

        $actionBtn = ' <div class="d-inline-block">
        <a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="bx bx-dots-vertical-rounded">

        </i></a>
        <div class="dropdown-menu dropdown-menu-end m-0">
        <a class=" hapus-modale_block dropdown-item " reporter_id="'. $data->id.'" program_id="'.$program_id.'" >
        <i class="bx bxs-edit" style="color:green"></i>Change Status</a>
        <div class="dropdown-divider">

        </div>

        <a class=" hapus-modal_delete_semi_private dropdown-item " class="text-danger" reporter_id="'. $data->id.'" program_id="'.$program_id.'">
        <i class="bx bxs-trash" style="color:red"></i>
        Delete Reporter</a>
        </div>
        </div>
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



//======================================get reporter blocking to submit program

public function blocking_get($request,$id)
{
    try{
        if (
        $request->ajax()) {
        Session::put('id',$id);


        $data=Program::with('reporter_blocking')->where('id',$id)->first()->reporter_blocking;

        $data=json_decode($data);

        return Datatables()->of($data)
        ->addIndexColumn()

        //created_at
        ->addColumn('created_at',function($data){

        $created_at=Carbon::parse($data->created_at)->format('Y-m-d');

        return $created_at;
        })
        ->rawColumns(['created_at'])
        //name
        ->addColumn('name',function($data){

        $name = $data->first_name." ".$data->last_name;

        return $name;
        })
        ->rawColumns(['name'])


        //action

        ->addColumn('action', function($data){
        $program_id=Session::get('id');

        $actionBtn = ' <div class="d-inline-block">
        <a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="bx bx-dots-vertical-rounded">

        </i></a>
        <div class="dropdown-menu dropdown-menu-end m-0">

        <a class=" hapus-modal_delete_blocking dropdown-item " class="text-danger" reporter_id="'. $data->id.'" program_id="'.$program_id.'">
        <i class="bx bxs-trash" style="color:red"></i>

        Delete Reporter</a>
        </div>
        </div>
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




//get all  program report
public function get_reports($request,$id)
{
    try{
$program=Program::find($id);
        if (
        $request->ajax()) {

        $data =DB::table('reports')
        ->where('program_id',$id)
        ->orderBy('reports.id', 'desc')
        ->leftJoin('programs', 'programs.id', '=', 'reports.program_id')
        ->leftJoin('reporters', 'reporters.id', '=', 'reports.reporter_id')
        ->leftJoin('report_statuses', 'report_statuses.id', '=', 'reports.status_id')

        ->select('reports.id','reports.target','reports.created_at','reports.vulnerability','reports.url_vulnerability', 'programs.management','programs.corporate_id','programs.image',
        'reporters.email','reporters.first_name','reporters.last_name','reporters.phone','report_statuses.status')
        ->get();

        return Datatables()->of($data)
        ->addIndexColumn()

        //image
        ->addColumn('image', function($data){

        if($data->image!= null){
        $image = $data->image;
        }else{
        $image=Auth::guard('corporate')->user()->image;


        }

        return $image;
        })
        ->rawColumns(['image'])

        //company_name


        ->addColumn('company_name', function($data){


        $company_name=Auth::guard('corporate')->user()->company_name;
        return $company_name;
        })
        ->rawColumns(['company_name'])

        //report status

        //report manged
        ->addColumn('management', function($data){
                $management=$data->management;
            return $management;
            })
            ->rawColumns(['management'])


        ->addColumn('status', function($data){


        $status=$data->status;
        return $status;
        })
        ->rawColumns(['status'])

        //name
        ->addColumn('name',function($data){

        $name = $data->first_name." ".$data->last_name;

        return $name;
        })
        ->rawColumns(['name'])


        //action

        ->addColumn('action', function($data){
        $actionBtn = ' <div class="d-inline-block">
        <a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="bx bx-dots-vertical-rounded">

        </i></a>
        <div class="dropdown-menu dropdown-menu-end m-0" >
        <a  style="color: #70859b;margin-left: 18px;text-decoration: none;" id="'. $data->id.'"href="show_report/'.$data->id.'"><i class="bx bx-show text-success"></i> Show Report</a>
        </a>
        <div id="change_status"><a class="change_status status-modal dropdown-item " id="'. $data->id.'"href=""data-bs-toggle="modal" data-bs-target="#report_edit_status"><i class="bx bxs-hourglass-top text-warning"></i>Change Status</a></div>
        </div>
        </div>
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





//show report  page

public function show_report($id)
{


    try{

        $check=Report::find($id);
        if($check){
        $report=Report::where('id',$check->id)->
        with('program',function($query){
        return $query->with('corporate')->first();
        })->with('reporter')->first();
        $images=ReportImage::where('report_id',$id)->get();
        return view('content.corporate.pages.report',compact(['report','images']));
        }else{
        return redirect()->back()->with('error', "Something Went Wrong.");

        }

    } catch (\Exception $ex) {

        return back()->with('error',  'something went wrong');

    }

}


//get report status using ajax
public function get_status($request)
{

    try{
        $id = $request->id;
        $check = Report::find($id);
        $data=$check->status_id;
        $report_id=$check->id;
        return response()->json(['data' => $data,'report_id' => $report_id]);
    } catch (\Exception $ex) {
        return back()->with('error',  'something went wrong');
    }

}






//update report status
public function status_report($request)


{
    DB::beginTransaction();
    try{
          $id = $request->id;
          $check = Report::find($id);
          if($check){
          $data=Report::where('id',$id)->update(['status_id' => $request->status]);
          Activity::create(['activeable_id'=>Auth::guard('corporate')->user()->id,'activeable_type'=>'App\Models\Corporate\Corporate',
          'activity'=>'Updated Report ','description_activity'=>" Updated Report Status "]);

         $status = Report::where('id',$id)->with('status')->first()->status->status;


          	// send notification
         $this->reporter_notification->
         sendReporterNotification('Report Status',Auth::guard('corporate')->user()->company_name.'  changed the status of the report to '.$status,
         'show_reporter_report',$id,$check->reporter_id,'corporate');


         //add point to reporter if report acceped
         if($request->status==3){

          if (str_contains($check->vulnerability, '(CRITICAL)')) {
            Point::updateOrCreate(
            ['report_id'=>$check->id],
            ['reporter_id'=>$check->reporter_id,
            'report_id'=>$check->id,
            'point'=>5,
            'vulnerability'=>$check->vulnerability ,
            ]);
            }elseif(str_contains($check->vulnerability, '(HIGH)')){
                Point::updateOrCreate(
                    ['report_id'=>$check->id],
                    ['reporter_id'=>$check->reporter_id,
                    'report_id'=>$check->id,
                    'point'=>4,
                    'vulnerability'=>$check->vulnerability ,
                    ]);

            }elseif(str_contains($check->vulnerability, '(MEDIUM)')){
                Point::updateOrCreate(
                    ['report_id'=>$check->id],
                    ['reporter_id'=>$check->reporter_id,
                    'report_id'=>$check->id,
                    'point'=>3,
                    'vulnerability'=>$check->vulnerability ,
                    ]);

            }elseif(str_contains($check->vulnerability, '(LOW)')){
                Point::updateOrCreate(
                    ['report_id'=>$check->id],
                    ['reporter_id'=>$check->reporter_id,
                    'report_id'=>$check->id,
                    'point'=>2,
                    'vulnerability'=>$check->vulnerability ,
                    ]);

            }elseif(str_contains($check->vulnerability, '(INFORMATION)')){
                Point::updateOrCreate(
                    ['report_id'=>$check->id],
                    ['reporter_id'=>$check->reporter_id,
                    'report_id'=>$check->id,
                    'point'=>1,
                    'vulnerability'=>$check->vulnerability ,
                    ]);

            }
        }else{
            Point::where('reporter_id',$check->reporter_id)->where('report_id',$check->id)->delete();
        }
        DB::commit();



          return response()->json(['status' => 'success'], 200);
          }else{
          return response()->json(['status' => 'failed']);
          }
      } catch (\Exception $ex) {
        DB::rollback();

          return back()->with('error',  'something went wrong');
      }


}




//status reporter semi_private

public function status($request)
{

    try{
        $program_id = $request->program_id;
        $reporter_id = $request->reporter_id;
        $check = SemiPrivateProgram::where('reporter_id',$reporter_id)->where('program_id',$program_id)->first();

        if($check){
        $data=$check;
        if($data->status == 1){
        $status=0;
        }else{
        $status=1;

        }
        SemiPrivateProgram::where('id',$data->id)->update(['status' => $status]);


                if($data->status == 1){
        //send notification
        $this->reporter_notification->
        sendReporterNotification('Join pending',Auth::guard('corporate')->user()->company_name.' suspended your joining the program',
        'reporter_program',$program_id,$reporter_id,'corporate');

                }else{
        //send notification
        $this->reporter_notification->
        sendReporterNotification('Accept to join',Auth::guard('corporate')->user()->company_name.' has accepted you to join the program',
        'reporter_program',$program_id,$reporter_id,'corporate');

                }


        Activity::create(['activeable_id'=>Auth::guard('corporate')->user()->id,'activeable_type'=>'App\Models\Corporate\Corporate',
        'activity'=>'Updated Program ','description_activity'=>"Updated Status semi_private_Program "]);

        return response()->json(['status' => 'success'], 200);


        }else{
        return response()->json(['status' => 'failed']);

        }

    } catch (\Exception $ex) {

       return back()->with('error',  'something went wrong');

    }
}








//store private_program to database

public function private_store($request)
{
    try{
        $messages = [
        'required' => trans('messages.field is required'),

        ];
        $validator = Validator::make($request->all(),[
        'program_id' =>'required',
        'reporter_id'=>'required',



        ],$messages);

        if ($validator->fails()) {

        return back()->with('error' ,'reporter feild is required');
        }

        $program_id= $request->program_id;
        $reporter_id= $request->reporter_id;
        $check=PrivateProgram::where('program_id',$program_id)->where('reporter_id',$reporter_id)->first();

        if($check){

        PrivateProgram::where('id',$check->id)->delete();

        PrivateProgram::create($request->all());

                    Activity::create(['activeable_id'=>Auth::guard('corporate')->user()->id,'activeable_type'=>'App\Models\Corporate\Corporate',
        'activity'=>'Added Reporter To Program ','description_activity'=>"Added Reporter To Private Program "]);

        return response()->json(['status' => 'success'], 200);

        }else{

        PrivateProgram::create($request->all());
                    Activity::create(['activeable_id'=>Auth::guard('corporate')->user()->id,'activeable_type'=>'App\Models\Corporate\Corporate',
        'activity'=>'Added Reporter To Program ','description_activity'=>"Added Reporter To Private Program "]);
        //send notification
        $this->reporter_notification->
        sendReporterNotification('request to join',Auth::guard('corporate')->user()->company_name.' invited you to join his program',
        'reporter_program',$program_id,$reporter_id,'corporate');

        return response()->json(['status' => 'success'], 200);
        }

    } catch (\Exception $ex) {

        return back()->with('error', 'something went wrong');

    }

}






//store blocking_program to database

public function blocking_store($request)
{

  try{
        $messages = [
        'required' => trans('messages.field is required'),

        ];
        $validator = Validator::make($request->all(),[
        'program_id' =>'required',
        'reporter_id'=>'required',

        ],$messages);

        if ($validator->fails()) {

        return back()->with('error' ,'reporter feild is required');
        }

        $program_id= $request->program_id;
        $reporter_id= $request->reporter_id;
        $check=BlockingProgram::where('program_id',$program_id)->where('reporter_id',$reporter_id)->first();

        if($check){

        BlockingProgram::where('id',$check->id)->delete();

        BlockingProgram::create($request->all());

                    Activity::create(['activeable_id'=>Auth::guard('corporate')->user()->id,'activeable_type'=>'App\Models\Corporate\Corporate',
        'activity'=>'Block Reporter','description_activity'=>"Blocked  Reporter To Send any report in to Program "]);
        return response()->json(['status' => 'success'], 200);

        }else{
        BlockingProgram::create($request->all());

                    Activity::create(['activeable_id'=>Auth::guard('corporate')->user()->id,'activeable_type'=>'App\Models\Corporate\Corporate',
        'activity'=>'Block Reporter','description_activity'=>"Blocked  Reporter To Send any report in to Program "]);
        return response()->json(['status' => 'success'], 200);
        }
    } catch (\Exception $ex) {

      return back()->with('error', 'something went wrong');

    }

}

//destroy semi_private_program from database

public function semi_private_delete($request){
   try {
        $program_id= $request->program_id;
        $reporter_id= $request->reporter_id;
        $check=SemiPrivateProgram::where('program_id',$program_id)->where('reporter_id',$reporter_id)->first();
        if($check){
        SemiPrivateProgram::where('id',$check->id)->delete();
                    Activity::create(['activeable_id'=>Auth::guard('corporate')->user()->id,'activeable_type'=>'App\Models\Corporate\Corporate',
        'activity'=>'Deleted Reporter From Program ','description_activity'=>"Deleted Reporter From semi_Private Program "]);
        return response()->json(['status' => 'success'], 200);
        }else{
        return response()->json(['status' => 'failed']);
        }

    } catch (\Exception $ex) {

        return back()->with('error',  'something went wrong');

    }
}




//destroy private_program from database

public function private_delete($request)
{

    try {
        $program_id= $request->program_id;
        $reporter_id= $request->reporter_id;

        $check=PrivateProgram::where('program_id',$program_id)->where('reporter_id',$reporter_id)->first();

        if($check){

        PrivateProgram::where('id',$check->id)->delete();

                    Activity::create(['activeable_id'=>Auth::guard('corporate')->user()->id,'activeable_type'=>'App\Models\Corporate\Corporate',
        'activity'=>'Deleted Reporter From Program ','description_activity'=>"Deleted Reporter From Private Program "]);


        return response()->json(['status' => 'success'], 200);


        }else{
        return response()->json(['status' => 'failed']);

        }

    } catch (\Exception $ex) {

    return back()->with('error',  'something went wrong');

    }
}




//destroy blocking_program from database

public function blocking_delete($request)
{

    try {
        $program_id= $request->program_id;
        $reporter_id= $request->reporter_id;

        $check=BlockingProgram::where('program_id',$program_id)->where('reporter_id',$reporter_id)->first();

        if($check){

        BlockingProgram::where('id',$check->id)->delete();

                    Activity::create(['activeable_id'=>Auth::guard('corporate')->user()->id,'activeable_type'=>'App\Models\Corporate\Corporate',
        'activity'=>'Deleted Reporter  ','description_activity'=>"Deleted Reporter From Black list "]);

        return response()->json(['status' => 'success'], 200);


        }else{
        return response()->json(['status' => 'failed']);

        }

    } catch (\Exception $ex) {

       return back()->with('error',  'something went wrong');

    }
}

}
