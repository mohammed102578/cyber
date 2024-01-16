<?php

namespace App\Repository\web;

use App\Interfaces\web\PrivacyInterface;
use App\Models\admin\Privacy;

class PrivacyRepository implements PrivacyInterface
{

   public $privacy;
   public function  __construct()
   {

      return $this->privacy = Privacy::first();
   }
   public function index()
   {

      return view('content.web.pages.hackingSd.all_privacies');
   }

   public function hackingSd_privacy()
   {

      $privacy = $this->privacy->hackingSd_terms_condition;
      $title="HackingSd Term & Conditions";
      return view('content.web.pages.hackingSd.privacy', compact('privacy','title'));
   }
   public function reporter_privacy()
   {

      $privacy = $this->privacy->reporter_terms_condition;
      $title="Reporter Privacy";
      return view('content.web.pages.hackingSd.privacy', compact('privacy','title'));
   }
   public function customer_privacy()
   {

      $privacy = $this->privacy->customers_terms_condition;

      $title="Customer Privacy";
      return view('content.web.pages.hackingSd.privacy', compact('privacy','title'));
   }
   public function privacy_ploicy()
   {

      $privacy = $this->privacy->privacy_policy;

      $title="Privacy Policy";
      return view('content.web.pages.hackingSd.privacy', compact('privacy','title'));
   }
   public function code_of_conduct()
   {

      $privacy = $this->privacy->code_of_conduct;

      $title="Code Of Conduct";
      return view('content.web.pages.hackingSd.privacy', compact('privacy','title'));
   }
   public function disclosure_privacy()
   {

      $privacy = $this->privacy->disclosure_policy;

      $title="Disclosure Privacy";
      return view('content.web.pages.hackingSd.privacy', compact('privacy','title'));
   }
}
