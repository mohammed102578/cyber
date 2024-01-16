<?php

namespace App\Repository\admin;

use App\Interfaces\admin\DashboardInterface;
use App\Models\Admin\Admin;
use App\Models\Reporter\Report;
use App\Models\Corporate\Program;
use Illuminate\Support\Facades\DB;
use App\Models\Reporter\Point;
use Illuminate\Support\Facades\Auth;

Class  DashboardRepository implements DashboardInterface
{
    public function dashboard()
    {

       try{
          //return all report status in one query
          $reports = DB::table('reports')
          ->selectRaw('COUNT(CASE WHEN status_id = 3 THEN 1 END) as report_accept_count')
          ->selectRaw('COUNT(CASE WHEN status_id != 0 THEN 1 END) as reports_count')
          ->first();
            $report_accept_count = $reports->report_accept_count;
            $reports_count = $reports->reports_count;


             //return all reporter status in one query
           $reporters = DB::table('reporters')->where('deleted_at',null)
          ->selectRaw('COUNT(CASE WHEN status = 0 THEN 1 END) as reporter_count_active')
          ->selectRaw('COUNT(CASE WHEN status = 1 THEN 1 END) as reporter_count_block')
          ->selectRaw('COUNT(CASE WHEN status < 2 THEN 1 END) as reporter_count')
          ->first();
            $reporter_count_active = $reporters->reporter_count_active;
            $reporter_count_block = $reporters->reporter_count_block;
            $reporter_count = $reporters->reporter_count;


            //return all reporter status in one query
           $corporates = DB::table('corporates')->where('deleted_at',null)
           ->selectRaw('COUNT(CASE WHEN status = 0 THEN 1 END) as corporate_count_active')
           ->selectRaw('COUNT(CASE WHEN status = 1 THEN 1 END) as corporate_count_block')
           ->selectRaw('COUNT(CASE WHEN status < 2 THEN 1 END) as corporate_count')
           ->first();
             $corporate_count_active = $corporates->corporate_count_active;
             $corporate_count_block = $corporates->corporate_count_block;
             $corporate_count = $corporates->corporate_count;


      //program status chart

          $program=Program::where('submit',1)
          ->select('status_id',DB::raw("count(status_id) as count"))
          ->groupBy('status_id')->get()->toArray();

          $program_status=[];
          $program_status_count=[];
          for($i=0;$i<count($program);$i++){
            if($program[$i]['status_id']==1){
                $program_status[]="pending";
            }

            elseif($program[$i]['status_id']==2){
                $program_status[]="in_review";

            }

            elseif($program[$i]['status_id']==3){
                $program_status[]="accepted";

            }

            elseif($program[$i]['status_id']==4){
                $program_status[]="rejected";

            }

            $program_status_count[]=$program[$i]['count'];
          }

          //all program submited
          $program_count= array_sum($program_status_count);

      //report_status chart

          $report=Report::
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

      //top month have report
          $report=Report::where('status_id',3)->where('paid',1)->orderBy('created_at','ASC')
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

      //paid reporting rate



   //return all report paid or not paid in one query
   $reports = DB::table('reports')->where('status_id',3)
   ->selectRaw('COUNT(CASE WHEN paid = 1 THEN 1 END) as paid_report')
   ->selectRaw('COUNT(CASE WHEN paid = 0 THEN 1 END) as unpaid_report')
   ->first();
     $paid_report = $reports->paid_report;
     $unpaid_report = $reports->unpaid_report;



      //corporate transaction

        $corporate_invoices =DB::table('invoices')
        ->take('5')
        ->where('sender_type','corporate')->where('receiver_type','admin')
        ->orderBy('invoices.id', 'desc')
        ->leftJoin('corporates', 'corporates.id', '=', 'invoices.sender_id')
        ->select('invoices.id','invoices.total_amount','invoices.bank_name' ,'invoices.serial_no_type','invoices.currency','invoices.serial_no',
          'corporates.company_name','corporates.email','corporates.section',
        'corporates.image')
        ->get();

      //reporter transaction
        $reporter_invoices =DB::table('invoices')
        ->take('5')
        ->where('sender_type','admin')->where('receiver_type','reporter')
        ->orderBy('invoices.id', 'desc')
        ->leftJoin('reporters', 'reporters.id', '=', 'invoices.receiver_id')
        ->select('invoices.id','invoices.total_amount','invoices.bank_name','invoices.serial_no_type','invoices.currency','invoices.serial_no',
          'reporters.first_name','reporters.email','reporters.last_name',
        'reporters.image')
        ->get();

      //top four report
  //top five report

          $top_reporters=Point::with('reporter')
          ->select('reporter_id',DB::raw("sum(point) as sum"))
          ->take('5')
          ->orderBy('sum', 'desc')
          ->groupBy('reporter_id')->get();

          return view('content.admin.pages.general.dashboard',compact('reports_count','reporter_count_active','reporter_count_block','corporate_count_active','corporate_count_block',
          'corporate_count','reporter_count','program_count','report_accept_count','program_status','program_status_count',
          'report_status','report_status_count','report_month_count','report_months','paid_report','unpaid_report',
          'reporter_invoices','corporate_invoices','top_reporters'));
           }catch(\Exception $ex){
          return back()->with('error',  'something went wrong');
        }
    }
}
