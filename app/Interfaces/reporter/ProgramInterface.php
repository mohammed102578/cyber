<?php

namespace App\Interfaces\reporter;




interface ProgramInterface
{


    //return all public program and sem_private program need to request to join and private program
    public function index();
    //show one program by details
    public function show($id);

    public function loadMoreData($request);

    public function request_join($request);
}
