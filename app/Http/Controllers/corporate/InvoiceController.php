<?php

namespace App\Http\Controllers\corporate;

use App\Http\Controllers\Controller;
use App\Http\Requests\Corporate\Add_Invoice_Request;
use App\Http\Requests\Corporate\update_Invoice_Request;
use App\Repository\corporate\InvoiceRepository;
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

    //get all reporters using ajax
    public function get_invoices(Request $request)
    {
        return $this->invoice->get_invoices($request);
    }


    public function create()
    {
        return $this->invoice->create();
    }


    public function store(Add_Invoice_Request $request)
    {
        return $this->invoice->store($request);
    }


    //edit invoice
    public function edit(Request $request)
    {
        return $this->invoice->edit($request);
    }




    //update invoice
    public function update(update_Invoice_Request $request)
    {
        return $this->invoice->update($request);
    }




}
