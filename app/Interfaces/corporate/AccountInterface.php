<?php

namespace App\Interfaces\corporate;



interface AccountInterface
{
  

public function index();

public function update($request);

public function change_username($request);

public function change_email($request);

public function change_password($request);


}
