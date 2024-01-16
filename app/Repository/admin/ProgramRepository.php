<?php

namespace App\Repository\admin;

use App\Http\Services\Notification;
use App\Interfaces\admin\ProgramInterface;
use App\Models\Activity;
use App\Models\Reporter\Reporter;
use App\Models\Corporate\Program;
use App\Models\Corporate\SemiPrivateProgram;
use App\Models\Corporate\Target;
use App\Models\Corporate\TypeTarget;
use App\Models\Corporate\Priority;
use App\Models\Corporate\ProgramUpdate;
use App\Models\Reporter\Report;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Validator;
use App\Traits\SaveImageTrait;

class ProgramRepository implements ProgramInterface
{

    use SaveImageTrait;
   public object $corporate_notification;
     public function __construct()
    {
        $this->corporate_notification = new Notification;

    }
public function index()
{

  try{
   return view('content.admin.pages.program.programs');
  } catch (\Exception $ex) {
   return back()->with('error',  'something went wrong');
  }

}

public function get_program($request)
{
    try{

          if (
          $request->ajax()) {
          $data =DB::table('programs')
          ->where('submit','=',1)
          ->orderBy('programs.id', 'desc')
          ->leftJoin('corporates', 'corporates.id', '=', 'programs.corporate_id')
          ->leftJoin('statuses', 'statuses.id', '=', 'programs.status_id')
          ->select('programs.*', 'corporates.company_name','corporates.email','corporates.section','corporates.image as corporate_image','corporates.city','statuses.status')
          ->get();
          return Datatables()->of($data)
          ->addIndexColumn()
          //status
          ->addColumn('status', function($data){
          $statutsBtn =  $data->status;
          return $statutsBtn;
          })
          ->rawColumns(['status'])

            //created_at
        ->addColumn('created_at',function($data){

            $created_at=Carbon::parse($data->created_at)->format('d M Y');

            return $created_at;
            })
            ->rawColumns(['created_at'])
          //image
          ->addColumn('image', function($data){
          if($data->image!= null){
          $image = $data->image;
          }else{
          $image =$data->corporate_image;
          }

          return $image;
          })
          ->rawColumns(['image'])

          //description
          ->addColumn('description', function($data){

          $description= substr($data->description_en,0,20);
          return $description;
          })
          ->rawColumns(['description'])


          //visibility
          ->addColumn('visibility', function($data){



          if($data->program_type==1){
          $visibility = "public";


          }elseif($data->program_type==2){

          $visibility = "semi_private";

          }else{

          $visibility = "private";

          }

          return $visibility;
          })
          ->rawColumns(['visibility'])

          //reporter_quantity


          ->addColumn('quantity', function($data){

          if($data->reporter_quantity==1){
          $quantity = "Full";
          }else{

          $quantity = "Light";

          }

          return $quantity;
          })
          ->rawColumns(['quantity'])


          //management


          ->addColumn('management', function($data){

          if($data->management==1){
          $management = "Hacking SD";
          }else{

          $management = $data->company_name;

          }

          return $management;
          })
          ->rawColumns(['management'])


          //action

          ->addColumn('action', function($data){
          $actionBtn = ' <div class="d-inline-block">
          <a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
          <i class="bx bx-dots-vertical-rounded">

          </i></a>
          <div class="dropdown-menu dropdown-menu-end m-0">
          <a  class="dropdown-item" id="'. $data->id.'"href="show_program/'.$data->id.'"><i class="bx bx-show text-success"></i> Show Program</a>
          <a  class="dropdown-item" id="'. $data->id.'"href="edit_program/'.$data->id.'"><i class="bx bx-edit text-info"></i> Edit Program</a>
          <a class=" status-modal dropdown-item " id="'. $data->id.'"href=""data-bs-toggle="modal" data-bs-target="#progarm_edit_status"><i class="bx bxs-hourglass-top text-warning"></i> Status</a>
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


//submit program



public function submit($request)
{

  try{
          $check=Program::find($request->id);

          if($check){

          $priority=Priority::where('program_id',$check->id)->get();
          $target=Target::where('program_id',$check->id)->get();

          if(isset($priority[0]) && isset($target[0])){

          $submit=$check->submit;


          if($submit==1){
          $submit=0;

          }else{
          $submit=1;

          }

          Program::where('id',$request->id)->update(['submit'=>$submit]);
          Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
          'activity'=>'Updated Program ','description_activity'=>" Updated Program Submited "]);

          return redirect()->back()->with('success', "program Updated successfully.");


          }else{

          return redirect()->back()->with('info', "please add priority and target first");
          }


          }else{
          return redirect()->back()->with('error', "something went wrong");
          }

  } catch (\Exception $ex) {

          return back()->with('error',  'something went wrong');

  }


}



//unsubmit_program
public function unsubmit_program()
{

  try{
   return view('content.admin.pages.program.unsubmit_programs');
  } catch (\Exception $ex) {
   return back()->with('error',  'something went wrong');
  }

}


//get_unsubmit_program
public function get_unsubmit_program($request)
{
    try{

          if (
          $request->ajax()) {
          $data =DB::table('programs')
          ->where('submit','=',0)
          ->orderBy('programs.id', 'desc')
          ->leftJoin('corporates', 'corporates.id', '=', 'programs.corporate_id')
          ->leftJoin('statuses', 'statuses.id', '=', 'programs.status_id')
          ->select('programs.*', 'corporates.company_name','corporates.email','corporates.section','corporates.image as corporate_image','corporates.city','statuses.status')
          ->get();
          return Datatables()->of($data)
          ->addIndexColumn()
          //status
          ->addColumn('status', function($data){
          $statutsBtn =  $data->status;
          return $statutsBtn;
          })
          ->rawColumns(['status'])

            //created_at
        ->addColumn('created_at',function($data){

            $created_at=Carbon::parse($data->created_at)->format('d M Y');

            return $created_at;
            })
            ->rawColumns(['created_at'])
          //image
          ->addColumn('image', function($data){
          if($data->image!= null){
          $image = $data->image;
          }else{
          $image =$data->corporate_image;
          }

          return $image;
          })
          ->rawColumns(['image'])

          //description
          ->addColumn('description', function($data){

          $description= substr($data->description_en,0,20);
          return $description;
          })
          ->rawColumns(['description'])


          //visibility
          ->addColumn('visibility', function($data){



          if($data->program_type==1){
          $visibility = "public";


          }elseif($data->program_type==2){

          $visibility = "semi_private";

          }else{

          $visibility = "private";

          }

          return $visibility;
          })
          ->rawColumns(['visibility'])

          //reporter_quantity


          ->addColumn('quantity', function($data){

          if($data->reporter_quantity==1){
          $quantity = "Full";
          }else{

          $quantity = "Light";

          }

          return $quantity;
          })
          ->rawColumns(['quantity'])


          //management


          ->addColumn('management', function($data){

          if($data->management==1){
          $management = "Hacking SD";
          }else{

          $management = $data->company_name;

          }

          return $management;
          })
          ->rawColumns(['management'])


          //action

          ->addColumn('action', function($data){
          $actionBtn = ' <div class="d-inline-block">
          <a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
          <i class="bx bx-dots-vertical-rounded">

          </i></a>
          <div class="dropdown-menu dropdown-menu-end m-0">
          <a  style="color: #5b636c;margin-left: 18px;text-decoration: none;" id="'. $data->id.'"href="show_program/'.$data->id.'"><i class="bx bx-show text-success"></i> Show Program</a>
          <br><br><a  style="color: #5b636c;margin-left: 18px;text-decoration: none;" id="'. $data->id.'"href="edit_program/'.$data->id.'"><i class="bx bx-edit text-info"></i> Edit Program</a>
          <br><a class=" status-modal dropdown-item " id="'. $data->id.'"href=""data-bs-toggle="modal" data-bs-target="#progarm_edit_status"><i class="bx bxs-hourglass-top text-warning"></i> Status</a>
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



//program show page using ajax

public function show($request)
{

    try {
        $program = Program::where('id', $request->id)
        ->with('corporate')->
        with('program_update',function($query){
            return $query->orderBy('created_at','DESC')->get();
        })->with('priority')
        ->first();

        if ($program) {
                       //program_brife
          $targets = Target::where('program_id', $request->id)->with('type_target')->get();


          //hactivity
          $hacktivities = Report::where('hacktivity', 1)->where('program_id', $request->id)->orderBy('updated_at', 'DESC')->with('program.corporate')->with('reporter')->get();


          //hall_of_fame
            $hall_of_fames =Report::with('reporter')
          -> where('status_id',3)
          ->where('program_id', $request->id)
          ->select('reporter_id',DB::raw("count(status_id) as count"))
          ->take('10')
          ->orderBy('count', 'desc')
          ->groupBy('reporter_id')->get();

         $program_report_count=Report::
          where('status_id',3)
         ->where('program_id', $request->id)->count();







            return view('content.admin.pages.program.show_program', compact(['program_report_count','hall_of_fames','hacktivities','program', 'targets']));
        } else {

            return redirect()->back()->with('error', "something went wrong.");
        }
           } catch (\Exception $ex) {

        return back()->with('error', 'something went wrong');
    }

}




//program edit page using ajax

public function edit($request)
{
    try{

      $check=Program::with('priority')->where('id',$request->id)->first();
      if($check){
      $program=$check;

      $type_targets=TypeTarget::all();
      $targets= Target::where('program_id',$check->id)->get();
      return view('content.admin.pages.program.edit_program',compact(['type_targets','targets','program']));
      }else{
      return redirect()->back()->with('error', "Something Went Wrong.");
      }

      } catch (\Exception $ex) {

      return back()->with('error',  'something went wrong');

  }

}








//update program to database

public function update($request)
{


    try{
              //start validate priority
              $range_to=$request->range_to;
              $validator = Validator::make(compact('range_to'), [
              'range_to' => 'required|array',
              'range_to.*' => 'integer'
              ]);


              if ($validator->fails()) {
              return redirect()->back()->withErrors(
              [
              'range_to' => 'all fields are required.'
              ]);
              }

              $range_from=$request->range_from;
              $validator = Validator::make(compact('range_from'), [
              'range_from' => 'required|array',
              'range_from.*' => 'integer'
              ]);

              if ($validator->fails()) {
              return redirect()->back()->withErrors(
              [
              'range_from' => 'all fields are required.'
              ]);
              }
              //end of validation

              $check=Program::find($request->program_id);

              if(!$check){
              return redirect()->back()->with('error', "Something Went Wrong.");

              }else{


              $program=[
              'reporter_quantity'=>$request->reporter_quantity,
              'program_type'=>$request->program_type,
              'management'=>$request->management,
              'currency'=>$request->currency,
              'description_ar'=>$request->description_ar,
              'description_en'=>$request->description_en,
              'policy_ar'=>$request->policy_ar,
              'policy_en'=>$request->policy_en,

              ];
              if($request->hasFile('image')){

              $program['image']=SaveImageTrait::save_image($request->image,'program');
              $program['corporate_id']=$check->corporate_id;

              }else{
              $program=$request->all();
              $program['corporate_id']=$check->corporate_id;

              }
              //update program
              $check->update($program);

              //start of priority
              $priority=Priority::where('program_id',$request->program_id)->get();
              if(!$priority){
              //priority create if not exist
              $i=0;
              $count=count($request->severity);
              for($i;$i<$count;$i++){
              Priority::create([
              'severity'=>$request->severity[$i],
              'range_from'=>$request->range_from[$i],
              'range_to'=>$request->range_to[$i],
              'program_id'=>$request->program_id,

              ]);
              }

              }else{

              // delete priority  if  exist after that create
              $priority=Priority::where('program_id',$request->program_id)->delete();
              $i=0;
              $count=count($request->severity);
              for($i;$i<$count;$i++){
              Priority::create([
              'severity'=>$request->severity[$i],
              'range_from'=>$request->range_from[$i],
              'range_to'=>$request->range_to[$i],
              'program_id'=>$request->program_id,

              ]);
              }
              }

              //end of priority


              //==============================================================


                //start target

                $target = Target::where('program_id', $request->program_id)->get();

                if (!$target) {


                    //priority create if not exist



                    $x = 0;

                    $count = count($request->target);

                    for ($i; $x < $count; $x++) {

                        Target::create([
                            'target' => $request->target[$x],
                            'type_target_id' => $request->field[$x],
                            'scope' => $request->scope[$x],
                            'program_id' => $request->program_id,

                        ]);
                    }

                    ProgramUpdate::create(['program_id'=>$check->id,'program_update'=>'New Targets have been added']);


                } else {
                    $delete = Target::where('program_id', $request->program_id)->delete();

                    $count = count($request->target);

                    $target = [];
                    $type_target_id = [];
                    $scope = [];
                    $i = 0;
                    for ($i; $i < $count; $i++) {
                        if ($request->target[$i] != null && $request->field[$i] != null && $request->scope[$i] != null) {
                            $scope[] = $request->scope[$i];
                            $target[] = $request->target[$i];
                            $type_target_id[] = $request->field[$i];

                        }


                    }
                   // delete priority  if  exist after that create

                   $x = 0;
                   $count = count($target);
                   for ($i; $x < $count; $x++) {
                       Target::create([
                           'target' => $target[$x],
                           'type_target_id' => $type_target_id[$x],
                           'scope' => $scope[$x],
                           'program_id' => $request->program_id,

                       ]);
                   }
                   ProgramUpdate::create(['program_id'=>$check->id,'program_update'=>'Targets have been updated']);

              //end of target
              Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
              'activity'=>'Updated Program ','description_activity'=>" Updated Program  "]);

             	// send notification
           $this->corporate_notification->
           sendCorporateNotification('Program Update',Auth::guard('admin')->user()->name.'  has updated your program ',
           'setting_program',$request->program_id,$check->corporate_id,'admin');


              return redirect()->back()->with('success', "Program Updated successfully .");
              }
              }
               } catch (\Exception $ex) {

              return back()->with('error',  'something went wrong');

    }

}






//get program status using ajax
public function get_status($request)

{

    try{
        $id = $request->id;
        $check = Program::find($id);
        $data=$check->status_id;
        $program_id=$check->id;
        return response()->json(['data' => $data,'program_id' => $program_id]);
    } catch (\Exception $ex) {

        return back()->with('error',  'something went wrong');

    }

}






//update program status
public function status($request)
{

    try{
        $id = $request->id;
        $check = Program::find($id);
        if($check){
        $data=Program::where('id',$id)->update(['status_id' => $request->status]);
        Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
        'activity'=>'Updated Program ','description_activity'=>" Updated Program Status "]);

        $status=Program::where('id',$id)->first()->status->status;
       	// send notification
           $this->corporate_notification->
           sendCorporateNotification('Program Status',Auth::guard('admin')->user()->name.'  changed the status of the program to '.$status,
           'setting_program',$id,$check->corporate_id,'admin');



        return response()->json(['status' => 'success'], 200);
        }else{
        return response()->json(['status' => 'failed']);

        }
    } catch (\Exception $ex) {

        return back()->with('error',  'something went wrong');
    }

}







//delete program
public function destroy($request)
{

    try{
          $id = $request->id;
          $check = Program::find($id);
          if($check){
          $data=$check;
          $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://");
          $replace=$actual_link.$_SERVER['HTTP_HOST']."/";
          $program = Program::where('id' ,$data->id)->select('image')->first()->image;
          if($program!=null){
          $image_path= str_replace($replace,"",$program);
          if(file_exists($image_path)){
          unlink($image_path);
          }
          }
          $data->delete();
          Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
          'activity'=>'deleted Program ','description_activity'=>" delted Program  "]);
       // send notification
       $this->corporate_notification->
       sendCorporateNotification('Program Delete',Auth::guard('admin')->user()->name.'  has Deleted your program ',
       'corporate_notification',null,$check->corporate_id,'admin');
          return response()->json(['status' => 'success'], 200);
          }else{
          return response()->json(['status' => 'failed']);
          }

    } catch (\Exception $ex) {
          return back()->with('error',  'something went wrong');
    }

}

}





