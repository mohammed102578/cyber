<?php

namespace App\Http\Controllers\reporter;

use App\Http\Controllers\Controller;
use App\Repository\reporter\InvoiceRepository;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    protected $invoice;
    public function __construct(InvoiceRepository $invoice)
    {

        $this->invoice = $invoice;
    }


    public function all_invoices()
    {
        return $this->invoice->all_invoices();
    }


    public function index()
    {
        return $this->invoice->index();
    }


    //get all invoices using ajax 
    public function get_invoices(Request $request)
    {
        return $this->invoice->get_invoices($request);
    }
}
