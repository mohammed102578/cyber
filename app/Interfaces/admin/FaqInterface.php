<?php

namespace App\Interfaces\admin;


interface  FaqInterface
{
public function index();
public function get_faqs($request);

public function store($request);
public function edit($request);
public function update($request);
public function destroy($request);



}


























?>