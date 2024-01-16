<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Repository\admin\CommentBlogRepository;
use Illuminate\Http\Request;

class CommentBlogController extends Controller
{

    protected $comment;
    public function __construct(CommentBlogRepository $comment){
        $comment=$this->comment=$comment;
    }
  
    public function index($id)
    {
       return $this->comment->index($id);
    }

  
    public function get_comments(Request $request)
    {
    return $this->comment->get_comments($request);
    }

   
    public function destroy(Request $request)
    {
      return $this->comment->destroy($request);
    }
}
