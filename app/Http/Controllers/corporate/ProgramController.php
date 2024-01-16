<?php

namespace App\Http\Controllers\corporate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Corporate\Add_program_Request;
use App\Http\Requests\Corporate\Update_program_Request;
use App\Repository\corporate\ProgramRepository;

class ProgramController extends Controller
{

    protected $program;
    public function __construct(ProgramRepository $program)
    {

        $this->program = $program;
    }
    public function index()
    {
        return $this->program->index();
    }

    //create a new program page


    public function create()
    {
        return $this->program->create();
    }

    //store program to database

    public function store(Add_program_Request $request)
    {
        return $this->program->store($request);
    }






    //update program to database

    public function update(Update_program_Request $request)
    {
        return $this->program->update($request);
    }




    public function destroy(Request $request)
    {
        return $this->program->destroy($request);
    }





    //program's settings page

    public function setting(Request $request)
    {
        return $this->program->setting($request);
    }






    //update submit status program
    public function submit(Request $request, $id)
    {
        return $this->program->submit($request, $id);
    }






    //program_requirement update or store

    public function program_requirement(Request $request)
    {
        return $this->program->program_requirement($request);
    }
}
