<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use App\Models\Corporate\Corporate;
use App\Models\Corporate\Program;
use App\Models\Corporate\SemiPrivateProgram;
use App\Models\Nationality;
use App\Models\Hobby;
use App\Models\Reporter\Report;
use App\Models\Reporter\Reporter;
use App\Models\Reporter\ReportStatus;
use App\Repository\admin\ProfileRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{

    public $profile;
    public function __construct(ProfileRepository $profile)
    {
        $this->profile = $profile;
    }
    //display profile page
    public function profile()
    {
        return $this->profile->profile();
    }

    //display setting page
    public function setting()
    {
        return $this->profile->setting();
    }


    public function reporter_profile($id)
    {
        return $this->profile->reporter_profile($id);
    }


    public function corporate_profile($id)
    {

        return $this->profile->corporate_profile($id);
    }
}
