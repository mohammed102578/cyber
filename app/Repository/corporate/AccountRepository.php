<?php

namespace App\Repository\corporate;

use App\Models\Nationality;
use App\Models\Hobby;
use App\Models\Corporate\Corporate;
use App\Http\Services\Notification;
use App\Interfaces\corporate\AccountInterface;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;
use App\Traits\SaveImageTrait;
class AccountRepository  implements AccountInterface
{


    use SaveImageTrait;
    public object $admin_notification;
    public function __construct()
    {
        $this->admin_notification = new Notification;

    }

public function index()
{
    try{
        $nationalities=Nationality::get();
        $hobby=Hobby::get();
        return view('content.corporate.pages.account',compact(['nationalities','hobby']));
    } catch (\Exception $ex) {
         return back()->with('error',  'something went wrong');
    }
}


//update account

public function update($request)
{

    try{

        if(isset($request->image)){
        $data=$request->except('_token');
        $data['image']=SaveImageTrait::save_image($request->image,'corporate_profile');
        $check=Corporate::find($request->id);
        if(!$check){
        return redirect()->back()->with( 'error', "Something Went Wrong.");
        }else{
        $check->update($data);
                    Activity::create(['activeable_id'=>Auth::guard('corporate')->user()->id,'activeable_type'=>'App\Models\Corporate\Corporate',
        'activity'=>'Updated Acount','description_activity'=>"updated Name And Image"]);

        //send notification
         $this->admin_notification->
         sendAdminNotification('Updated Account',Auth::guard('corporate')->user()->company_name.' Updated The Account Details And Image',
         'admin_corporate_profile',$check->id,'corporate');

        return redirect()->back()->with('success', "Account Updated successfully .");
        }
        }else{
        $check=Corporate::find($request->id);
        if(!$check){
        return redirect()->back()->with('error', "Something Went Wrong.");
        }else{
        $data=$request->except('_token');
        $check->update($data);
                    Activity::create(['activeable_id'=>Auth::guard('corporate')->user()->id,'activeable_type'=>'App\Models\Corporate\Corporate',
        'activity'=>'Updated Acount','description_activity'=>"updated Name "]);
          //send notification
          $this->admin_notification->
          sendAdminNotification('Updated Account',Auth::guard('corporate')->user()->company_name.' Updated The Account Details',
          'admin_corporate_profile',$check->id,'corporate');

        return redirect()->back()->with('success', "Account Updated successfully .");
        }
        }

    } catch (\Exception $ex) {
        return back()->with('error',  'something went wrong');
    }

}



//change username number


public function change_username($request)
{
    try{

        $check=Corporate::find($request->id);
        if(!$check){
        return redirect()->back()->with('error', "Something Went Wrong.");
        }else{
        if (password_verify($request->password, $check->password)) {
        $check->update(['username'=>$request->new_username]);
                    Activity::create(['activeable_id'=>Auth::guard('corporate')->user()->id,'activeable_type'=>'App\Models\Corporate\Corporate',
        'activity'=>'Updated Acount','description_activity'=>"updated user name "]);
          //send notification
          $this->admin_notification->
          sendAdminNotification('Updated user_name',Auth::guard('corporate')->user()->company_name.' Updated The user_name ',
          'admin_corporate_profile',$check->id,'corporate');

        return redirect()->back()->with('success', "Username Updated successfully .");
        }else{
        return redirect()->route('corporate_security')->withErrors(
        [
        'username_password' => 'The password is incorrect.',

        ]);
        }
        }
    } catch (\Exception $ex) {
         return back()->with('error',  'something went wrong');
    }
}




//update email
public function change_email($request)
{

    try{
        $check=Corporate::find($request->id);
        if(!$check){
        return redirect()->back()->with('error', "Something Went Wrong.");
        }else{
        if (password_verify($request->password, $check->password)) {
        $check->update(['email'=>$request->new_email]);
                    Activity::create(['activeable_id'=>Auth::guard('corporate')->user()->id,'activeable_type'=>'App\Models\Corporate\Corporate',
        'activity'=>'Updated Acount','description_activity'=>"updated E-mail "]);
          //send notification
          $this->admin_notification->
          sendAdminNotification('Updated E-mail',Auth::guard('corporate')->user()->company_name.' Updated The E-mail ',
          'admin_corporate_profile',$check->id,'corporate');
        return redirect()->back()->with('success', "E-mail Updated successfully .");

        }else{
        return redirect()->route('corporate_security')->withErrors(
        [
        'email_password' => 'The password is incorrect.',
        ]);
        }
        }
    } catch (\Exception $ex) {
        return back()->with('error',  'something went wrong');
    }
}




//password update
public function change_password($request)
{
    try{
        $check=Corporate::find($request->id);

        if(!$check)
        {
        return redirect()->back()->with('error', "Something Went Wrong.");
        }else{
        if (password_verify($request->old_password, $check->password))
         {
        $check->update(['password'=>$request->new_password]);
                    Activity::create(['activeable_id'=>Auth::guard('corporate')->user()->id,'activeable_type'=>'App\Models\Corporate\Corporate',
        'activity'=>'Updated Acount','description_activity'=>"updated password "]);
          //send notification
          $this->admin_notification->
          sendAdminNotification('Updated Password',Auth::guard('corporate')->user()->company_name.' Updated The Password ',
          'admin_corporate_profile',$check->id,'corporate');
        return redirect()->back()->with('success', "Password Updated successfully .");

        }else{
        return redirect()->route('corporate_security')->withErrors(
        [
        'old_password' => 'The old password is incorrect.'

        ]);
        }
        }

    } catch (\Exception $ex) {
        return back()->with('error',  'something went wrong');
    }
}



}
