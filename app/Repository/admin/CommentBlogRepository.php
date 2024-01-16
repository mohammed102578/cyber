<?php

namespace App\Repository\admin;

use App\Interfaces\admin\CommentBlogInterface;
use App\Interfaces\admin\ContactInterface;
use App\Models\Activity;
use App\Models\BlogComment;
use App\Models\Contact;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommentBlogRepository implements CommentBlogInterface
{

//get vulnerability page
public function index($id)
{
    try{
       return view('content.admin.pages.blog.comments_blog',compact('id'));
    } catch (\Exception $ex) {
       return back()->with('error',  'something went wrong');
    }
}

//get all vulnerability use ajax and yajar data table
public function get_comments($request)
{
    try{
        if (
        $request->ajax()) {

            $id=$request->id;

            $data =DB::table('blog_comments')->where('blog_id',$id)->get();


        return Datatables()->of($data)
        ->addIndexColumn()
              //created_at
          ->addColumn('created_at',function($data){

            $created_at=Carbon::parse($data->created_at)->format('d F Y');

            return $created_at;
            })
            ->rawColumns(['created_at'])


        ->addColumn('action', function($data){
        $actionBtn = '<a class=" hapus-modale_delete dropdown-item " id="'. $data->id.'" ><i class="bx bx-trash text-danger" style="margin-bottom:6px;"></i> </a>';
        return $actionBtn;
        })
        ->rawColumns(['action'])
        ->make(true);
        }
    } catch (\Exception $ex) {

       return back()->with('error', 'something went wrong');

    }
}


//delete comment
public function destroy($request)

{
    try{
        $id = $request->id;
        $check = BlogComment::find($id);
        if($check){
        $data=$check;
        BlogComment::where('id',$data->id)->delete();
        Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
        'activity'=>'deleted  Comment ','description_activity'=>"deleted Blog\'s Comment"]);
        return response()->json(['status' => 'success']);
        }else{
        return response()->json(['status' => 'failed']);
        }

    } catch (\Exception $ex) {
       return back()->with('error',  'something went wrong');
    }
}






}
