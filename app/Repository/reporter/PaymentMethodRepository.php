<?php

namespace App\Repository\reporter;


use App\Http\Services\Notification;
use App\Interfaces\reporter\PaymentMethodInterface;
use App\Models\PaymentMethod;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class PaymentMethodRepository implements PaymentMethodInterface
{

    public object $admin_notification;
    public function __construct()
    {
        $this->admin_notification = new Notification;

    }
    public function store($request)
    {

        try {
            $payment_method = [
                'payment_type' => $request->payment_type,
                'serial_no' => $request->serial_no,
                'name' => $request->name,
                'exp_date' => $request->exp_date,
                'bank_name' => $request->bank_name,
                'paymentable_id' => Auth::guard('reporter')->user()->id,
                'paymentable_type' => 'App\Models\Reporter\Reporter',
            ];

           $payment_method_create= PaymentMethod::create($payment_method);
            Activity::create([
                'activeable_id' => Auth::guard('reporter')->user()->id, 'activeable_type' => 'App\Models\Reporter\Reporter',
                'activity' => 'Added Payment_method', 'description_activity' => "Added Payment_method  $request->bank_name"
            ]);
             //send notification
        $this->admin_notification->
        sendAdminNotification('Added payment method',Auth::guard('reporter')->user()->first_name.' Added The New Payment method',
        'admin_notifications',null,'reporter');

            return redirect()->back()->with('success', 'payment_method Added successfully');
        } catch (\Exception $ex) {

            return back()->with('error', 'something went wrong');
        }
    }









    //edit payment method
    public function edit($request)
    {

        try {
            $id = $request->id;
            $check = PaymentMethod::find($id);
            if ($check) {
                $data = $check;
                return response()->json($data);
            }
        } catch (\Exception $ex) {

            return back()->with('error',  'something went wrong');
        }
    }



    //update payment method
    public function update($request)


    {

        try {
            $id = $request->id;
            $check = PaymentMethod::find($id);

            if ($check) {

                $payment_method = [
                    'payment_type' => $request->payment_type,
                    'serial_no' => $request->serial_no,
                    'name' => $request->name,
                    'exp_date' => $request->exp_date,
                    'bank_name' => $request->bank_name,
                    'paymentable_id' => Auth::guard('reporter')->user()->id,
                    'paymentable_type' => 'App\Models\Reporter\Reporter',
                ];


                $data = PaymentMethod::where('id', $check->id)->update($payment_method);
                //send notification
                $this->admin_notification->
                sendAdminNotification('Updated payment method',Auth::guard('reporter')->user()->first_name.' Updated The New Payment method',
                'admin_notifications',null,'reporter');
                Activity::create([
                    'activeable_id' => Auth::guard('reporter')->user()->id, 'activeable_type' => 'App\Models\Reporter\Reporter',
                    'activity' => 'Updated Payment_method', 'description_activity' => "Updated Payment_method  $request->bank_name"
                ]);
                return response()->json(['status' => 'success'], 200);
            } else {
                return response()->json(['status' => 'failed']);
            }
        } catch (\Exception $ex) {

            return back()->with('error',  'something went wrong');
        }
    }







    //delete reporter
    public function destroy($request)
    {

        try {
            $id = $request->id;
            $check = PaymentMethod::find($id);

            if ($check) {
                $data = $check;

                $data->delete();
                //send notification
                $this->admin_notification->
                sendAdminNotification('Deleted payment method',Auth::guard('reporter')->user()->first_name.' Deleted The New Payment method',
                'admin_notifications',null,'reporter');
                Activity::create([
                    'activeable_id' => Auth::guard('reporter')->user()->id, 'activeable_type' => 'App\Models\Reporter\Reporter',
                    'activity' => 'Deleted Payment_method', 'description_activity' => "Deleted Payment_method  $check->bank_name"
                ]);
                return response()->json(['status' => 'success'], 200);
            } else {
                return response()->json(['status' => 'failed']);
            }
        } catch (\Exception $ex) {

            return back()->with('error',  'something went wrong');
        }
    }
}
