<?php

namespace App\Interfaces\corporate;


interface InvoiceInterface
{


public function all_invoices();


public function index();


public function get_invoices($request);

public function create();

public function store($request);


public function edit($request);


public function update($request);






}
