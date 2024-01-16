<?php

namespace App\Repository\reporter;

use App\Http\Services\Notification;
use App\Interfaces\reporter\ProgramInterface;
use App\Models\Corporate\Corporate;
use App\Models\Corporate\Program;
use App\Models\Corporate\SemiPrivateProgram;
use App\Models\Corporate\Target;
use App\Models\Reporter\Report;
use App\Models\Reporter\Reporter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session as FacadesSession;
use Illuminate\Support\Facades\Validator;



class ProgramRepository implements ProgramInterface
{

   public object $corporate_notification;
    public function __construct()
    {
        $this->corporate_notification = new Notification;

    }
  //return all public program and sem_private program need to request to join and private program
public function index()
{

    try{
        //start private program to submit report
        $private=DB::table('private_programs')
        ->where('private_programs.reporter_id',Auth::guard('reporter')->user()->id)
        ->leftJoin('programs','programs.id','private_programs.program_id')
        ->leftJoin('corporates','corporates.id','=','programs.corporate_id')
        ->leftJoin('reports', function($join) {
            $join->on('programs.id', '=', 'reports.program_id')
                 ->where('reports.status_id', '=', 3);
        })
        ->select('programs.id','programs.image','programs.program_type','programs.description_en','programs.management','corporates.company_name','corporates.image as company_image',
            DB::raw('COUNT(reports.id) AS accept_report'), DB::raw('COUNT(CASE WHEN reports.paid = 1 THEN reports.id END) AS rewarded_report'))
        ->groupBy('programs.id','programs.image','programs.program_type','programs.description_en','programs.management','corporates.company_name','corporates.image')
        ->get();


        //end private program to submit report


        //start joined reporter to program
        $joined=DB::table('semi_private_programs')
        ->where('semi_private_programs.reporter_id',Auth::guard('reporter')->user()->id)
        ->where('semi_private_programs.status',1)
        ->leftJoin('programs','programs.id','semi_private_programs.program_id')
        ->leftJoin('corporates','corporates.id','=','programs.corporate_id')
        ->leftJoin('reports', function($join) {
            $join->on('programs.id', '=', 'reports.program_id')
                 ->where('reports.status_id', '=', 3);
        })
        ->select('programs.id','programs.image','programs.program_type','programs.description_en','programs.management','corporates.company_name','corporates.image as company_image',
            DB::raw('COUNT(reports.id) AS accept_report'), DB::raw('COUNT(CASE WHEN reports.paid = 1 THEN reports.id END) AS rewarded_report'))
        ->groupBy('programs.id','programs.image','programs.program_type','programs.description_en','programs.management','corporates.company_name','corporates.image')
        ->get();

        //end joined reporter to program


        $check_reporter_in_semi_private=SemiPrivateProgram::where('reporter_id',Auth::guard('reporter')->user()->id)->select('program_id')->get();
        $request_to_joined=SemiPrivateProgram::where('reporter_id',Auth::guard('reporter')->user()->id)->get();

        $reporter_in_semi_private=[];
        foreach($check_reporter_in_semi_private as $join){

        $reporter_in_semi_private[]=$join->program_id;
        }

        $check_reporter_in_semi_private= $reporter_in_semi_private;

        //all programs show in reporter to submit or request to joined program


        $public= DB::table('programs')
        ->where('programs.submit', 1)
        ->where('programs.status_id', '=', 3)
        ->where('programs.program_type', '!=', 3)
        ->leftJoin('corporates','corporates.id','=','programs.corporate_id')
        ->leftJoin('reports', function($join) {
            $join->on('programs.id', '=', 'reports.program_id')
                 ->where('reports.status_id', '=', 3);
        })
        ->select('programs.id','programs.image','programs.program_type','programs.description_en','programs.management','corporates.company_name','corporates.image as company_image',
            DB::raw('COUNT(reports.id) AS accept_report'), DB::raw('COUNT(CASE WHEN reports.paid = 1 THEN reports.id END) AS rewarded_report'))
        ->groupBy('programs.id','programs.image','programs.program_type','programs.description_en','programs.management','corporates.company_name','corporates.image')
       ->orderBy('programs.id','DESC')
        ->get();



       //merge_to_object semi_private and  public in one object all

        //count all program before proccess loadmore
        $count_all_program=count($public);
        //Returns only ten rows and the rest of the rows return loadmore
        $all= $public->take(10);
        // $all= $merge_program->skip(5)->take(10);





        return view('content.reporter.pages.programs',compact('count_all_program','all','private','joined','request_to_joined','check_reporter_in_semi_private'));
           } catch (\Exception $ex) {

    return back()->with('error', 'something went wrong');

    }
}

//returnloadmore

public function loadMoreData($request)
{
        //all programs show in reporter to submit or request to joined program

        $public= DB::table('programs')
        ->where('programs.submit', 1)
        ->where('programs.status_id', '=', 3)
        ->where('programs.program_type', '!=', 3)
        ->leftJoin('corporates','corporates.id','=','programs.corporate_id')
        ->leftJoin('reports', function($join) {
            $join->on('programs.id', '=', 'reports.program_id')
                 ->where('reports.status_id', '=', 3);
        })
        ->select('programs.id','programs.image','programs.program_type','programs.description_en','programs.management','corporates.company_name','corporates.image as company_image',
            DB::raw('COUNT(reports.id) AS accept_report'), DB::raw('COUNT(CASE WHEN reports.paid = 1 THEN reports.id END) AS rewarded_report'))
        ->groupBy('programs.id','programs.image','programs.program_type','programs.description_en','programs.management','corporates.company_name','corporates.image')
        ->get();



      $count= count($public);
      //Returns only ten rows and the rest of the rows return loadmore
      //$all= $merge_program->take(10);

      if($request->offset >$count-1){
        return response()->json(['status' => 'failed']);
      }
       $all= $public->skip($request->offset)->take(1);

       $all=$all[$request->offset];
      $all->description_en=substr($all->description_en,0,250)." ...";


    return response()->json($all);
}
    //show one program by details
    public function show($id)
    {
        try {
            $check = Program::where('id',$id)->where('status_id',3)->first();

            if ($check && $check->submit == 1) {

                $SemiPrivateProgram = SemiPrivateProgram::where('reporter_id', Auth::guard('reporter')->user()->id)->select('program_id')->get();
                $request_to_joined = SemiPrivateProgram::where('reporter_id', Auth::guard('reporter')->user()->id)->get();



                $array = [];
                foreach ($SemiPrivateProgram as $join) {

                    $array[] = $join->program_id;
                }

                $check_in_array = $array;
                $program = Program::where('id', $id)->with('corporate')->with('program_update',function($query){

                    return $query->orderBy('created_at','DESC')->get();
                })->with('priority')->with('reporter_blocking')->with('reporter_private')
                ->with('reporter_semi_private')
                ->with('report',
                function ($query) {
                    return $query->where('status_id', 3)->get();
                })
                ->first();

                           //program_brife
              $targets = Target::where('program_id', $id)->with('type_target')->get();


              //hactivity
              $hacktivities = Report::with('program')->with('reporter')->where('hacktivity', 1)->where('program_id', $id)->orderBy('updated_at', 'DESC')->get();


              //hall_of_fame
                $hall_of_fames =Report::with('reporter')
              -> where('status_id',3)
              ->where('program_id', $id)
              ->select('reporter_id',DB::raw("count(status_id) as count"))
              ->take(10)
              ->orderBy('count', 'desc')
              ->groupBy('reporter_id')->get();

             $program_report_count=Report::
              where('status_id',3)
             ->where('program_id', $id)->count();



              //connection
             $connection= Corporate::find($check->corporate_id);
             $connections= $connection->connectionable()->get();



                return view('content.reporter.pages.program', compact(['connections','program_report_count','hall_of_fames','hacktivities','program', 'targets', 'check_in_array', 'request_to_joined']));
            } else {

                return redirect()->back()->with('error', "something went wrong.");
            }
        } catch (\Exception $ex) {

            return back()->with('error', 'something went wrong');
        }
    }

