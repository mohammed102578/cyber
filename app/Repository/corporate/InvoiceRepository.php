<?php

namespace App\Repository\corporate;


use App\Http\Services\Notification;
use App\Interfaces\corporate\InvoiceInterface;
use App\Models\Admin\Admin;
use App\Models\Activity;
use App\Models\Corporate\Program;
use App\Models\Invoice;
use App\Models\Reporter\Report;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceRepository implements InvoiceInterface
{

    public object $admin_notification;
    public function __construct()
    {
        $this->admin_notification = new Notification;

    }
public function all_invoices()
{
    try{
         return view('content.corporate.pages.all_invoices');
    }catch(\Exception $ex){
        return redirect()->back()->with('error','something wen wrong');
    }
}

public function index()
{

    try{
        $admin=Admin::first();
        $payment_methods=$admin->paymentable()->get();

        return view('content.corporate.pages.invoice',compact(['payment_methods','admin']));
    }catch(\Exception $ex){
        return redirect()->back()->with('error','something wen wrong');
    }
}

//get all reporters using ajax
public function get_invoices($request)
{
        try{

                if (
                $request->ajax()) {
                $data =DB::table('invoices')
                ->where('sender_type','corporate')->where('sender_id',Auth::guard('corporate')->user()->id)
                ->orderBy('invoices.id', 'desc')
                ->get();
                return Datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('status', function($data){
                if($data->status == 1){
                $statutsBtn = 'OK';
                }else{
                $statutsBtn =  'Pending';
                }
                return $statutsBtn;
                })
                ->rawColumns(['status'])
                 //total_amount
        ->addColumn('total_amount', function($data){

            return $total_amount=number_format($data->total_amount, 0, '.', ',');
        })
        ->rawColumns(['total_amount'])
            //created_at
            ->addColumn('created_at',function($data){

                $created_at=Carbon::parse($data->created_at)->format('d M Y');

                return $created_at;
                })
                ->rawColumns(['created_at'])

                ->addColumn('action', function($data){
                $actionBtn = ' <div class="d-inline-block">
                <a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                <i class="bx bx-dots-vertical-rounded">
                </i></a>
                <div class="dropdown-menu dropdown-menu-end m-0">
                <a class=" edit_modal_invoice dropdown-item " id="'. $data->id.'"href=""data-bs-toggle="modal" data-bs-target="#edit_invoice"><i class="bx bx-edit text-info"></i> Edit Invoice</a>
                <a class=" edit_modal_invoice dropdown-item " id="'. $data->id.'"href=""data-bs-toggle="modal" data-bs-target="#show_explain_invoice"><i class="bx bxs-detail text-success"></i> Explain Invoice</a>

                </div>
                </div>
                ';
                return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
                }
        } catch (\Exception $ex) {

                return back()->with('error', 'something went wrong');

        }
}



public function create()
{

                $rand= rand(1,10000000);
                $invoice_number=Auth::guard('corporate')->user()->id.$rand;
                $admin=Admin::first();
                $payment_methods=$admin->paymentable()->get();

               $programs=Program::whereHas('report',function($query) {
                $query->where('paid', '=', 0);
            })->where('corporate_id',Auth::guard('corporate')->user()->id)->with('report',function($query){
               return $query->where('paid',0)->get();
               })->select('id')->get();
                return view('content.corporate.pages.create_invoice',compact(['programs','invoice_number','admin','payment_methods']));
                try{     }catch(\Exception $ex){
                return redirect()->back()->with('error','something wen wrong');
        }
}


public function store($request)
{
        try{
                $invoice=[
                'sender_type'=>'corporate',
                'receiver_type'=>'admin',
                'sender_id'=>Auth::guard('corporate')->user()->id,
                'receiver_id'=>1,
                'invoice_number'=>$request->invoice_number,
                'invoice_date'=>$request->invoice_date,
                'currency'=>$request->currency,
                'total_amount'=>$request->total_amount,
                'bank_name'=>$request->bank_name,
                'serial_no'=>$request->serial_no,
                'serial_no_type'=>$request->serial_no_type,
                'reference_number'=>$request->reference_number,
                'explain_invoice'=>$request->explain_invoice,
                'report_id'=>$request->report_id
            ];



            Invoice::create($invoice);
            Report::where('id',$request->report_id)->update(['paid'=>1]);

                Activity::create(['activeable_id'=>Auth::guard('corporate')->user()->id,'activeable_type'=>'App\Models\Corporate\Corporate',
                'activity'=>'Created Invoice','description_activity'=>"Created Invoice By number $request->invoice_number"]);

                //send notification
                $this->admin_notification->
                sendAdminNotification('Add Invoice',Auth::guard('corporate')->user()->company_name.' Added an invoice for the total amount of '.$request->total_amount,
                'admin_corporate_paid_invoices',null,'corporate');
                return redirect()->back()->with('success', "invoice Added successfully .");
        }catch(\Exception $ex){
                return redirect()->back()->with('error','something wen wrong');
        }
}


//edit invoice
public function edit($request)
{

    try{
        $id = $request->id;
        $check = Invoice::find($id);
        if($check){
        $data=$check;
        return response()->json($data);
        }
    } catch (\Exception $ex) {
        return back()->with('error',  'something went wrong');
    }

}




//update invoice
public function update($request)
{

    try{
        $id = $request->id;
        $check = Invoice::find($id);
        if($check){
        $invoice=[
        'sender_type'=>'corporate',
        'receiver_type'=>'admin',
        'sender_id'=>Auth::guard('corporate')->user()->id,
        'receiver_id'=>1,
        'invoice_number'=>$request->invoice_number,
        'invoice_date'=>$request->invoice_date,
        'currency'=>$request->currency,
        'total_amount'=>$request->total_amount,
        'bank_name'=>$request->bank_name,
        'serial_no'=>$request->serial_no,
        'serial_no_type'=>$request->serial_no_type,
        'reference_number'=>$request->reference_number,
        'explain_invoice'=>$request->explain_invoice,
        'status'=>0
        ];
        $data=Invoice::where('id', $check->id)->update($invoice);
                    Activity::create(['activeable_id'=>Auth::guard('corporate')->user()->id,'activeable_type'=>'App\Models\Corporate\Corporate',
        'activity'=>'Updated Invoice','description_activity'=>"Updated Invoice By number $request->invoice_number"]);
         //send notification
         $this->admin_notification->
         sendAdminNotification('Update Invoice',Auth::guard('corporate')->user()->company_name.' Updated an Invoice ',
         'admin_corporate_paid_invoices',null,'corporate');

         return response()->json(['status' => 'success'], 200);

        }else{
        return response()->json(['status' => 'failed']);
        }
    } catch (\Exception $ex) {

        return back()->with('error',  'something went wrong');

    }

}




}
