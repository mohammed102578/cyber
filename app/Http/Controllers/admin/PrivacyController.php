<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePrivacyRequest;
use App\Repository\admin\PrivacyRepository;

class PrivacyController extends Controller
{
   

    public  $privacy;
    public function __construct(PrivacyRepository $privacy)
    {
        return $this->privacy=$privacy;
    }
    public function index()
    {
           return  $this->privacy->index(); 
    }

 

    public function store_update(StorePrivacyRequest $request)
    {
           return  $this->privacy->store_update($request); 
    }
}
