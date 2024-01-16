<?php

namespace App\Http\Controllers\corporate;

use App\Http\Controllers\Controller;
use App\Repository\corporate\SearchJsonRepository;


class SearchJsonController extends Controller
{
  protected $search;
  public function __construct(SearchJsonRepository $search)
  {

      $this->search = $search;
  }
   public function search_vertical()
 {
  return $this->search->search_vertical();

 }
}
