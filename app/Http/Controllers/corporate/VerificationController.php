<?php

namespace App\Http\Controllers\corporate;

use App\Http\Controllers\Controller;
use App\Repository\corporate\VerificationRepository;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    protected $security;
    public function __construct(VerificationRepository $security)
    {
        $this->security = $security;
    }
    public function index()
    {
        return $this->security->index();
    }




    public function store(Request $request)
    {
        return $this->security->store($request);
    }


    public function resend(Request $request)
    {
        return $this->security->resend($request);
    }
}
