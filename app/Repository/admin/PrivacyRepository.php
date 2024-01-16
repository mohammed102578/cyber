<?php

namespace App\Repository\admin;

use App\Interfaces\admin\PrivacyInterface;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;
use App\Models\admin\Privacy;

class PrivacyRepository implements PrivacyInterface
{


  public function index()
  {
  try{

    $privacy=Privacy::first();
  return view('content.admin.pages.general.privacy',compact('privacy'));

  } catch (\Exception $ex) {

  return back()->with('error',  'something went wrong');

  }
  }

//updated or store privacy setting
public function store_update($request)
{


     $data= $request->except('_token');


   $check = Privacy::updateOrCreate(['id'=>1],$data);


 if($check){
  Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
  'activity'=>'Update Privacy ','description_activity'=>" Update Privacy  "]);
  return back()->with('success',  'Privacy Updated Successfully');
  }else{
    return back()->with('error',  'something went wrong');
  }


  try{   } catch (\Exception $ex) {
      return back()->with('error',  'something went wrong');
  }

}




}







