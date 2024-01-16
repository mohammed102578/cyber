<?php

namespace App\Interfaces\admin;


interface  HobbyInterface
{
public function index();
public function get_hobbies($request);

public function store($request);
public function edit($request);
public function update($request);
public function destroy($request);



}


























?>