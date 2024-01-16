<?php

namespace App\Http\Controllers\reporter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repository\reporter\AllReportsAcceptRepository;


class AllReportsAcceptController extends Controller
{
    protected $allReportAccept;
    public function __construct(AllReportsAcceptRepository $allReportAccept)
    {

        $this->allReportAccept = $allReportAccept;
    }
    public function index()
    {
        return $this->allReportAccept->index();
    }

    public function get_accepted_reports(Request $request)
    {
        return $this->allReportAccept->get_accepted_reports($request);
    }
}
