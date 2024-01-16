<?php

namespace App\Http\Controllers\reporter;

use App\Http\Controllers\Controller;
use App\Repository\reporter\VerificationRepository;
use Illuminate\Http\Request;

class VerificationController extends Controller
{

    protected $verification;
    public function __construct(VerificationRepository $verification)
    {

        $this->verification = $verification;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->verification->index();
    }


    public function store(Request $request)
    {
        return $this->verification->store($request);
    }


    public function resend(Request $request)
    {
        return $this->verification->resend($request);
    }
}
