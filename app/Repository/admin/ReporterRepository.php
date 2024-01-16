<?php

namespace App\Repository\admin;

use App\Models\Reporter\Reporter;
use App\Interfaces\admin\ReporterInterface;
use App\Models\Activity;
use App\Models\Nationality;
use App\Models\Hobby;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ReporterRepository implements ReporterInterface
{

//get reporter page
public function index()
{
    try{
        $nationalities=Nationality::get();
        $hobby=Hobby::get();
        return view('content.admin.pages.reporter.reporters',compact(['nationalities','hobby']));
    } catch (\Exception $ex) {

        return back()->with('error', 'something went wrong');

    }
}


//get all reporters using ajax
public function get_reporters($request)
{
    try{

        if (
        $request->ajax()) {
        $data =Reporter::orderBy('id','DESC')->get();
        return Datatables()->of($data)
        ->addIndexColumn()
        ->addColumn('status', function($data){
        if($data->status == 1){
        $statutsBtn = 'Blocked';
        }else{
        $statutsBtn =  'UnBlock';
        }
        return $statutsBtn;
        })
        ->rawColumns(['status'])
        ->addColumn('name',function($data){
        $name = $data->first_name." ".$data->last_name;
        return $name;
        })
        ->rawColumns(['name'])
          //created_at
          ->addColumn('created_at',function($data){

            $created_at=Carbon::parse($data->created_at)->format('d M Y');

            return $created_at;
            })
            ->rawColumns(['created_at'])
               //last_seen_at
               ->addColumn('last_seen_at', function ($data) {

                $created_at = Carbon::parse($data->last_seen_at)->format('d M Y H:i');

                return $created_at;
            })
            ->rawColumns(['last_seen_at'])
        ->addColumn('hobby',function($data){
        $statutsBtn = json_decode($data->hobby);
        $hobby_data=[];
        foreach($statutsBtn as $hobby){
        $hobby_data[]=$hobby;
        }
        return $hobby_data;
        })
        ->rawColumns(['hobby'])
        ->addColumn('address',function($data){
        $statutsBtn = $data->nationality." / ".$data->city;
        return $statutsBtn;
        })
        ->rawColumns(['address'])
        ->addColumn('action', function($data){
        $actionBtn = ' <div class="d-inline-block">
        <a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="bx bx-dots-vertical-rounded">
        </i></a>
        <div class="dropdown-menu dropdown-menu-end m-0">
        <a class=" edit_modal_reporter dropdown-item " id="'. $data->id.'"href=""data-bs-toggle="modal" data-bs-target="#edit_reporter"><i class="bx bx-edit text-info"></i> Edit Reporter</a>
        <a class=" hapus-modale_block dropdown-item " id="'. $data->id.'" ><i class="bx bx-block  text-warning"></i> Block</a>
        <div class="dropdown-divider">
        </div>
        <a class=" hapus-modale dropdown-item " id="'. $data->id.'" ><i class="bx bx-trash text-danger" style="margin-bottom:6px;"></i> Delete</a>
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


//edit reporter
public function edit($request)
{

    try{
        $id = $request->id;
        $check = Reporter::find($id);
        if($check){
        $data=$check;
        return response()->json($data);
        }
    } catch (\Exception $ex) {
        return back()->with('error',  'something went wrong');
    }
}



//update reporter
public function update($request)
{

    try{
        $id = $request->id;
        $check = Reporter::find($id);
        if($check){
        $reporter_data=[
        "first_name"=>$request->first_name,
        "last_name"=>$request->last_name,
        "phone"=>$request->phone,
        "birthday"=>$request-> birthday,
        "nationality"=>$request->nationality,
        "city"=>$request->city,
        "email"=>$request->email,
        "job"=>$request->job,
        "company"=>$request->company,
        ];
        if($request->password !=null){
        $reporter_data['password']=bcrypt($request->password);
        }
        $data=Reporter::where('id', $check->id)->update($reporter_data);
        Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
        'activity'=>'Updated reporter','description_activity'=>"updated reporter "]);
        return response()->json(['status' => 'success'], 200);
        }else{
        return response()->json(['status' => 'failed']);
        }
    } catch (\Exception $ex) {
       return back()->with('error',  'something went wrong');
    }

}






//block reporter
public function block($request)
{

    try{
        $id = $request->id;
        $check = Reporter::find($id);
        if($check){
        $data=$check;
        if($data->status == 1){
        $status=0;
        }else{
        $status=1;
        }
        Reporter::where('id',$data->id)->update(['status' => $status]);
        Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
        'activity'=>'Updated Reporter Status','description_activity'=>"updated Reporter Status "]);
        return response()->json(['status' => 'success'], 200);
        }else{
        return response()->json(['status' => 'failed']);
        }

    } catch (\Exception $ex) {
       return back()->with('error',  'something went wrong');
    }
}




//delete reporter
public function soft_delete($request)
{

    try{
        $id = $request->id;
        $check = Reporter::find($id);
        if($check){
        $data=$check;

        $data->delete();
        Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
        'activity'=>'deleted reporter','description_activity'=>"soft deleted reporter "]);
        return response()->json(['status' => 'success'], 200);
        }else{
        return response()->json(['status' => 'failed']);

        }


    } catch (\Exception $ex) {

        return back()->with('error',  'something went wrong');

    }
}


