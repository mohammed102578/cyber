<?php

namespace App\Interfaces\corporate;

interface VisibilityProgramInterface
{

public function private_get($request,$id);


public function semi_private_get($request,$id);


public function blocking_get($request,$id);

public function get_reports($request,$id);

public function show_report($id);

public function status($request);

public function get_status($request);

public function status_report($request);

public function private_store($request);

public function blocking_store($request);

public function semi_private_delete($request);

public function private_delete($request);

public function blocking_delete($request);

}
?>
