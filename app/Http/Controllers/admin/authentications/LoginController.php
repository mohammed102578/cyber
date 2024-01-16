<?php

namespace App\Http\Controllers\Admin\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Services\MobileDetect;
use App\Models\RecentDevice;
use Stevebauman\Location\Facades\Location;





class LoginController extends Controller
{


  public function __construct()
  {
      $this->middleware('guest:admin')->except('logout');
  }



//get my ip address




  public function index()
  {
    return view('content.admin.authentications.auth-login-basic');
  }





  /*

login function

  */






  public function login(LoginRequest $request)
    {
         $credentials =  ['email'=>$request->email,'password'=>$request->password];

         if(!Auth::guard('admin')->validate($credentials)):
          return redirect()->route('admin_login')->withErrors(
            [
                'login' => 'Email and password do not match',
            ]);
        endif;






        $user = Auth::guard('admin')->getProvider()->retrieveByCredentials($credentials);

       $login= Auth::guard('admin')->login($user);


//START Detect admin Information


$ip = $_SERVER['REMOTE_ADDR'];
$ip= Location::get($ip);

$location= $ip->countryName." / ".$ip->regionName. " / " .$ip->cityName;

$platform= MobileDetect::systemInfo()['os']." / ".MobileDetect::systemInfo()['device'];
$browser= MobileDetect::browser();
$device_name= MobileDetect::name_device();
$icon= MobileDetect::icon();

RecentDevice::create(['browser'=>$browser,'device'=>$device_name,'platform'=>$platform,'location'=>$location,'deviceable_id'=>$user->id,'deviceable_type'=>"App\Models\Admin\Admin",'icon'=>$icon]);
//START Detect admin Information

        return redirect()->route('admin_dashboard')->with('success', 'You are successfully logged in!');

    }



 /**
 * Function Authenticated users
 * @param request
 */
 protected function authenticated(Request $request)
 {
    Auth::guard('admin')->logoutOtherDevices($request->password);
 }


    public function logout()
    {
        Session::flush();

        Auth::guard('admin')->logout();

        return redirect()->route('admin_login');
    }

}
