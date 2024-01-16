<?php

namespace App\Http\Controllers\corporate;

use App\Http\Controllers\Controller;
use App\Http\Requests\Corporate\AccountRequest;
use App\Http\Requests\Corporate\EmailRequest;
use App\Http\Requests\Corporate\UsernameRequest;
use App\Http\Requests\Corporate\PasswordRequest;
use App\Repository\corporate\AccountRepository;

class AccountController extends Controller
{

    public $account;
    public function __construct(AccountRepository $account)
    {
        $this->account = $account;
    }

    public function index()
    {
        return $this->account->index();
    }


    //update account

    public function update(AccountRequest $request)
    {
        return $this->account->update($request);
    }



    //change username number


    public function change_username(UsernameRequest $request)
    {
        return $this->account->change_username($request);
    }




    //update email
    public function change_email(EmailRequest $request)
    {
        return $this->account->change_email($request);
    }



    //password update  
    public function change_password(PasswordRequest $request)
    {
        return $this->account->change_password($request);
    }
}
