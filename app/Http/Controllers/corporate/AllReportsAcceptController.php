<?php

namespace App\Http\Controllers\corporate;

use App\Http\Controllers\Controller;
use App\Repository\corporate\AllReportsAcceptRepository;
use Illuminate\Http\Request;


class AllReportsAcceptController extends Controller
{

    public $allReportAccept;
    public function __construct(AllReportsAcceptRepository $allReportAccept)
    {
        $this->allReportAccept = $allReportAccept;
    }

  //get all reports accepted
public function index()
{
    return $this->allReportAccept->index();

}

  //get all reports accepted using ajax

public function accept_reports_get(Request $request)
{
    return $this->allReportAccept->accept_reports_get($request);

}




}





