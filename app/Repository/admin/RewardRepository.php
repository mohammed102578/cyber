<?php

namespace App\Repository\admin;


use App\Interfaces\admin\RewardInterface;
use App\Models\Invoice;
use App\Models\Reporter\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RewardRepository implements RewardInterface
{

//create vulnewrabilty



public function corporate_reward()


{


    try{
//corporate reward


      //paid reporting rate


    $reports = DB::table('reports')->where('status_id', 3)
    ->selectRaw('COUNT(CASE WHEN paid = 1 THEN 1 END) as paid')
    ->selectRaw('COUNT(CASE WHEN paid = 0 THEN 1 END) as unpaid')
    ->selectRaw('COUNT(CASE WHEN paid <= 2  THEN 1 END) as all_report')
    ->first();
    $corporate_paid_report = $reports->paid;
    $corporate_unpaid_report = $reports->unpaid;
    $all_corporate_reports = $reports->all_report;


      if ($all_corporate_reports == 0) {
        $all_corporate_reports = 1;
      }



      //top month have report
      $report = Invoice::where('receiver_type', 'admin')->orderBy('invoice_date', 'ASC')
        ->select(DB::raw("count(id) as count"), DB::raw('MONTH(invoice_date) month'))
        ->groupBy('month')->get()->toArray();

      $month = array_column($report, 'month');


      $corporate_report_months = array();

      foreach ($month as $report_month) {

        if ($report_month == 1) {
          $corporate_report_months[] = "january";
        }
        if ($report_month == 2) {
          $corporate_report_months[] = "February";
        }
        if ($report_month == 3) {
          $corporate_report_months[] = "March ";
        }
        if ($report_month == 4) {
          $corporate_report_months[] = "April ";
        }
        if ($report_month == 5) {
          $corporate_report_months[] = "May";
        }
        if ($report_month == 6) {
          $corporate_report_months[] = "June";
        }
        if ($report_month == 7) {
          $corporate_report_months[] = "July ";
        }
        if ($report_month == 8) {
          $corporate_report_months[] = "August";
        }
        if ($report_month == 9) {
          $corporate_report_months[] = "September";
        }
        if ($report_month == 10) {
          $corporate_report_months[] = "October";
        }
        if ($report_month == 11) {
          $corporate_report_months[] = "November ";
        }
        if ($report_month == 12) {
          $corporate_report_months[] = "December ";
        }
      }

      $corporate_report_month_count = [];
      for ($i = 0; $i < count($report); $i++) {

        $corporate_report_month_count[] = $report[$i]['count'];
      }

      //reward overview
      $corporate_rewards = Invoice::where('receiver_type', 'admin')->selectRaw('COUNT(*) as count, SUM(total_amount) as amount, AVG(total_amount) as average, MAX(total_amount) as highest')->first();
      $corporate_rewards_count = $corporate_rewards->count;
      $corporate_rewards_amount = number_format($corporate_rewards->amount, 0, '.', ',');
      $corporate_average_reward = number_format($corporate_rewards->average, 0, '.', ',');
      $corporate_highest_reward = number_format($corporate_rewards->highest, 0, '.', ',');











$corporate_rewards = DB::table('reports')
      ->where('reports.status_id', 3)
      ->where('reports.paid', 1)
      ->orderBy('reports.id', 'desc')
      ->leftJoin('programs', 'programs.id', '=', 'reports.program_id')
      ->leftJoin('corporates', 'corporates.id', '=', 'programs.corporate_id')
      ->rightJoin('invoices', function($join) {
          $join->on('invoices.report_id', '=', 'reports.id')
               ->where('invoices.receiver_type', '=', 'admin');
      })
      ->select('reports.target','reports.vulnerability','reports.url_vulnerability', 'corporates.company_name',
      'invoices.invoice_date','invoices.total_amount','invoices.receiver_type','invoices.invoice_number')
      ->get();


//=========================end corporate reward==========================================================











      return view('content.admin.pages.corporate.corporate_reward',
      compact('corporate_rewards_count','corporate_rewards', 'corporate_rewards_amount', 'corporate_average_reward', 'corporate_highest_reward', 'corporate_report_months', 'corporate_report_month_count', 'all_corporate_reports', 'corporate_unpaid_report', 'corporate_paid_report'));


    } catch (\Exception $ex) {
        return back()->with('error',  'something went wrong');
    }

}






public function reporter_reward()
{



      //paid reporting rate
   //return all report status in one query
          $reports = DB::table('reports')->where('status_id', 3)
          ->selectRaw('COUNT(CASE WHEN paid = 1 THEN 1 END) as paid')
          ->selectRaw('COUNT(CASE WHEN paid = 0 THEN 1 END) as unpaid')
          ->selectRaw('COUNT(CASE WHEN paid <= 2  THEN 1 END) as all_report')
          ->first();
          $reporter_paid_report = $reports->paid;
          $reporter_unpaid_report = $reports->unpaid;
          $all_reporter_reports = $reports->all_report;
        if($all_reporter_reports==0){
          $all_reporter_reports=1;
        }
      //top month have report
      $report = Invoice::where('sender_type', 'admin')->orderBy('invoice_date', 'ASC')
        ->select(DB::raw("count(id) as count"), DB::raw('MONTH(invoice_date) month'))
        ->groupBy('month')->get()->toArray();

      $month = array_column($report, 'month');


      $reporter_report_months = array();

      foreach ($month as $report_month) {

        if ($report_month == 1) {
          $reporter_report_months[] = "january";
        }
        if ($report_month == 2) {
          $reporter_report_months[] = "February";
        }
        if ($report_month == 3) {
          $reporter_report_months[] = "March ";
        }
        if ($report_month == 4) {
          $reporter_report_months[] = "April ";
        }
        if ($report_month == 5) {
          $reporter_report_months[] = "May";
        }
        if ($report_month == 6) {
          $reporter_report_months[] = "June";
        }
        if ($report_month == 7) {
          $reporter_report_months[] = "July ";
        }
        if ($report_month == 8) {
          $reporter_report_months[] = "August";
        }
        if ($report_month == 9) {
          $reporter_report_months[] = "September";
        }
        if ($report_month == 10) {
          $reporter_report_months[] = "October";
        }
        if ($report_month == 11) {
          $reporter_report_months[] = "November ";
        }
        if ($report_month == 12) {
          $reporter_report_months[] = "December ";
        }
      }

      $reporter_report_month_count = [];
      for ($i = 0; $i < count($report); $i++) {

        $reporter_report_month_count[] = $report[$i]['count'];
      }
      //tab active

      //reward overview



    $reporter_rewards = Invoice::where('sender_type', 'admin')->selectRaw('COUNT(*) as count, SUM(total_amount) as amount, AVG(total_amount) as average, MAX(total_amount) as highest')->first();
    $reporter_rewards_count = $reporter_rewards->count;
    $reporter_rewards_amount = number_format($reporter_rewards->amount, 0, '.', ',');
    $reporter_average_reward = number_format($reporter_rewards->average, 0, '.', ',');
    $reporter_highest_reward = number_format($reporter_rewards->highest, 0, '.', ',');




      $reporter_rewards =DB::table('reports')
      ->where('reports.status_id', 3)->where('reports.paid', 1)
      ->orderBy('reports.id', 'desc')
      ->leftJoin('programs', 'programs.id', '=', 'reports.program_id')
      ->leftJoin('corporates', 'corporates.id', '=', 'programs.corporate_id')
      ->leftJoin('reporters', 'reporters.id', '=', 'reports.reporter_id')
      ->rightJoin('invoices', function($join) {
        $join->on('invoices.report_id', '=', 'reports.id')
             ->where('invoices.receiver_type', '=', 'reporter');
    })      ->select('reports.target','reports.vulnerability','reports.url_vulnerability', 'reporters.first_name','reporters.email','reporters.last_name','corporates.company_name',
      'invoices.invoice_date','invoices.total_amount','invoices.currency','invoices.invoice_number',)
      ->get();





    return view('content.admin.pages.reporter.reporter_reward',
    compact('reporter_rewards', 'reporter_rewards_count', 'reporter_rewards_amount', 'reporter_average_reward', 'reporter_highest_reward', 'reporter_report_months', 'reporter_report_month_count', 'all_reporter_reports', 'reporter_unpaid_report', 'reporter_paid_report'
  ));
  try{
} catch (\Exception $ex) {
    return back()->with('error',  'something went wrong');
}
}






}
