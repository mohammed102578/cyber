<?php

namespace App\Interfaces\web;

interface ProgramInterface
{

public function program();

public function loadMoreData($request);

public function show($request);


}
