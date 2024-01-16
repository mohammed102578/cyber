<?php

namespace App\Http\Controllers\reporter;

use App\Http\Controllers\Controller;
use App\Repository\reporter\ProgramRepository;
use Illuminate\Http\Request;



class ProgramController extends Controller
{
    protected $program;
    public function __construct(ProgramRepository $program)
    {

        $this->program = $program;
    }


    //return all public program and sem_private program need to request to join and private program
    public function index()
    {
        return $this->program->index();
    }
    //show one program by details
    public function show($id)
    {
        return $this->program->show($id);
    }

    public function loadMoreData(Request $request)
    {
        return $this->program->loadMoreData($request);
   
    }
    public function request_join(Request $request)
    {
        return $this->program->request_join($request);
    }
}
