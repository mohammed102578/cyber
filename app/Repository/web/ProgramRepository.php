<?php

namespace App\Repository\web;

use App\Interfaces\web\ProgramInterface;
use App\Models\Corporate\Corporate;
use App\Models\Corporate\Program;
use App\Models\Corporate\Target;
use App\Models\Reporter\Report;
use Illuminate\Support\Facades\DB;

class ProgramRepository implements ProgramInterface
{

    //return all public program and sem_private program need to request to join and private program
public function program()
{

  try{

    $programs= DB::table('programs')
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




        //count all program before proccess loadmore
        $count_all_program=count($programs);
        //Returns only ten rows and the rest of the rows return loadmore
        $programs= $programs->take(30);


        return view('content.web.pages.programs',compact('programs'));
      } catch (\Exception $ex) {

    return back()->with('error', 'something went wrong');

    }
}

//returnloadmore

public function loadMoreData($request)
{
      //all programs show in reporter to submit or request to joined program
      $programs= Program::where('submit',1)->where('status_id','=',3)->where('program_type','!=',3)->orderBy('id','DESC')->get();



      $count= count($programs);

      //this mean access to the last item
      if($request->offset > $count-1){
        return response()->json(['status' => 'failed']);
      }
       $programs=$programs->skip($request->offset)->take(1);

       $programs=$programs[$request->offset];

      $corporate=Corporate::find($programs->corporate_id);
      $accept_report=Report::where('program_id',$programs->id)->where('status_id',3)->count();
      $rewarded_report=Report::where('program_id',$programs->id)->where('status_id',3)->where('paid',1)->count();

      $company_name=$corporate->company_name;
      $company_image=$corporate->image;
      $programs->company_name=$company_name;
      $programs->company_image=$company_image;
      $programs->accept_report=$accept_report;
      $programs->rewarded_report=$rewarded_report;
      $programs->description_en=substr($programs->description_en,0,200)." ...";


    return response()->json($programs);
}


//program show page using ajax

public function show($request)
{
  try {

        $check = Program::find($request->id);

        if ($check) {

            $program = Program::where('id', $request->id)
            ->with('corporate')->
            with('program_update',function($query){

                return $query->orderBy('created_at','DESC')->get();
            })->with('priority')->with('reporter_blocking')
            ->with('reporter_private')
            ->with('reporter_semi_private')
            ->with('report',
            function ($query) {
                return $query->where('status_id', 3)->get();
            })
            ->first();

                       //program_brife
          $targets = Target::where('program_id', $request->id)->with('type_target')->get();


          //hactivity
          $hacktivities = Report::with('program')->with('reporter')->where('hacktivity', 1)->where('program_id', $request->id)->orderBy('updated_at', 'DESC')->get();


          //hall_of_fame
            $hall_of_fames =Report::with('reporter')
          -> where('status_id',3)
          ->where('program_id', $request->id)
          ->select('reporter_id',DB::raw("count(status_id) as count"))
          ->take('10')
          ->orderBy('count', 'desc')
          ->groupBy('reporter_id')->get();

         $program_report_count=Report::
          where('status_id',3)
         ->where('program_id', $request->id)->count();







            return view('content.web.pages.program', compact(['program_report_count','hall_of_fames','hacktivities','program', 'targets']));
        } else {

            return redirect()->back()->with('error', "something went wrong.");
        }
           } catch (\Exception $ex) {

        return back()->with('error', 'something went wrong');
    }

}


}
