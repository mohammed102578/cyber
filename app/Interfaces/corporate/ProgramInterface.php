<?php

namespace App\Interfaces\corporate;


interface ProgramInterface
{


    public function index();

    public function create();
  
    public function store($request);
   
    public function update($request);
  
    public function destroy($request);
   
    public function setting($request);
 
    public function submit($request, $id);
    
    public function program_requirement($request);
 


}



?>