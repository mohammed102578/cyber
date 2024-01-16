<?php

namespace App\Repository\corporate;

use App\Interfaces\corporate\AllReportsAcceptInterface;
use App\Models\Corporate\Corporate;
use App\Models\Corporate\Priority;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AllReportsAcceptRepository implements AllReportsAcceptInterface
{
  //get all reports accepted
public function index()
{

    try{
        return view('content.corporate.pages.all_reports_accept');

    } catch (\Exception $ex) {

        return back()->with('error',  'something went wrong');

    }

}

  //get all reports accepted using ajax

public function accept_reports_get($request)
{

    try{

        if (
        $request->ajax()) {

        $data =DB::table('reports')
            ->where('reports.status_id', '=','3')
            ->where('programs.corporate_id', '=',Auth::guard('corporate')->user()->id)
            ->orderBy('reports.id', 'desc')
            ->leftJoin('programs', 'programs.id', '=', 'reports.program_id')
            ->leftJoin('reporters', 'reporters.id', '=', 'reports.reporter_id')
            ->leftJoin('corporates', 'corporates.id', '=', 'programs.corporate_id')
            ->select('reports.id','reports.target','reports.paid','reports.created_at','reports.vulnerability','reports.url_vulnerability', 'programs.corporate_id',
            'programs.id as program_id','programs.currency','reporters.email','reporters.first_name','reporters.last_name','reporters.phone','reporters.image','corporates.company_name')
            ->get();


            return Datatables()->of($data)->addIndexColumn()


            //image
            ->addColumn('image', function($data){
            $image = $data->image;
            return $image;
            })->rawColumns(['image'])


            //name
            ->addColumn('name',function($data){

                $name = $data->first_name." ".$data->last_name;

            return $name;
            })
            ->rawColumns(['name'])

            //paid status
            ->addColumn('paid', function($data){

                if($data->paid == 1){
                $statutsBtn = 'paid';


                }else{
                $statutsBtn =  'unpaid';

                }
                return $statutsBtn;
            })
            ->rawColumns(['paid'])



            //except amount
            ->addColumn('severity',function($data){
                $severity= "";

            if (str_contains($data->vulnerability, '(CRITICAL)')) {
            $severity= "CRITICAL";
            }elseif(str_contains($data->vulnerability, '(HIGH)')){
            $severity= "HIGH";

            }elseif(str_contains($data->vulnerability, '(MEDIUM)')){
            $severity= "MEDIUM";

            }elseif(str_contains($data->vulnerability, '(LOW)')){
                $severity= "LOW";

            }elseif(str_contains($data->vulnerability, '(INFORMATION)')){
                $severity= "INFORMATION";

            }


            return $severity;
            })
            ->rawColumns(['severity'])


        ->make(true);
        }
    } catch (\Exception $ex) {

    return back()->with('error', 'something went wrong');

    }
}




}





