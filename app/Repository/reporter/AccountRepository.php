<?php

namespace App\Repository\reporter;

use App\Interfaces\reporter\AccountInterface;
use App\Models\Nationality;
use App\Models\Hobby;
use App\Models\Reporter\Reporter;
use App\Http\Services\Notification;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;
use App\Traits\SaveImageTrait;

class AccountRepository implements AccountInterface
{

    use SaveImageTrait;

    public object $admin_notification;
    public function __construct()
    {
        $this->admin_notification = new Notification;

    }

    public function index()
    {
        try {
            $nationalities = Nationality::get();
            $hobby = Hobby::get();
            return view('content.reporter.pages.account', compact(['nationalities', 'hobby']));
        } catch (\Exception $ex) {

            return back()->with('error', 'something went wrong');
        }
    }


    //update account

    public function update($request)
    {
        try {

            $data=$request->all();
            if (isset($request->image)) {

                $data['image'] = SaveImageTrait::save_image($request->image,'reporter_profile');

                $check = Reporter::find($request->id);
                if (!$check) {
                    return redirect()->back()->with('error', "Something Went Wrong.");
                } else {
                    $data['name'] = $request->name;

                    $check->update($data);
        //send notification
        $this->admin_notification->
        sendAdminNotification('Updated Account',Auth::guard('reporter')->user()->first_name.' Updated The Account Details And Image',
        'admin_reporter_profile',$check->id,'reporter');

                    Activity::create([
                        'activeable_id' => Auth::guard('reporter')->user()->id, 'activeable_type' => 'App\Models\Reporter\Reporter',
                        'activity' => 'Updated Acount', 'description_activity' => "updated Name And Image"
                    ]);
                    return redirect()->back()->with('success', "Account Updated successfully .");
                }
            } else {
                $check = Reporter::find($request->id);

                if (!$check) {
                    return redirect()->back()->with('error', "Something Went Wrong.");
                } else {
                    $data['name'] = $request->name;
                    $check->update($data);
        //send notification
        $this->admin_notification->
        sendAdminNotification('Updated Account',Auth::guard('reporter')->user()->first_name.' Updated The Account Details',
        'admin_reporter_profile',$check->id,'reporter');

                    Activity::create([
                        'activeable_id' => Auth::guard('reporter')->user()->id, 'activeable_type' => 'App\Models\Reporter\Reporter',
                        'activity' => 'Updated Acount', 'description_activity' => "updated Name "
                    ]);
                    return redirect()->back()->with('success', "Account Updated successfully .");
                }
            }
        } catch (\Exception $ex) {

            return back()->with('error', 'something went wrong');
        }
    }



    //update email
    public function change_email($request)
    {
        try {
            $check = Reporter::find($request->id);
            if (!$check) {
                return redirect()->back()->with('error', "Something Went Wrong.");
            } else {
                if (password_verify($request->password, $check->password)) {
                    $check->update(['email' => $request->new_email]);
                    Activity::create([
                        'activeable_id' => Auth::guard('reporter')->user()->id, 'activeable_type' => 'App\Models\Reporter\Reporter',
                        'activity' => 'Updated Acount', 'description_activity' => "updated E-mail "
                    ]);
                      //send notification
        $this->admin_notification->
        sendAdminNotification('Updated E-mail',Auth::guard('reporter')->user()->first_name.' Updated The E-mail ',
        'admin_reporter_profile',$check->id,'reporter');
                    return redirect()->back()->with('success', "E-mail Updated successfully .");
                } else {
                    return redirect()->route('reporter_security')->withErrors(
                        [
                            'email_password' => 'The password is incorrect.',
                        ]
                    );
                }
            }
        } catch (\Exception $ex) {

            return back()->with('error', 'something went wrong');
        }
    }


    //change phone number


    public function change_phone($request)
    {
        try {

            $check = Reporter::find($request->id);
            if (!$check) {
                return redirect()->back()->with('error', "Something Went Wrong.");
            } else {
                if (password_verify($request->password, $check->password)) {
                    $check->update(['phone' => $request->new_phone]);
                    Activity::create([
                        'activeable_id' => Auth::guard('reporter')->user()->id, 'activeable_type' => 'App\Models\Reporter\Reporter',
                        'activity' => 'Updated Acount', 'description_activity' => "updated Phone "
                    ]);

        //send notification
        $this->admin_notification->
        sendAdminNotification('Updated Phone',Auth::guard('reporter')->user()->first_name.' Updated The Phone number',
        'admin_reporter_profile',$check->id,'reporter');

                    return redirect()->back()->with('success', "Phone Updated successfully .");
                } else {
                    return redirect()->route('reporter_security')->withErrors(
                        [
                            'phone_password' => 'The password is incorrect.',

                        ]
                    );
                }
            }
        } catch (\Exception $ex) {

            return back()->with('error', 'something went wrong');
        }
    }

    //password update
    public function change_password($request)
    {
        try {
            $check = Reporter::find($request->id);

            if (!$check) {
                return redirect()->back()->with('error', "Something Went Wrong.");
            } else {

                if (password_verify($request->old_password, $check->password)) {
                    $check->update(['password' => $request->new_password]);
                    Activity::create([
                        'activeable_id' => Auth::guard('reporter')->user()->id, 'activeable_type' => 'App\Models\Reporter\Reporter',
                        'activity' => 'Updated Acount', 'description_activity' => "updated Password "
                    ]);

                      //send notification
        $this->admin_notification->
        sendAdminNotification('Updated password',Auth::guard('reporter')->user()->first_name.' Updated The Password',
        'admin_reporter_profile',$check->id,'reporter');

                    return redirect()->back()->with('success', "Password Updated successfully .");

                } else {
                    return redirect()->route('reporter_security')->withErrors(
                        [
                            'old_password' => 'The old password is incorrect.'

                        ]
                    );
                }
            }
        } catch (\Exception $ex) {

            return back()->with('error', 'something went wrong');
        }
    }
}
