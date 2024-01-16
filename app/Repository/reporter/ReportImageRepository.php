<?php

namespace App\Repository\reporter;

use App\Interfaces\reporter\ReportImageInterface;
use App\Models\Activity;
use App\Models\Reporter\Report;
use App\Models\Reporter\ReportImage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportImageRepository implements ReportImageInterface
{
  //==========get all reporter report's
  public function images($id)
  {

    try {

      $check =  Report::where('id', $id)->where('reporter_id', Auth::guard('reporter')->user()->id)->first();
      if ($check) {
        $report_id = $check->id;
        return view('content.reporter.pages.report_images', compact(['report_id']));
      } else {
        return back()->with('error',  'something went wrong');
      }
    } catch (\Exception $ex) {

      return back()->with('error',  'something went wrong');
    }
  }


  //==========get all reporter report's using ajax

  public function get_images($request, $id)
  {
    try {
    if ($request->ajax())
     {
      $data = DB::table('report_images')->where('report_id', $id)->get();
      return Datatables()->of($data)
        ->addIndexColumn()
         //created_at
         ->addColumn('created_at', function ($data) {

            $created_at = Carbon::parse($data->created_at)->format(' F , d Y');

            return $created_at;
        })
        ->rawColumns(['created_at'])
        //action
        ->addColumn('action', function ($data) {
          $actionBtn = '
                      <a class=" hapus-modale " id="' . $data->id . '" ><i class="bx bx-trash text-danger"></i> </a>
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
  public function add_image($request)
  {

    //store image reporter


    $validateImageData = $request->validate([
      'images' => 'required|array',
      'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5048'
    ]);


    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://");
    $actual_link . $_SERVER['HTTP_HOST'];
    $report_id = $request->report_id;
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



      Activity::create([
        'activeable_id' => Auth::guard('reporter')->user()->id, 'activeable_type' => 'App\Models\Reporter\Reporter',
        'activity' => 'Added Report\'s Image', 'description_activity' => "Added Report\'s Image  "
      ]);
    }



    return redirect()->back()->with('success', "Images Added successfully .");
  }



  //====================delete program
  public function destroy($request)
  {

    try {
      $id = $request->id;
      $check = ReportImage::find($id);
      if ($check) {
        $data = $check;
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://");
        $image = ReportImage::where('id', $data->id)->first()->image;


        $replace = $actual_link . $_SERVER['HTTP_HOST'] . "/";
        $image_path = str_replace($replace, "", $image);
        if (file_exists($image_path)) {
          unlink($image_path);
        }

        ReportImage::where('id', $data->id)->delete();




        Activity::create([
          'activeable_id' => Auth::guard('reporter')->user()->id, 'activeable_type' => 'App\Models\Reporter\Reporter',
          'activity' => 'Deleted Report\'s Image', 'description_activity' => "Deleted Report\'s Image  "
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
