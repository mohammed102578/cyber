<?php

namespace App\Repository\web;

use App\Interfaces\web\HacktivityInterface;
use App\Models\Reporter\Report;
use App\Models\Reporter\ReportStatus;

class HacktivityRepository implements HacktivityInterface
{
    public $program_type;

    public function hacktivity($request)
    {
        try {
        //this excute post route
        if ($request->has('status')) {

            $request->all();
            $this->program_type = $request->program_type;
            $paid = $request->paid;
            $severity = $request->severity;
            $status = $request->status;

            if ($this->program_type == 0 && $paid == 2 && $severity == 0 && $status == 0) {
                $hacktivities = Report::with('program.corporate')->with('program.program_update')->with('reporter')->where('hacktivity', 1)->orderBy('updated_at', 'DESC')->get();

            } elseif ($this->program_type != 0 || $paid != 2 || $severity != 0 || $status != 0) {

                if ($this->program_type == 0) {
                    $this->program_type = [1, 2, 3];
                } else {
                    $this->program_type = [$this->program_type];
                }

                if ($paid == 2) {
                    $paid = [1, 0];
                } else {
                    $paid = [$paid];

                }
                if ($status == 0) {
                    $status = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
                } else {
                    $status = [$status];
                }


                if ($severity == 0) {
                    $hacktivities = Report::with('program.corporate')->with('program.program_update')->with('reporter')->where('hacktivity', 1)
                        ->whereHas('program', function ($query) {
                            $query->whereIn('program_type', $this->program_type);
                        })
                        ->whereIn('status_id', $status)
                        ->whereIn('paid', $paid)
                        ->orderBy('updated_at', 'DESC')->get();

                } else {
                    $hacktivities = Report::with('program.corporate')->with('program.program_update')->with('reporter')->where('hacktivity', 1)
                        ->whereHas('program', function ($query) {
                            $query->whereIn('program_type', $this->program_type);
                        })
                        ->whereIn('status_id', $status)
                        ->whereIn('paid', $paid)
                        ->where('vulnerability', 'LIKE', "%$severity%")
                        ->orderBy('updated_at', 'DESC')->get();

                }


            }
            $opens = ReportStatus::whereIn('id', [1, 2])->get();
            $accepteds = ReportStatus::whereIn('id', [3, 4, 5])->get();
            $rejecteds = ReportStatus::whereIn('id', [6, 7, 8, 9, 10])->get();
            return view('content.web.pages.reporter.hacktivity', compact('hacktivities', 'accepteds', 'opens', 'rejecteds'));


        } else {
        //this excute get route

            //use in selected
            $opens = ReportStatus::whereIn('id', [1, 2])->get();
            $accepteds = ReportStatus::whereIn('id', [3, 4, 5])->get();
            $rejecteds = ReportStatus::whereIn('id', [6, 7, 8, 9, 10])->get();

            //hactivity
              $hacktivities = Report::with('program.corporate')->with('program.program_update')->with('reporter')->where('hacktivity', 1)->orderBy('updated_at', 'DESC')->take(20)->get();


            return view('content.web.pages.reporter.hacktivity', compact('hacktivities', 'accepteds', 'opens', 'rejecteds'));

        }

        } catch (\Exception $ex) {

            return back()->with('error', 'something went wrong');

        }
    }



    //returnloadmore

public function loadMoreData($request)
{
      //hacktivity
    $hacktivities = Report::with('program.corporate')->with('program.program_update')->with('reporter')->where('hacktivity', 1)->orderBy('updated_at', 'DESC')->get();



      $count= count($hacktivities);

      //this mean access to the last item
      if($request->offset > $count-1){
        return response()->json(['status' => 'failed']);
      }
       $hacktivities=$hacktivities->skip($request->offset)->take(1);

       $hacktivities=$hacktivities[$request->offset];
       if($hacktivities->program->image !=null){
        $hacktivities->image=$hacktivities->program->image ;
       }else{
        $hacktivities->image= $hacktivities->program->corporate->image ;
       }
        $hacktivities->title= explode('/', $hacktivities->vulnerability)[0];
        $hacktivities->program_id = $hacktivities->program->id ;
        $hacktivities->company_name = $hacktivities->program->corporate->company_name ;
        $hacktivities->first_name =$hacktivities->reporter->first_name;



    if(str_contains($hacktivities->vulnerability, '(CRITICAL)')){
            $hacktivities->severity ="CRITICAL";

    }elseif(str_contains($hacktivities->vulnerability, '(HIGH)')){
        $hacktivities->severity ="HIGH";

    }elseif(str_contains($hacktivities->vulnerability, '(MEDIUM)')){
        $hacktivities->severity ="MEDIUM";

    }elseif(str_contains($hacktivities->vulnerability, '(LOW)')){
        $hacktivities->severity ="LOW";

       }elseif(str_contains($hacktivities->vulnerability, '(INFORMATION)')){
        $hacktivities->severity ="INFORMATION";

       }


       if($hacktivities->program->program_update->last() != null){
        $hacktivities->program_update= "Updated ".$hacktivities->program->program_update->last()->created_at->diffForHumans() ;
    }else{
        $hacktivities->program_update="update found";
    }



     $hacktivities;
    return response()->json($hacktivities);
}


}
