<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CommentRequest;
use App\Repository\web\BlogRepository;

class BlogController extends Controller
{


    public object $blog;

public function __construct(BlogRepository $blog){
     $this->blog=$blog;
}

    public function index(){
      return $this->blog->index();
    }

    public function details($id){

        return $this->blog->details($id);

    }
    public function store_comment(CommentRequest $request){
        return $this->blog->store_comment($request);

    }

    public function category($id){
        return $this->blog->category($id);

    }

    public function tag($id){
        return $this->blog->tag($id);

    }
}
