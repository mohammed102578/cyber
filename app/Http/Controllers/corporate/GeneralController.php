<?php

namespace App\Http\Controllers\corporate;

use App\Http\Controllers\Controller;
use App\Repository\corporate\GeneralRepository;
use Illuminate\Support\Facades\Request;

class GeneralController extends Controller
{

  protected $general;
  public function __construct(GeneralRepository $general)
  {

      $this->general = $general;
  }

  public function faq()
  {
    return $this->general->faq();

  }

}
