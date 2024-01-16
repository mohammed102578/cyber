<?php

namespace App\Repository\admin;

use App\Http\Services\Notification;
use App\Interfaces\admin\ReportInterface;
use App\Models\Activity;
use App\Models\ChatReport;
use App\Models\Corporate\Corporate;
use App\Models\Reporter\Point;
use App\Models\Reporter\Reporter;
use App\Models\Reporter\Report;
use App\Models\Reporter\ReportImage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportRepository implements ReportInterface
{

    public $reporter_notification;
    public function __construct()
    {
        $this->reporter_notification = new Notification;

    }

public function reports()
{
    try{
        return view('content.admin.pages.report.reports');
          } catch (\Exception $ex) {
         return back()->with('error',  'something went wrong');

    }
}

public function get_reports($request)
{
      try{

              if (
              $request->ajax()) {
              $data = DB::table('reports')
              ->orderBy('id','desc')
              ->join('programs', 'reports.program_id', '=', 'programs.id')
              ->join('corporates', 'programs.corporate_id', '=', 'corporates.id')
              ->leftJoin('reporters', 'reporters.id', '=', 'reports.reporter_id')
              ->leftJoin('report_statuses', 'report_statuses.id', '=', 'reports.status_id')
              ->select('reports.id','reports.target','reports.created_at','reports.vulnerability','reports.hacktivity','reports.url_vulnerability','programs.image','programs.management','corporates.image as corporate_image',
                    'reporters.email','reporters.first_name','reporters.last_name','reporters.phone','report_statuses.status','corporates.company_name as corporate_name')
                    ->get();
              return Datatables()->of($data)
              ->addIndexColumn()
              //image
              ->addColumn('image', function($data){
              if($data->image!= null){
              $image = $data->image;
              }else{
              $image=$data->corporate_image;
              }

              return $image;
              })
              ->rawColumns(['image'])
            //created_at
            ->addColumn('created_at',function($data){

                $created_at=Carbon::parse($data->created_at)->format('d M Y');

                return $created_at;
                })
                ->rawColumns(['created_at'])


              //report status
              ->addColumn('status', function($data){
              $status=$data->status;
              return $status;
              })
              ->rawColumns(['status'])
            //report manged
            ->addColumn('management', function($data){
            if($data->management==1){
                $management="BY ".app('platform_setting')->name ;

            }else{
                $management=$data->corporate_name;

            }
            return $management;
            })
            ->rawColumns(['management'])
            //report hacktivity
            ->addColumn('hacktivity', function($data){
                $hacktivity=$data->hacktivity;
                return $hacktivity;
                })
                ->rawColumns(['hacktivity'])

              //name
              ->addColumn('name',function($data){

              $name = $data->first_name." ".$data->last_name;

              return $name;
              })
              ->rawColumns(['name'])
              //action
              ->addColumn('action', function($data){
              $actionBtn = ' <div class="d-inline-block">
              <a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
              <i class="bx bx-dots-vertical-rounded">
              </i></a>
              <div class="dropdown-menu dropdown-menu-end m-0">
              <a  class="dropdown-item" id="'. $data->id.'"href="show_report/'.$data->id.'"><i class="bx bx-show text-success"></i> Show Report</a>
              <a class=" status-modal dropdown-item " id="'. $data->id.'"href=""data-bs-toggle="modal" data-bs-target="#report_edit_status"><i class="bx bxs-hourglass-top text-warning"></i> Status</a>
              <a class=" hapus-modal-hacktivity dropdown-item " id="'. $data->id.'"><i class="bx bxs-chevrons-up text-info"></i> Hacktivity</a>
              <div class="dropdown-divider">

              </div>
              <a class=" hapus-modale dropdown-item " id="'. $data->id.'" ><i class="bx bx-trash text-danger"></i> Delete</a>
              </div>
              </div>
              ';
              return $actionBtn;
              })
              ->rawColumns(['action'])
              ->make(true);
              }

      } catch (\Exception $ex) {

            return back()->with('error', 'something went wrong');

      }
}




//show report  page

public function show_report($id)
{

    try{
              $reporters= Reporter::all();
              $check=Report::find($id);
              if($check){
              ChatReport::where('admin_id',Auth::guard('admin')->user()->id)->
              where('receiver_type','admin')->update(['read'=>1]);
              $report=Report::where('id',$check->id)->
              with('chat',function($query){
              return $query->with('reporter')->get();
              })->
              with('program',function($query){
              return $query->with('corporate')->first();
              })->with('reporter')->first();
              if(count($report->chat) > 0){
              $reporter_image= $report->chat[0]->reporter->image;
              $reporter_name= $report->chat[0]->reporter->name;
              }else{
              $reporter_image="";
              $reporter_name= "";
              }
              $images=ReportImage::where('report_id',$id)->get();
              return view('content.admin.pages.report.report',compact(['report','images','reporter_image','reporter_name']));
              }else{
              return redirect()->back()->with('error', "Something Went Wrong.");

              }


        } catch (\Exception $ex) {

              return back()->with('error',  'something went wrong');

        }

}




//get report status using ajax
public function get_status($request)
{

    try{
        $id = $request->id;
        $check = Report::find($id);
        $data=$check->status_id;
        $report_id=$check->id;
        return response()->json(['data' => $data,'report_id' => $report_id]);
    } catch (\Exception $ex) {
        return back()->with('error',  'something went wrong');
    }

}






//update report status
public function status($request)


{
    DB::beginTransaction();
    try{
          $id = $request->id;
          $check = Report::find($id);
          if($check){
          $data=Report::where('id',$id)->update(['status_id' => $request->status]);
          Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
          'activity'=>'Updated Report ','description_activity'=>" Updated Report Status "]);

         $status = Report::where('id',$id)->with('status')->first()->status->status;


          	// send notification
         $this->reporter_notification->
         sendReporterNotification('Report Status',Auth::guard('admin')->user()->name.'  changed the status of the report to '.$status,
         'show_reporter_report',$id,$check->reporter_id,'admin');


         //add point to reporter if report acceped
         if($request->status==3){

          if (str_contains($check->vulnerability, '(CRITICAL)')) {
            Point::updateOrCreate(
            ['report_id'=>$check->id],
            ['reporter_id'=>$check->reporter_id,
            'report_id'=>$check->id,
            'point'=>5,
            'vulnerability'=>$check->vulnerability ,
            ]);
            }elseif(str_contains($check->vulnerability, '(HIGH)')){
                Point::updateOrCreate(
                    ['report_id'=>$check->id],
                    ['reporter_id'=>$check->reporter_id,
                    'report_id'=>$check->id,
                    'point'=>4,
                    'vulnerability'=>$check->vulnerability ,
                    ]);

            }elseif(str_contains($check->vulnerability, '(MEDIUM)')){
                Point::updateOrCreate(
                    ['report_id'=>$check->id],
                    ['reporter_id'=>$check->reporter_id,
                    'report_id'=>$check->id,
                    'point'=>3,
                    'vulnerability'=>$check->vulnerability ,
                    ]);

            }elseif(str_contains($check->vulnerability, '(LOW)')){
                Point::updateOrCreate(
                    ['report_id'=>$check->id],
                    ['reporter_id'=>$check->reporter_id,
                    'report_id'=>$check->id,
                    'point'=>2,
                    'vulnerability'=>$check->vulnerability ,
                    ]);

            }elseif(str_contains($check->vulnerability, '(INFORMATION)')){
                Point::updateOrCreate(
                    ['report_id'=>$check->id],
                    ['reporter_id'=>$check->reporter_id,
                    'report_id'=>$check->id,
                    'point'=>1,
                    'vulnerability'=>$check->vulnerability ,
                    ]);

            }
        }else{
            Point::where('reporter_id',$check->reporter_id)->where('report_id',$check->id)->delete();
        }
        DB::commit();



          return response()->json(['status' => 'success'], 200);
          }else{
          return response()->json(['status' => 'failed']);
          }
      } catch (\Exception $ex) {
        DB::rollback();

          return back()->with('error',  'something went wrong');
      }


}




