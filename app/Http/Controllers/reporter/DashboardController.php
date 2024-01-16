<?php

namespace App\Http\Controllers\reporter;

use App\Http\Controllers\Controller;
use App\Repository\reporter\DashboardRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    protected $dashboard;
    protected $conversations;
    public function __construct(DashboardRepository $dashboard)
  {

      $this->dashboard = $dashboard;
  }
  public function dashboard()
{
  return $this->dashboard->dashboard();
}
}
