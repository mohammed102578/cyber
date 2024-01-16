<?php

namespace App\Http\Controllers\corporate;

use App\Http\Controllers\Controller;
use App\Repository\corporate\DashboardRepository;

class DashboardController extends Controller
{

    protected $dashboard;
    public function __construct(DashboardRepository $dashboard)
    {

        $this->dashboard = $dashboard;
    }
    public function dashboard()
    {
        return $this->dashboard->dashboard();
    }
}
