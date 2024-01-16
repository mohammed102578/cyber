<?php

namespace App\Repository\reporter;

use App\Interfaces\reporter\AllReportsAcceptInterface;
use App\Models\Corporate\Corporate;
use App\Models\Corporate\Priority;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AllReportsAcceptRepository implements AllReportsAcceptInterface
{
public function index()
{

    try{

       return view('content.reporter.pages.all_reports_accept');

    } catch (\Exception $ex) {

       return back()->with('error',  'something went wrong');

    }

}

public function get_accepted_reports($request)
{
    try{

        if (
        $request->ajax()) {
        $data =DB::table('reports')
        ->where('reports.status_id', '=','3')
        ->where('reports.reporter_id', '=',Auth::guard('reporter')->user()->id)
        ->orderBy('reports.id', 'desc')
        ->leftJoin('programs', 'programs.id', '=', 'reports.program_id')
        ->leftJoin('corporates', 'corporates.id','=','programs.corporate_id')
        ->leftJoin('reporters', 'reporters.id', '=', 'reports.reporter_id')
        ->leftJoin('statuses', 'statuses.id', '=', 'reports.status_id')

        ->select('reports.id','reports.target','reports.paid','reports.created_at','reports.vulnerability','reports.url_vulnerability', 'programs.corporate_id',
        'programs.id as program_id','corporates.company_name','corporates.company_name','programs.currency','reporters.email','reporters.first_name','reporters.last_name','reporters.phone','reporters.image','statuses.status')
        ->get();



        return Datatables()->of($data)
        ->addIndexColumn()

        //image
        ->addColumn('image', function($data){

        $image = $data->image;


        return $image;
        })
        ->rawColumns(['image'])

        //name
        ->addColumn('name',function($data){

        $name = $data->first_name." ".$data->last_name;

        return $name;
        })
        ->rawColumns(['name'])

        ->addColumn('paid', function($data){

        if($data->paid == 1){
        $statutsBtn = 'paid';


        }else{
        $statutsBtn =  'unpaid';

        }
        return $statutsBtn;
        })
        ->rawColumns(['paid'])





        ->make(true);
        }

    } catch (\Exception $ex) {

        return back()->with('error', 'something went wrong');

    }
}








}