//====================================soft delete

public function trash_reporters()
{
    try{

        return view('content.admin.pages.reporter.trash_reporters');
    } catch (\Exception $ex) {

        return back()->with('error', 'something went wrong');

    }
}


//===================get all reporters using ajax
public function get_trash_reporters($request)
{
    try{

        if (
        $request->ajax()) {
        $data =Reporter::onlyTrashed()->orderBy('id','DESC')->get();
        return Datatables()->of($data)
        ->addIndexColumn()
        ->addColumn('status', function($data){
        if($data->status == 1){
        $statutsBtn = 'Blocked';
        }else{
        $statutsBtn =  'UnBlock';
        }
        return $statutsBtn;
        })
        ->rawColumns(['status'])
        ->addColumn('name',function($data){
        $statutsBtn = $data->first_name." ".$data->last_name;
        return $statutsBtn;
        })
        ->rawColumns(['name'])
        ->addColumn('hobby',function($data){
        $statutsBtn = json_decode($data->hobby);
        $hobby_data=[];
        foreach($statutsBtn as $hobby){
        $hobby_data[]=$hobby;
        }
        return $hobby_data;
        })
        ->rawColumns(['hobby'])
          //created_at
          ->addColumn('created_at',function($data){

            $created_at=Carbon::parse($data->created_at)->format('d M Y');

            return $created_at;
            })
            ->rawColumns(['created_at'])
        ->addColumn('address',function($data){
        $statutsBtn = $data->nationality." / ".$data->city;
        return $statutsBtn;
        })
        ->rawColumns(['address'])
        ->addColumn('action', function($data){
        $actionBtn = ' <div class="d-inline-block">
        <a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="bx bx-dots-vertical-rounded">
        </i></a>
        <div class="dropdown-menu dropdown-menu-end m-0">
        <a class=" restore_module dropdown-item " id="'. $data->id.'" ><i class="bx bx-arrow-back text-info"></i> Restore</a>
        <div class="dropdown-divider">
        </div>
        <a class=" hapus-modale dropdown-item " id="'. $data->id.'" ><i class="bx bx-trash text-danger"></i> Force Delete</a>
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


//destroy reporter from database

//delete reporter
public function destroy($request)
{

    try{
        $id = $request->id;
        $check =  Reporter::withTrashed()->findOrFail($id);

        if($check){
        $data=$check;
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://");
        $replace=$actual_link.$_SERVER['HTTP_HOST']."/";
        $corporate = Reporter::withTrashed()->where('id' ,$data->id)->select('image')->first()->image;
        $image_path= str_replace($replace,"",$corporate);
        if(file_exists($image_path)){
        unlink($image_path);
        }
        $data->forceDelete();
        Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
        'activity'=>'deleted reporter','description_activity'=>"deleted reporter from database"]);
        return response()->json(['status' => 'success'], 200);
        }else{
        return response()->json(['status' => 'failed']);

        }


    } catch (\Exception $ex) {

        return back()->with('error',  'something went wrong');

    }
}






//restore

//restore reporter
public function restore($request)
{

    try{
        $id = $request->id;
        $check =Reporter::withTrashed()->where('id', $id)->restore();
        if($check){

        Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
        'activity'=>'restore reporter','description_activity'=>"restore reporter from trash "]);
        return response()->json(['status' => 'success'], 200);
        }else{
        return response()->json(['status' => 'failed']);

        }


    } catch (\Exception $ex) {

        return back()->with('error',  'something went wrong');

    }
}




}
