<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HobbyRequest;
use App\Repository\admin\HobbyRepository;
use Illuminate\Http\Request;

class HobbyController extends Controller
{

  public $hobby;
  public function __construct(HobbyRepository $hobby)
  {
      $this->hobby = $hobby;
  }

//get hobby 
public function index()
{
return $this->hobby->index();
} 

//get all hobby use ajax and yajar data table
public function get_hobbies(Request $request)
{
  return $this->hobby->get_hobbies($request);

}

//edit hobby use ajax 
public function edit(Request $request)
{
  return $this->hobby->edit($request);

}

//updated hobby
public function update(HobbyRequest $request)
{
  return $this->hobby->update($request);
}

//create hobby
public function store(HobbyRequest $request)
{
  return $this->hobby->store($request);

}

// delete hobby
public function destroy(Request $request)
{
  return $this->hobby->destroy($request);

}


}
