<?php

namespace App\Repository\admin;

use App\Models\Nationality;
use App\Models\Hobby;
use App\Interfaces\admin\AccountInterface;
use App\Models\Admin\Admin;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;
use App\Traits\SaveImageTrait;


class AccountRepository implements AccountInterface
{
    use SaveImageTrait;

    /**
     *   Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function account()
    {
        try {
            return view('content.admin.pages.admin_setting.account');
        } catch (\Exception $ex) {

            return back()->with('error', 'something went wrong');

        }
    }


//return security
public function security()
{
  try{
      $admin=Admin::find(Auth::guard('admin')->user()->id);
      $recent_devices=$admin->deviceable()->take(5)->orderBy('id','desc')->get();
      return view('content.admin.pages.admin_setting.security',compact('recent_devices'));
    }catch(\Exception $ex){
      return back()->with('error',  'something went wrong');

    }
}

    /**
     * Update the speci   fied resource in storage.
     * Update Account
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($request)
    {

        try {
            if (isset($request->image)) {
                $data['image'] = SaveImageTrait::save_image($request->image, 'admin_profile');
                $check = Admin::find($request->id);
                if (!$check) {
                    return redirect()->back()->with('error', "Something Went Wrong.");
                } else {
                    $data['name'] = $request->name;
                    $check->update($data);
                    Activity::create([
                        'activeable_id' => Auth::guard('admin')->user()->id,
                        'activeable_type' => 'App\Models\Admin\Admin',
                        'activity' => 'Updated Acount',
                        'description_activity' => "updated Name And Image"
                    ]);
                    return redirect()->back()->with('success', "Account Updated successfully .");
                }
            } else {
                $check = Admin::find($request->id);

                if (!$check) {
                    return redirect()->back()->with('error', "Something Went Wrong.");

                } else {
                    $data['name'] = $request->name;
                    $check->update($data);
                    Activity::create([
                        'activeable_id' => Auth::guard('admin')->user()->id,
                        'activeable_type' => 'App\Models\Admin\Admin',
                        'activity' => 'Updated Acount',
                        'description_activity' => "updated Name "
                    ]);
                    return redirect()->back()->with('success', "Account Updated successfully .");

                }
            }
        } catch (\Exception $ex) {

            return back()->with('error', 'something went wrong');

        }
    }


    /**
     * Update the specified resource in storage.
     * Upda  te E-mail
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function change_email($request)
    {
        try {
            $check = Admin::find($request->id);

            if (!$check) {
                return redirect()->back()->with('error', "Something Went Wrong.");

            } else {
                if (password_verify($request->password, $check->password)) {
                    $check->update(['email' => $request->new_email]);
                    Activity::create([
                        'activeable_id' => Auth::guard('admin')->user()->id,
                        'activeable_type' => 'App\Models\Admin\Admin',
                        'activity' => 'Updated Acount',
                        'description_activity' => "updated Email "
                    ]);
                    return redirect()->back()->with('success', "Email Updated successfully .");

                } else {
                    return redirect()->route('admin_security')->withErrors(
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





    /**
     * Update the specifi  ed resource in storage.
     * Update Phone Number
     * @param  \Ill  uminate\Http\Request$request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function change_phone($request)
    {

        try {
            $check = Admin::find($request->id);


            if (!$check) {
                return redirect()->back()->with('error', "Something Went Wrong.");

            } else {
                if (password_verify($request->password, $check->password)) {
                    $check->update(['phone' => $request->new_phone]);
                    Activity::create([
                        'activeable_id' => Auth::guard('admin')->user()->id,
                        'activeable_type' => 'App\Models\Admin\Admin',
                        'activity' => 'Updated Acount',
                        'description_activity' => "updated Phone "
                    ]);
                    return redirect()->back()->with('success', "Phone Updated successfully .");

                } else {
                    return redirect()->route('admin_security')->withErrors(
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



    /**
     * Update the specified resource in storage.
     * Update password
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $i  d
     * @return \Illuminate\Http\Response
     */
    public function change_password($request)
    {
        try {
            $check = Admin::find($request->id);

            if (!$check) {
                return redirect()->back()->with('error', "Something Went Wrong.");

            } else {

                if (password_verify($request->old_password, $check->password)) {

                    $check->update(['password' => $request->new_password]);
                   return redirect()->back()->with('success', "Password Updated successfully .");

                } else {

                    return redirect()->route('admin_security')->withErrors(
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
