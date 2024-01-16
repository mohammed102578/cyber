<?php

namespace App\Repository\admin;

use App\Interfaces\admin\TermInterface;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Term;

class TermRepository implements TermInterface
{


  public function index()
  {
  try{

    $term=Term::first();
  return view('content.admin.pages.general.term',compact('term'));

  } catch (\Exception $ex) {

  return back()->with('error',  'something went wrong');

  }
  }

//updated or store term setting
public function store_update($request)
{


     $data= $request->except('_token');


   $check = Term::updateOrCreate(['id'=>1],$data);


 if($check){
  Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
  'activity'=>'Update Term ','description_activity'=>" Update Term  "]);
  return back()->with('success',  'Term Updated Successfully');
  }else{
    return back()->with('error',  'something went wrong');
  }


  try{   } catch (\Exception $ex) {
      return back()->with('error',  'something went wrong');
  }

}




}







