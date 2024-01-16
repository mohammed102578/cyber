<?php

namespace App\Interfaces\corporate;


interface VerificationInterface
{

    public function index();

    public function store($request);

    public function resend($request);
}
