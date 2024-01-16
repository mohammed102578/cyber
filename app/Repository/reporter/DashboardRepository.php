<?php

namespace App\Repository\reporter;

use App\Interfaces\reporter\DashboardInterface;
use App\Models\Reporter\Report;
use App\Models\Corporate\PrivateProgram;
use App\Models\Corporate\Program;
use App\Models\Corporate\SemiPrivateProgram;
use App\Models\Invoice;
use App\Models\Reporter\Point;
use App\Models\Reporter\Reporter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardRepository implements DashboardInterface
{

  public function dashboard()
  {

        try{

    $report_accept_count=Report::where('status_id',3)->where('reporter_id',Auth::guard('reporter')->user()->id)->count();
    $private_program=PrivateProgram::where('reporter_id',Auth::guard('reporter')->user()->id)->count();
    $semi_private_program=SemiPrivateProgram::where('reporter_id',Auth::guard('reporter')->user()->id)->count();
    $program_count=Program::where('submit',1)->count();
    $reports_count=Report::where('reporter_id',Auth::guard('reporter')->user()->id)->count();
    $invoice_count=Invoice::where('sender_type','admin')->where('receiver_type','reporter')->where('receiver_id',Auth::guard('reporter')->user()->id)->count();




 //report_status chart

 $report=Report::
 where('reporter_id',Auth::guard('reporter')->user()->id)->
 select('status_id',DB::raw("count(status_id) as count"))
 ->groupBy('status_id')->get()->toArray();
 $report_status=[];
 $report_status_count=[];


 for($i=0;$i<count($report);$i++){
if($report[$i]['status_id']==1){
   $report_status[]="New";
}

elseif($report[$i]['status_id']==2){
   $report_status[]="Triaged";

}

elseif($report[$i]['status_id']==3){
   $report_status[]="Resolved";

}

elseif($report[$i]['status_id']==4){
   $report_status[]="UnResolved";

}

elseif($report[$i]['status_id']==5){
   $report_status[]="Duplicate";

}

elseif($report[$i]['status_id']==6){
   $report_status[]="Out Of Scope";

}

elseif($report[$i]['status_id']==7){
   $report_status[]="Not reproducible";

}

elseif($report[$i]['status_id']==8){
   $report_status[]="Wont fix";

}

elseif($report[$i]['status_id']==9){
   $report_status[]="Not Applicable";

}
elseif($report[$i]['status_id']==10){
   $report_status[]="Spam";

}
 $report_status_count[]=$report[$i]['count'];
 }

 $report_status_count;


//top month have report
 $report=Report::where('status_id',3)->where('reporter_id',Auth::guard('reporter')->user()->id)->where('paid',1)->orderBy('created_at','ASC')
->select(DB::raw("count(id) as count"),DB::raw('MONTH(created_at) month'))
->groupBy('month')->get()->toArray();

$month = array_column($report, 'month');


$report_months=array();

foreach($month as $report_month){

if($report_month==1){
$report_months[]="january";

}if($report_month==2){
 $report_months[]="February";

}if($report_month==3){
$report_months[]="March ";

}if($report_month==4){
 $report_months[]="April ";

}if($report_month==5){
 $report_months[]="May";
}if($report_month==6){
$report_months[]="June";

}if($report_month==7){
 $report_months[]="July ";

}if($report_month==8){
$report_months[]="August";

}if($report_month==9){
$report_months[]="September";

}if($report_month==10){
 $report_months[]="October";

}if($report_month==11){
$report_months[]="November ";

}if($report_month==12){
$report_months[]="December ";


}
}

$report_month_count=[];
for($i=0; $i<count($report);$i++ ){

  $report_month_count[]=$report[$i]['count'];

}

  //return all report paid or not paid in one query
  $reports = DB::table('reports')->where('status_id',3)
  ->where('reporter_id',Auth::guard('reporter')->user()->id)
  ->selectRaw('COUNT(CASE WHEN paid = 1 THEN 1 END) as paid_report')
  ->selectRaw('COUNT(CASE WHEN paid = 0 THEN 1 END) as unpaid_report')
  ->selectRaw('COUNT(CASE WHEN paid <= 2 THEN 1 END) as all_reporter_reports')
  ->first();
    $paid_report = $reports->paid_report;
    $unpaid_report = $reports->unpaid_report;
    $all_reporter_reports = $reports->all_reporter_reports;


    if($all_reporter_reports==0){
      $all_reporter_reports=1;
    }
 //reporter transaction

   $last_invoices =DB::table('invoices')
   ->take(5)
   ->where('sender_type','admin')->where('receiver_type','reporter')->where('receiver_id',Auth::guard('reporter')->user()->id)
   ->orderBy('invoices.id', 'desc')
   ->leftJoin('reporters', 'reporters.id', '=', 'invoices.receiver_id')
   ->select('invoices.id','invoices.total_amount','invoices.bank_name','invoices.serial_no_type','invoices.currency','invoices.serial_no',
    'reporters.first_name','reporters.email','reporters.last_name',
   'reporters.image')
   ->get();

//top five report

   $top_reporters=Point::with('reporter')
->select('reporter_id',DB::raw("sum(point) as sum"))
->take(5)
->orderBy('sum', 'desc')
->groupBy('reporter_id')->get();



 return view('content.reporter.pages.dashboard',compact('all_reporter_reports','reports_count','semi_private_program','private_program','report_accept_count','invoice_count','last_invoices',
 'report_status','report_status_count','report_month_count','report_months','paid_report','unpaid_report',
'top_reporters'));
 }catch (\Exception $ex) {

  return back()->with('error', 'something went wrong');

}
}
}
