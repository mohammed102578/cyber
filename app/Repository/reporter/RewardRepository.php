<?php

namespace App\Repository\reporter;


use App\Interfaces\reporter\RewardInterface;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RewardRepository implements RewardInterface
{
  public function reward($request)
  {
try{
    if (!empty($request->from)) {

//paid reporting rate
$from=$request->from;
$to=$request->to;


     //paid reporting rate
   //return all report status in one query
   $reports = DB::table('reports')
   ->whereBetween('created_at', [$from, $to])
   ->where('reporter_id', Auth::guard('reporter')->user()->id)->where('status_id', 3)
   ->selectRaw('COUNT(CASE WHEN paid = 1 THEN 1 END) as paid')
   ->selectRaw('COUNT(CASE WHEN paid = 0 THEN 1 END) as unpaid')
   ->selectRaw('COUNT(CASE WHEN paid <= 2  THEN 1 END) as all_report')
   ->first();
   $paid_report = $reports->paid;
   $unpaid_report = $reports->unpaid;
   $all_reporter_reports = $reports->all_report;
   if($all_reporter_reports==0){
    $all_reporter_reports=1;
  }

//top month have report
$report = Invoice::where('receiver_id', Auth::guard('reporter')->user()->id)
->whereBetween('created_at', [$from, $to])->where('receiver_type', 'reporter')->orderBy('invoice_date', 'ASC')
->select(DB::raw("count(id) as count"), DB::raw('MONTH(invoice_date) month'))
->groupBy('month')->get()->toArray();

$month = array_column($report, 'month');


$report_months = array();

foreach ($month as $report_month) {

if ($report_month == 1) {
  $report_months[] = "january";
}
if ($report_month == 2) {
  $report_months[] = "February";
}
if ($report_month == 3) {
  $report_months[] = "March ";
}
if ($report_month == 4) {
  $report_months[] = "April ";
}
if ($report_month == 5) {
  $report_months[] = "May";
}
if ($report_month == 6) {
  $report_months[] = "June";
}
if ($report_month == 7) {
  $report_months[] = "July ";
}
if ($report_month == 8) {
  $report_months[] = "August";
}
if ($report_month == 9) {
  $report_months[] = "September";
}
if ($report_month == 10) {
  $report_months[] = "October";
}
if ($report_month == 11) {
  $report_months[] = "November ";
}
if ($report_month == 12) {
  $report_months[] = "December ";
}
}

$report_month_count = [];
for ($i = 0; $i < count($report); $i++) {

$report_month_count[] = $report[$i]['count'];
}


    //reward overview

    $rewards = Invoice::whereBetween('created_at', [$from, $to])->where('receiver_id', Auth::guard('reporter')->user()->id)->where('receiver_type', 'reporter')->selectRaw('COUNT(*) as count, SUM(total_amount) as amount, AVG(total_amount) as average, MAX(total_amount) as highest')->first();
    $rewards_count = $rewards->count;
    $rewards_amount = number_format($rewards->amount, 0, '.', ',');
    $average_reward = number_format($rewards->average, 0, '.', ',');
    $highest_reward = number_format($rewards->highest, 0, '.', ',');



$reporter_rewards =DB::table('reports')
->whereBetween('reports.created_at', [$from, $to])
->where('reports.reporter_id', Auth::guard('reporter')->user()->id)
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


      $active = "time";

      return view('content.reporter.pages.reward', compact('reporter_rewards', 'rewards_count', 'rewards_amount', 'average_reward', 'highest_reward', 'report_months', 'report_month_count', 'all_reporter_reports', 'unpaid_report', 'paid_report', 'active'));
    } else {


     //paid reporting rate
   //return all report status in one query
   $reports = DB::table('reports')
   ->where('reporter_id', Auth::guard('reporter')->user()->id)->where('status_id', 3)
   ->selectRaw('COUNT(CASE WHEN paid = 1 THEN 1 END) as paid')
   ->selectRaw('COUNT(CASE WHEN paid = 0 THEN 1 END) as unpaid')
   ->selectRaw('COUNT(CASE WHEN paid <= 2  THEN 1 END) as all_report')
   ->first();
   $paid_report = $reports->paid;
   $unpaid_report = $reports->unpaid;
   $all_reporter_reports = $reports->all_report;


      //top month have report
      $report = Invoice::where('receiver_id', Auth::guard('reporter')->user()->id)->where('receiver_type', 'reporter')->orderBy('invoice_date', 'ASC')
        ->select(DB::raw("count(id) as count"), DB::raw('MONTH(invoice_date) month'))
        ->groupBy('month')->get()->toArray();

      $month = array_column($report, 'month');


      $report_months = array();

      foreach ($month as $report_month) {

        if ($report_month == 1) {
          $report_months[] = "january";
        }
        if ($report_month == 2) {
          $report_months[] = "February";
        }
        if ($report_month == 3) {
          $report_months[] = "March ";
        }
        if ($report_month == 4) {
          $report_months[] = "April ";
        }
        if ($report_month == 5) {
          $report_months[] = "May";
        }
        if ($report_month == 6) {
          $report_months[] = "June";
        }
        if ($report_month == 7) {
          $report_months[] = "July ";
        }
        if ($report_month == 8) {
          $report_months[] = "August";
        }
        if ($report_month == 9) {
          $report_months[] = "September";
        }
        if ($report_month == 10) {
          $report_months[] = "October";
        }
        if ($report_month == 11) {
          $report_months[] = "November ";
        }
        if ($report_month == 12) {
          $report_months[] = "December ";
        }
      }

      $report_month_count = [];
      for ($i = 0; $i < count($report); $i++) {

        $report_month_count[] = $report[$i]['count'];
      }
      //tab active
      $active = "now";

      //reward overview

      $rewards = Invoice::where('receiver_id', Auth::guard('reporter')->user()->id)->where('receiver_type', 'reporter')->selectRaw('COUNT(*) as count, SUM(total_amount) as amount, AVG(total_amount) as average, MAX(total_amount) as highest')->first();
      $rewards_count = $rewards->count;
      $rewards_amount = number_format($rewards->amount, 0, '.', ',');
      $average_reward = number_format($rewards->average, 0, '.', ',');
      $highest_reward = number_format($rewards->highest, 0, '.', ',');







      $reporter_rewards =DB::table('reports')
      ->where('reports.reporter_id', Auth::guard('reporter')->user()->id)
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





      return view('content.reporter.pages.reward', compact('reporter_rewards', 'rewards_count', 'rewards_amount', 'average_reward', 'highest_reward', 'report_months', 'report_month_count', 'all_reporter_reports', 'unpaid_report', 'paid_report', 'active'));
    }

  } catch (\Exception $ex) {

    return back()->with('error',  'something went wrong');

 }
  }
}
