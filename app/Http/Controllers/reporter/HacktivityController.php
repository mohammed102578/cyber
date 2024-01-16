<?php

namespace App\Http\Controllers\reporter;

use App\Http\Controllers\Controller;
use App\Repository\reporter\HacktivityRepository;
use Illuminate\Http\Request;

class HacktivityController extends Controller
{
    protected $hacktivity;
    public function __construct(HacktivityRepository $hacktivity)
    {

        $this->hacktivity = $hacktivity;
    }

    public function hacktivity(Request $request)
   {
    return $this->hacktivity->hacktivity($request);

   }

   public function loadMoreData(Request $request)
   {
    return $this->hacktivity->loadMoreData($request);
   }

}
