<?php

namespace App\Http\Controllers\corporate\authentications;

use App\Http\Controllers\Controller;
use App\Http\Services\SendVerification;
use App\Models\Corporate\Corporate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ForgotPasswordController extends Controller
{

  public $corporate_id;



  public function forgot_password()
  {
    return view('content.corporate.authentications.auth-forgot-password-basic');
  }
  


  public function check_email(Request $request)
  {

    $validatedData = $request->validate([
      'email' => 'required',
     
  ], [
      'email.required' => 'this field is required.',
  ]);


    $email_request=$request->email;
    $corporate=Corporate::where('email',$email_request)->first();
    if($corporate){
      $recived_code=$email_request;
      $corporate->reset_code();
      $details=[
        'title'=>'Verification Code',
       'body'=>'Verification Code is '.$corporate->reset_code,'email'=>$corporate->email,
       'name'=>$corporate->company_name
       ];

        $corporate_email=(new SendVerification($details));
        dispatch($corporate_email);


      return redirect()->route('corporate_verification_code',$recived_code);
    }else{
      return redirect()->back()->withErrors(
        [
            'email' => 'not foun this E-mail.'

        ]);
    }
  




   }

   //verification_code
   public function verification_code($email)
   {
 
     $recived_code=$email;
     return view('content.corporate.authentications.verification_code_reset_password',compact('recived_code'));
   }
   

//resend code
public function resend(Request $request)
{

   $request->recived_code;
$corporate=Corporate::where('email',$request->recived_code)->first();
$corporate->reset_code();

$details=[
    'title'=>'Verification Code',
   'body'=>'Verification Code is '.$corporate->reset_code,'email'=>$corporate->email,
   'name'=>$corporate->company_name
   ];

    $corporate_email=(new SendVerification($details));
    dispatch($corporate_email);
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

     $corporate=Corporate::where('phone',$request->recived_code)->first();

  }else{
     $corporate=Corporate::where('email',$request->recived_code)->first();

  }
 

if($request->code != $corporate->reset_code){
      return redirect()->back()->withErrors(
          [
              'code' => 'please Enter a Valid Code.'
          ]);
  }else{

      $now=now();
      $expire = strtotime($corporate->expire_time);
      $now = strtotime($now);
      
      // Compare timestamps
      if ($expire > $now) {
        Corporate::where('id',$corporate->id)->update(['code'=>null,'expire_time'=>null]);
      } else {
          return redirect()->back()->withErrors(
              [
                  'code' => 'This code is expired.'
              ]);
      }
      


  }
  Session::put('corporate_id',$corporate->id);
  return redirect()->route('corporate_reset_password');

  }


  public function reset_password(){

     $corporate_id= Session::get('corporate_id');

    return view('content.corporate.authentications.change_password',compact('corporate_id'));
  }


  public function update_password(Request $request){

    $validatedData = $request->validate([
      'password' => 'required',
      'confirm_password' => 'required',
     
  ], [
    'password.required' => 'this field is required.',
    'confirm_password.required' => 'this field is required.',
  ]);

$corporate=Corporate::where('id',$request->corporate_id)->update(['password'=>bcrypt($request->password),
'reset_code'=>null,'expire_time'=>null]);
if($corporate){
  return redirect()->route('corporate_login')->with('success','password updated successfully');
}else{
  return redirect()->back()->with('error','something went wrong');
}

}
}
 
