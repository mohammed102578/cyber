<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EmailSendRequest;
use App\Repository\admin\E_mailRepository;
use Illuminate\Http\Request;



class  E_mailController extends Controller
{

    public $email;
    public function __construct(E_mailRepository $email)
    {
        $this->email = $email;
    }

    public function email()

    {
        return $this->email->email();
    }

    public function get_corporate_emails(Request $request)
    {
        return $this->email->get_corporate_emails($request);
    }

    public function get_reporter_emails(Request $request)
    {
        return $this->email->get_reporter_emails($request);
    }

    public function store(EmailSendRequest $request)
    {
        return $this->email->store($request);
    }


    public function show_email(Request $request)
    {
        return $this->email->show_email($request);
    }


    public function destroy(Request $request)

    {
        return $this->email->destroy($request);
    }
}
