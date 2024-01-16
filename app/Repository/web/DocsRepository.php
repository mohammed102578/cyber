<?php

namespace App\Repository\web;

use App\Interfaces\web\DocsInterface;

class DocsRepository implements DocsInterface
{
    public function docs(){
        return view('content.web.pages.reporter.docs');

       }
       public function overview(){
        return view('content.web.pages.reporter.overview');

       }
}
