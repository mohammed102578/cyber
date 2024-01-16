<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Repository\admin\DashboardRepository;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{


  public $dashboard;
  public function __construct(DashboardRepository $dashboard)
  {
      $this->dashboard = $dashboard;
  }

  public function dashboard()
  {
     return $this->dashboard->dashboard();
  }
}
