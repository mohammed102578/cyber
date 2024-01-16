<?php

namespace App\Interfaces\admin;


interface  ReportInterface
{
    public function reports();
   
    public function get_reports($request);
   
    public function show_report($id);
   
    public function get_status($request);
    
    public function status($request);
    
    public function hacktivity($request);
 
    public function destroy($request);
  

}


























?>