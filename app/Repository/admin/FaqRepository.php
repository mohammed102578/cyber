<?php

namespace App\Repository\admin;

use App\Interfaces\admin\FaqInterface;
use App\Models\Activity;
use App\Models\Faq;
use App\Models\FaqClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FaqRepository implements FaqInterface
{

//get faq
public function index()
{
try{

  $faq_classes=FaqClass::all();
return view('content.admin.pages.general.faqs',compact('faq_classes'));

} catch (\Exception $ex) {

return back()->with('error',  'something went wrong');

}
}

//get all faq use ajax and yajar data table
public function get_faqs($request)
{
    try{
        if (
        $request->ajax()) {

          $data =DB::table('faqs')
          ->orderBy('faqs.id', 'DESC')
          ->leftJoin('faq_classes', 'faq_classes.id', '=', 'faqs.faq_class_id')
          ->select('faqs.id','faqs.question','faqs.answer','faq_classes.name as faq_class')
          ->get();

        return Datatables()->of($data)
        ->addIndexColumn()

        ->addColumn('action', function($data){
        $actionBtn = ' <div class="d-inline-block">
        <a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="bx bx-dots-vertical-rounded">

        </i></a>
        <div class="dropdown-menu dropdown-menu-end m-0">
        <a class=" edit_modal_faq dropdown-item " id="'. $data->id.'"href=""data-bs-toggle="modal" data-bs-target="#edit_faq"><i class="bx bx-edit text-info"></i> Edit faq</a>

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





//edit faq use ajax
public function edit($request)
{

  try{
    $id = $request->id;
    $check = Faq::find($id);
    $faq_class = FaqClass::find($check->faq_class_id);
    $data=$check;
    $data->name=$faq_class->name;
    return response()->json($data);
  } catch (\Exception $ex) {

    return back()->with('error',  'something went wrong');

  }

}

//updated faq
public function update($request)
{

    try{
          $id = $request->id;
          $check = Faq::find($id);
          if($check){
          $faq_data=[
            "question"=> $request->question,
            'answer'=> $request->answer,
            'faq_class_id'=>$request->faq_class_id
          ];
          $data=Faq::where('id', $check->id)->update($faq_data);
          Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
          'activity'=>'Updated Faq ','description_activity'=>" Updated Faq"]);
          return response()->json(['status' => 'success'], 200);
          }else{
          return response()->json(['status' => 'failed']);
          }

    } catch (\Exception $ex) {
          return back()->with('error',  'something went wrong');
    }

}

//create faq
public function store($request)
{

  try{
      $check = Faq::create($request->all());
      if($check){
      Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
      'activity'=>'Created Faq ','description_activity'=>" Created Faq  "]);
      return response()->json(['status' => 'success'], 200);
      }else{
      return response()->json(['status' => 'failed']);
      }
  } catch (\Exception $ex) {
      return back()->with('error',  'something went wrong');
  }
}



// delete faq
public function destroy($request)
{

try{
    $id = $request->id;
    $check = Faq::find($id);
    if($check){
    $data=$check;
    $data->delete();
    Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
    'activity'=>'Deleted Faq ','description_activity'=>" Deleted Faq"]);
    return response()->json(['status' => 'success'], 200);
    }else{
    return response()->json(['status' => 'failed']);
    }
} catch (\Exception $ex) {
    return back()->with('error',  'something went wrong');
}
}





















}
