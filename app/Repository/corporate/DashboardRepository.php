<?php

namespace App\Repository\corporate;

use App\Interfaces\corporate\DashboardInterface;
use App\Models\Corporate\Program;
use App\Models\Invoice;
use App\Models\Reporter\Point;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardRepository implements DashboardInterface
{
    public function dashboard()
    {


        $programs = DB::table('programs')->where('status_id', 3)->where('corporate_id', Auth::guard('corporate')->user()->id)
            ->selectRaw('COUNT(CASE WHEN program_type = 3 THEN 1 END) as private_program')
            ->selectRaw('COUNT(CASE WHEN program_type = 2 THEN 1 END) as semi_private_program')
            ->selectRaw('COUNT(CASE WHEN program_type = 1 THEN 1 END) as public_program')
            ->selectRaw('COUNT(CASE WHEN program_type < 4 THEN 1 END) as program_accept_count')
            ->first();
        $program_accept_count = $programs->program_accept_count;
        $private_program = $programs->private_program;
        $semi_private_program = $programs->semi_private_program;
        $public_program = $programs->public_program;


        $invoice_count = Invoice::where('sender_type', 'corporate')->where('receiver_type', 'admin')->where('sender_id', Auth::guard('corporate')->user()->id)->count();



        //program status chart

        $program = Program::where('corporate_id', Auth::guard('corporate')->user()->id)->where('submit', 1)
            ->select('status_id', DB::raw("count(status_id) as count"))
            ->groupBy('status_id')->get()->toArray();

        $program_status = [];
        $program_status_count = [];
        for ($i = 0; $i < count($program); $i++) {
            if ($program[$i]['status_id'] == 1) {
                $program_status[] = "pending";
            } elseif ($program[$i]['status_id'] == 2) {
                $program_status[] = "in_review";
            } elseif ($program[$i]['status_id'] == 3) {
                $program_status[] = "accepted";
            } elseif ($program[$i]['status_id'] == 4) {
                $program_status[] = "rejected";
            }

            $program_status_count[] = $program[$i]['count'];
        }



        //top month have report
        $program = Program::where('status_id', 3)->where('corporate_id', Auth::guard('corporate')->user()->id)->orderBy('created_at', 'ASC')
            ->select(DB::raw("count(id) as count"), DB::raw('MONTH(created_at) month'))
            ->groupBy('month')->get()->toArray();

        $month = array_column($program, 'month');
        $program_months = array();

        foreach ($month as $program_month) {

            if ($program_month == 1) {
                $program_months[] = "january";
            }
            if ($program_month == 2) {
                $program_months[] = "February";
            }
            if ($program_month == 3) {
                $program_months[] = "March ";
            }
            if ($program_month == 4) {
                $program_months[] = "April ";
            }
            if ($program_month == 5) {
                $program_months[] = "May";
            }
            if ($program_month == 6) {
                $program_months[] = "June";
            }
            if ($program_month == 7) {
                $program_months[] = "July ";
            }
            if ($program_month == 8) {
                $program_months[] = "August";
            }
            if ($program_month == 9) {
                $program_months[] = "September";
            }
            if ($program_month == 10) {
                $program_months[] = "October";
            }
            if ($program_month == 11) {
                $program_months[] = "November ";
            }
            if ($program_month == 12) {
                $program_months[] = "December ";
            }
        }
        $program_month_count = [];
        for ($i = 0; $i < count($program); $i++) {

            $program_month_count[] = $program[$i]['count'];
        }


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

        //reporter transaction

        $last_invoices = DB::table('invoices')
            ->take('5')
            ->where('sender_type', 'corporate')->where('receiver_type', 'admin')->where('sender_id', Auth::guard('corporate')->user()->id)
            ->orderBy('invoices.id', 'desc')
            ->select('invoices.id', 'invoices.total_amount', 'invoices.bank_name', 'invoices.serial_no_type', 'invoices.currency', 'invoices.serial_no',)
            ->get();


        //top five report

        $top_reporters = Point::with('reporter')
            ->select('reporter_id', DB::raw("sum(point) as sum"))
            ->take('5')
            ->orderBy('sum', 'desc')
            ->groupBy('reporter_id')->get();

        return view('content.corporate.pages.dashboard', compact(
            'all_corporate_reports',
            'public_program',
            'semi_private_program',
            'private_program',
            'program_accept_count',
            'invoice_count',
            'last_invoices',
            'program_status',
            'program_status_count',
            'program_month_count',
            'program_months',
            'paid_report',
            'unpaid_report',
            'top_reporters'
        ));
        try {
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'something went wrong');
        }
    }
}
