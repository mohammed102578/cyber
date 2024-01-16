<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Repository\web\DocsRepository;

class DocsController extends Controller
{


    public object $doc;
    public function __construct(DocsRepository $doc)
    {
        $this->doc = $doc;
    }
    public function docs()
    {
        return $this->doc->docs();
    }
    public function overview()
    {
        return $this->doc->overview();
    }
}
