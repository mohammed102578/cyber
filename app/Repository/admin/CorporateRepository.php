<?php

namespace App\Repository\admin;

use App\Interfaces\admin\CorporateInterface;
use App\Models\Activity;
use App\Models\Corporate\Corporate;
use App\Models\Nationality;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CorporateRepository implements CorporateInterface
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        try {
            $nationalities = Nationality::get();
            return view('content.admin.pages.corporate.corporates', compact('nationalities'));
        } catch (\Exception $ex) {
            return back()->with('error',  'something went wrong');
        }
    }

    /**
     * Display a listing of the resource.
     * get all corporate use ajax and yajar data table
     * @return \Illuminate\Http\Response
     */

    public function get_corporates($request)
    {
        try {
            if ($request->ajax()) {
                $data = Corporate::orderBy('id', 'DESC')->get();
                return Datatables()->of($data)->addIndexColumn()
                    ->addColumn('status', function ($data) {
                        if ($data->status == 1) {
                            $statutsBtn = 'Blocked';
                        } else {
                            $statutsBtn =  'UnBlock';
                        }
                        return $statutsBtn;
                    })
                    ->rawColumns(['status'])

                    //created_at
                    ->addColumn('created_at', function ($data) {

                        $created_at = Carbon::parse($data->created_at)->format('d M Y');

                        return $created_at;
                    })
                    ->rawColumns(['created_at'])

                    //last_seen_at
                    ->addColumn('last_seen_at', function ($data) {

                        $created_at = Carbon::parse($data->last_seen_at)->format('d M Y H:i');

                        return $created_at;
                    })
                    ->rawColumns(['last_seen_at'])

                    ->addColumn('address', function ($data) {
                        $statutsBtn = $data->nationality . " / " . $data->city;
                        return $statutsBtn;
                    })
                    ->rawColumns(['address'])
                    ->addColumn('action', function ($data) {
                        $actionBtn = ' <div class="d-inline-block">
          <a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
          <i class="bx bx-dots-vertical-rounded">
          </i></a>
          <div class="dropdown-menu dropdown-menu-end m-0">
          <a class=" edit_modal_corporate dropdown-item " id="' . $data->id . '"href=""data-bs-toggle="modal" data-bs-target="#edit_corporate"> <i class="bx bx-edit text-info"></i> Edit Corporate</a>
          <a class=" hapus-modale_block dropdown-item " id="' . $data->id . '" ><i class="bx bx-block  text-warning"></i> Block</a>
          <div class="dropdown-divider">
          </div>
          <a class=" hapus-modale dropdown-item " id="' . $data->id . '" ><i class="bx bx-trash text-danger" style="margin-bottom:6px;"></i> Delete</a>
          </div>
          </div>
        ';
                        return $actionBtn;
                    })->rawColumns(['action'])->make(true);
            }
        } catch (\Exception $ex) {

            return back()->with('error', 'something went wrong');
        }
    }



    /**
     * Show the form for editing the specified resource.
     * edit corporate use ajax
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($request)
    {
        try {
            $id = $request->id;
            $check = Corporate::find($id);
            $data = $check;
            return response()->json($data);
        } catch (\Exception $ex) {
            return back()->with('error',  'something went wrong');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update($request)
    {

        try {
            $id = $request->id;
            $check = Corporate::find($id);
            if ($check) {
                $corporate_data = [
                    "id" => $request->id,
                    "company_name" => $request->company_name,
                    "website" => $request->website,
                    "username" => $request->username,
                    "email" => $request->email,
                    "field" => $request->field,
                    "section" => $request->section,
                    "city" => $request->city,
                    "nationality" => $request->nationality,
                    "password" => bcrypt($request->password),
                ];

                if ($request->password != null) {
                    $corporate_data['password'] = bcrypt($request->password);
                }

                $data = Corporate::where('id', $check->id)->update($corporate_data);
                Activity::create([
                    'activeable_id' => Auth::guard('admin')->user()->id, 'activeable_type' => 'App\Models\Admin\Admin',
                    'activity' => 'Updated Corporate ', 'description_activity' => " Updated Corporate  "
                ]);
                return response()->json(['status' => 'success'], 200);
            } else {
                return response()->json(['status' => 'failed']);
            }
        } catch (\Exception $ex) {

            return back()->with('error',  'something went wrong');
        }
    }



    /**
     * Update the specified resource in storage.
     * updated status
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //block corporate
    public function block($request)
    {

        try {

            $id = $request->id;
            $check = Corporate::find($id);
            if ($check) {
                $data = $check;
                if ($data->status == 1) {
                    $status = 0;
                } else {
                    $status = 1;
                }
                Corporate::where('id', $data->id)->update(['status' => $status]);
                Activity::create([
                    'activeable_id' => Auth::guard('admin')->user()->id, 'activeable_type' => 'App\Models\Admin\Admin',
                    'activity' => 'Updated Corporate ', 'description_activity' => " Updated Corporate Status "
                ]);
                return response()->json(['status' => 'success'], 200);
            } else {
                return response()->json(['status' => 'failed']);
            }
        } catch (\Exception $ex) {
            return back()->with('error',  'something went wrong');
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function soft_delete($request)
    {

        try {
            $id = $request->id;
            $check = Corporate::find($id);

            if ($check) {
                $data = $check;


                $data->delete();
                Activity::create([
                    'activeable_id' => Auth::guard('admin')->user()->id, 'activeable_type' => 'App\Models\Admin\Admin',
                    'activity' => 'Soft Deleted Corporate ', 'description_activity' => "Soft Deleted Corporate  "
                ]);

                return response()->json(['status' => 'success'], 200);
            } else {
                return response()->json(['status' => 'failed']);
            }
        } catch (\Exception $ex) {

            return back()->with('error',  'something went wrong');
        }
    }





    //================soft delete

    public function trash_corporates()
    {
        try {
            return view('content.admin.pages.corporate.corporates_trash');
        } catch (\Exception $ex) {
            return back()->with('error',  'something went wrong');
        }
    }

    /**
     * Display a listing of the resource.
     * get all corporate use ajax and yajar data table
     * @return \Illuminate\Http\Response
     */

    public function get_trash_corporates($request)
    {
        try {
            if ($request->ajax()) {
                $data = Corporate::onlyTrashed()->orderBy('id', 'DESC')->get();
                return Datatables()->of($data)->addIndexColumn()
                    ->addColumn('status', function ($data) {
                        if ($data->status == 1) {
                            $statutsBtn = 'Blocked';
                        } else {
                            $statutsBtn =  'UnBlock';
                        }
                        return $statutsBtn;
                    })
                    ->rawColumns(['status'])
                    //created_at
                    ->addColumn('created_at', function ($data) {

                        $created_at = Carbon::parse($data->created_at)->format('d M Y');

                        return $created_at;
                    })
                    ->rawColumns(['created_at'])
                    ->addColumn('address', function ($data) {
                        $statutsBtn = $data->nationality . " / " . $data->city;
                        return $statutsBtn;
                    })
                    ->rawColumns(['address'])
                    ->addColumn('action', function ($data) {
                        $actionBtn = ' <div class="d-inline-block">
          <a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
          <i class="bx bx-dots-vertical-rounded">
          </i></a>
          <div class="dropdown-menu dropdown-menu-end m-0">
          <a class=" restore_module dropdown-item " id="' . $data->id . '" ><i class="bx bx-arrow-back text-info"></i> Restore</a>
          <div class="dropdown-divider">
          </div>
          <a class=" hapus-modale dropdown-item " id="' . $data->id . '" ><i class="bx bx-trash text-danger"></i> Force Delete</a>
          </div>
          </div>
        ';
                        return $actionBtn;
                    })->rawColumns(['action'])->make(true);
            }
        } catch (\Exception $ex) {

            return back()->with('error', 'something went wrong');
        }
    }



    //restore corporate
    public function restore($request)
    {

        try {
            $id = $request->id;
            $check = Corporate::withTrashed()->where('id', $id)->restore();
            if ($check) {

                Activity::create([
                    'activeable_id' => Auth::guard('admin')->user()->id, 'activeable_type' => 'App\Models\Admin\Admin',
                    'activity' => 'restore corporate', 'description_activity' => "restore corporate from trash "
                ]);
                return response()->json(['status' => 'success'], 200);
            } else {
                return response()->json(['status' => 'failed']);
            }
        } catch (\Exception $ex) {

            return back()->with('error',  'something went wrong');
        }
    }




    //force delete


    public function destroy($request)
    {

        try {
            $id = $request->id;
            $check =  Corporate::withTrashed()->findOrFail($id);

            if ($check) {
                $data = $check;
                $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://");
                $replace = $actual_link . $_SERVER['HTTP_HOST'] . "/";
                $corporate = Corporate::withTrashed()->where('id', $data->id)->select('image')->first()->image;
                $image_path = str_replace($replace, "", $corporate);
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
                $data->forceDelete();

                Activity::create([
                    'activeable_id' => Auth::guard('admin')->user()->id, 'activeable_type' => 'App\Models\Admin\Admin',
                    'activity' => 'Deleted Corporate ', 'description_activity' => " Deleted Corporate  "
                ]);

                return response()->json(['status' => 'success'], 200);
            } else {
                return response()->json(['status' => 'failed']);
            }
        } catch (\Exception $ex) {

            return back()->with('error',  'something went wrong');
        }
    }
}
