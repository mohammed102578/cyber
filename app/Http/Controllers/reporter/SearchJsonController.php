<?php

namespace App\Http\Controllers\reporter;

use App\Http\Controllers\Controller;
use App\Repository\reporter\SearchJsonRepository;

class SearchJsonController extends Controller
{
  protected $search_vertical;
  public function __construct(SearchJsonRepository $search_vertical)
  {

      $this->search_vertical = $search_vertical;
  }

  public function search_vertical()
{
  return $this->search_vertical->search_vertical();

}
}
