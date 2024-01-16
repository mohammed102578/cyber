<?php

namespace App\Interfaces\reporter;

interface InvoiceInterface
{

public function all_invoices();
public function index();
public function get_invoices($request);

}
