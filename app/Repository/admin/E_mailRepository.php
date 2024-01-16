<?php

namespace App\Repository\admin;
use App\Interfaces\admin\E_mailInterface;
use App\Jobs\SendEmail;
use App\Jobs\SendEmailGroups;
use App\Models\Activity;
use App\Models\Admin\Email;
use App\Models\Corporate\Corporate;
use App\Models\Reporter\Reporter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class  E_mailRepository implements E_mailInterface
{

    public $title;
    public $body;
    public function email()

    {
        try{



            $reporters=Reporter::all();
            $corporates=Corporate::all();
            return view('content.admin.pages.general.email',compact(['reporters','corporates']));
           }catch(\Exception $ex){
        return redirect()->back()->with('error','something went wrong');
        }
    }




    public function get_corporate_emails($request)
    {
        try{
            if (
            $request->ajax()) {

                $data =DB::table('emails')->where('receiver_type','Corporate')->orderBy('id','DESC')->get();

            return Datatables()->of($data)
            ->addIndexColumn()
            //email_created
            ->addColumn('created', function($data){

                $date = strtotime ( $data->created_at );

                return $created_at=date ( 'd M Y h:i' , $date );               })

            ->rawColumns(['created'])


            ->addColumn('action', function($data){

                    $actionBtn = '
                    <a class="email_delete_corporate" id="'. $data->id.'"><i class="bx bx-trash text-danger"></i></a>
                    <a data-bs-toggle="modal" data-bs-target="#show_email" class="show_email" id="'. $data->id.'"><i class="bx bx-show text-info"></i></a>
                    ';


                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
            }

        } catch (\Exception $ex) {

           return back()->with('error', 'something went wrong');

        }
    }




    public function get_reporter_emails($request)
    {
        try{
            if (
            $request->ajax()) {

                $data =DB::table('emails')->where('receiver_type','Reporter')->orderBy('id','DESC')->get();

            return Datatables()->of($data)
            ->addIndexColumn()
            //email_created
            ->addColumn('created', function($data){

                $date = strtotime ( $data->created_at );

                return $created_at=date ( 'd M Y h:i' , $date );               })

            ->rawColumns(['created'])

            ->addColumn('action', function($data){

                    $actionBtn = '
                    <a class="email_delete_reporter" id="'. $data->id.'"><i class="bx bx-trash text-danger"></i></a>
                    <a data-bs-toggle="modal" data-bs-target="#show_email" class="show_email" id="'. $data->id.'"><i class="bx bx-show text-info"></i></a>

                    ';


                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
            }

        } catch (\Exception $ex) {

           return back()->with('error', 'something went wrong');

        }
    }




    public function store($request)
    {

        try{
            $this->title=$request->title;
            $this->body=$request->body;
        if($request->reporter=='all'){


                 DB::table('reporters')->orderBy('id')->chunk(50, function ($data) {
                    dispatch(new SendEmailGroups($data,$this->title,$this->body));

                });
        }elseif($request->reporter !='No' && $request->reporter !='all'){
       $reporter_id=$request->reporter;
       $reporter=Reporter::find($reporter_id);
       $email=['title'=>$request->title,'body'=>$request->body,'email'=>
       $reporter->email,'name'=>
       $reporter->first_name." ".$reporter->last_name,'receiver_type'=>'Reporter'];
       $create=Email::create($email);
       $email['all']='no';
       $reporter_email=(new SendEmail($email));
       dispatch($reporter_email);
        }




        if($request->corporate=='all'){

        DB::table('corporates')->orderBy('id')->chunk(50, function ($data) {
            dispatch(new SendEmailGroups($data,$this->title,$this->body));
        });

        }elseif($request->corporate !='No' && $request->corporate !='all'){
       $corporate_id=$request->corporate;

       $corporate=Corporate::find($corporate_id);
       $email=['title'=>$request->title,'body'=>$request->body,'email'=>
       $corporate->email,'name'=>$corporate->company_name,'receiver_type'=>'Corporate'];
       $create=Email::create($email);
       $corporate_email=(new SendEmail($email));
       dispatch($corporate_email);
        }

        if($request->corporate=='No' && $request->reporter=='No'){
            return response()->json(['status' => 'failed']);

        }

        Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Corporate\Corporate',
        'activity'=>'Add Notification ','description_activity'=>" Notification Added"]);



    if(isset($create)){
        return response()->json(['status' => 'success'], 200);


    }else{
      return response()->json(['status' => 'failed']);

    }




    } catch (\Exception $ex) {

    return back()->with('error',  'something went wrong');

    }



    }

    public function show_email($request)
    {

        try{
            $id = $request->id;
            $check = Email::find($id);
            if($check){
            $data=$check;
            return response()->json($data);
            }
        } catch (\Exception $ex) {
            return back()->with('error',  'something went wrong');
        }
    }


    public function destroy($request)

    {
        try{
            $id = $request->id;
            $check = Email::find($id);
            if($check){
            $data=$check;

            Email::where('id',$data->id)->delete();
            Activity::create(['activeable_id'=>Auth::guard('admin')->user()->id,'activeable_type'=>'App\Models\Admin\Admin',
            'activity'=>'deleted Email ','description_activity'=>"deleted Email"]);

            return response()->json(['status' => 'success'], 200);

            }else{
            return response()->json(['status' => 'failed']);
            }

        } catch (\Exception $ex) {
           return back()->with('error',  'something went wrong');
        }
    }



}
