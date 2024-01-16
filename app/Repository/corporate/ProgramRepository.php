<?php

namespace App\Repository\corporate;


use App\Models\Corporate\Program;
use App\Models\Corporate\Priority;
use App\Models\Corporate\ProgramRequirement;
use App\Http\Services\Notification;
use App\Interfaces\corporate\ProgramInterface;
use App\Models\Activity;
use App\Models\Corporate\ProgramUpdate;
use App\Models\Corporate\TypeTarget;
use App\Models\Corporate\Target;
use App\Models\Reporter\Report;
use App\Models\Reporter\Reporter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Traits\SaveImageTrait;

class ProgramRepository implements ProgramInterface
{

    use SaveImageTrait;

    public object $admin_notification;
    public function __construct()
    {
        $this->admin_notification = new Notification;
    }
    public function index()
    {

        try {

            $all = Program::where('corporate_id', Auth::guard('corporate')->user()->id)
            ->whereIn('status_id', [1, 2, 3, 4])
            ->with(['report' => function ($query) {
                $query->where('status_id', 3);
            }])
            ->orderBy('id', 'DESC')
            ->get();

                $pending = $all->where('status_id', 1);
                $in_review = $all->where('status_id', 2);
                $accepted = $all->where('status_id', 3);
                $rejected = $all->where('status_id', 4);

            return view('content.corporate.pages.program.programs', compact('all', 'pending', 'in_review', 'accepted', 'rejected'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'something went wrong');
        }
    }

    //create a new program page


    public function create()
    {
        try {
            return view('content.corporate.pages.program.add_program');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'something went wrong');
        }
    }

    //store program to database

