<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReporterRequest;
use App\Repository\admin\ReporterRepository;
use Illuminate\Http\Request;


class ReporterController extends Controller
{

    protected $reporter;
public function __construct(ReporterRepository $reporter){

    $this->reporter=$reporter;
}
 
//get reporter page
public function index()
{
   return $this->reporter->index();
} 


//get all reporters using ajax 
public function get_reporters(Request $request)
{
  
    return $this->reporter->get_reporters($request);

}

//edit reporter
public function edit(Request $request)
{
  
    return $this->reporter->edit($request);

}


//
public function update(ReporterRequest $request)
{
  
    return $this->reporter->update($request);

}


//block reporter          
public function block(Request $request)
{
    return $this->reporter->block($request);

}


//delete reporter
public function soft_delete(Request $request)
{

    return $this->reporter->soft_delete($request);


}


//====================================soft delete

public function trash_reporters()
{
 
    return $this->reporter->trash_reporters();

} 


//===================get all reporters using ajax 
public function get_trash_reporters(Request $request)
{
    return $this->reporter->get_trash_reporters($request);

}


//destroy reporter from database
public function destroy(Request $request)
{

    return $this->reporter->destroy($request);

   
}


//restore reporter
public function restore(Request $request)
{

    return $this->reporter->restore($request);

}




}