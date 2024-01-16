<?php

namespace App\Repository\corporate;

use App\Interfaces\corporate\SearchJsonInterface;
use App\Models\Corporate\Program;

use Illuminate\Support\Facades\Auth;

class SearchJsonRepository implements SearchJsonInterface
{
   public function search_vertical()
   {
   $pages= [
    [
      "url"=> "/corporate/dashboard",
      "name"=> "Dashboard",
      "icon"=> "menu-icon tf-icons bx bx-home-circle",
      "slug"=> "dashboard-analytics"
    ],
    [
      "url"=> "/corporate/programs",
      "name"=> "Programs",
      "icon"=> "menu-icon tf-icons bx bx-layout",
      "slug"=> "layouts"

    ],
    [
      "url"=> "/corporate/leaderboard",
      "name"=> "Leaderboard",
      "icon"=> "menu-icon tf-icons bx bx-layout",
      "slug"=> "layouts"

    ],

    [
      "url"=> "/corporate/emails",
      "name"=> "E-mail",
      "icon"=> "menu-icon tf-icons bx bx-envelope",
      "slug"=> "layouts"

    ],
    [
      "url"=> "/corporate/reward",
      "name"=> "Rewards",
      "icon"=> "menu-icon tf-icons bx bx-medal",
      "slug"=> "layouts"

    ],
    [
      "url"=> "/corporate/chats",
      "name"=> "Chats",
      "icon"=> "menu-icon tf-icons bx bx-chat",
      "slug"=> "layouts"

    ],

    [
      "url"=> "/corporate/all_invoices",
      "name"=> "Invoices",
      "icon"=> "menu-icon tf-icons bx bx-credit-card",
      "slug"=> "layouts"

    ]
];



$programs=Program::where('corporate_id',Auth::guard('corporate')->user()->id)->with('corporate')->with('status')->get();



    foreach($programs as $program){
        $program->url="corporate/setting_program/".$program->id;
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



    $all_programs= $programs->makeHidden(['corporate','status','created_at','updated_at','corporate_id','reporter_quantity','management','currency','description_en','description_ar','policy_en','status_id','policy_ar','submit','program_type'])->toArray();
     return $data=['pages'=>$pages,'programs'=>$all_programs];
   }
}
