<?php

namespace App\Interfaces\admin;


interface  TeamInterface
{
public function index();
public function get_teams($request);
public function edit($id);
public function store($request);
public function update($request);
public function destroy($id);



}


























?>
