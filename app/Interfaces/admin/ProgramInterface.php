<?php

namespace App\Interfaces\admin;


interface  ProgramInterface
{
    public function index();
    
    public function get_program($request);
       
    public function submit($request);
   
    public function unsubmit_program();
  
    public function get_unsubmit_program($request);
      
    public function show($request);
  
    public function edit($request);
        
    public function update($request) ;

    public function status($request);


    public function get_status($request);

    public function destroy($request);
  
}


























?>