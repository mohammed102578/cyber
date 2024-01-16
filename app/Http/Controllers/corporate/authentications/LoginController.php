<?php

namespace App\Http\Controllers\corporate\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Corporate\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Hobby;
use App\Http\Services\MobileDetect;
use App\Models\Recent_device;
use App\Models\RecentDevice;
use Stevebauman\Location\Facades\Location;




class LoginController extends Controller
{


  public function __construct()
  {

  
    $this->middleware('guest:corporate')->except(['logout','corporate_deleted','corporate_activate']);
  }





  public function index()
  {
    return view('content.corporate.authentications.auth-login-basic');
  }


//get my ip address



  /*

login function

  */

  public function login(LoginRequest $request)
    {
        $credentials = $request->getCredentials();


        if(!Auth::guard('corporate')->validate($credentials)):
          return redirect()->route('corporate_login')->withErrors(
            [
              'login' => 'Your credentials are incorrect.'
            ]);
        endif;




        $user = Auth::guard('corporate')->getProvider()->retrieveByCredentials($credentials);

        Auth::guard('corporate')->login($user);

//START Detect corporate Information

$ip = $_SERVER['REMOTE_ADDR'];
$ip= Location::get($ip);

$location= $ip->countryName." / ".$ip->regionName. " / " .$ip->cityName;

$platform= MobileDetect::systemInfo()['os']." / ".MobileDetect::systemInfo()['device'];
$browser= MobileDetect::browser();
$device_name= MobileDetect::name_device();
$icon= MobileDetect::icon();

RecentDevice::create(['browser'=>$browser,'device'=>$device_name,'platform'=>$platform,'location'=>$location,'deviceable_id'=>$user->id,'deviceable_type'=>"App\Models\Corporate\Corporate",'icon'=>$icon]);
//START Detect corporate Information


        return redirect()->route('corporate_dashboard')->with('success', 'You are successfully logged in!');

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
        
        Auth::guard('corporate')->logout();
        
        return redirect()->route('corporate_login');
    }


    
    public function corporate_deleted()
    {
        Session::flush();
        
        Auth::guard('corporate')->logout();
        
        return view('content.corporate.authentications.deleted_corporate');
      }



      public function corporate_activate()
    {
        Session::flush();
        
        Auth::guard('corporate')->logout();
        
        return view('content.corporate.authentications.activate_corporate');
      }

}
