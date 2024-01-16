<?php

namespace App\Http\Controllers\Reporter\authentications;

use App\Http\Controllers\Controller;

use App\Models\Reporter\Reporter;
use App\Models\Nationality;
use App\Models\Hobby;

use App\Http\Requests\Reporter\RegisterRequest;
use App\Http\Services\SendOtpMobile;
use App\Http\Services\SendVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
  public function index()
  {
     $nationalities=Nationality::get();
     $hobby=Hobby::get();
    return view('content.reporter.authentications.auth-register-basic',compact(['nationalities','hobby']));

  }

 
  /*

registration


  */

  public function register(RegisterRequest $request) 
  {


//return $request->all();
    
 $hobby=implode(",",$request->hobby);

$reporter=[
    'email' =>  $request->email,
    'phone' =>  $request->phone,
    'password' =>  $request->password ,
    'first_name' =>  $request->first_name ,
    'job' =>  $request->job ,
    'hobby' =>  $request->hobby ,
    'last_name' =>  $request->last_name ,
    'birthday' =>  $request->birthday,
    'company' =>  $request->company ,
    'city' =>  $request->city ,
    'nationality' =>  $request->nationality ,
   
  
  ];

      $reporter = Reporter::create($reporter);

       auth('reporter')->login($reporter);


       $user=Reporter::where('id',$reporter->id)->first();
       

       $user->verification_code();



       $details=[
        'title'=>'Verification Code',
       'body'=>'Verification Code is '.$user->code,'email'=>$user->email,
       'name'=>$user->first_name." ".$user->last_name
       ];

       $sms=new SendOtpMobile();
       $sms->sendSMS($user->phone,$user->code);
   
        $reporter_email=(new SendVerification($details));
        dispatch($reporter_email);
      return redirect('reporter/dashboard')->with('success', "Account successfully registered.");
  }


  public function reloadCaptcha()
  {
      return response()->json(['captcha'=> captcha_img()]);
  }
}
