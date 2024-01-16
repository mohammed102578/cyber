<?php

namespace App\Repository\reporter;

use App\Http\Services\SendOtpMobile;
use App\Http\Services\SendVerification;
use App\Interfaces\reporter\VerificationInterface;
use App\Models\Reporter\Reporter;
use Illuminate\Support\Facades\Auth;

class VerificationRepository implements VerificationInterface
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reporter = Reporter::find(Auth::guard('reporter')->user()->id);
        if ($reporter->code == null) {
            return redirect()->route('reporter_dashboard');
        } else {
            return view('content.reporter.authentications.verification_account');
        }
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

public function store($request)
{
 $validatedData = $request->validate([
        'code' => 'required',

    ], [
        'code.required' => 'this field is required.',
    ]);
$reporter=Auth::guard('reporter')->user();
    if($request->code != $reporter->code){
        return redirect()->back()->withErrors(
            [
                'code' => 'please Enter a Valid Code.'
            ]);
    }else{

        $reporter=Auth::guard('reporter')->user();
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
    return redirect()->route('reporter_dashboard');

}


public function resend($request)
{
$reporter=Reporter::where('id',Auth::guard('reporter')->user()->id)->first();
$reporter->verification_code();

$details=[
    'title'=>'Verification Code',
   'body'=>'Verification Code is '.$reporter->code,'email'=>$reporter->email,
   'name'=>$reporter->first_name." ".$reporter->last_name
   ];


    $sms=new SendOtpMobile();
    $sms->sendSMS($reporter->phone,$reporter->code);

    $reporter_email=(new SendVerification($details));
    dispatch($reporter_email);

return response()->json(['status' => 'success'], 200);
}


}
