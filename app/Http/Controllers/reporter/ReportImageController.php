<?php

namespace App\Http\Controllers\reporter;

use App\Http\Controllers\Controller;
use App\Repository\reporter\ReportImageRepository;
use Illuminate\Http\Request;


class ReportImageController extends Controller
{

  protected $image;
  public function __construct(ReportImageRepository $image)
  {

    $this->image = $image;
  }
  //==========get all reporter report's
  public function images($id)
  {
    return $this->image->images($id);
  }


  //==========get all reporter report's using ajax

  public function get_images(Request $request, $id)
  {
    return $this->image->get_images($request, $id);
  }

  //==================create report
  public function add_image(Request $request)
  {
    return $this->image->add_image($request);
  }

  //====================delete program
  public function destroy(Request $request)
  {
    return $this->image->destroy($request);
  }
}
