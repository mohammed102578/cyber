<?php

namespace App\Interfaces\corporate;


interface ConnectionInterface
{
  public function store($request);
  public function destroy($id);
 
}
