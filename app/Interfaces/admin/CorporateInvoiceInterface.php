<?php

namespace App\Interfaces\admin;


interface  CorporateInvoiceInterface
{
public function index();

public function get_paid_invoices($request);

public function check_invoice($request);

public function destroy($request);

public function print_invoice($request);

}


























?>