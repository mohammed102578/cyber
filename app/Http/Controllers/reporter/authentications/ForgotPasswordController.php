<?php

namespace App\Http\Controllers\reporter\authentications;

use App\Http\Controllers\Controller;
use App\Http\Services\SendOtpMobile;
use App\Http\Services\SendVerification;
use App\Models\Reporter\Reporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ForgotPasswordController extends Controller
{

  public $reporter_id;



  public function forgot_password()
  {
    return view('content.reporter.authentications.auth-forgot-password-basic');
  }
  


  public function check_email_or_phone(Request $request)
  {

    $validatedData = $request->validate([
      'phone' => 'required',
     
  ], [
      'phone.required' => 'this field is required.',
  ]);


  if(is_numeric($request->phone)){
    $reporter=Reporter::where('phone',$request->phone)->first();
    if($reporter){
      $recived_code=$request->phone;
      $reporter->reset_code();
      $sms=new SendOtpMobile();
      $sms->sendSMS($reporter->phone,$reporter->reset_code);
  
return redirect()->route('reporter_verification_code',$recived_code);

    }else{
      return redirect()->back()->withErrors(
        [
            'phone' => 'not found this phone number.'

        ]);
    }
  }else{
    $email_request=$request->phone;
    $reporter=Reporter::where('email',$email_request)->first();
    if($reporter){
      $recived_code=$email_request;
      $reporter->reset_code();
      $details=[
        'title'=>'Verification Code',
       'body'=>'Verification Code is '.$reporter->reset_code,'email'=>$reporter->email,
       'name'=>$reporter->first_name." ".$reporter->last_name
       ];

        $reporter_email=(new SendVerification($details));
        dispatch($reporter_email);


      return redirect()->route('reporter_verification_code',$recived_code);
    }else{
      return redirect()->back()->withErrors(
        [
            'phone' => 'not foun this E-mail.'

        ]);
    }
  }




   }

   //verification_code
   public function verification_code($phone_or_email)
   {
 
     $recived_code=$phone_or_email;
     return view('content.reporter.authentications.verification_code_reset_password',compact('recived_code'));
   }
   

//resend code
public function resend(Request $request)
{

  if(is_numeric($request->recived_code)){

    $reporter=Reporter::where('phone',$request->recived_code)->first();

 }else{
    $reporter=Reporter::where('email',$request->recived_code)->first();

 }




$reporter->reset_code();

$details=[
    'title'=>'Verification Code',
   'body'=>'Verification Code is '.$reporter->reset_code,'email'=>$reporter->email,
   'name'=>$reporter->first_name." ".$reporter->last_name
   ];

   $sms=new SendOtpMobile();
   $sms->sendSMS($reporter->phone,$reporter->reset_code);

    $reporter_email=(new SendVerification($details));
    dispatch($reporter_email);
return response()->json(['status' => 'success'], 200);
}


   //check_verification_code
   public function check_verification_code(Request $request)
  {

    $validatedData = $request->validate([
      'code' => 'required',
     
  ], [
      'code.required' => 'this field is required.',
  ]);

  if(is_numeric($request->recived_code)){

     $reporter=Reporter::where('phone',$request->recived_code)->first();

  }else{
     $reporter=Reporter::where('email',$request->recived_code)->first();

  }
 

if($request->code != $reporter->reset_code){
      return redirect()->back()->withErrors(
          [
              'code' => 'please Enter a Valid Code.'
          ]);
  }else{

      $now=now();
      $expire = strtotime($reporter->expire_time);
      $now = strtotime($now);
      
      // Compare timestamps
      if ($expire > $now) {
        Reporter::where('id',$reporter->id)->update(['code'=>null,'expire_time'=>null]);
      } else {
          return redirect()->back()->withErrors(
              [
                  'code' => 'This code is expired.'
              ]);
      }
      


  }
  Session::put('reporter_id',$reporter->id);
  return redirect()->route('reporter_reset_password');

  }


  public function reset_password(){

     $reporter_id= Session::get('reporter_id');

    return view('content.reporter.authentications.change_password',compact('reporter_id'));
  }


  public function update_password(Request $request){

    $validatedData = $request->validate([
      'password' => 'required',
      'confirm_password' => 'required',
     
  ], [
    'password.required' => 'this field is required.',
    'confirm_password.required' => 'this field is required.',
  ]);

$reporter=Reporter::where('id',$request->reporter_id)->update(['password'=>bcrypt($request->password),
'reset_code'=>null,'expire_time'=>null]);



if($reporter){
  return redirect()->route('reporter_login')->with('success','password updated successfully');
}else{
  return redirect()->back()->with('error','something went wrong');
}

}
}
 
