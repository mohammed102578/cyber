<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Add_Invoice_Request;
use App\Http\Requests\Admin\update_Invoice_Request;
use App\Repository\admin\ReporterInvoiceRepository;
use Illuminate\Http\Request;


class ReporterInvoiceController extends Controller
{


  public $reporter_invoice;
  public function __construct(ReporterInvoiceRepository $reporter_invoice)
  {
    $this->reporter_invoice = $reporter_invoice;
  }

  public function all_invoices()
  {
    return $this->reporter_invoice->all_invoices();
  }


  public function index()
  {
    return $this->reporter_invoice->index();
  }

  //get all reporters using ajax 
  public function get_paid_invoices(Request $request)
  {
    return $this->reporter_invoice->get_paid_invoices($request);
  }

  public function create(Request $request)
  {
    return $this->reporter_invoice->create($request);
  }

  public function store(Add_Invoice_Request $request)
  {
    return $this->reporter_invoice->store($request);
  }

  public function edit(Request $request)
  {
    return $this->reporter_invoice->edit($request);
  }

  //update invoice      
  public function update(update_Invoice_Request $request)
  {
    return $this->reporter_invoice->update($request);
  }

  //print invoice
  public function print(Request $request)
  {
    return $this->reporter_invoice->print($request);
  }


  //delete invoice
  public function destroy(Request $request)
  {
    return $this->reporter_invoice->destroy($request);
  }
}
