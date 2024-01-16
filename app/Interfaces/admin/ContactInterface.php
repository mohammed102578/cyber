<?php

namespace App\Interfaces\admin;


interface  ContactInterface
{
    public function index();
    public function get_contacts($request);
    public function contacts_answer($request);
    public function destroy($request);


}


























?>