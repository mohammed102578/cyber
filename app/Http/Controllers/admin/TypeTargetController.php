<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TargetRequest;
use App\Models\Activity;
use Illuminate\Http\Request;
use App\Models\Corporate\TypeTarget;
use App\Repository\admin\TypeTargetRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TypeTargetController extends Controller
{

    public $target;
  public function __construct(TypeTargetRepository $target)
  {
    $this->target = $target;
  }
//get type target page
public function index()
{
return $this->target->index();
} 

//get all target use ajax and yajar data table
public function get_type_targets(Request $request)
{
    return $this->target->get_type_targets($request);

}

//store target
public function store(TargetRequest $request)
{
    return $this->target->store($request);

}

//edit target use ajax 
public function edit(Request $request)
{
    return $this->target->edit($request);

}

//update target
public function update(TargetRequest $request)
{
    return $this->target->update($request);

}

// delete type target
public function destroy(Request $request)
{
    return $this->target->destroy($request);

}




}
