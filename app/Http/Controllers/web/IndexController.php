<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Repository\web\IndexRepository;

class IndexController extends Controller
{

    public object $index;

    public function __construct(IndexRepository $index){
        $this->index=$index;
    }
    public function index(){
       return $this->index->index();
    }

    public function about(){

        return $this->index->about();
    }

    public function terms(){
        return $this->index->terms();

    }

    public function team(){
        return $this->index->team();
    }

    public function testimonial(){
        return $this->index->testimonial();
    }
}
