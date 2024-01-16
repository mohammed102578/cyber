<?php

namespace App\Repository\reporter;

use App\Http\Services\Notification;
use App\Interfaces\reporter\SecurityInterface;
use App\Models\Reporter\Reporter;
use Illuminate\Support\Facades\Auth;

class SecurityRepository implements  SecurityInterface
{


    public object $admin_notification;
    public function __construct()
    {
        $this->admin_notification = new Notification;

    }
public function security($request)

{
    try{
        $reporter=Reporter::find(Auth::guard('reporter')->user()->id);
        $recent_devices=$reporter->deviceable()->take(5)->orderBy('id','desc')->get();
       return view('content.reporter.pages.security',compact('recent_devices'));
    } catch (\Exception $ex) {

       return back()->with('error',  'something went wrong');

    }
}



public function delete_account($request)
{

        try{
            $check=Reporter::find($request->id);
            if(!$check){
            redirect()->back()->with('error', "Something Went Wrong.");

            }else{
            if (password_verify($request->delete_password, $check->password)) {
            $check->delete();
             //send notification
         $this->admin_notification->
         sendAdminNotification('Delete Account',Auth::guard('corporate')->user()->first_name.' Deleted His Account ',
         'admin_trash_reporters',null,'reporter');

            return redirect()->route('reporter_logout')->with('success', "Account Deleted successfully .");

            }else{
            return redirect()->route('reporter_security')->withErrors(
            [
            'delete_password' => 'The password is incorrect.',

            ]);

            }

            }
        } catch (\Exception $ex) {

            return back()->with('error',  'something went wrong');

        }

}


}
