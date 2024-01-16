<?php

namespace App\Http\Controllers\reporter;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reporter\DeleteRequest;
use App\Repository\reporter\SecurityRepository;
use Illuminate\Http\Request;


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
