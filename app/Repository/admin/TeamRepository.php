<?php

namespace App\Repository\admin;

use App\Interfaces\admin\TeamInterface;
use App\Models\Activity;
use App\Models\Admin\Team;
use App\Traits\SaveImageTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeamRepository implements TeamInterface
{

    use SaveImageTrait;

//get team
public function index()
{
try{

return view('content.admin.pages.general.teams');

} catch (\Exception $ex) {

return back()->with('error',  'something went wrong');

}
}

//get all team use ajax and yajar data table
public function get_teams($request)
{
    try{
        if (
        $request->ajax()) {

          $data =DB::table('teams')->orderBy('id','DESC')->get();

        return Datatables()->of($data)
        ->addIndexColumn()

        ->addColumn('action', function($data){
        $actionBtn = ' <div class="d-inline-block">
        <a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="bx bx-dots-vertical-rounded">

        </i></a>
        <div class="dropdown-menu dropdown-menu-end m-0">
        <a class=" edit_modal_team dropdown-item " id="'. $data->id.'"href=""data-bs-toggle="modal" data-bs-target="#edit_team"><i class="bx bx-edit text-info"></i> Edit team</a>

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

//create team
public function store($request)
{

  try{
    $data= $request->except('_token');
    $data['image']=SaveImageTrait::save_image($request->image,'team');

      $check = Team::create($data);
      if($check){
      Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
      'activity'=>'Created Team ','description_activity'=>" Created Team  "]);
      return response()->json(['status' => 'success'], 200);
      }else{
      return response()->json(['status' => 'failed']);
      }
  } catch (\Exception $ex) {
      return back()->with('error',  'something went wrong');
  }
}





//edit team use ajax
public function edit($request)
{

  try{
    $id = $request->id;
    $check = Team::find($id);
    $data=$check;
    return response()->json($data);
  } catch (\Exception $ex) {

    return back()->with('error',  'something went wrong');

  }

}

//updated team
public function update($request)
{

    try{

          $id = $request->id;
          $check = Team::find($id);
          if($check){
            if($request->has('image')){
                $team_data=[
                    "name"=> $request->name,
                    'job'=> $request->job,
                    'facebook'=>$request->facebook,
                     "linkedIn"=> $request->linkedIn,
                    'twitter'=> $request->twitter,
                    'instagram'=>$request->instagram,
                  ];
                  $team_data['image']=SaveImageTrait::save_image($request->image,'team');

                  $data=Team::where('id', $check->id)->update($team_data);

            }else{
                $team_data=[
                    "name"=> $request->name,
                    'job'=> $request->job,
                    'facebook'=>$request->facebook,
                     "linkedIn"=> $request->linkedIn,
                    'twitter'=> $request->twitter,
                    'instagram'=>$request->instagram,
                  ];
                  $data=Team::where('id', $check->id)->update($team_data);

            }


          Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
          'activity'=>'Updated Team ','description_activity'=>" Updated Team"]);
          return response()->json(['status' => 'success'], 200);
          }else{
          return response()->json(['status' => 'failed']);
          }

    } catch (\Exception $ex) {
          return back()->with('error',  'something went wrong');
    }

}



// delete team
public function destroy($request)
{

try{
    $id = $request->id;
    $check = Team::find($id);
    if($check){
    $data=$check;
    $data->delete();
    Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
    'activity'=>'Deleted Team ','description_activity'=>" Deleted Team"]);
    return response()->json(['status' => 'success'], 200);
    }else{
    return response()->json(['status' => 'failed']);
    }
} catch (\Exception $ex) {
    return back()->with('error',  'something went wrong');
}
}





















}
