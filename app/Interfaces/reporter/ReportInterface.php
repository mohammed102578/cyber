<?php

namespace App\Interfaces\reporter;

interface ReportInterface
{



public function index();


public function get_reports($request);


public function create($id);


public function store($request);

public function edit($id);


public function update($request);


public function belong_vulnerability($request);

public function belong_belong_vulnerability($request);

public function show($id);


public function destroy($request);




}
