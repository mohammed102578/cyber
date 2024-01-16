<?php

namespace App\Http\Controllers\corporate;

use App\Http\Controllers\Controller;
use App\Http\Requests\Corporate\DeleteRequest;
use Illuminate\Http\Request;
use App\Repository\corporate\SecurityRepository;

class SecurityController extends Controller
{


    protected $security;
    public function __construct(SecurityRepository $security)
    {
        $this->security = $security;
    }
    public function security(Request $request)
    {
        return $this->security->security($request);
    }

    public function delete_account(DeleteRequest $request)
    {
        return $this->security->delete_account($request);
    }
}
