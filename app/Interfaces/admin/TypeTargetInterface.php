<?php

namespace App\Interfaces\admin;


interface  TypeTargetInterface
{
public function index();
public function get_type_targets($request);
public function edit($request);
public function store($request);
public function update($request);
public function destroy($request);

}


























?>