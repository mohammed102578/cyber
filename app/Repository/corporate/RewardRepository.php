<?php

namespace App\Repository\corporate;


use App\Interfaces\corporate\RewardInterface;
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
      $from = $request->from;
      $to = $request->to;

     //paid reporting rate
     $counts = DB::table('reports')
     ->selectRaw('COUNT(CASE WHEN reports.status_id = 3 AND reports.paid = 1 THEN 1 END) as paid_report')
     ->selectRaw('COUNT(CASE WHEN reports.status_id = 3 AND reports.paid = 0 THEN 1 END) as unpaid_report')
     ->selectRaw('COUNT(CASE WHEN reports.status_id = 3 THEN 1 END) as all_corporate_reports')
     ->leftJoin('programs', 'programs.id', '=', 'reports.program_id')
     ->where('programs.status_id', '=', '3')
     ->where('programs.corporate_id', '=', Auth::guard('corporate')->user()->id)
     ->whereBetween('reports.created_at', [$from, $to])
     ->first();

 // Access the counts like this:
 $paid_report = $counts->paid_report;
 $unpaid_report = $counts->unpaid_report;
 $all_corporate_reports = $counts->all_corporate_reports;

      if ($all_corporate_reports == 0) {
        $all_corporate_reports = 1;
      }
      //top month have report
      $report = Invoice::where('sender_id', Auth::guard('corporate')->user()->id)
        ->where('created_at', '>=', $from)
        ->where('created_at', '<=', $to)
        ->where('sender_type', 'corporate')->orderBy('invoice_date', 'ASC')
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
      $rewards = Invoice::where('sender_id', Auth::guard('corporate')->user()->id)
        ->where('created_at', '>=', $from)
        ->where('created_at', '<=', $to)
        ->where('sender_type', 'corporate')->count();

      $amount = Invoice::where('sender_id', Auth::guard('corporate')->user()->id)
        ->where('created_at', '>=', $from)
        ->where('created_at', '<=', $to)
        ->where('sender_type', 'corporate')->sum('total_amount');
      $rewards_amount = number_format($amount, 0, '.', ',');

      $average = Invoice::where('sender_id', Auth::guard('corporate')->user()->id)
        ->where('created_at', '>=', $from)
        ->where('created_at', '<=', $to)
        ->where('sender_type', 'corporate')->average('total_amount');
      $average_reward = number_format($average, 0, '.', ',');

      $highest = Invoice::where('sender_id', Auth::guard('corporate')->user()->id)
        ->where('created_at', '>=', $from)
        ->where('created_at', '<=', $to)
        ->where('sender_type', 'corporate')->max('total_amount');
      $highest_reward = number_format($highest, 0, '.', ',');



      $corporate_rewards = DB::table('invoices')
        ->where('sender_type', 'corporate')->where('receiver_type', 'admin')->where('sender_id', Auth::guard('corporate')->user()->id)
        ->orderBy('invoices.id', 'desc')
        ->where('invoices.created_at', '>=', $from)
        ->where('invoices.created_at', '<=', $to)
        ->leftJoin('reports', 'reports.id', '=', 'invoices.report_id')
        ->select('reports.vulnerability', 'invoices.total_amount', 'invoices.created_at',)
        ->orderBy('invoices.id', 'desc')
       ->get();




      return view('content.corporate.pages.reward', compact('corporate_rewards', 'rewards', 'rewards_amount', 'average_reward', 'highest_reward', 'report_months', 'report_month_count', 'all_corporate_reports', 'unpaid_report', 'paid_report', 'active'));
    } else {




      //paid reporting rate
      $counts = DB::table('reports')
      ->selectRaw('COUNT(CASE WHEN reports.status_id = 3 AND reports.paid = 1 THEN 1 END) as paid_report')
      ->selectRaw('COUNT(CASE WHEN reports.status_id = 3 AND reports.paid = 0 THEN 1 END) as unpaid_report')
      ->selectRaw('COUNT(CASE WHEN reports.status_id = 3 THEN 1 END) as all_corporate_reports')
      ->leftJoin('programs', 'programs.id', '=', 'reports.program_id')
      ->where('programs.status_id', '=', '3')
      ->where('programs.corporate_id', '=', Auth::guard('corporate')->user()->id)
      ->first();

  // Access the counts like this:
  $paid_report = $counts->paid_report;
  $unpaid_report = $counts->unpaid_report;
  $all_corporate_reports = $counts->all_corporate_reports;

      if ($all_corporate_reports == 0) {
        $all_corporate_reports = 1;
      }
      //top month have report
      $report = Invoice::where('sender_id', Auth::guard('corporate')->user()->id)->where('sender_type', 'corporate')->orderBy('invoice_date', 'ASC')
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
      $rewards = Invoice::where('sender_id', Auth::guard('corporate')->user()->id)->where('sender_type', 'corporate')->count();

      $amount = Invoice::where('sender_id', Auth::guard('corporate')->user()->id)->where('sender_type', 'corporate')->sum('total_amount');
      $rewards_amount = number_format($amount, 0, '.', ',');

      $average = Invoice::where('sender_id', Auth::guard('corporate')->user()->id)->where('sender_type', 'corporate')->average('total_amount');
      $average_reward = number_format($average, 0, '.', ',');

      $highest = Invoice::where('sender_id', Auth::guard('corporate')->user()->id)->where('sender_type', 'corporate')->max('total_amount');
      $highest_reward = number_format($highest, 0, '.', ',');



      $corporate_rewards = DB::table('invoices')
        ->where('sender_type', 'corporate')->where('receiver_type', 'admin')->where('sender_id', Auth::guard('corporate')->user()->id)
        ->orderBy('invoices.id', 'desc')
        ->leftJoin('reports', 'reports.id', '=', 'invoices.report_id')
        ->select('reports.vulnerability', 'invoices.total_amount', 'invoices.created_at',)
        ->orderBy('invoices.id', 'desc')
        ->get();




      return view('content.corporate.pages.reward', compact('corporate_rewards', 'rewards', 'rewards_amount', 'average_reward', 'highest_reward', 'report_months', 'report_month_count', 'all_corporate_reports', 'unpaid_report', 'paid_report', 'active'));
    }


    } catch (\Exception $ex) {

      return back()->with('error',  'something went wrong');
    }
  }
}
