<?php

namespace App\Interfaces\admin;


interface  ProfileInterface
{
    public function profile();


    //display setting page
    public function setting();

    public function reporter_profile($id);

    public function corporate_profile($id);

}


























?>
