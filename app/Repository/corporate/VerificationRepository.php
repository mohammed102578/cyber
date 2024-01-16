<?php

namespace App\Repository\corporate;

use App\Http\Services\SendVerification;
use App\Interfaces\corporate\VerificationInterface;
use App\Models\Corporate\Corporate;
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
        $corporate = Auth::guard('corporate')->user();
        if ($corporate->code == null) {
            return redirect()->route('corporate_dashboard');
        } else {
            return view('content.corporate.authentications.verification_account');
        }
    }



    public function store($request)
    {
        $validatedData = $request->validate([
            'code' => 'required',

        ], [
            'code.required' => 'this field is required.',
        ]);
        $corporate = Auth::guard('corporate')->user();
        if ($request->code != $corporate->code) {
            return redirect()->back()->withErrors(
                [
                    'code' => 'please Enter a Valid Code.'
                ]
            );
        } else {

            $corporate = Auth::guard('corporate')->user();
            $now = now();
            $expire = strtotime($corporate->expire_time);
            $now = strtotime($now);

            // Compare timestamps
            if ($expire > $now) {
                Corporate::where('id', $corporate->id)->update(['code' => null, 'expire_time' => null]);
            } else {
                return redirect()->back()->withErrors(
                    [
                        'code' => 'This code is expired.'
                    ]
                );
            }
        }
        return redirect()->route('corporate_dashboard');
    }


    public function resend($request)
    {
        $corporate = Corporate::where('id', Auth::guard('corporate')->user()->id)->first();
        $corporate->verification_code();

        $details = [
            'title' => 'Verification Code',
            'body' => 'Verification Code is ' . $corporate->code, 'email' => $corporate->email,
            'name' => $corporate->company_name
        ];

        $corporate_email = (new SendVerification($details));
        dispatch($corporate_email);
        return response()->json(['status' => 'success'], 200);
    }
}
