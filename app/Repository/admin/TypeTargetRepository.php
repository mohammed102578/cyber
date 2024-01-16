<?php

namespace App\Repository\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TargetRequest;
use App\Interfaces\admin\TypeTargetInterface;
use App\Models\Activity;
use Illuminate\Http\Request;
use App\Models\Corporate\TypeTarget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TypeTargetRepository implements TypeTargetInterface
{

//get type target page
public function index()
{

    try{
         return view('content.admin.pages.general.type_targets');
    } catch (\Exception $ex) {
         return back()->with('error',  'something went wrong');
    }
}

//get all target use ajax and yajar data table
public function get_type_targets($request)
{
    try{
        if (
        $request->ajax()) {

        $data =DB::table('type_targets')->orderBy('id','DESC')->get();

        return Datatables()->of($data)
        ->addIndexColumn()

        ->addColumn('action', function($data){
        $actionBtn = ' <div class="d-inline-block">
        <a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="bx bx-dots-vertical-rounded">

        </i></a>
        <div class="dropdown-menu dropdown-menu-end m-0">
        <a class=" edit_modal_target dropdown-item " id="'. $data->id.'"href=""data-bs-toggle="modal" data-bs-target="#edit_target"><i class="bx bx-edit text-info"></i> Edit target</a>

        <div class="dropdown-divider">

        </div>
        <a class=" hapus-modale dropdown-item " id="'. $data->id.'" ><i class="bx bx-trash text-danger" style="margin-bottom:6px;"></i> Delete </a>
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




//store target



public function store($request)
{

    try{

        $check = TypeTarget::create($request->all());
        if($check){
        Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
        'activity'=>'created Target','description_activity'=>"created Target "]);
        return response()->json(['status' => 'success'], 200);
        }else{
        return response()->json(['status' => 'failed']);
        }
    } catch (\Exception $ex) {
        return back()->with('error',  'something went wrong');
    }

}



//edit target use ajax
public function edit($request)
{
    try{
        $id = $request->id;
        return  $check = TypeTarget::find($id);
        $data=$check;
        return response()->json($data);
    } catch (\Exception $ex) {
        return back()->with('error',  'something went wrong');
    }

}

//update target
public function update($request)
{

    try{
        $id = $request->id;
        $check = TypeTarget::find($id);
        if($check){
        $target_data=[
        "target"=> $request->target,
        ];
        $data=TypeTarget::where('id', $check->id)->update($target_data);
        Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
        'activity'=>'Updated Target','description_activity'=>"updated Target "]);
        return response()->json(['status' => 'success'], 200);
        }else{
        return response()->json(['status' => 'failed']);
        }
    } catch (\Exception $ex) {

        return back()->with('error',  'something went wrong');
    }

}



// delete type target
public function destroy($request)
{

    try{
        $id = $request->id;
        $check = TypeTarget::find($id);

        if($check){
        $data=$check;

        $data->delete();
        Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
        'activity'=>'deleted Target','description_activity'=>"deleted Target "]);

        return response()->json(['status' => 'success'], 200);


        }else{
        return response()->json(['status' => 'failed']);

        }


    } catch (\Exception $ex) {

        return back()->with('error',  'something went wrong');

    }
}




}
