<?php

namespace App\Http\Controllers\Reporter\authentications;

use App\Http\Controllers\Controller;
use App\Models\Reporter\Reporter;
use Illuminate\Http\Request;
use App\Http\Requests\Reporter\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Stevebauman\Location\Facades\Location;
use App\Http\Services\MobileDetect;
use App\Http\Services\SendOtpMobile;
use App\Http\Services\SendVerification;
use App\Models\Recent_device;
use App\Models\RecentDevice;

class LoginController extends Controller
{


  public function __construct()
  {
      $this->middleware('guest:reporter')->except(['logout','reporter_deleted','reporter_activate']);
  }





  public function index()
  {
    return view('content.reporter.authentications.auth-login-basic');
  }


//get my ip address


  /*

login function

  */

  public function login(LoginRequest $request)
    {

        $credentials = $request->getCredentials();



        if(!Auth::guard('reporter')->validate($credentials)):
          return redirect()->route('reporter_login')->withErrors(
            [
                'login' => 'Your credentials are incorrect.'
,
            ]);
        endif;




        $user = Auth::guard('reporter')->getProvider()->retrieveByCredentials($credentials);


         Auth::guard('reporter')->login($user);
        
       
//START Detect Reporter Information


$ip = $_SERVER['REMOTE_ADDR'];
$ip= Location::get($ip);

$location= $ip->countryName." / ".$ip->regionName. " / " .$ip->cityName;

$platform= MobileDetect::systemInfo()['os']." / ".MobileDetect::systemInfo()['device'];
$browser= MobileDetect::browser();
$device_name= MobileDetect::name_device();
$icon= MobileDetect::icon();

RecentDevice::create(['browser'=>$browser,'device'=>$device_name,'platform'=>$platform,'location'=>$location,'deviceable_id'=>$user->id,'deviceable_type'=>"App\Models\Reporter\Reporter",'icon'=>$icon]);
//START Detect Reporter Information



        return redirect()->route('reporter_dashboard')->with('success', 'You are successfully logged in!');

    }

    
    /**
     * Handle response after user authenticated
     * 
     * @param Request $request
     * @param Auth $user
     * 
     * @return \Illuminate\Http\Response
     */
    protected function authenticated(Request $request, $user) 
    {
        return redirect()->intended();
    }



    public function logout()
    {
        Session::flush();
        
        Auth::guard('reporter')->logout();
        
        return redirect()->route('reporter_login');
    }

    public function reporter_deleted()
    {
        Session::flush();
        
        Auth::guard('reporter')->logout();
        
        return view('content.reporter.authentications.deleted_reporter');
      }



      public function reporter_activate()
    {
        Session::flush();
        
        Auth::guard('reporter')->logout();
        
        return view('content.reporter.authentications.activate_reporter');
      }
}
