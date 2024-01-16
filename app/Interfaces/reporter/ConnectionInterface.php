<?php

namespace App\Interfaces\reporter;

interface ConnectionInterface
{

  public function store($request);

  public function destroy($id);
 
}
