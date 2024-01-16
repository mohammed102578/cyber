<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Repository\web\ProgramRepository;
use Illuminate\Http\Request;

class ProgramController extends Controller
{

    public object $program;
    public function __construct(ProgramRepository $program)
    {
        $this->program = $program;
    }
    //return all public program and sem_private program need to request to join and private program
    public function program()
    {
        return $this->program->program();
    }

    //returnloadmore

    public function loadMoreData(Request  $request)
    {
        return $this->program->loadMoreData($request);
    }


    //program show page using ajax

    public function show(Request $request)
    {
        return $this->program->show($request);
    }
}
