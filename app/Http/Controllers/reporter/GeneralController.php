<?php

namespace App\Http\Controllers\reporter;

use App\Http\Controllers\Controller;
use App\Repository\reporter\GeneralRepository;
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
