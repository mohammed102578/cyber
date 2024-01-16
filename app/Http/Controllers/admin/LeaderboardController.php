<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Repository\admin\LeaderboardRepository;

;
use Illuminate\Http\Request;


class LeaderboardController extends Controller
{
  public $leaderboard;
  public function __construct(LeaderboardRepository $leaderboard)
  {
      $this->leaderboard = $leaderboard;
  }
public function leaderboard(Request $request)
{

return $this->leaderboard->leaderboard($request);
}




}
