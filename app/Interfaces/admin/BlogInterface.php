<?php

namespace App\Interfaces\admin;


interface  BlogInterface
{
public function index();
public function edit($id);
public function store($request);
public function update($request);
public function archive($request);
public function destroy($id);



}


























?>