<?php

namespace App\Repository\reporter;

use App\Interfaces\reporter\ProfileInterface;
use App\Models\Nationality;
use App\Models\Hobby;
use App\Models\Reporter\Reporter;
use App\Models\Corporate\SemiPrivateProgram;
use App\Models\Reporter\Report;
use App\Models\Reporter\ReportStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileRepository implements ProfileInterface
{
    public function profile()
    {
        try {
            $reporter = Reporter::find(Auth::guard('reporter')->user()->id);
            $activities = $reporter->activeable()->take(5)->orderBy('id', 'desc')->get();
            $connections = $reporter->connectionable()->get();

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


            return view('content.reporter.pages.profile', compact(['activities', 'linkedin', 'github', 'twitter', 'facebook']));
        } catch (\Exception $ex) {

            return back()->with('error', 'something went wrong');
        }
    }

    public function reporter_profile($id)
    {
        try {
            $check = Reporter::find($id);
            if ($check) {
                $reporter = $check;
                $hacktivities = Report::with('program.corporate')->with('reporter')->where('hacktivity', 1)->where('reporter_id', $id)->orderBy('updated_at', 'DESC')->get();
                $rank = [];
                $top_reporters = Report::with('reporter')
                    ->where('status_id', 3)
                    ->select('reporter_id', DB::raw("count(status_id) as count"))
                    ->orderBy('count', 'desc')
                    ->groupBy('reporter_id')->get();

                $number = 0;
                for ($i = 0; $i < count($top_reporters); $i++) {
                    $number++;
                    if ($top_reporters[$i]->reporter_id == $id) {
                        $rank['rank'] = $number;
                        break;
                    }
                }

                //averge imapct
                $report = Report::where('reporter_id', $id)->avg('status_id');

                $average = round($report);

                $average_report_status = ReportStatus::find($average)->status;


                //report accepted
                $all_report = Report::where('reporter_id', $id)->count();
                $accepted_report = Report::where('reporter_id', $id)->where('status_id', 3)->count();
                $accepted_report_precent = ($accepted_report * 100) / $all_report;
                $accepted_report_precent = number_format((float)$accepted_report_precent, 2, '.', '');

                //report rewarded
                $report_rewarded = Report::where('reporter_id', $id)->where('paid', 1)->count();


                //program joined

                $program_joined = SemiPrivateProgram::where('reporter_id', $id)->where('status', 1)->count();
                return view('content.reporter.pages.reporter_profile', compact(['program_joined', 'report_rewarded', 'accepted_report_precent', 'reporter', 'hacktivities', 'rank', 'average_report_status']));
            } else {
                return back()->with('error', 'something went wrong');
            }
        } catch (\Exception $ex) {

            return back()->with('error', 'something went wrong');
        }
    }


    public function setting()
    {
        try {
            return view('content.reporter.pages.setting');
        } catch (\Exception $ex) {

            return back()->with('error', 'something went wrong');
        }
    }


    public function account()
    {

        try {

            $nationalities = Nationality::get();
            $hobby = Hobby::get();

            return view('content.reporter.pages.account', compact(['nationalities', 'hobby']));
        } catch (\Exception $ex) {

            return back()->with('error', 'something went wrong');
        }
    }
}
