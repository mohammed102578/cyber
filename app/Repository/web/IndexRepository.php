<?php

namespace App\Repository\web;

use App\Interfaces\web\IndexInterface;
use App\Models\Admin\Team;
use App\Models\Admin\Term;
use App\Models\Blog;

class IndexRepository implements IndexInterface
{
    public function index(){
        $blogs=Blog::orderBy('id','DESC')->take(3)->get();
        return view('content.web.pages.hackingSd.index',compact('blogs'));
    }

    public function about(){


        return view('content.web.pages.hackingSd.about');
    }

    public function terms(){

        $term=Term::first();
        return view('content.web.pages.hackingSd.terms',compact('term'));
    }

    public function team(){

        $teams=Team::all();
        return view('content.web.pages.hackingSd.team',compact('teams'));
    }

    public function testimonial(){

        return view('content.web.pages.hackingSd.testimonial');
    }
}
