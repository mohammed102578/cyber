<?php

namespace App\Http\Controllers\corporate;

use App\Http\Controllers\Controller;
use App\Repository\corporate\VisibilityProgramRepository;
use Illuminate\Http\Request;



class VisibilityProgramController extends Controller
{
    protected $visibility;
    public function __construct(VisibilityProgramRepository $visibility)
    {
        $this->visibility = $visibility;
    }
    //============get semi_private_program using Ajax

    public function private_get(Request $request, $id)
    {
        return $this->visibility->private_get($request, $id);
    }

    //============get semi_private_program using Ajax
    public function semi_private_get(Request $request, $id)
    {
        return $this->visibility->semi_private_get($request, $id);
    }



    //======================================get reporter blocking to submit program

    public function blocking_get(Request $request, $id)
    {
        return $this->visibility->blocking_get($request, $id);
    }




    //get all  program report
    public function get_reports(Request $request, $id)
    {
        return $this->visibility->get_reports($request, $id);
    }




    //show report  page

    public function show_report($id)
    {
        return $this->visibility->show_report($id);
    }


  //get all  program report
  public function status_report(Request $request)
  {
      return $this->visibility->status_report($request);
  }




  //show report  page

  public function get_status(Request $request)
  {
      return $this->visibility->get_status($request);
  }


    //status reporter semi_private

    public function status(Request $request)
    {
        return $this->visibility->status($request);
    }



    //store private_program to database

    public function private_store(Request $request)
    {
        return $this->visibility->private_store($request);
    }



    //store blocking_program to database

    public function blocking_store(Request $request)
    {
        return $this->visibility->blocking_store($request);
    }

    //destroy semi_private_program from database

    public function semi_private_delete(Request $request)
    {
        return $this->visibility->semi_private_delete($request);
    }




    //destroy private_program from database

    public function private_delete(Request $request)
    {
        return $this->visibility->private_delete($request);
    }




    //destroy blocking_program from database

    public function blocking_delete(Request $request)
    {
        return $this->visibility->blocking_delete($request);
    }
}
