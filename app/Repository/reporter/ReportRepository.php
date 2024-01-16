<?php

namespace App\Repository\reporter;


use App\Http\Services\Notification;
use App\Interfaces\reporter\ReportInterface;
use App\Models\BelongBelongVulnerability;
use App\Models\BelongVulnerability;
use App\Models\ChatReport;
use App\Models\Corporate\BlockingProgram;
use App\Models\Corporate\Program;
use App\Models\Corporate\ProgramRequirement;
use App\Models\Corporate\Target;
use App\Models\Reporter\Report;
use App\Models\Vulnerability;
use App\Models\Reporter\ReportImage;
use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportRepository implements ReportInterface
{


    public object $admin_notification;
    public function __construct()
    {
        $this->admin_notification = new Notification;
    }


    //==========get all reporter report's
    public function index()
    {

        try {

            return view('content.reporter.pages.reports');
        } catch (\Exception $ex) {

            return back()->with('error',  'something went wrong');
        }
    }


    //==========get all reporter report's using ajax

    public function get_reports($request)
    {
        try {

            if (
                $request->ajax()
            ) {


                $data = DB::table('reports')
                    ->where('reports.reporter_id', Auth::guard('reporter')->user()->id)
                    ->orderBy('reports.id', 'desc')
                    ->leftJoin('programs', 'programs.id', '=', 'reports.program_id')
                    ->leftJoin('corporates','corporates.id','=','programs.corporate_id')
                    ->leftJoin('reporters', 'reporters.id', '=', 'reports.reporter_id')
                    ->leftJoin('report_statuses', 'report_statuses.id', '=', 'reports.status_id')

                    ->select(
                        'reports.id',
                        'reports.target',
                        'reports.created_at',
                        'reports.vulnerability',
                        'reports.status_id',
                        'reports.url_vulnerability',
                        'programs.corporate_id',
                        'programs.image',
                        'reporters.email',
                        'reporters.first_name',
                        'reporters.last_name',
                        'reporters.phone',
                        'corporates.company_name',
                        'corporates.section',
                        'corporates.website',
                        'corporates.image as company_image',
                        'report_statuses.status'
                    )
                    ->get();



                return Datatables()->of($data)
                    ->addIndexColumn()

                    //image
                    ->addColumn('image', function ($data) {

                        if ($data->image != null) {
                            $image = $data->image;
                        } else {
                            $image = $data->company_image;
                        }

                        return $image;
                    })
                    ->rawColumns(['image'])

                    //created_at
                    ->addColumn('created_at', function ($data) {

                        $created_at = Carbon::parse($data->created_at)->format('d M Y');

                        return $created_at;
                    })
                    ->rawColumns(['created_at'])

                    //company_name

                    ->addColumn('company_name', function ($data) {


                        $company_name = $data->company_name;
                        return $company_name;
                    })
                    ->rawColumns(['company_name'])

                    //report status


                    ->addColumn('status', function ($data) {


                        $status = $data->status;
                        return $status;
                    })
                    ->rawColumns(['status'])


                    //company_section


                    ->addColumn('section', function ($data) {


                        $section = $data->section;
                        return $section;
                    })
                    ->rawColumns(['section'])

                    //company_website


                    ->addColumn('website', function ($data) {


                        $website = $data->website;
                        return $website;
                    })
                    ->rawColumns(['website'])

                    //name
                    ->addColumn('name', function ($data) {

                        $name = $data->first_name . " " . $data->last_name;

                        return $name;
                    })
                    ->rawColumns(['name'])





                    //action

                    ->addColumn('action', function ($data) {
                        $actionBtn = ' <div class="d-inline-block">
        <a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="bx bx-dots-vertical-rounded">

        </i></a>
        <div class="dropdown-menu dropdown-menu-end m-0">
        <a  style="color: #5b636c;margin-left: 18px;text-decoration: none;" id="' . $data->id . '"href="show_report/' . $data->id . '"><i class="bx bx-show text-success"></i> Show Report</a> <br>  <br>
        <a  style="color: #5b636c;margin-left: 18px;text-decoration: none;" id="' . $data->id . '"href="edit_report/' . $data->id . '"><i class="bx bx-edit text-info"></i> Edit Report</a>  <br>  <br>
        <a  style="color: #5b636c;margin-left: 18px;text-decoration: none;" id="' . $data->id . '"href="report_images/' . $data->id . '"><i class="bx bx-images text-warning"></i> Report Image\'s</a>
        <div class="dropdown-divider">

        </div>
        <a class=" hapus-modale dropdown-item " id="' . $data->id . '" ><i class="bx bx-trash text-danger" style="margin-bottom:6px;"></i> Delete </a>
        </div>
        </div>
        ';
                        return $actionBtn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
        } catch (\Exception $ex) {

            return back()->with('error', 'something went wrong');
        }
    }


    //==================create report
    public function create($id)
    {

        try {
            // start check if reporter have permission to send reports
            $program_require = ProgramRequirement::where('program_id', $id)->first();
            $reporter_accept_report_count = Report::where('reporter_id', Auth::guard('reporter')->user()->id)->where('status_id', 3)->count();
            if ($program_require != null) {

                if ($program_require->no_requirements == null) {
                    if ($reporter_accept_report_count < $program_require->submission_count) {
                        return redirect()->route('reporter_programs',)->with('info', "you must be have more than  $program_require->submission_count reports to submit report in this program");
                    }
                }
            }
            // end check if reporter have permission to send reports
            $check = Program::find($id);
            if ($check && $check->submit == 1) {
                //check_reporter in report when mode of use light
                $reporter_in_report = DB::table('reports')->where('program_id', $check->id)->select(DB::raw('COUNT(*) as count'))->groupBy('reporter_id')->count();
                if ($check->reporter_quantity == 1 && $reporter_in_report >= 2) {
                    return redirect()->back()->with('warning', "This program accommodates a specific number of reporters, and the maximum number has been reached");
                }

                //block reporter to submit for this program
                $block = BlockingProgram::where('program_id', $id)->where('reporter_id', Auth::guard('reporter')->user()->id)->first();
                if ($block) {
                    return redirect()->back()->with('warning', "You are Blocked Can't submit report");
                }

                $program = Program::where('id', $id)->with('corporate')->first();
                $targets = Target::where('program_id', $id)->with('type_target')->get();
                $vulnerabilities = Vulnerability::get();
                $accept_report = Report::where('program_id', $id)->where('status_id', 3)->count();
                $rewarded_report = Report::where('program_id', $id)->where('status_id', 3)->where('paid', 1)->count();

                return view('content.reporter.pages.create_report', compact(['targets', 'vulnerabilities', 'program', 'accept_report', 'rewarded_report']));
            } else {

                return redirect()->back()->with('error', "something went wrong.");
            }
        } catch (\Exception $ex) {

            return back()->with('error',  'something went wrong');
        }
    }



    //====================store report
    public function store($request)
    {
        DB::beginTransaction();


        try {
            $report = [

                'program_id' => $request->program_id,
                'summarize' => $request->summarize,
                'target' => $request->target,
                'url_vulnerability' => $request->url_vulnerability,
                'description' => $request->description,
                'reproduce' => $request->reproduce,
                'impact' => $request->impact,
                'recommendation' => $request->recommendation,

            ];

            $vulnerability = Vulnerability::where('id', $request->vulnerability_id)->first()->vulnerability;
            $belong_vulnerability = BelongVulnerability::where('id', $request->belong_vulnerability_id)->first()->vulnerability;
            $report_vulnerability = $vulnerability . "  / " . $belong_vulnerability;
            if ($request->belong_belong_vulnerability_id != null) {
                $belong_belong_vulnerability = BelongBelongVulnerability::where('id', $request->belong_belong_vulnerability_id)->first()->vulnerability;
                $report_vulnerability = $vulnerability . " / " . $belong_vulnerability . " / " . $belong_belong_vulnerability;
            }

            $report['vulnerability'] = $report_vulnerability;
            $report['reporter_id'] = Auth::guard('reporter')->user()->id;
            $new_report_create = Report::create($report);




            //store image reporter



            $validateImageData = $request->validate([
                'images' => 'required|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5048'
            ]);
            if ($request->hasfile('images')) {

                $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://");
                $actual_link . $_SERVER['HTTP_HOST'];
                $report_id = DB::table('reports')
                    ->latest()
                    ->first()->id;
                $count = count($request->images);


                for ($i = 0; $i < $count; $i++) {
                    $image[$i] = $request->images[$i];
                    $newimage[$i] = $i . (rand(10, 100)) . "-" . time() . "." . $image[$i]->clientExtension();
                    $image[$i]->move('uploade/report_image', $newimage[$i]);
                    $final_image[$i] = $actual_link . $_SERVER['HTTP_HOST'] . '/uploade/report_image/' . $newimage[$i];
                    ReportImage::create([
                        'image' => $final_image[$i],
                        'report_id' => $report_id,

                    ]);
                }
            }

            Activity::create([
                'activeable_id' => Auth::guard('reporter')->user()->id, 'activeable_type' => 'App\Models\Reporter\Reporter',
                'activity' => 'Added Report', 'description_activity' => "Added Report " . substr($request->summarize, 0, 100)
            ]);

            DB::commit();

            //send_notification
            $this->admin_notification->sendAdminNotification(
                    'New Report',
                    Auth::guard('reporter')->user()->first_name . ' Added New Report',
                    'show_admin_report',
                    $new_report_create->id,
                    'reporter'
                );

            return redirect()->back()->with('success', "Report Added successfully .");
        } catch (\Exception $ex) {

            DB::rollback();
            return back()->with('error',  'something went wrong');
        }
    }





    //==================create report
    public function edit($id)
    {

        try {
            $report = Report::find($id);


            if ($report) {



                $vulnerability = explode(" / ", $report->vulnerability);

                if (isset($vulnerability[2])) {
                    $sub_sub_vulnerability = BelongBelongVulnerability::where('vulnerability', $vulnerability[2])->first()->vulnerability;
                } else {
                    $sub_sub_vulnerability = "";
                }

                $main_vulnerability = Vulnerability::where('vulnerability', $vulnerability[0])->first()->id;
                $sub_vulnerability = BelongVulnerability::where('vulnerability', $vulnerability[1])->first()->vulnerability;
                $program = Report::where('id', $id)->with('program', function ($query) {
                    return $query->with('corporate');
                })->first()->program;

                $targets = Target::where('program_id', $program->id)->with('type_target')->get();
                $vulnerabilities = Vulnerability::get();
                $accept_report = Report::where('program_id', $program->id)->where('status_id', 3)->count();
                $rewarded_report = Report::where('program_id', $program->id)->where('status_id', 3)->where('paid', 1)->count();


                return view('content.reporter.pages.edit_report', compact(['targets', 'vulnerabilities', 'program', 'report', 'main_vulnerability', 'sub_vulnerability', 'sub_sub_vulnerability', 'accept_report', 'rewarded_report']));
            } else {
                return redirect()->back()->with('error', "something went wrong.");
            }
        } catch (\Exception $ex) {

            return back()->with('error',  'something went wrong');
        }
    }



    //====================store report
    public function update($request)
    {
        try {
            $request->all();
            $report = [

                'program_id' => $request->program_id,
                'summarize' => $request->summarize,
                'target' => $request->target,
                'url_vulnerability' => $request->url_vulnerability,
                'description' => $request->description,
                'reproduce' => $request->reproduce,
                'impact' => $request->impact,
                'recommendation' => $request->recommendation,

            ];
            //return $request->all();






            $vulnerability = Vulnerability::where('id', $request->vulnerability_id)->first()->vulnerability;

            if (is_numeric($request->belong_vulnerability_id)) {
                $belong_vulnerability = BelongVulnerability::where('id', $request->belong_vulnerability_id)->first()->vulnerability;
            } else {
                $belong_vulnerability = $request->belong_vulnerability_id;
            }

            $report_vulnerability = $vulnerability . "  / " . $belong_vulnerability;
            if ($request->belong_belong_vulnerability_id != null) {
                if (is_numeric($request->belong_belong_vulnerability_id)) {
                    $belong_belong_vulnerability = BelongBelongVulnerability::where('id', $request->belong_belong_vulnerability_id)->first()->vulnerability;
                } else {
                    $belong_belong_vulnerability = $request->belong_belong_vulnerability_id;
                }
                $report_vulnerability = $vulnerability . " / " . $belong_vulnerability . " / " . $belong_belong_vulnerability;
            }


            $report['vulnerability'] = $report_vulnerability;
            $report['reporter_id'] = Auth::guard('reporter')->user()->id;

            $check = Report::find($request->report_id);

            if ($check) {
                Report::where('id', $check->id)->update($report);

                Activity::create([
                    'activeable_id' => Auth::guard('reporter')->user()->id, 'activeable_type' => 'App\Models\Reporter\Reporter',
                    'activity' => 'Updated Report', 'description_activity' => "Updated Report " . substr($request->summarize, 0, 100)
                ]);

                //send notification
                $this->admin_notification->sendAdminNotification(
                        'Updated Report',
                        Auth::guard('reporter')->user()->first_name . ' Updated The Report',
                        'show_admin_report',
                        $check->id,
                        'reporter'
                    );



                return redirect()->back()->with('success', "Report Updated successfully .");
            } else {
                return redirect()->back()->with('error', "Something Went Wrong.");
            }
        } catch (\Exception $ex) {

            return back()->with('error',  'something went wrong');
        }
    }


    //================get belong_vulnerability image use ajax
    public function belong_vulnerability($request)
    {
        try {
            return $belong_vulnerability = BelongVulnerability::where('vulnerability_id', $request->id)->pluck("vulnerability", "id");
        } catch (\Exception $ex) {

            return back()->with('error',  'something went wrong');
        }
    }


    //====================get belong belong_vulnerability image use ajax

    public function belong_belong_vulnerability($request)
    {
        try {
            return $Belong_belong_vulnerability = BelongBelongVulnerability::where('belong_vulnerability_id', $request->id)->pluck("vulnerability", "id");
        } catch (\Exception $ex) {
            return back()->with('error',  'something went wrong');
        }
    }


    //====================show report  page

    public function show($id)
    {


        try {

            $check = Report::find($id);
            if ($check) {

                //update read chat report status
                ChatReport::where('reporter_id', Auth::guard('reporter')->user()->id)
                ->where('receiver_type', 'reporter')->update(['read' => 1]);

                // $report = Report::where('id', $check->id)->with('chat', function ($query) {
                //         return $query->with('admin')->get();
                //     })->with('program', function ($query) {
                //         return $query->with('corporate')->first();
                //     })->with('reporter')->first();
                $report = Report::where('id', $check->id)->with('chat.admin')->with('program.corporate')->with('reporter')->first();

                if (count($report->chat) > 0) {
                    $admin_image = $report->chat[0]->admin->image;
                    $admin_name = $report->chat[0]->admin->name;
                } else {
                    $admin_image = "";
                    $admin_name = "";
                }

                $images = ReportImage::where('report_id', $id)->get();


                return view('content.reporter.pages.report', compact(['report', 'images', 'admin_image', 'admin_name']));
            } else {
                return redirect()->back()->with('error', "Something Went Wrong.");
            }
        } catch (\Exception $ex) {

            return back()->with('error',  'something went wrong');
        }
    }



    //====================delete report
    public function destroy($request)
    {
        DB::beginTransaction();

        try {
            $id = $request->id;
            $check = Report::find($id);
            if ($check) {
                $data = $check;
                $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://");
                $image = ReportImage::where('report_id', $data->id)->select('image')->get();
                if ($image != null) {


                    $count = count($image);
                    for ($i = 0; $i < $count; $i++) {
                        $replace = $actual_link . $_SERVER['HTTP_HOST'] . "/";
                        $image_path = str_replace($replace, "", $image[$i]['image']);
                        if (file_exists($image_path)) {
                            unlink($image_path);
                        }
                    }
                }
                ReportImage::where('report_id', $data->id)->delete();

                $data->delete();

                Activity::create([
                    'activeable_id' => Auth::guard('reporter')->user()->id, 'activeable_type' => 'App\Models\Reporter\Reporter',
                    'activity' => 'Deleted Report', 'description_activity' => "Deleted Report " . substr($check->summarize, 0, 100)
                ]);
                DB::commit();

                //send notification
                $this->admin_notification->sendAdminNotification(
                        'Deleted Report',
                        Auth::guard('reporter')->user()->first_name . ' Deleted The Report',
                        'admin_notifications',
                        null,
                        'reporter'
                    );

                return response()->json(['status' => 'success'], 200);
            } else {
                return response()->json(['status' => 'failed']);
            }
        } catch (\Exception $ex) {
            DB::rollback();
            return back()->with('error',  'something went wrong');
        }
    }
}
