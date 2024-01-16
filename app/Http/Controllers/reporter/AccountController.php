<?php

namespace App\Http\Controllers\reporter;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reporter\AccountRequest;
use App\Http\Requests\Reporter\EmailRequest;
use App\Http\Requests\Reporter\PhoneRequest;
use App\Http\Requests\Reporter\PasswordRequest;
use App\Repository\reporter\AccountRepository;

class AccountController extends Controller
{

    protected $account;
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

    //update email
    public function change_email(EmailRequest $request)
    {
        return $this->account->change_email($request);
    }

    //change phone number
    public function change_phone(PhoneRequest $request)
    {
        return $this->account->change_phone($request);
    }
    //password update  
    public function change_password(PasswordRequest $request)
    {
        return $this->account->change_password($request);
    }
}
