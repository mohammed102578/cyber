<?php

namespace App\Repository\admin;



use App\Http\Services\Notification;
use App\Interfaces\admin\ReporterInvoiceInterface;
use App\Models\Admin\Admin;
use App\Models\Activity;
use App\Models\Invoice;
use App\Models\PaymentMethod;
use App\Models\Reporter\Report;
use App\Models\Reporter\Reporter;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReporterInvoiceRepository implements ReporterInvoiceInterface
{


    public $reporter_notification;
    public function __construct()
    {
        $this->reporter_notification = new Notification;

    }
  public function all_invoices()
  {
    try{
        $reporters=Reporter::whereHas('report',function($query){
             $query->where('paid',0);
        })->get();
        return view('content.admin.pages.invoice.all_invoices',compact('reporters'));
          } catch (\Exception $ex) {
        return back()->with('error', 'something went wrong');
      }
  }


  public function index()
  {
    try{
        $payment_methods=PaymentMethod::all();
        $admin=Admin::first();
        return view('content.admin.pages.invoice.paid_reporter_invoices',compact(['payment_methods','admin']));
         } catch (\Exception $ex) {
        return back()->with('error', 'something went wrong');
      }
  }

//get all reporters using ajax
public function get_paid_invoices($request)
{
 try{

      if ($request->ajax()) {
        $data =DB::table('invoices')
        ->where('invoices.sender_type','admin')->where('invoices.receiver_type','reporter')->where('invoices.sender_id',Auth::guard('admin')->user()->id)
        ->orderBy('invoices.id', 'desc')
        ->leftJoin('reporters', 'reporters.id', '=', 'invoices.receiver_id')
        ->select('invoices.*', 'reporters.first_name','reporters.last_name','reporters.email')
        ->get();

      return Datatables()->of($data)
      ->addIndexColumn()

      //reporter_name
      ->addColumn('reporter_name', function($data){
      return $reporter_full_name=$data->first_name." ".$data->last_name;
      })
      ->rawColumns(['reporter_name'])

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

    //invoice_number
    ->addColumn('invoice_number', function($data){

        return $invoice_number="#".$data->invoice_number;
    })
    ->rawColumns(['invoice_number'])



      ->addColumn('action', function($data){
      $actionBtn = ' <div class="d-inline-block">
      <a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
      <i class="bx bx-dots-vertical-rounded">

      </i></a>
      <div class="dropdown-menu dropdown-menu-end m-0">
      <a class=" edit_modal_invoice dropdown-item " id="'. $data->id.'"href=""data-bs-toggle="modal" data-bs-target="#edit_invoice"><i class="bx bx-check-double text-info"></i> Edit Invoice</a>

      <a class=" print_invoice_modal dropdown-item " id="'. $data->id.'"href=""data-bs-toggle="modal" data-bs-target="#print_invoice"><i class="bx bxs-detail text-success"></i> More Details</a>
      <div class="dropdown-divider">

      </div>
      <a class=" hapus-modale dropdown-item " id="'. $data->id.'" ><i class="bx bx-trash text-danger" style="margin-bottom:6px;"></i> Delete </a>
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



public function create($request)
{
  try{
        $id=$request->reporter_id;
        if(isset($id)){
            $rand= rand(1,10000000);
            $invoice_number=$id.$rand;
            $reporter=Reporter::where('id',$id)->first();
            $payment_methods=$reporter->paymentable()->get();
            $reports=Report::where('reporter_id',$id)->where('status_id',3)->where('paid',0)->get();
            return view('content.admin.pages.invoice.create_invoice',compact(['reports','invoice_number','reporter','payment_methods']));
        }else{
            return back()->with('error', 'something went wrong');

        }

      } catch (\Exception $ex) {
        return back()->with('error', 'something went wrong');
      }
}


public function store($request)
{

    DB::beginTransaction();
    try{

          $invoice=[
          'sender_type'=>'admin',
          'receiver_type'=>'reporter',
          'sender_id'=>Auth::guard('admin')->user()->id,
          'receiver_id'=>$request->reporter_id,
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
          Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
          'activity'=>'Create Invoice ','description_activity'=>"Admin Create reporter Invoice "]);

          // send notification
          $this->reporter_notification->
          sendReporterNotification('Add Invoice ',Auth::guard('admin')->user()->name.' Added an invoice for the total amount of '.$request->total_amount,
          'reporter_invoices',null,$request->reporter_id,'admin');


          DB::commit();

          return redirect()->route('admin_paid_invoices')->with('success', "invoice Added successfully .");
          } catch (\Exception $ex) {
        DB::rollback();

        return back()->with('error', 'something went wrong');
    }
}



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
        'sender_type'=>'admin',
        'receiver_type'=>'reporter',
        'sender_id'=>Auth::guard('admin')->user()->id,
        'receiver_id'=>$request->reporter_id,
        'invoice_number'=>$request->invoice_number,
        'invoice_date'=>$request->invoice_date,
        'currency'=>$request->currency,
        'total_amount'=>$request->total_amount,
        'bank_name'=>$request->bank_name,
        'serial_no'=>$request->serial_no,
        'serial_no_type'=>$request->serial_no_type,
        'reference_number'=>$request->reference_number,
        'explain_invoice'=>$request->explain_invoice
        ];
        $data=Invoice::where('id', $check->id)->update($invoice);
        Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
        'activity'=>'Updated Invoice','description_activity'=>"Admin Updated Reporter Invoice "]);

         // send notification
         $this->reporter_notification->
         sendReporterNotification('Update Invoice ',Auth::guard('admin')->user()->name.' Updateed an invoice for the total amount of '.$request->total_amount,
         'reporter_invoices',null,$request->reporter_id,'admin');


        return response()->json(['status' => 'success'], 200);
        }else{
        return response()->json(['status' => 'failed']);
        }
    } catch (\Exception $ex) {

        return back()->with('error',  'something went wrong');

    }

}




//print invoice
public function print($request)
{

    try{
        $validator = Validator::make($request->all(),[
        'invoice_id' =>'required|numeric',
        'page_size' =>'required|numeric',

        ]);
        if ($validator->fails()) {
        return back()->with('error' ,'You have entered incorrect data');
        }
        $invoice_id = $request->invoice_id;

        $check = Invoice::find($invoice_id);
        if($check){
        $invoice=$check;
        $reporter=Reporter::where('id',$invoice->receiver_id)->first();
        if($request->page_size==1){
        return view('content.admin.pages.invoice.reporter_A4',compact('invoice','reporter'));
        }else{
        return view('content.admin.pages.invoice.reporter_ticket',compact('invoice','reporter'));
        }
        }else{
        return back()->with('error',  'something went wrong');
        }
    } catch (\Exception $ex) {

        return back()->with('error',  'something went wrong');

    }
}





//delete invoice
public function destroy($request)
{

    try{
        $id = $request->id;
        $check = Invoice::find($id);
        if($check){
        $data=$check;
        Report::where('id',$data->report_id)->update(['paid'=>0]);
        Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
        'activity'=>'Deleted Invoice','description_activity'=>"Admin Deleted Reporter Invoice "]);
         // send notification
         $this->reporter_notification->
         sendReporterNotification('Delete Invoice ',Auth::guard('admin')->user()->name.' Deleted an invoice for the total amount of '.$request->total_amount,
         'reporter_invoices',null,$data->receiver_id,'admin');
         $data->delete();

        return response()->json(['status' => 'success'], 200);
        }else{
        return response()->json(['status' => 'failed']);
        }
    } catch (\Exception $ex) {
        return back()->with('error',  'something went wrong');
    }
}



}
