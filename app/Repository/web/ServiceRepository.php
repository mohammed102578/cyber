<?php

namespace App\Repository\web;

use App\Interfaces\web\ServiceInterface;
use App\Models\Blog;

class ServiceRepository implements ServiceInterface
{
    public function index(){

        return view('content.web.pages.services.services');
    }



    public function bug_bounty(){
        return view('content.web.pages.services.bug_bounty');
    }

    public function vulenrability_disclousre_policy(){
        return view('content.web.pages.services.vulenrability_disclousre_policy');
    }

    public function penetest_as_a_service(){
        return view('content.web.pages.services.penetest_as_a_service');
    }


}
