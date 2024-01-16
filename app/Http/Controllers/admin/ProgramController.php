<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Corporate\Update_program_Request;
use App\Http\Services\Notification;
use App\Repository\admin\ProgramRepository;
use Illuminate\Http\Request;

class ProgramController extends Controller
{

    public $program;
    public function __construct(ProgramRepository $program)
    {
        $this->program=$program;
      
    }
public function index()
{
return $this->program->index();
} 

public function get_program(Request $request)
{
    return $this->program->get_program($request);

}


//submit program

public function submit(Request $request)
{
    return $this->program->submit($request);

}


//unsubmit_program
public function unsubmit_program()
{
    return $this->program->unsubmit_program();

} 

//get_unsubmit_program
public function get_unsubmit_program(Request $request)
{
    return $this->program->get_unsubmit_program($request);    
}


//program show page using ajax

public function show(Request $request)
{
    return $this->program->show($request);    

}


//program edit page using ajax

public function edit(Request $request)
{
    return $this->program->edit($request);    

}

//update program to database
public function update(Update_program_Request $request)
{
    return $this->program->update($request);    

}

public function status(Request $request)
{
    return $this->program->status($request);    

}

//update program to database
public function get_status(Request $request)
{
    return $this->program->get_status($request);    

}

//delete program
public function destroy(Request $request)
{
    return $this->program->destroy($request);    
}

}





