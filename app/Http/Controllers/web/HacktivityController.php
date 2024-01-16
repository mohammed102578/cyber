<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Repository\web\HacktivityRepository;
use Illuminate\Http\Request;

class HacktivityController extends Controller
{
    public $program_type;
    public $hacktivity;


    public function __construct(HacktivityRepository $hacktivity)
    {
        $this->hacktivity = $hacktivity;
    }
    public function hacktivity(Request $request)
    {
        return $this->hacktivity->hacktivity($request);
    }

    public function loadMoreData(Request  $request)
    {
        return $this->hacktivity->loadMoreData($request);
    }
}