    public function request_join($request)
    {

        try {
            $messages = [
                'required' => 'field is required',

            ];
            $validator = Validator::make($request->all(), [
                'program_id' => 'required',



            ], $messages);

            if ($validator->fails()) {

                FacadesSession::flash('errors', $validator->errors());
                return back()->with('error', 'reporter feild is required');
            }

            $check = Program::find($request->program_id);

            if ($check && $check->submit == 1) {

                if ($request->submit != null) {
                    SemiPrivateProgram::create(['program_id' => $check->id, 'reporter_id' => Auth::guard('reporter')->user()->id]);

                   //send notification
                $this->corporate_notification->
                sendCorporateNotification('Request To Joined',Auth::guard('reporter')->user()->first_name.' Request To Joined Program',
                'setting_program',$check->id,$check->corporate_id,'reporter');

                return redirect()->back()->with('success', "The request to join was successful .");

                } else {
                    SemiPrivateProgram::where('program_id', $check->id)->where('reporter_id', Auth::guard('reporter')->user()->id)->delete();

                    //send notification
                $this->corporate_notification->
                sendCorporateNotification('Cancel the joining request',Auth::guard('reporter')->user()->first_name.' canceled the application to join the program',
                'setting_program',$check->id,$check->corporate_id,'reporter');


                    return redirect()->back()->with('success', "The request to join has been canceled successfully .");
                }
            } else {

                return redirect()->back()->with('error', "something went wrong.");
            }
        } catch (\Exception $ex) {

            return back()->with('error', 'something went wrong');
        }
    }
}
