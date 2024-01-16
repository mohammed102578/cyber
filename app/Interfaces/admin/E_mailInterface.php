<?php

namespace App\Interfaces\admin;


interface  E_mailInterface
{
public function email();
public function get_corporate_emails($request);
public function get_reporter_emails($request);
public function store($request);

public function show_email($request);

public function destroy($request);



}


























?>