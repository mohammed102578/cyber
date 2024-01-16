<?php

namespace App\Repository\admin;

use App\Interfaces\admin\BlogInterface;
use App\Models\Activity;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Traits\SaveImageTrait;

class BlogRepository implements BlogInterface
{

  use SaveImageTrait;
//get blog
public function index()
{

  try{
  $blogs =DB::table('blogs')
  ->orderBy('blogs.id', 'desc')
  ->leftJoin('blog_categories', 'blog_categories.id', '=', 'blogs.blog_category_id')
  ->select('blogs.id','blogs.title','blogs.archive','blogs.created_at','blogs.body','blogs.image','blogs.author','blogs.tags','blogs.created_at',
  'blog_categories.name as blog_category')
  ->get();
  $blog_categories=BlogCategory::all();
  $tags=Tag::all();
return view('content.admin.pages.blog.blogs',compact('blog_categories','tags','blogs'));

} catch (\Exception $ex) {

return back()->with('error',  'something went wrong');

}
}
//edit blog
public function edit($id)
{

    try{

          $blog = Blog::find($id);
          if($blog){

            $blog_categories=BlogCategory::all();
            $tags=Tag::all();
          return view('content.admin.pages.blog.edit_blog',compact('blog_categories','tags','blog'));
          }else{
            return back()->with('error',  'something went wrong');

          }
    } catch (\Exception $ex) {
          return back()->with('error',  'something went wrong');
    }

}


//updated blog
public function update($request)
{

try{
    $data= $request->except('_token');
if($request->has('image')){
  $data['image']=SaveImageTrait::save_image($request->image,'blog');
  $data['tags'] = json_encode($request->tags);
 // return $data;
 $check = Blog::where('id',$request->id)->update($data);
 if($check){
  Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
  'activity'=>'Update Blog ','description_activity'=>" Update Blog  "]);
  return back()->with('success',  'Blog Updated Successfully');
  }else{
    return back()->with('error',  'something went wrong');
  }
}else{

  $data['tags'] = json_encode($request->tags);
 // return $data;
    $check = Blog::where('id',$request->id)->update($data);
    if($check){
    Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
    'activity'=>'Update Blog ','description_activity'=>" Update Blog  "]);
    return back()->with('success',  'Blog Updated Successfully');
    }else{
      return back()->with('error',  'something went wrong');
    }
}


      } catch (\Exception $ex) {
      return back()->with('error',  'something went wrong');
  }

}

//create blog
public function store($request)
{
  try{
    $data= $request->all();
    if($request->has('image')){

      $data['image']=SaveImageTrait::save_image($request->image,'blog');
      $data['tags'] = json_encode($request->tags);
    // return $data;
      $check = Blog::create($data);
      if($check){
      Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
      'activity'=>'Created Blog ','description_activity'=>" Created Blog  "]);
      return back()->with('success',  'Blog Added Successfully');
      }else{
        return back()->with('error',  'something went wrong');
      }}else{
        return redirect()->back()->withErrors(
          [
              'image' => 'this field is required.',
          ]);
      }


       } catch (\Exception $ex) {
      return back()->with('error',  'something went wrong');
  }
}

//archive
public function archive($request)
{

    try{
        $id = $request->id;
        $check = Blog::find($id);
        if($check){
        $data=$check;
        if($data->archive == 1){
        $archive=0;
        }else{
        $archive=1;
        }
        Blog::where('id',$data->id)->update(['archive' => $archive]);
        Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
        'activity'=>'Updated blog archive','description_activity'=>"updated blog archive "]);
        return response()->json(['status' => 'success'], 200);
        }else{
        return response()->json(['status' => 'failed']);
        }

    } catch (\Exception $ex) {
       return back()->with('error',  'something went wrong');
    }
}



// delete blog
public function destroy($id)
{

    $check = Blog::find($id);
    if($check){
    $data=$check;
    $data->delete();
    Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
    'activity'=>'Deleted Blog ','description_activity'=>" Deleted Blog"]);
    return back()->with('success',  'Blog Deleted Successfully');
      }else{
        return back()->with('error',  'something went wrong');
      }
      try{
} catch (\Exception $ex) {
    return back()->with('error',  'something went wrong');
}
}





















}
