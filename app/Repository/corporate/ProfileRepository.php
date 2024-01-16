<?php

namespace App\Repository\corporate;

use App\Interfaces\corporate\ProfileInterface;
use App\Models\Corporate\Corporate;
use App\Models\Nationality;
use App\Models\Hobby;
use Illuminate\Support\Facades\Auth;

class ProfileRepository implements ProfileInterface
{
    public function profile()
    {
        try {
            $corporate = Corporate::find(Auth::guard('corporate')->user()->id);
            $activities = $corporate->activeable()->take(5)->orderBy('id', 'desc')->get();
            $connections = $corporate->connectionable()->get();

            $facebook = [];
            $twitter = [];
            $linkedin = [];
            $github = [];

            foreach ($connections as $connection) {
                if ($connection->app_name == 'facebook') {
                    $facebook['link'] = $connection->link;
                    $facebook['app_name'] = $connection->app_name;
                    $facebook['id'] = $connection->id;
                } elseif ($connection->app_name == 'twitter') {
                    $twitter['link'] = $connection->link;
                    $twitter['app_name'] = $connection->app_name;
                    $twitter['id'] = $connection->id;
                } elseif ($connection->app_name == 'github') {
                    $github['link'] = $connection->link;
                    $github['app_name'] = $connection->app_name;
                    $github['id'] = $connection->id;
                } else {
                    $linkedin['link'] = $connection->link;
                    $linkedin['app_name'] = $connection->app_name;
                    $linkedin['id'] = $connection->id;
                }
            }


            return view('content.corporate.pages.profile', compact(['activities', 'linkedin', 'github', 'twitter', 'facebook']));
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'something went wrong');
        }
    }

    public function setting()
    {
        try {
            return view('content.corporate.pages.setting');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'something went wrong');
        }
    }




    public function security()
    {
        try {
            return view('content.corporate.pages.security');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'something went wrong');
        }
    }
}
