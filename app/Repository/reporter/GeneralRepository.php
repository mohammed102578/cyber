<?php

namespace App\Repository\reporter;

use App\Interfaces\reporter\GeneralInterface;
use App\Models\Faq;
use App\Models\FaqClass;

class GeneralRepository implements GeneralInterface
{



  public function faq()
  {
       $faq_classes= FaqClass::with('faq')->get();

    return view('content.reporter.pages.faq',compact('faq_classes'));
  }

}