    public function store($request)
    {

        try {

            $request->all();
            if ($request->hasFile('image')) {

                $program['image'] = SaveImageTrait::save_image($request->image, 'program');

                $program['corporate_id'] = auth()->user('corporate')->id;
            } else {
                $program = $request->all();


                $program['corporate_id'] = auth()->user('corporate')->id;
            }

            $program_id = Program::create($program);
            Activity::create([
                'activeable_id' => Auth::guard('corporate')->user()->id,
                'activeable_type' => 'App\Models\Corporate\Corporate',
                'activity' => 'Created Program',
                'description_activity' => "Created Program"
            ]);

            //send notification
            $this->admin_notification->sendAdminNotification(
                    'Add Program',
                    Auth::guard('corporate')->user()->company_name . ' Added a new program ',
                    'admin_show_program',
                    $program_id->id,
                    'corporate'
                );

            return redirect()->route('corporate_programs')->with('success', "Program Added successfully .");
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'something went wrong');
        }
    }






    //update program to database

    public function update($request)
    {
        DB::beginTransaction();

        try {


            //start validate priority
            $range_to = $request->range_to;
            $validator = Validator::make(compact('range_to'), [
                'range_to' => 'required|array',
                'range_to.*' => 'integer'
            ]);


            if ($validator->fails()) {
                return redirect()->back()->withErrors(
                    [
                        'range_to' => 'all fields are required.'
                    ]
                );
            }

            $range_from = $request->range_from;
            $validator = Validator::make(compact('range_from'), [
                'range_from' => 'required|array',
                'range_from.*' => 'integer'
            ]);




            if ($validator->fails()) {
                return redirect()->back()->withErrors(
                    [
                        'range_from' => 'all fields are required.'
                    ]
                );
            }
            //end of validation
            $check = Program::find($request->program_id);

            //insert in  program_update table
            if ($request->reporter_quantity != $check->reporter_quantity) {

                if ($request->reporter_quantity == 1) {
                    $reporter_quantity = "Reporter Qunatity has been Changed From Full To Lite";
                } else {
                    $reporter_quantity = "Reporter Qunatity has been Changed From Lite To Full";
                }
                ProgramUpdate::create(['program_id' => $check->id, 'program_update' => $reporter_quantity]);
            } elseif ($request->program_type != $check->program_type) {



                if ($request->program_type == 1) {
                    $program_type = "Program Type has been Changed  To Public";
                } elseif ($request->program_type == 2) {

                    $program_type = "Program Type has been Changed  To Semi-Private";
                } else {
                    $program_type = "Program Type has been Changed  To Private";
                }
                ProgramUpdate::create(['program_id' => $check->id, 'program_update' => $program_type]);
            } elseif ($request->management != $check->management) {
                if ($request->management == 1) {
                    $management = "The program Mangement has been changed To HackingSd";
                } else {
                    $management = " The program Mangement has been changed To By-self";
                }
                ProgramUpdate::create(['program_id' => $check->id, 'program_update' => $management]);
            } elseif ($request->currency != $check->currency) {
                if ($request->currency == 'USD') {
                    $currency = "The program currency has been changed to USD";
                } else {
                    $currency = "The program currency has been changed to SDG";
                }
                ProgramUpdate::create(['program_id' => $check->id, 'program_update' => $currency]);
            } elseif ($request->description_ar != $check->description_ar) {
                ProgramUpdate::create(['program_id' => $check->id, 'program_update' => 'Program description has changed']);
            } elseif ($request->description_en != $check->description_en) {
                ProgramUpdate::create(['program_id' => $check->id, 'program_update' => 'Program description has changed']);
            } elseif ($request->policy_ar != $check->policy_ar) {
                ProgramUpdate::create(['program_id' => $check->id, 'program_update' => 'Program Policy has changed']);
            } elseif ($request->policy_en != $check->policy_en) {
                ProgramUpdate::create(['program_id' => $check->id, 'program_update' => 'Program Policy has changed']);
            }



            if (!$check) {
                return redirect()->back()->with('error', "Something Went Wrong.");
            } else {


                $program = [
                    'reporter_quantity' => $request->reporter_quantity,
                    'program_type' => $request->program_type,
                    'management' => $request->management,
                    'currency' => $request->currency,
                    'description_ar' => $request->description_ar,
                    'description_en' => $request->description_en,
                    'policy_ar' => $request->policy_ar,
                    'policy_en' => $request->policy_en,

                ];


                if ($request->hasFile('image')) {

                    $program['image'] = SaveImageTrait::save_image($request->image, 'program');

                    $program['corporate_id'] = auth()->user('corporate')->id;
                } else {
                    $program = $request->all();
                    $program['corporate_id'] = auth()->user('corporate')->id;
                }

                //update program
                $check->update($program);





                //start of priority

                $priority = Priority::where('program_id', $request->program_id)->get();

                if (!$priority) {


                    //priority create if not exist


                    $i = 0;

                    $count = count($request->severity);

                    for ($i; $i < $count; $i++) {

                        Priority::create([
                            'severity' => $request->severity[$i],
                            'range_from' => $request->range_from[$i],
                            'range_to' => $request->range_to[$i],
                            'program_id' => $request->program_id,

                        ]);
                    }
                    ProgramUpdate::create(['program_id' => $check->id, 'program_update' => 'New priorities have been added']);
                } else {

                    // delete priority  if  exist after that create

                    $priority = Priority::where('program_id', $request->program_id)->delete();

                    $i = 0;

                    $count = count($request->severity);

                    for ($i; $i < $count; $i++) {

                        Priority::create([
                            'severity' => $request->severity[$i],
                            'range_from' => $request->range_from[$i],
                            'range_to' => $request->range_to[$i],
                            'program_id' => $request->program_id,

                        ]);
                    }
                    ProgramUpdate::create(['program_id' => $check->id, 'program_update' => 'Priorities have been updated']);
                }

                //end of priority


                //==============================================================



                //start target

                $target = Target::where('program_id', $request->program_id)->get();

                if (!$target) {


                    //priority create if not exist



                    $x = 0;

                    $count = count($request->target);

                    for ($i; $x < $count; $x++) {

                        Target::create([
                            'target' => $request->target[$x],
                            'type_target_id' => $request->field[$x],
                            'scope' => $request->scope[$x],
                            'program_id' => $request->program_id,

                        ]);
                    }

                    ProgramUpdate::create(['program_id' => $check->id, 'program_update' => 'New Targets have been added']);
                } else {
                    $delete = Target::where('program_id', $request->program_id)->delete();

                    $count = count($request->target);

                    $target = [];
                    $type_target_id = [];
                    $scope = [];
                    $i = 0;
                    for ($i; $i < $count; $i++) {
                        if ($request->target[$i] != null && $request->field[$i] != null && $request->scope[$i] != null) {
                            $scope[] = $request->scope[$i];
                            $target[] = $request->target[$i];
                            $type_target_id[] = $request->field[$i];
                        }
                    }




                    // delete priority  if  exist after that create

                    $x = 0;
                    $count = count($target);
                    for ($i; $x < $count; $x++) {
                        Target::create([
                            'target' => $target[$x],
                            'type_target_id' => $type_target_id[$x],
                            'scope' => $scope[$x],
                            'program_id' => $request->program_id,

                        ]);
                    }
                    ProgramUpdate::create(['program_id' => $check->id, 'program_update' => 'Targets have been updated']);


                    //end of target
                    Activity::create([
                        'activeable_id' => Auth::guard('corporate')->user()->id,
                        'activeable_type' => 'App\Models\Corporate\Corporate',
                        'activity' => 'Updated Program',
                        'description_activity' => "Updated Program "
                    ]);
                    DB::commit();

                    //send notification
                    $this->admin_notification->sendAdminNotification(
                            'Update Program',
                            Auth::guard('corporate')->user()->company_name . ' Updated his program ',
                            'admin_show_program',
                            $request->program_id,
                            'corporate'
                        );

                    return redirect()->back()->with('success', "Program Updated successfully .");
                }
            }
        } catch (\Exception $ex) {
            DB::rollback();

            return redirect()->back()->with('error', 'something went wrong');
        }
    }




    public function destroy($request)
    {
        try {
            $check = Program::find($request->id);
            if ($check) {
                Program::where('id', $check->id)->delete();
                Activity::create([
                    'activeable_id' => Auth::guard('corporate')->user()->id,
                    'activeable_type' => 'App\Models\Corporate\Corporate',
                    'activity' => 'Deleted Program',
                    'description_activity' => "Deleted Program "
                ]);

                //send notification
                $this->admin_notification->sendAdminNotification(
                        'Delete Program',
                        Auth::guard('corporate')->user()->company_name . ' Deleted his program ',
                        'admin_notifications',
                        null,
                        'corporate'
                    );
                return redirect()->route('corporate_programs')->with('success', "program deleted successfully.");
            } else {
                return redirect()->back()->with('error', "something went wrong");
            }
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'something went wrong');
        }
    }





    //program's settings page

    public function setting($request)
    {


        try {

            $reporters = Reporter::all();
            $check = Program::with('program_requirement')->where('id', $request->id)->where('corporate_id', Auth::guard('corporate')->user()->id)->first();
            if ($check) {
                $program = Program::where('id', $check->id)
                    ->where('corporate_id', auth()->user('corporate')->id)
                    ->with('corporate')
                    ->with('program_update', function ($query) {

                        return $query->orderBy('created_at', 'DESC')->get();
                    })->with('priority')
                    ->with('reporter_blocking')
                    ->with('reporter_private')
                    ->with('reporter_semi_private')
                    ->with(
                        'report',
                        function ($query) {
                            return $query->where('status_id', 3)->get();
                        }
                    )
                    ->first();


                $reporter_semi_private = $program->reporter_semi_private;
                $reporter_private = $program->reporter_private;
                $reporter_blocking = $program->reporter_blocking;
                $type_targets = TypeTarget::all();
                $active = "program_brife";
                //program_brife
                $targets = Target::where('program_id', $request->id)->with('type_target')->get();


                //hactivity
                $hacktivities = Report::where('hacktivity', 1)->where('program_id', $request->id)->orderBy('updated_at', 'DESC')->get();


                //hall_of_fame
                $hall_of_fames = Report::with('reporter')
                    ->where('status_id', 3)
                    ->where('program_id', $request->id)
                    ->select('reporter_id', DB::raw("count(status_id) as count"))
                    ->take('10')
                    ->orderBy('count', 'desc')
                    ->groupBy('reporter_id')->get();

                $program_report_count = Report::where('status_id', 3)
                    ->where('program_id', $request->id)->count();


                return view('content.corporate.pages.program.setting_program', compact(['program_report_count', 'hall_of_fames', 'hacktivities', 'active', 'type_targets', 'targets', 'program', 'reporters', 'reporter_blocking', 'reporter_semi_private', 'reporter_private']));
            } else {
                return redirect()->back()->with('error', "Something Went Wrong.");
            }
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'something went wrong');
        }
    }






    //update submit status program
    public function submit($request, $id)
    {
        try {
            $check = Program::find($id);

            if ($check) {
                $priority = Priority::where('program_id', $check->id)->get();
                $target = Target::where('program_id', $check->id)->get();
                if (isset($priority[0]) && isset($target[0])) {
                    $submit = $check->submit;
                    if ($submit == 1) {
                        $submit = 0;
                        //send notification
                        $this->admin_notification->sendAdminNotification(
                                'Unsubmit Program',
                                Auth::guard('corporate')->user()->company_name . ' Did not submitted his program ',
                                'admin_show_program',
                                $id,
                                'corporate'
                            );
                    } else {
                        $submit = 1;
                        //send notification
                        $this->admin_notification->sendAdminNotification(
                                'Submit Program',
                                Auth::guard('corporate')->user()->company_name . ' Submitted his program ',
                                'admin_show_program',
                                $id,
                                'corporate'
                            );
                    }
                    Program::where('id', $id)->update(['submit' => $submit]);
                    Activity::create([
                        'activeable_id' => Auth::guard('corporate')->user()->id,
                        'activeable_type' => 'App\Models\Corporate\Corporate',
                        'activity' => 'Updated Program Submit',
                        'description_activity' => "Updated Program Submit"
                    ]);
                    return redirect()->back()->with('success', "program Updated successfully.");
                } else {
                    return redirect()->back()->with('info', "please add priority and target first");
                }
            } else {
                return redirect()->back()->with('error', "something went wrong");
            }
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'something went wrong');
        }
    }






    //program_requirement update or store

    public function program_requirement($request)
    {
        try {
            if ($request->Id_verification != null) {
                $id_verification = 1;
            } else {
                $id_verification = 0;
            }

            if ($request->NDA_signing != null) {
                $NDA_signing = 1;
            } else {
                $NDA_signing = 0;
            }

            if ($request->no_requirements != null) {
                $no_requirements = 1;
            } else {
                $no_requirements = 0;
            }
            ProgramRequirement::updateOrCreate(
                ['program_id' => $request->program_id],
                [
                    'program_id' => $request->program_id,
                    'Id_verification' => $id_verification,
                    'NDA_signing' => $NDA_signing,
                    'submission_count' => $request->submission_count,
                    'no_requirements' => $no_requirements
                ]
            );

            $acive = "";
            return redirect()->back()->with('success', "program_requirement Updated successfully.");
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'something went wrong');
        }
    }
}