//update report hacktivity
public function hacktivity($request)
{

    try{
        $id = $request->id;
        $check = Report::find($id);
        if($check){
        $data=$check;
        if($data->hacktivity == 1){
        $hacktivity=0;
        }else{
        $hacktivity=1;
        }
        Report::where('id',$data->id)->update(['hacktivity' => $hacktivity]);
        Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
        'activity'=>'Updated Reporter hacktivity','description_activity'=>"updated Reporter hacktivity "]);
        return response()->json(['status' => 'success'], 200);
        }else{
        return response()->json(['status' => 'failed']);
        }

    } catch (\Exception $ex) {
       return back()->with('error',  'something went wrong');
    }


}








//delete report
public function destroy($request)
{

    try{
          $id = $request->id;
          $check = Report::find($id);
          if($check){
          $data=$check;
          $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://");
          $image = ReportImage::where('report_id' , $data->id)->select('image')->get();
          if($image!=null){
          $count=count($image);
          for($i=0;$i<$count;$i++){
          $replace=$actual_link.$_SERVER['HTTP_HOST']."/";
          $image_path= str_replace($replace,"",$image[$i]['image']);
          if(file_exists($image_path)){
          unlink($image_path);
          }
          }
          }
          ReportImage::where('report_id' , $data->id)->delete();
          Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
          'activity'=>'Deleted Report ','description_activity'=>" Deleted Report Status "]);
          $data->delete();

          // send notification
         $this->reporter_notification->
         sendReporterNotification('Delete Report',Auth::guard('admin')->user()->name.'  Deleted the report',
         'reporter_notification',null,$check->reporter_id,'admin');


          return response()->json(['status' => 'success'], 200);
          }else{
          return response()->json(['status' => 'failed']);
          }

    } catch (\Exception $ex) {
         return back()->with('error',  'something went wrong');
    }
    }

}





