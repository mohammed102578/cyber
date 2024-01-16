<?php

namespace App\Interfaces\reporter;

interface VerificationInterface
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index();

    public function store($request);

    public function resend($request);
}
