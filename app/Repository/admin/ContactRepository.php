<?php

namespace App\Repository\admin;

use App\Interfaces\admin\ContactInterface;
use App\Models\Activity;
use App\Models\Contact;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContactRepository implements ContactInterface
{

//get vulnerability page
public function index()
{
    try{
       return view('content.admin.pages.general.contact');
    } catch (\Exception $ex) {
       return back()->with('error',  'something went wrong');
    }
}

//get all vulnerability use ajax and yajar data table
public function get_contacts($request)
{
    try{
        if (
        $request->ajax()) {

        $data =DB::table('contacts')->orderBy('id','DESC')->get();

        return Datatables()->of($data)
        ->addIndexColumn()

        //answer
        ->addColumn('answer', function($data){
            if($data->answer == 1){
            $answerBtn = 'Communication done';
            }else{
              $answerBtn =  'Communication is not done';
            }
            return $answerBtn;
            })
            ->rawColumns(['answer'])

            // productInterest
            ->addColumn(' productInterest ', function($data){
                if($data-> productInterest != null){
                $productInterest = $data-> productInterest;
                }else{
                  $productInterest =  'Not found ProductInterest';
                }
                return $productInterest;
                })
                ->rawColumns(['productInterest'])

              //created_at
          ->addColumn('created_at',function($data){

            $created_at=Carbon::parse($data->created_at)->format('d F Y');

            return $created_at;
            })
            ->rawColumns(['created_at'])

            //name
            ->addColumn('name',function($data){
                $name = $data->first_name." ".$data->last_name;
                return $name;
                })
                ->rawColumns(['name'])
        ->addColumn('action', function($data){
        $actionBtn = ' <div class="d-inline-block">
        <a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="bx bx-dots-vertical-rounded">

        </i></a>
        <div class="dropdown-menu dropdown-menu-end m-0">
        <a class=" hapus-modale_answer dropdown-item " id="'. $data->id.'"><i class="bx bx-comment-edit text-info"></i>Answer Status</a>

        <div class="dropdown-divider">

        </div>
        <a class=" hapus-modale_delete dropdown-item " id="'. $data->id.'" ><i class="bx bx-trash text-danger" style="margin-bottom:6px;"></i> Delete</a>
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

//block corporate
public function contacts_answer($request)
{

    try{

          $id = $request->id;
          $check = Contact::find($id);
          if($check)
          {
          $data=$check;
          if($data->answer == 1)
          {
          $answer=0;
          }else{
          $answer=1;
          }
          Contact::where('id',$data->id)->update(['answer' => $answer]);
          Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
          'activity'=>'Updated Contact ','description_activity'=>" Updated Contact Answer Status "]);
          return response()->json(['status' => 'success'], 200);
          }else{
            return response()->json(['status' => 'failed']);
          }
      } catch (\Exception $ex) {
          return back()->with('error',  'something went wrong');
      }
}


//delete contact
public function destroy($request)

{
    try{
        $id = $request->id;
        $check = Contact::find($id);
        if($check){
        $data=$check;
        Contact::where('id',$data->id)->delete();
        Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
        'activity'=>'deleted Contact ','description_activity'=>"deleted Contact"]);
        return response()->json(['status' => 'success']);
        }else{
        return response()->json(['status' => 'failed']);
        }

    } catch (\Exception $ex) {
       return back()->with('error',  'something went wrong');
    }
}






}
