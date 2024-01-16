<?php

namespace App\Repository\corporate;

use App\Interfaces\corporate\LeaderboardInterface;
use App\Models\Reporter\Point;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LeaderboardRepository implements LeaderboardInterface
{
public function leaderboard($request)
{
  try{
      if(!empty($request->from)){
      $messages = [
      'required' => 'this field is required',
      ];
      $validator = Validator::make($request->all(),[
      'from' =>'required|date',
      'to' =>'required|date',
      'severity' =>'required|string',
      ],$messages);
      if ($validator->fails()) {
      Session::flash('errors', $validator->errors());
      return back()->with('error' ,'please Enter all data');
      }
      //top ten report now
      $top_reporters=Point::with('reporter.report')
      ->select('reporter_id',DB::raw("sum(point) as sum"),DB::raw("count(point) as count" ))
      ->take('100')
      ->orderBy('sum', 'desc')
      ->groupBy('reporter_id')->get();
      //top ten report TIME
      $from=$request->from;
      $to=$request->to;
      $severity=$request->severity;
      $top_reporters_time=Point::with('reporter.report')
      ->whereBetween('created_at', [$from, $to])
      -> where('vulnerability','LIKE',"%$severity%")
      ->select('reporter_id',DB::raw("sum(point) as sum"),DB::raw("count(point) as count" ))
      ->take('100')
      ->orderBy('sum', 'desc')
      ->groupBy('reporter_id')->get();
      $active="time";
      return view('content.corporate.pages.leaderboard',compact('top_reporters','top_reporters_time','active'));
      }else{
      //top ten report now
      $top_reporters=Point::with('reporter.report')
      ->select('reporter_id',DB::raw("sum(point) as sum"),DB::raw("count(point) as count" ))
      ->take('100')
      ->orderBy('sum', 'desc')
      ->groupBy('reporter_id')->get();
      $active="now";
      return view('content.corporate.pages.leaderboard',compact('top_reporters','active'));

      }
       }catch(\Exception $ex){
      return back()->with('error',  'something went wrong');

    }

}




}
