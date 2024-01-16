<?php

namespace App\Repository\reporter;

use App\Interfaces\reporter\SearchJsonInterface;
use App\Models\Reporter\Report;
use Illuminate\Support\Facades\Auth;

class SearchJsonRepository implements SearchJsonInterface
{
   public function search_vertical(){
   $pages= [
    [
      "url"=> "/reporter/dashboard",
      "name"=> "Dashboard",
      "icon"=> "menu-icon tf-icons bx bx-home-circle",
      "slug"=> "dashboard-analytics"
    ],
    [
      "url"=> "/reporter/leaderboard",
      "name"=> "Leaderboard",
      "icon"=> "menu-icon tf-icons bx bx-layout",
      "slug"=> "layouts"

    ],

    [
      "url"=> "/reporter/programs",
      "name"=> "Programs",
      "icon"=> "menu-icon tf-icons bx bx-box",
      "slug"=> "layouts"

    ],
    [
      "url"=> "/reporter/reports",
      "name"=> "Reports",
      "icon"=> "menu-icon tf-icons bx bx-file",
      "slug"=> "layouts"

    ],
    [
      "url"=> "/reporter/reward",
      "name"=> "Rewards",
      "icon"=> "menu-icon tf-icons bx bx-medal",
      "slug"=> "layouts"

    ],
    [
      "url"=> "/reporter/hacktivity",
      "name"=> "Hacktivity",
      "icon"=> "menu-icon tf-icons bx bxs-network-chart",
      "slug"=> "layouts"

    ],
    [
      "url"=> "/reporter/chats",
      "name"=> "Chats",
      "icon"=> "menu-icon tf-icons bx bx-chat",
      "slug"=> "layouts"

    ],

    [
      "url"=> "/reporter/all_invoices",
      "name"=> "Invoices",
      "icon"=> "menu-icon tf-icons bx bx-credit-card",
      "slug"=> "layouts"

    ]
];




$reports=Report::where('reporter_id',Auth::guard('reporter')->user()->id)->with('reporter')->with('status')->get();






    foreach($reports as $report){
        $report->url="reporter/show_report/".$report->id;
        $report->src= $report->reporter->image;
        $report->reporter_name= $report->reporter->first_name." ".$report->reporter->last_name;
        $report->name= $report->vulnerability;
        $report->report_status= $report->status->status;

    }





    $all_reports= $reports->makeHidden(['reporter','status','created_at','updated_at','recommendation','impact','reproduce','program_id','reporter_id','summarize','description','status_id','hacktivity','paid','vulnerability'])->toArray();

     return $data=['pages'=>$pages,'reports'=>$all_reports];
   }
}
