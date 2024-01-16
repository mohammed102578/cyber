<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;


class TestController extends Controller
{
public function test(){

    return view('content.admin.pages.test');
}
}
