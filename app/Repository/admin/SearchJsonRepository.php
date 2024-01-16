<?php

namespace App\Repository\admin;

use App\Interfaces\admin\SearchJsonInterface;
use App\Models\Corporate\Corporate;
use App\Models\Corporate\Program;
use App\Models\Reporter\Report;
use App\Models\Reporter\Reporter;
 
class SearchJsonRepository implements SearchJsonInterface
{
   public function search_vertical()
   {
   $pages= [
    [
      "url"=> "admin/dashboard",
      "name"=> "Dashboard",
      "icon"=> "menu-icon tf-icons bx bx-home-circle",
      "slug"=> "dashboard-analytics"
    ],
    [
      "url"=> "admin/leaderboard",
      "name"=> "Leaderboard",
      "icon"=> "menu-icon tf-icons bx bx-layout",
      "slug"=> "layouts"
    ],
    [
      "url"=> "admin/corporates",
      "name"=> "Corporates",
      "icon"=> "menu-icon tf-icons bx bxs-buildings",
      "slug"=> "layouts-without-menu"
    ],
    [
      "url"=> "admin/trash_corporates",
      "name"=> "Corporates In Trash",
      "icon"=> "menu-icon tf-icons bx bxs-building-house",
      "slug"=> "layouts-without-navbar"
    ],
    [
      "url"=> "admin/reporters",
      "icon"=> "menu-icon tf-icons bx bx-user",
      "name"=> "Reporters",
      "slug"=> "layouts-without-menu"
    ],
    [
      "url"=> "admin/trash_reporters",
      "name"=> "Reporters In Trash",
      "icon"=> "menu-icon tf-icons bx bxs-user-badge",
      "slug"=> "layouts-without-navbar"
    ],
    [
      "url"=> "admin/programs",
      "icon"=> "menu-icon tf-icons bx bxs-component",
      "name"=> "Submit Programs",
      "slug"=> "layouts"
    ],
    [
      "url"=> "admin/unsubmit_programs",
      "name"=> "Unsubmit Programs",
      "icon"=> "menu-icon tf-icons bx bxs-confused",
      "slug"=> "layouts"
    ],
    [
      "url"=> "admin/emails",
      "name"=> "E-mail",
      "icon"=> "menu-icon tf-icons bx bx-envelope",
      "slug"=> "layouts"
    ],
    [
      "url"=> "admin/chat",
      "name"=> "Chats",
      "icon"=> "menu-icon tf-icons bx bx-chat",
      "slug"=> "layouts"
    ],
    [
      "url"=> "admin/notifications",
      "name"=> "Notifications",
      "icon"=> "menu-icon tf-icons bx bx-bell",
      "slug"=> "layouts"
    ],
    [
      "url"=> "admin/reports",
      "name"=> "Reports",
      "icon"=> "menu-icon tf-icons bx bxs-report",
      "slug"=> "layouts"
    ],
    [
      "url"=> "admin/reporter_accept_reports",
      "name"=> "Accept Reports",
      "icon"=> "menu-icon tf-icons bx bx-gift",
      "slug"=> "layouts"
    ],
    [
      "url"=> "admin/corporate_paid_invoices",
      "name"=> "Corporate Invoices",
      "icon"=> "menu-icon tf-icons bx bx-credit-card",
      "slug"=> "layouts"
    ],
    [
      "url"=> "admin/paid_invoices",
      "name"=> "Reporter Invoices",
      "icon"=> "menu-icon tf-icons bx bx-credit-card",
      "slug"=> "layouts"
    ],
    [
      "url"=> "admin/reporter_reward",
      "name"=> "Reporter Rewards",
      "icon"=> "menu-icon tf-icons bx bx-medal",
      "slug"=> "layouts"
    ],
    [
      "url"=> "admin/corporate_reward",
      "name"=> "HackingSd Rewards",
      "icon"=> "menu-icon tf-icons bx bx-medal",
      "slug"=> "layouts"
    ],
    [
      "url"=> "admin/type_target",
      "name"=> "Targets",
      "icon"=> "menu-icon tf-icons bx bx-gift",
      "slug"=> "layouts"
    ],
    [
      "url"=> "admin/hobbies",
      "name"=> "Hobbies",
      "icon"=> "menu-icon tf-icons bx bxs-certification",
      "slug"=> "layouts"
    ],
    [
      "url"=> "admin/recent_device",
      "name"=> "Recent Device",
      "icon"=> "menu-icon tf-icons bx bxs-devices",
      "slug"=> "layouts"
    ],
    [
      "url"=> "admin/activities",
      "name"=> "Activities",
      "icon"=> "menu-icon tf-icons bx bxs-notepad",
      "slug"=> "layouts"
    ],
    [
      "url"=> "admin/vulnerabilities",
      "name"=> "Vulnerabilities",
      "icon"=> "menu-icon tf-icons bx bxs-bug-alt",
      "slug"=> "layouts"
    ],
   
   
    [
      "url"=> "admin/setting",
      "name"=> "Settings",
      "icon"=> "menu-icon tf-icons bx bxs-cog",
      "slug"=> "layouts"
    ]
];




$reporters=Reporter::where('id','>',0)->select('image as src','id','first_name','last_name')->get();
$corporates=Corporate::where('id','>',0)->select('image as src','id','company_name as name')->get();
$reports=Report::where('id','>',0)->with('reporter')->with('status')->get();
$programs=Program::where('id','>',0)->with('corporate')->with('status')->get();


foreach($reporters as $reporter){
$reporter->url="admin/reporter_profile/".$reporter->id;
$reporter->subtitle="Reporter";
$reporter->name=$reporter->first_name." ".$reporter->last_name;

}




foreach($corporates as $corporate){
    $corporate->url="admin/corporate_profile/".$corporate->id;
    $corporate->subtitle="Corporate";
    
    }
    



    foreach($reports as $report){
        $report->url="admin/show_report/".$report->id;
        $report->src= $report->reporter->image;
        $report->reporter_name= $report->reporter->first_name." ".$report->reporter->last_name;
        $report->name= $report->vulnerability;
        $report->report_status= $report->status->status;
        
    }




    
    foreach($programs as $program){
        $program->url="admin/show_program/".$program->id;
        $program->src= $program->corporate->image;
        $program->corporate_name= $program->corporate->company_name;
        $program->name= substr($program->description_en,0,200) ;
        $program->program_status= $program->status->status;
        if($program->program_type==1){
            $program->type= "public";

        }elseif($program->program_type==2){
            $program->type= "Semi_Private";

        }else{
            $program->type= "Private";

        }
    }


    $all_reports= $reports->makeHidden(['reporter','status','created_at','updated_at','recommendation','impact','reproduce','program_id','reporter_id','summarize','description','status_id','hacktivity','paid','vulnerability'])->toArray();
   
    $all_programs= $programs->makeHidden(['corporate','status','created_at','updated_at','corporate_id','reporter_quantity','management','currency','description_en','description_ar','policy_en','status_id','policy_ar','submit','program_type'])->toArray();
     return $data=['pages'=>$pages,'corporates'=>$corporates,'reporters'=>$reporters->makeHidden(['first_name','last_name'])->toArray(),'reports'=>$all_reports,'programs'=>$all_programs];
    //return json_encode($reporters);
   }
}
