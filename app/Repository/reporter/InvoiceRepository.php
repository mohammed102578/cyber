<?php

namespace App\Repository\reporter;

use App\Interfaces\reporter\InvoiceInterface;
use App\Models\Reporter\Reporter;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB ;

class InvoiceRepository implements InvoiceInterface
{



public function all_invoices()
{
    try{
       return view('content.reporter.pages.all_invoices');
    } catch (\Exception $ex) {

        return back()->with('error', 'something went wrong');

    }
}


public function index()
{

    try{
        $reporter=Reporter::find(Auth::guard('reporter')->user()->id);
        $payment_methods=$reporter->paymentable()->get();
        return view('content.reporter.pages.billing',compact(['payment_methods']));
    } catch (\Exception $ex) {

        return back()->with('error', 'something went wrong');

    }
}





//get all invoices using ajax
public function get_invoices($request)
{
    try{

        if (
        $request->ajax()) {

        $data =DB::table('invoices')
        ->where('sender_type','admin')->where('receiver_type','reporter')->where('receiver_id',Auth::guard('reporter')->user()->id)
        ->orderBy('invoices.id', 'desc')
        ->get();


        return Datatables()->of($data)
        ->addIndexColumn()


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


        ->make(true);
        }
    } catch (\Exception $ex) {

        return back()->with('error', 'something went wrong');

    }
    }


}
