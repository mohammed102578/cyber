<?php

namespace App\Interfaces\admin;


interface  CorporateInterface
{
public function index();

public function get_corporates($request);
public function edit($request);
public function update($request);

public function block($request);

public function soft_delete($request);

public function trash_corporates();

public function get_trash_corporates($request);

public function destroy($request);

public function restore($request);


}


























?>