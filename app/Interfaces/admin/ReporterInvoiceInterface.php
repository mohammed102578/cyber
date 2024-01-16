<?php

namespace App\Interfaces\admin;


interface  ReporterInvoiceInterface
{
  public function all_invoices();
   
  public function index();
    
  public function get_paid_invoices($request);
 
  public function create($request);
 
  public function store($request);
  
  public function edit($request);
 
  public function update( $request);
  public function print($request);
 
  public function destroy($request);
 
}


























?>