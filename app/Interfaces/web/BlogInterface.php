<?php

namespace App\Interfaces\web;


interface BlogInterface
{
    public function index();

    public function details($id);

    public function store_comment($request);

    public function category($id);

    public function tag($id);
}
