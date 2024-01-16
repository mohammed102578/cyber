<?php

namespace App\Repository\admin;

use App\Interfaces\admin\HobbyInterface;
use App\Models\Activity;
use App\Models\Hobby;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HobbyRepository implements HobbyInterface
{

//get hobby
public function index()
{
try{
return view('content.admin.pages.general.hobbies');

} catch (\Exception $ex) {

return back()->with('error',  'something went wrong');

}
}

//get all hobby use ajax and yajar data table
public function get_hobbies($request)
{
    try{
        if (
        $request->ajax()) {

        $data =DB::table('hobbies')->orderBy('id','DESC')->get();

        return Datatables()->of($data)
        ->addIndexColumn()

        ->addColumn('action', function($data){
        $actionBtn = ' <div class="d-inline-block">
        <a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="bx bx-dots-vertical-rounded">

        </i></a>
        <div class="dropdown-menu dropdown-menu-end m-0">
        <a class=" edit_modal_hobby dropdown-item " id="'. $data->id.'"href=""data-bs-toggle="modal" data-bs-target="#edit_hobby"><i class="bx bx-edit text-info"></i> Edit hobby</a>

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





//edit hobby use ajax
public function edit($request)
{

  try{
    $id = $request->id;
    $check = Hobby::find($id);
    $data=$check;
    return response()->json($data);
  } catch (\Exception $ex) {

    return back()->with('error',  'something went wrong');

  }

}

//updated hobby
public function update($request)
{

    try{
          $id = $request->id;
          $check = Hobby::find($id);
          if($check){
          $hobby_data=[
          "name"=> $request->name,
          ];
          $data=Hobby::where('id', $check->id)->update($hobby_data);
          Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
          'activity'=>'Updated Hobby ','description_activity'=>" Updated Hobby"]);
          return response()->json(['status' => 'success'], 200);
          }else{
          return response()->json(['status' => 'failed']);
          }

    } catch (\Exception $ex) {
          return back()->with('error',  'something went wrong');
    }

}

//create hobby
public function store($request)
{

  try{
      $check = Hobby::create($request->all());
      if($check){
      Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
      'activity'=>'Created Hobby ','description_activity'=>" Created Hobby  "]);
      return response()->json(['status' => 'success'], 200);
      }else{
      return response()->json(['status' => 'failed']);
      }
  } catch (\Exception $ex) {
      return back()->with('error',  'something went wrong');
  }
}



// delete hobby
public function destroy($request)
{

try{
    $id = $request->id;
    $check = Hobby::find($id);
    if($check){
    $data=$check;
    $data->delete();
    Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
    'activity'=>'Deleted Hobby ','description_activity'=>" Deleted Hobby"]);
    return response()->json(['status' => 'success'], 200);
    }else{
    return response()->json(['status' => 'failed']);
    }
} catch (\Exception $ex) {
    return back()->with('error',  'something went wrong');
}
}





















}
