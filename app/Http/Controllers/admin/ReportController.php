<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Services\Notification;
use App\Models\Activity;
use App\Models\ChatReport;
use App\Models\Corporate\Corporate;
use App\Models\Reporter\Point;
use Illuminate\Http\Request;
use App\Models\Reporter\Reporter;
use App\Models\Reporter\Report;
use App\Models\Reporter\ReportImage;
use App\Repository\admin\ReportRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{

    public $report;
    public function __construct(ReportRepository $report)
    {
        $this->report = $report;
      
    }

public function reports()
{
return $this->report->reports();
} 

public function get_reports(Request $request)
{
    return $this->report->get_reports($request);

}

//show report  page 

public function show_report($id)
{
    return $this->report->show_report($id);

}

//get report status using ajax
public function get_status(Request $request)
{

    return $this->report->get_status($request);

}


//update report status 
public function status(Request $request)
{
    return $this->report->status($request);

}

//update report hacktivity 
public function hacktivity(Request $request)
{
    return $this->report->hacktivity($request);

}

//delete report
public function destroy(Request $request)
{

    return $this->report->destroy($request);

 }

}





