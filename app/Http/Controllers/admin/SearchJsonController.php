<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Corporate\Corporate;
use App\Models\Corporate\Program;
use App\Models\Reporter\Report;
use App\Models\Reporter\Reporter;
use App\Repository\admin\SearchJsonRepository;
use Illuminate\Http\Request;

class SearchJsonController extends Controller
{

  public $json;
  public function __construct(SearchJsonRepository $json)
  {
    $this->json = $json;
  }
   public function search_vertical(){
  return$this->json->search_vertical();
   }
}
