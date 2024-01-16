<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Repository\web\ServiceRepository;

class ServiceController extends Controller
{
    public object $service;

    public function __construct(ServiceRepository $service)
    {
        $this->service = $service;
    }
    public function index()
    {
        return $this->service->index();
    }
    public function bug_bounty()
    {
        return $this->service->bug_bounty();
    }

    public function vulenrability_disclousre_policy()
    {
        return $this->service->vulenrability_disclousre_policy();
    }
    public function penetest_as_a_service()
    {
        return $this->service->penetest_as_a_service();
    }
}
