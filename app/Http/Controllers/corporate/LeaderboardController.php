<?php

namespace App\Http\Controllers\corporate;

use App\Http\Controllers\Controller;
use App\Repository\corporate\LeaderboardRepository;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
  protected $leaderboard;
  public function __construct(LeaderboardRepository $leaderboard)
  {

    $this->leaderboard = $leaderboard;
  }
  public function leaderboard(Request $request)
  {
    return $this->leaderboard->leaderboard($request);
  }
}
