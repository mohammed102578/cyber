<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TeamRequest;
use App\Repository\admin\TeamRepository;
use Illuminate\Http\Request;

class TeamController extends Controller
{

  public $team;
  public function __construct(TeamRepository $team)
  {
      $this->team = $team;
  }

//get team
public function index()
{
return $this->team->index();
}

//get all team use ajax and yajar data table
public function get_teams(Request $request)
{
  return $this->team->get_teams($request);

}

//create team
public function store(TeamRequest $request)
{
  return $this->team->store($request);

}

//edit team use ajax
public function edit(Request $request)
{
  return $this->team->edit($request);

}

//updated team
public function update(TeamRequest $request)
{
  return $this->team->update($request);
}


// delete team
public function destroy(Request $request)
{
  return $this->team->destroy($request);

}


}
