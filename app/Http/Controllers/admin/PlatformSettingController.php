<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePlatformSettingRequest;
use App\Repository\admin\PlatformRepository;
use Illuminate\Http\Request;

class PlatformSettingController extends Controller
{
   

    public  $platform;
    public function __construct(PlatformRepository $platform)
    {
        return $this->platform=$platform;
    }
    public function index()
    {
           return  $this->platform->index(); 
    }

 

    public function store_update(StorePlatformSettingRequest $request)
    {
           return  $this->platform->store_update($request); 
    }
}
