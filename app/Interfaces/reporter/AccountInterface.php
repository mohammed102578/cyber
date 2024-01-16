<?php

namespace App\Interfaces\reporter;


 interface AccountInterface 
{

    public function index();

    public function update($request);
   
    public function change_email($request);
   
    public function change_phone($request);
   
    public function change_password($request);
  
}
