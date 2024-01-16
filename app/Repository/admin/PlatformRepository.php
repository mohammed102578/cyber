<?php

namespace App\Repository\admin;

use App\Interfaces\admin\PlatformInterface;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\PlatformSetting;
use App\Traits\SaveImageTrait;
class PlatformRepository implements PlatformInterface
{
  use SaveImageTrait;


  public function index()
  {
  try{

    $platform=PlatformSetting::first();
  return view('content.admin.pages.admin_setting.platform_setting',compact('platform'));

  } catch (\Exception $ex) {

  return back()->with('error',  'something went wrong');

  }
  }

//updated or store platform setting
public function store_update($request)
{

    try{
     $data= $request->except('_token');
if($request->has('logo') && $request->has('company_logo')){

  $data['logo']=SaveImageTrait::save_image($request->logo,'platform');
  $data['company_logo']=SaveImageTrait::save_image($request->company_logo,'platform');

}elseif(!$request->has('logo') && !$request->has('company_logo')){
  $data= $request->except('_token');

}elseif(!$request->has('logo')){
  $data['company_logo']=SaveImageTrait::save_image($request->company_logo,'platform');
}elseif(!$request->has('company_logo')){
  $data['logo']=SaveImageTrait::save_image($request->logo,'platform');

}

   $check = PlatformSetting::updateOrCreate(['id'=>1],$data);


 if($check){
  Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
  'activity'=>'Update platform_setting Data ','description_activity'=>" Update platform_setting Data  "]);
  return back()->with('success',  'Data Updated Successfully');
  }else{
    return back()->with('error',  'something went wrong');
  }


  } catch (\Exception $ex) {
      return back()->with('error',  'something went wrong');
  }

}




}







