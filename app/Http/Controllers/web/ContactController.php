<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\ContactRequest;
use App\Http\Services\Notification;
use App\Repository\web\ContactRepository;

class ContactController extends Controller
{

    public object $contact;
    public object $admin_notification;
    public function __construct(ContactRepository $contact)
    {
        $this->contact =$contact;
        $this->admin_notification = new Notification;

    }

    public function index(){
        return $this->contact->index();
    }


    public function request_ademo(){
        return $this->contact->request_ademo();
    }


    public function store(ContactRequest $request)
    {
        return $this->contact->store($request);

    }
}
