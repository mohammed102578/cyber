<?php

namespace App\Repository\web;


use App\Http\Services\Notification;
use App\Interfaces\web\ContactInterface;
use App\Models\Contact;

class ContactRepository implements ContactInterface
{

    public object $admin_notification;
    public function __construct()
    {
        $this->admin_notification = new Notification;

    }

    public function index(){
        return view('content.web.pages.request_to_hackingSd.contact');
    }


    public function request_ademo(){
        return view('content.web.pages.request_to_hackingSd.request_demo');
    }


    public function store($request){
    try{
       if($request->has('productInterest')){
        $data=$request->except('_token');
       $productInterest= json_encode($request->productInterest);
       $data['productInterest']=$productInterest;
       Contact::create($data);
        //send notification
        $this->admin_notification->
        sendAdminNotification('Contact with us',$request->first_name.' wants to communicate with you',
        'admin_contact',null,null);

       toastr()->success('Data has been saved successfully!');
       return redirect()->back();


       }else{
       $data=$request->except('_token');
       $productInterest= json_encode($request->productInterest);
       Contact::create($data);
        //send notification
        $this->admin_notification->
        sendAdminNotification('Contact with us',$request->first_name.' wants to communicate with you',
        'admin_contact',null,null);

       toastr()->success('Data has been saved successfully!');
       return redirect()->back();
       }

    }catch(\Exception $ex){
        toastr()->error('Some thing went wrong!');
        return redirect()->back();
     }

    }
}
