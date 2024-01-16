<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FaqRequest;
use App\Repository\admin\FaqRepository;
use Illuminate\Http\Request;

class FaqController extends Controller
{

  public $faq;
  public function __construct(FaqRepository $faq)
  {
      $this->faq = $faq;
  }

//get faq
public function index()
{
return $this->faq->index();
}

//get all faq use ajax and yajar data table
public function get_faqs(Request $request)
{
  return $this->faq->get_faqs($request);

}

//edit faq use ajax
public function edit(Request $request)
{
  return $this->faq->edit($request);

}

//updated faq
public function update(FaqRequest $request)
{
  return $this->faq->update($request);
}

//create faq
public function store(FaqRequest $request)
{
  return $this->faq->store($request);

}

// delete faq
public function destroy(Request $request)
{
  return $this->faq->destroy($request);

}


}
