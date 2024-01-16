<?php

namespace App\Http\Controllers\corporate;

use App\Http\Controllers\Controller;
use App\Repository\corporate\ProfileRepository;

class ProfileController extends Controller
{

    protected $profile;
    public function __construct(ProfileRepository $profile)
    {

        $this->profile = $profile;
    }

    public function profile()
    {
        return $this->profile->profile();
    }

    public function setting()
    {
        return $this->profile->setting();
    }


    public function account()
    {
    }


    public function security()
    {
    }
}
