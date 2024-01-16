<?php

namespace App\Http\Controllers\reporter;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reporter\ReportRequest;
use App\Repository\reporter\ReportRepository;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    protected $report;
    public function __construct(ReportRepository $report)
    {

        $this->report = $report;
    }


    //==========get all reporter report's
    public function index()
    {
        return $this->report->index();
    }


    //==========get all reporter report's using ajax

    public function get_reports(Request $request)
    {
        return $this->report->get_reports($request);
    }


    //==================create report
    public function create($id)
    {
        return $this->report->create($id);
    }


    //====================store report
    public function store(ReportRequest $request)
    {
        return $this->report->store($request);
    }





    //==================create report
    public function edit($id)
    {
        return $this->report->edit($id);
    }



    //====================store report
    public function update(ReportRequest $request)
    {
        return $this->report->update($request);
    }


    //================get belong_vulnerability image use ajax
    public function belong_vulnerability(Request $request)
    {
        return $this->report->belong_vulnerability($request);
    }


    //====================get belong belong_vulnerability image use ajax

    public function belong_belong_vulnerability(Request $request)
    {
        return $this->report->belong_belong_vulnerability($request);
    }


    //====================show report  page 

    public function show($id)
    {
        return $this->report->show($id);
    }



    //====================delete report
    public function destroy(Request $request)
    {
        return $this->report->destroy($request);
    }
}
