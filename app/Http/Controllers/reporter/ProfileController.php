<?php

namespace App\Http\Controllers\reporter;

use App\Http\Controllers\Controller;
use App\Repository\reporter\ProfileRepository;

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

    public function reporter_profile($id)
    {
        return $this->profile->reporter_profile($id);
    }


    public function setting()
    {
        return $this->profile->setting();
    }


    public function account()
    {
        return $this->profile->account();
    }
}
