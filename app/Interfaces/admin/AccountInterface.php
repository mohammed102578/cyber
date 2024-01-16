<?php

namespace App\Interfaces\admin;


interface  AccountInterface
{
public function account();

public function security();

public function update($request);

public function change_email($request);

public function change_phone($request);

public function change_password($request);




}


























?>
