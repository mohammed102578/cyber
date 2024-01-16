<?php

namespace App\Repository\reporter;


use App\Interfaces\reporter\ConnectionInterface;
use App\Models\Connection;
use Illuminate\Support\Facades\Auth;

class ConnectionRepository implements ConnectionInterface
{

  public function store($request)
  {
    try {
      $data = [
        'link' => $request->link,
        'app_name' => $request->app_name,
        'connectionable_id' => Auth::guard('reporter')->user()->id,
        'connectionable_type' => 'App\Models\Reporter\Reporter'
      ];


      Connection::create($data);
      return back()->with('success', 'Added ' . $request->app_name . ' link successfully');
    } catch (\Exception $ex) {

      return back()->with('error', 'something went wrong');
    }
  }



  public function destroy($id)
  {
    try {
      $connection = Connection::find($id);
      $connection->delete();
      return back()->with('success', 'Deleted ' . $connection->app_name . ' link successfully');
    } catch (\Exception $ex) {

      return back()->with('error', 'something went wrong');
    }
  }
}
