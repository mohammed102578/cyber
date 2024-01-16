<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CorporateRequest;
use App\Models\Activity;
use Illuminate\Http\Request;
use App\Models\Corporate\Corporate;
use App\Models\Nationality;
use App\Repository\admin\CorporateRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CorporateController extends Controller
{

  public $corporate;
  public function __construct(CorporateRepository $corporate)
  {
    $this->corporate = $corporate;
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */

  public function index()
  {
    return $this->corporate->index();
  }

  /**
   * Display a listing of the resource.
   * get all corporate use ajax and yajar data table
   * @return \Illuminate\Http\Response
   */

  public function get_corporates(Request $request)
  {
    return $this->corporate->get_corporates($request);
  }


  /**
   * Show the form for editing the specified resource.
   * edit corporate use ajax 
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */

  public function edit(Request $request)


  {

    return $this->corporate->edit($request);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */

  public function update(CorporateRequest $request)
  {
    return $this->corporate->update($request);
  }



  /**
   * Update the specified resource in storage.
   * updated status
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */

  //block corporate
  public function block(Request $request)
  {

    return $this->corporate->block($request);
  }


  /**
   * Remove the specified resource from storage.
   * 
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */

  public function soft_delete(Request $request)
  {
    return $this->corporate->soft_delete($request);
  }

  //================soft delete

  public function trash_corporates()
  {
    return $this->corporate->trash_corporates();
  }

  /**
   * Display a listing of the resource.
   * get all corporate use ajax and yajar data table
   * @return \Illuminate\Http\Response
   */

  public function get_trash_corporates(Request $request)
  {
    return $this->corporate->get_trash_corporates($request);
  }

  //force delete 
  public function destroy(Request $request)
  {

    return $this->corporate->destroy($request);
  }

  //restore corporate
  public function restore(Request $request)
  {
    return $this->corporate->restore($request);
  }
}
