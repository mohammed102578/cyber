<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Repository\admin\CorporateInvoiceRepository;
use Illuminate\Http\Request;



class CorporateInvoiceController extends Controller
{

    public $corporate_invoice;
    public function __construct(CorporateInvoiceRepository $corporate_invoice)
    {
        $this->corporate_invoice = $corporate_invoice;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->corporate_invoice->index();
    }
 

    /**
     * Display a listing of the resource.
     * display data using ajax and yajara datatable
     * @return \Illuminate\Http\Response
     */
    public function get_paid_invoices(Request $request)
    {
        return $this->corporate_invoice->get_paid_invoices($request);
    }

 


    /**
     * Update the specified resource in storage.
     * updata status invoice
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function check_invoice(Request $request)
    {
        return $this->corporate_invoice->check_invoice($request);
    }




    /**
     * print the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function print_invoice(Request $request)
    {
        return $this->corporate_invoice->print_invoice($request);
    }


     /**
     * delete the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        return $this->corporate_invoice->destroy($request);
    }
}
