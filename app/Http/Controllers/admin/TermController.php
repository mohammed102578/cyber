<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTermRequest;
use App\Repository\admin\TermRepository;

class TermController extends Controller
{


    public  $term;
    public function __construct(TermRepository $term)
    {
        return $this->term=$term;
    }
    public function index()
    {
           return  $this->term->index();
    }



    public function store_update(StoreTermRequest $request)
    {
           return  $this->term->store_update($request);
    }
}
