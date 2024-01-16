<?php

namespace App\Interfaces\admin;


interface  ReporterInterface
{
public function index();

public function get_reporters($request);
public function edit($request);
public function update($request);

public function block($request);

public function soft_delete($request);

public function trash_reporters();

public function get_trash_reporters($request);

public function destroy($request);

public function restore($request);


}


























?>