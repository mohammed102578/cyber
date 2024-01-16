<?php

namespace App\Interfaces\reporter;

interface ReportImageInterface
{
  public function images($id);

  public function get_images($request, $id);

  public function add_image($request);

  public function destroy($request);
}
