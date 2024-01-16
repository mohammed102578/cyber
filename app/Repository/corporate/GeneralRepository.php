<?php

namespace App\Repository\corporate;

use App\Interfaces\corporate\GeneralInterface;
use App\Models\FaqClass;

class GeneralRepository implements GeneralInterface
{


  public function faq()
  {
    $faq_classes= FaqClass::with('faq')->get();

    return view('content.corporate.pages.faq',compact('faq_classes'));
  }

}
