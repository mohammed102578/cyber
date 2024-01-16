<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Repository\admin\ContactRepository;
use Illuminate\Http\Request;

class ContactController extends Controller
{

    public $contact;
    public function __construct(ContactRepository $contact)
    {
        $this->contact = $contact;
    }
    //get contact page
    public function index()
    {
        return $this->contact->index();
    }

    //get all contact use ajax and yajar data table
    public function get_contacts(Request $request)
    {
        return $this->contact->get_contacts($request);
    }


    //stoer vulnewrabilty

    public function contacts_answer(Request $request)
    {
        return $this->contact->contacts_answer($request);
    }


    public function destroy(Request $request)
    {
        return $this->contact->destroy($request);
    }


}
