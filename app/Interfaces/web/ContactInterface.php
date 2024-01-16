<?php

namespace App\Interfaces\web;


interface ContactInterface
{


    public function index();

    public function request_ademo();

    public function store($request);
}
