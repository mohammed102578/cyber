<?php

namespace App\Repository\web;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\Tag;
use App\Interfaces\web\BlogInterface;
class BlogRepository implements BlogInterface
{
    public function index(){

        $blogs=Blog::with('category')->where('archive',0)->orderBy('id','DESC')->get();
        $category='all';
        return view('content.web.pages.blog',compact('blogs','category'));
    }



    public function details($id){

        $blog=Blog::with('category')->with('comments')->withCount('comments')->where('id',$id)->first();
        $blogs=Blog::orderBy('id','DESC')->take(5)->get();
        $categories=BlogCategory::all();
        $tags=Tag::all();
        $archives=Blog::where('archive',1)->orderBy('updated_at','DESC')->take(5)->get();
        return view('content.web.pages.blog_details',compact('blog','blogs','categories','archives','tags'));
    }


    public function store_comment($request){

      $comment=BlogComment::create($request->all());
      $comment->date=$comment->created_at->format('F d, Y');
      $comment->image=strtoupper(substr($comment->name,0,2));
      return response()->json($comment);
    }

    public function category($id){
      $blogs=Blog::with('category')->where('archive',0)->where('blog_category_id',$id)->orderBy('id','DESC')->get();
      $category=BlogCategory::find($id)->name;
      return view('content.web.pages.blog',compact('blogs','category'));
    }

    public function tag($id){

        $tag=Tag::where('id',$id)->first()->name;

        $blogs=Blog::with('category')->where('archive',0)->whereJsonContains('tags',$tag)->orderBy('id','DESC')->get();
        $category=$tag;
      return view('content.web.pages.blog',compact('blogs','category'));
    }
}
