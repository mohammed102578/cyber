<?php

namespace App\Repository\corporate;

use App\Http\Services\Notification;
use App\Interfaces\corporate\SecurityInterface;
use App\Models\Corporate\Corporate;
use Illuminate\Support\Facades\Auth;

class SecurityRepository implements SecurityInterface
{


    public object $admin_notification;
    public function __construct()
    {
        $this->admin_notification = new Notification;

    }
public function security($request)

{
    try{
        $corporate=Corporate::find(Auth::guard('corporate')->user()->id);
        $recent_devices=$corporate->deviceable()->take(5)->orderBy('id','desc')->get();
        return view('content.corporate.pages.security',compact('recent_devices'));
    }catch(\Exception $ex){
    return redirect()->back()->with('error','something went wrong');
    }
}



public function delete_account($request){

     try{
        $check=Corporate::find($request->id);
        if(!$check){
        redirect()->back()->with('error', "Something Went Wrong.");
        }else{
        if (password_verify($request->delete_password, $check->password)) {
        $check->delete();
         //send notification
         $this->admin_notification->
         sendAdminNotification('Delete Account',Auth::guard('corporate')->user()->company_name.' Deleted His Account ',
         'admin_trash_corporates',null,'corporate');

        return redirect()->route('corporate_logout')->with('success', "Account Deleted successfully .");
        }else{
        return redirect()->route('corporate_security')->withErrors(
        [
        'delete_password' => 'The password is incorrect.',
        ]);
        }
        }
    }catch(\Exception $ex){
    return redirect()->back()->with('error','something went wrong');
    }

}


}
