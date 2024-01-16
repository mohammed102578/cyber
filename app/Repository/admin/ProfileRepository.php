<?php

namespace App\Repository\admin;

use App\Interfaces\admin\ProfileInterface;
use App\Models\Admin\Admin;
use App\Models\Corporate\Corporate;
use App\Models\Corporate\Program;
use App\Models\Corporate\SemiPrivateProgram;
use App\Models\Nationality;
use App\Models\Hobby;
use App\Models\Reporter\Report;
use App\Models\Reporter\Reporter;
use App\Models\Reporter\ReportStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileRepository implements ProfileInterface
{
//display profile page
public function profile()
{
    try{
        $admin=Admin::find(Auth::guard('admin')->user()->id);
        $activities=$admin->activeable()->take(5)->orderBy('id','desc')->get();
        $accept_programs_count=Program::where('status_id',3)->count();
        $accept_reports_count=Report::where('status_id',3)->count();
        $threshold = now()->subMinutes(3);
        $reporter=Reporter::where('last_seen_at','>',$threshold)->count();
        $corporate=Corporate::where('last_seen_at','>',$threshold)->count();
        $connected=$reporter+$corporate;

        return view('content.admin.pages.admin_setting.profile',compact(['connected','activities','accept_reports_count','accept_programs_count']));
          }catch(\Exception $ex){
        return back()->with('error',  'something went wrong');

    }
}

//display setting page
public function setting()
{
  try{
    return view('content.admin.pages.admin_setting.setting');
}catch(\Exception $ex){
    return back()->with('error',  'something went wrong');

  }
}




public function reporter_profile($id)
{
    try{
        $check=Reporter::find($id);
if($check){
    $reporter=$check;
    $hacktivities = Report::where('hacktivity', 1)->where('reporter_id', $id)->orderBy('updated_at', 'DESC')->get();
    $rank= [];
    $top_reporters=Report::with('reporter')
    ->where('status_id',3)
    ->select('reporter_id',DB::raw("count(status_id) as count"))
    ->orderBy('count', 'desc')
    ->groupBy('reporter_id')->get();

$number=0;
    for($i=0;$i< count($top_reporters);$i++){
        $number++;
if($top_reporters[$i]->reporter_id==$id){
    $rank['rank']=$number;
    break;

}
}

//averge imapct
$report = Report::where('reporter_id', $id)->avg('status_id');

$average= round($report);

if($average != 0){
    $average_report_status=ReportStatus::find($average)->status;

}else{
    $average_report_status=0;

}


//report accepted
$all_report = Report::where('reporter_id', $id)->count();
if($all_report==0){
    $all_report=1;
}
$accepted_report= Report::where('reporter_id', $id)->where('status_id',3)->count();
$accepted_report_precent= ($accepted_report*100)/$all_report;
$accepted_report_precent= number_format((float)$accepted_report_precent, 2, '.', '');

//report rewarded
$report_rewarded = Report::where('reporter_id', $id)->where('paid', 1)->count();


//program joined

$program_joined=SemiPrivateProgram::where('reporter_id',$id)->where('status',1)->count();
 return view('content.admin.pages.reporter.reporter_profile',compact(['program_joined','report_rewarded','accepted_report_precent','reporter','hacktivities','rank','average_report_status']));

}else{
    return back()->with('error', 'something went wrong');

}
} catch (\Exception $ex) {

        return back()->with('error', 'something went wrong');

    }

}





public function corporate_profile($id)
{
    try{
        $corporate=Corporate::find($id);
        $activities=$corporate->activeable()->take(5)->orderBy('id','desc')->get();
        $connections=$corporate->connectionable()->get();

        $facebook=[];
        $twitter=[];
        $linkedin=[];
        $github=[];

        foreach($connections as $connection){
           if($connection->app_name=='facebook'){
            $facebook['link']=$connection->link;
            $facebook['app_name']=$connection->app_name;
            $facebook['id']=$connection->id;

           }elseif($connection->app_name=='twitter'){
            $twitter['link']=$connection->link;
            $twitter['app_name']=$connection->app_name;
            $twitter['id']=$connection->id;
           }elseif($connection->app_name=='github'){
            $github['link']=$connection->link;
            $github['app_name']=$connection->app_name;
            $github['id']=$connection->id;
           }else{
            $linkedin['link']=$connection->link;
            $linkedin['app_name']=$connection->app_name;
            $linkedin['id']=$connection->id;

           }
        }


        return view('content.admin.pages.corporate.corporate_profile',compact(['corporate','activities','linkedin','github','twitter','facebook']));


 } catch (\Exception $ex) {

        return back()->with('error', 'something went wrong');

    }

}








}

