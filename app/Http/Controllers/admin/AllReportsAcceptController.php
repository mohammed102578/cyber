<?php

namespace App\Http\Controllers\admin;


use App\Http\Controllers\Controller;
use App\Repository\admin\AllReportsAcceptRepository;
use Illuminate\Http\Request;


class AllReportsAcceptController extends Controller
{


    protected $acceptReporter;
    public function __construct(AllReportsAcceptRepository $acceptReporter)
    {

        $this->acceptReporter = $acceptReporter;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    return $this->acceptReporter->index();
    }
     /**
     * Display a listing of the resource.
     * using ajax and yajara data table
     * @return \Illuminate\Http\Response
     */

     public function get_accept_reports(Request $request)
     {
        return $this->acceptReporter->get_accept_reports($request);

     }
    /**
     * Update the specified resource in storage.
     * updated status report paid or unpaid
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function paid_report(Request $request)
    {
        return $this->acceptReporter->paid_report($request);

    }


}
