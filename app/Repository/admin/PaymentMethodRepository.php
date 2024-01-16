<?php

namespace App\Repository\admin;

use App\Http\Requests\Admin\Payment_methodRequest;
use App\Interfaces\admin\PaymentMethodInterface;
use App\Models\Activity;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentMethodRepository implements PaymentMethodInterface
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($request)
    {

        try{
            $payment_method=[
            'payment_type'=>$request->payment_type,
            'serial_no'=>$request->serial_no,
            'name'=>$request->name,
            'exp_date'=>$request->exp_date,
            'bank_name'=>$request->bank_name,
            'paymentable_id'=>Auth::guard('admin')->user()->id,
            'paymentable_type'=>'App\Models\Admin\Admin',
            ];

            PaymentMethod::create($payment_method);
            Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
            'activity'=>'Create Payment_method ','description_activity'=>" Create Admin Payment method "]);
            return redirect()->back()->with('success','payment_method Added successfully');
            }catch(\Exception $ex){
            return back()->with('error',  'something went wrong');

        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($request)


    {

        try{
            $id = $request->id;
            $check = PaymentMethod::find($id);
            if($check){
            $data=$check;
            return response()->json($data);
            }

        } catch (\Exception $ex) {

            return back()->with('error',  'something went wrong');

        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($request)
    {

        try{
            $id = $request->id;
            $check = PaymentMethod::find($id);
            if($check){
            $payment_method=[
            'payment_type'=>$request->payment_type,
            'serial_no'=>$request->serial_no,
            'name'=>$request->name,
            'exp_date'=>$request->exp_date,
            'bank_name'=>$request->bank_name,
            'paymentable_id'=>Auth::guard('admin')->user()->id,
            'paymentable_type'=>'App\Models\Admin\Admin',            ];
            $data=PaymentMethod::where('id', $check->id)->update($payment_method);
            Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
            'activity'=>'Updated Invoice ','description_activity'=>" Updated Corporate Invoice "]);
            return response()->json(['status' => 'success'], 200);
            }else{
            return response()->json(['status' => 'failed']);
            }
        } catch (\Exception $ex) {

                return back()->with('error',  'something went wrong');

        }

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($request)
    {

      try{
            $id = $request->id;
            $check = PaymentMethod::find($id);
            if($check){
            $data=$check;
            $data->delete();
            Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
            'activity'=>'Deleted Invoice ','description_activity'=>" Deleted Corporate Invoice "]);
            return response()->json(['status' => 'success'], 200);
            }else{
            return response()->json(['status' => 'failed']);
            }
        } catch (\Exception $ex) {
            return back()->with('error',  'something went wrong');
        }
    }
}
