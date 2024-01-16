<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Repository\web\PrivacyRepository;

class PrivacyController extends Controller
{

    public $privacy;
    public function  __construct(PrivacyRepository $privacy)
    {

        $this->privacy = $privacy;
    }
    public function index()
    {
        return $this->privacy->index();
    }

    public function hackingSd_privacy()
    {
        return $this->privacy->hackingSd_privacy();
    }
    public function reporter_privacy()
    {
        return $this->privacy->reporter_privacy();
    }
    public function customer_privacy()
    {
        return $this->privacy->customer_privacy();
    }
    public function privacy_ploicy()
    {

        return  $this->privacy->privacy_ploicy();
    }
    public function code_of_conduct()
    {
        return $this->privacy->code_of_conduct();
    }
    public function disclosure_privacy()
    {
        return $this->privacy->disclosure_privacy();
    }
}
