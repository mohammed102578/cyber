<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AccountRequest;
use App\Http\Requests\Admin\EmailRequest;
use App\Http\Requests\Admin\PhoneRequest;
use App\Http\Requests\Admin\PasswordRequest;
use App\Repository\admin\AccountRepository;

class AccountController extends Controller
{

    protected $account;
    public function __construct(AccountRepository $account)
    {

        $this->account = $account;
    }


    public function account()
    {
        return $this->account->account();
    }



    public function security()
    {
        return $this->account->security();
    }

    public function update(AccountRequest $request)
    {
        return $this->account->update($request);
    }

    public function change_email(EmailRequest $request)
    {
        return $this->account->change_email($request);
    }


    public function change_phone(PhoneRequest $request)
    {
        return $this->account->change_phone($request);
    }

    public function change_password(PasswordRequest $request)
    {
        return $this->account->change_password($request);
    }
}
