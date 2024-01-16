<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogRequest;
use App\Repository\admin\BlogRepository;
use Illuminate\Http\Request;

class BlogController extends Controller
{

  public $blog;
  public function __construct(BlogRepository $blog)
  {
      $this->blog = $blog;
  }

//get blog
public function index()
{
return $this->blog->index();
}

//create blog
public function store(Request $request)
{
  return $this->blog->store($request);
}

//updated blog
public function update(BlogRequest $request)
{
  return $this->blog->update($request);
}


//edit blog
public function edit($id)
{
  return $this->blog->edit($id);
}

//archive
public function archive(Request $request)
{
  return $this->blog->archive($request);
}


// delete blog
public function destroy($id)
{

  return $this->blog->destroy($id);

}


}
