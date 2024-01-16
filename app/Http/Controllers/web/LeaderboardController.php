<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repository\web\LeaderboardRepository;

class LeaderboardController extends Controller
{

    public object $leaderboard;

    public function __construct(LeaderboardRepository $leaderboard)
    {
        $this->leaderboard = $leaderboard;
    }
    public function leaderboard(Request $request)
    {
        return $this->leaderboard->leaderboard($request);
    }
}
