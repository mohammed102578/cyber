<?php

namespace App\Http\Controllers\corporate\authentications;

use App\Http\Controllers\Controller;

use App\Models\Corporate\Corporate;
use App\Models\Nationality;
use App\Http\Requests\Corporate\RegisterRequest;
use App\Http\Services\SendVerification;

class RegisterController extends Controller
{
  public function index()
  {
     $nationalities=Nationality::get();
    return view('content.corporate.authentications.auth-register-basic',compact(['nationalities']));
  }


  /*

registration


  */

  public function register(RegisterRequest $request) 
  {

      $corporate = Corporate::create($request->validated());

     auth('corporate')->login($corporate);

     $user=Corporate::where('id',$corporate->id)->first();
       

     $user->verification_code();



     $details=[
      'title'=>'Verification Code',
     'body'=>'Verification Code is '.$user->code,'email'=>$user->email,
     'name'=>$user->company_name
     ];

      $corporate=(new SendVerification($details));
      dispatch($corporate);
      return redirect('corporate/dashboard')->with('success', "Account successfully registered.");
  }


  public function reloadCaptcha()
  {
      return response()->json(['captcha'=> captcha_img()]);
  }
}
