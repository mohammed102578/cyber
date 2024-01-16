<?php

namespace App\Repository\admin;


use App\Interfaces\admin\AllReportsAcceptInterface;
use App\Models\Activity;
use App\Models\Reporter\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AllReportsAcceptRepository implements AllReportsAcceptInterface
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        try{
             return view('content.admin.pages.report.all_reports_accept');

              } catch (\Exception $ex) {

             return back()->with('error',  'something went wrong');

       }
    }






     /**
     * Display a listing of the resource.
     * using ajax and yajara data table
     * @return \Illuminate\Http\Response
     */

     public function get_accept_reports($request)
     {
    try{

        if($request->ajax()) {


            $data =DB::table('reports')
            ->where('reports.status_id', '=','3')
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
        $severity="";
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




        //action

        ->addColumn('action', function($data){
        $actionBtn = ' <div class="d-inline-block">
        <a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="bx bx-dots-vertical-rounded">

        </i></a>
        <div class="dropdown-menu dropdown-menu-end m-0 p-0">
        <a class=" hapus-modal_block dropdown-item  " id="'. $data->id.'" ><i class="bx bx-money text-info" ></i> Paid Status</a>

        <a class=" dropdown-item "  href="'.route('admin_show_program',$data->program_id).'" ><i class="bx bx-show-alt text-warning" ></i> Show Program
        </a></div>';
            return $actionBtn;
        })
        ->rawColumns(['action'])
        ->make(true);
         }

      } catch (\Exception $ex) {

            return back()->with('error',  'something went wrong');

      }


     }




    /**
     * Update the specified resource in storage.
     * updated status report paid or unpaid
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function paid_report($request)
    {

     try{
        $id = $request->id;
        $check = Report::find($id);

        if($check){
        $data=$check;
        if($data->paid == 1){
            $paid=0;
        }else{
            $paid=1;

        }

        Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
        'activity'=>'Updated Report ','description_activity'=>" Updated  Report Paid Operation "]);


        Report::where('id',$data->id)->update(['paid' => $paid]);
        return response()->json(['status' => 'success'], 200);


         }else{
           return response()->json(['status' => 'failed']);

         }

         } catch (\Exception $ex) {

         return back()->with('error',  'something went wrong');

        }
    }


}
