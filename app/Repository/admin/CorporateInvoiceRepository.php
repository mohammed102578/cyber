<?php

namespace App\Repository\admin;

use App\Http\Services\Notification;
use App\Interfaces\admin\CorporateInvoiceInterface;
use App\Models\Activity;
use App\Models\Admin\Admin;
use App\Models\Invoice;
use App\Models\Corporate\Corporate;
use App\Models\Reporter\Report;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CorporateInvoiceRepository implements CorporateInvoiceInterface
{

    public object $corporate_notification;
    public function __construct()
    {
        $this->corporate_notification = new Notification;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $admin = Admin::find(Auth::guard('admin')->user()->id);
            $payment_methods = $admin->paymentable()->get();
            return view('content.admin.pages.invoice.paid_corporate_invoices', compact('payment_methods'));
        } catch (\Exception $ex) {
            return back()->with('error', 'something went wrong');
        }
    }


    /**
     * Display a listing of the resource.
     * display data using ajax and yajara datatable
     * @return \Illuminate\Http\Response
     */
    public function get_paid_invoices($request)
    {

        try {

            if ($request->ajax()) {

                $data = DB::table('invoices')
                    ->where('invoices.sender_type', 'corporate')->where('invoices.receiver_type', 'admin')
                    ->orderBy('invoices.id', 'desc')
                    ->leftJoin('corporates', 'corporates.id', '=', 'invoices.sender_id')
                    ->select('invoices.*', 'corporates.company_name', 'corporates.email')
                    ->get();


                return Datatables()->of($data)
                    ->addIndexColumn()

                    //created_at
                    ->addColumn('created_at', function ($data) {

                        $created_at = Carbon::parse($data->created_at)->format('d M Y');

                        return $created_at;
                    })
                    ->rawColumns(['created_at'])

                    //total_amount
                    ->addColumn('total_amount', function ($data) {

                        return $total_amount = number_format($data->total_amount, 0, '.', ',');
                    })
                    ->rawColumns(['total_amount'])

                    //invoice_number
                    ->addColumn('invoice_number', function ($data) {

                        return $invoice_number = "#" . $data->invoice_number;
                    })
                    ->rawColumns(['invoice_number'])


                    ->addColumn('status', function ($data) {

                        if ($data->status == 1) {
                            $statutsBtn = 'OK';
                        } else {
                            $statutsBtn =  'Pending';
                        }
                        return $statutsBtn;
                    })->rawColumns(['status'])



                    ->addColumn('action', function ($data) {
                        $actionBtn = ' <div class="d-inline-block">
            <a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
            <i class="bx bx-dots-vertical-rounded">

            </i></a>
            <div class="dropdown-menu dropdown-menu-end m-0">
            <a class=" hapus-modal_check dropdown-item " id="' . $data->id . '"href=""data-bs-toggle="modal" data-bs-target="#edit_invoice"><i class="bx bx-check-double text-info"></i> Check Invoice</a>

            <a class=" print_invoice_modal dropdown-item " id="' . $data->id . '"href=""data-bs-toggle="modal" data-bs-target="#print_invoice"><i class="bx bxs-detail text-success"></i> More Details</a>
            <div class="dropdown-divider">

            </div>
            <a class=" hapus-modale dropdown-item " id="' . $data->id . '" ><i class="bx bx-trash text-danger" style="margin-bottom:6px;"></i> Delete</a>
            </div>
            </div> ';
                        return $actionBtn;
                    })->rawColumns(['action'])->make(true);
            }
        } catch (\Exception $ex) {

            return back()->with('error', 'something went wrong');
        }
    }




    /**
     * Update the specified resource in storage.
     * updata status invoice
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function check_invoice($request)
    {

        try {
            $id = $request->id;
            $check = Invoice::find($id);

            if ($check) {
                $data = $check;
                if ($data->status == 1) {
                    $status = 0;
                    // send notification
                    $this->corporate_notification->sendCorporateNotification(
                            'Invoice Status',
                            Auth::guard('admin')->user()->name . '  changed the status of the invoice to pendding',
                            'corporate_invoice',
                            null,
                            $check->sender_id,
                            'admin'
                        );
                } else {
                    $status = 1;
                    // send notification
                    $this->corporate_notification->sendCorporateNotification(
                            'Invoice Status',
                            Auth::guard('admin')->user()->name . '  changed the status of the invoice to OK',
                            'corporate_invoice',
                            null,
                            $check->sender_id,
                            'admin'
                        );
                }
                Invoice::where('id', $data->id)->update(['status' => $status]);
                Activity::create([
                    'activeable_id' => Auth::guard('admin')->user()->id, 'activeable_type' => 'App\Models\Admin\Admin',
                    'activity' => 'Updated Invoice ', 'description_activity' => " Updated Corporate Invoice Status"
                ]);
                return response()->json(['status' => 'success'], 200);
            } else {
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

        try {
            $id = $request->id;
            $check = Invoice::find($id);
            if ($check) {
                $data = $check;
                Report::where('id',$data->report_id)->update(['paid'=>0]);
                $data->delete();
                Activity::create([
                    'activeable_id' => Auth::guard('admin')->user()->id, 'activeable_type' => 'App\Models\Admin\Admin',
                    'activity' => 'Deleted Invoice ', 'description_activity' => " Deleted Corporate Invoice "
                ]);
                return response()->json(['status' => 'success'], 200);
            } else {
                return response()->json(['status' => 'failed']);
            }
        } catch (\Exception $ex) {
            return back()->with('error',  'something went wrong');
        }
    }




    /**
     * print the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function print_invoice($request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'invoice_id' => 'required|numeric',
                'page_size' => 'required|numeric',
            ]);


            if ($validator->fails()) {
                return back()->with('error', 'You have entered incorrect data');
            }
            $invoice_id = $request->invoice_id;
            $check = Invoice::find($invoice_id);

            if ($check) {
                $invoice = $check;
                $corporate = Corporate::where('id', $invoice->sender_id)->first();
                if ($request->page_size == 1) {
                    return view('content.admin.pages.invoice.corporate_A4', compact('invoice', 'corporate'));
                } else {
                    return view('content.admin.pages.invoice.corporate_ticket', compact('invoice', 'corporate'));
                }
            } else {
                return back()->with('error',  'something went wrong');
            }
        } catch (\Exception $ex) {

            return back()->with('error',  'something went wrong');
        }
    }
}
